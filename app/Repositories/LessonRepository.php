<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LessonRepository
{
    public function __construct(private readonly StudentRepository $studentRepository, private readonly ScheduleRepository $scheduleRepository)
    {
    }

    public function getScheduleData($id): array
    {
        return DB::select('
        SELECT
            schedule.*,
            student.first_name AS student_first_name,
            student.last_name AS student_last_name,
            teacher.first_name AS teacher_first_name,
            teacher.last_name AS teacher_last_name
        FROM schedule
        JOIN users AS student ON schedule.student_id = student.id
        JOIN users AS teacher ON schedule.teacher_id = teacher.id
        WHERE schedule.id = ?
    ', [$id]);
    }

    public function getLessonData($id): array
    {
        return DB::select('
        SELECT
            lessons.*,
            schedule.teacher_id,
            schedule.student_id,
            schedule.classes_time_start,
            schedule.classes_time_end,
            schedule.date_end,
            schedule.resigned_by,
            schedule.resignation_reason,
            student.first_name AS student_first_name,
            student.last_name AS student_last_name,
            teacher.first_name AS teacher_first_name,
            teacher.last_name AS teacher_last_name
        FROM lessons
        JOIN schedule ON lessons.schedule_id = schedule.id
        JOIN users AS student ON schedule.student_id = student.id
        JOIN users AS teacher ON schedule.teacher_id = teacher.id
        WHERE lessons.id = ?
    ', [$id]);
    }

    public function getGradesForLesson($id): array
    {
        return DB::select('SELECT * FROM grades WHERE lesson_id = ? AND deleted_at IS NULL', [$id]);
    }

    public function getHomeworksForLesson($id): array
    {
        return DB::select('SELECT * FROM homeworks WHERE lesson_id = ? AND deleted_at IS NULL', [$id]);
    }

    //sprawdzenie, czy dla danego schedule nie występuje już jakaś instancja lekcji dla wybranej daty
    private function checkIsScheduleLesson($scheduleId, $date): bool
    {
        $record = DB::select('SELECT * FROM lessons WHERE schedule_id = ? AND date = ?', [$scheduleId, $date]);
        return (!empty($record));
    }

    public function isProperLessonDate($scheduleId, $lessonDate): bool
    {
        $scheduleData = $this->scheduleRepository->getScheduleData($scheduleId);
        $scheduleFrom = $scheduleData->date_begin;
        $scheduleTo = $scheduleData->date_end;

        //sprawdzenie dnia tygodnia dla daty z requestu
        $currentLessonWeekDay = Carbon::create($lessonDate)->dayOfWeek === 0 ? 7 : Carbon::create($lessonDate)->dayOfWeek;

        //sprawdzenie czy lekcja jest w wyznaczonym wczesniej zakresie od-do ze schedule
        $lessonInDateRange = ($lessonDate >= $scheduleFrom && (!($scheduleTo !== null) || $lessonDate <= $scheduleTo));

        //sprawdzenie czy schedule week day jest taki sam jak week day z requestu
        $isSameWeekDay = $scheduleData->classes_weekday === $currentLessonWeekDay;

        //sprawdzenie czy dla danej daty nie ma już instancji lekcji (jesli true, znaczy ze jest i odrzucamy)
        $isScheduleLessonForCurrentDate = $this->checkIsScheduleLesson($scheduleData->id, $lessonDate);


        return ($lessonInDateRange && $isSameWeekDay && !$isScheduleLessonForCurrentDate);
    }

    public function addAbsence($date, $scheduleId, $absentPerson, $absenceMessage): int
    {
        return DB::table('lessons')->insertGetId([
            'date' => $date,
            'schedule_id' => $scheduleId,
            'canceled_by_student' => $absentPerson === 'student',
            'canceled_by_teacher' => $absentPerson === 'teacher',
            'topic' => NULL,
            'notes' => NULL,
            'absence_reason' => $absenceMessage,
            'created_at' => now(),
        ]);
    }

    public function saveNewLesson($topic, $notes, $scheduleId, $lessonDate, $grades, $homeworks):int
    {
        $newLessonId = DB::table('lessons')->insertGetId([
            'date' => $lessonDate,
            'schedule_id' => $scheduleId,
            'topic' => $topic,
            'notes' => $notes,
            'canceled_by_teacher' => 0,
            'canceled_by_student' => 0,
            'created_at' => now(),
        ]);

        foreach ($grades as $gradeData) {
            $grade = $gradeData['grade'];
            $desc = $gradeData['desc'];

            DB::table('grades')->insert([
                'lesson_id' => $newLessonId,
                'grade' => $grade,
                'desc' => $desc,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($homeworks as $homework) {
            $desc = $homework['desc'];

            DB::table('homeworks')->insert([
                'lesson_id' => $newLessonId,
                'desc' => $desc,
                'date' => $homework['date'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $newLessonId;
    }

    public function updateLesson($topic, $notes, $lessonId, $lessonDate, $canceledByStudent, $canceledByTeacher, $absenceReason, $grades, $homeworks): int
    {
        //ustawia deleted_at dla wszystkich ocen dla tej lekcji
        DB::table('grades')
            ->where('lesson_id', $lessonId)
            ->update(['deleted_at' => now()]);

        //ustawia deleted_at dla wszystkich zadan domowych dla tej lekcji
        DB::table('homeworks')
            ->where('lesson_id', $lessonId)
            ->update(['deleted_at' => now()]);

        //ddaje nowe oceny nawet, jesli nie roznia sie od poprzednich
        foreach ($grades as $gradeData) {
            $grade = $gradeData['grade'];
            $desc = $gradeData['desc'];

            DB::table('grades')->insert([
                'lesson_id' => $lessonId,
                'grade' => $grade,
                'desc' => $desc,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        //ddaje nowe zadania domowe nawet, jesli nie roznia sie od poprzednich
        foreach ($homeworks as $homework) {
            $desc = $homework['desc'];

            DB::table('homeworks')->insert([
                'lesson_id' => $lessonId,
                'desc' => $desc,
                'completed_at' => isset($homework->completed_at) ? $homework->completed_at : null,
                'date' => $homework['date'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return DB::table('lessons')
            ->where('id', $lessonId)
            ->update([
                'date' => $lessonDate,
                'topic' => $topic,
                'notes' => $notes,
                'canceled_by_student' => $canceledByStudent,
                'canceled_by_teacher' => $canceledByTeacher,
                'absence_reason' => $absenceReason,
                // Inne pola do zaktualizowania
                'updated_at' => now(), // Przykładowo, możesz zaktualizować pole updated_at
            ]);
    }

    public function setHomeworkAsCompleted($homeworkId):void {
        DB::table('homeworks')
            ->where('id', $homeworkId)
            ->update(['completed_at' => now()]);
    }

    public function getTeacherAttachments($teacherId):array{
        return DB::select('SELECT * FROM files WHERE uploaded_by = ? AND deleted_at IS NULL', [$teacherId]);
    }

    public function getLessonAttachments($lessonId):array {
        return DB::select('SELECT * FROM files_lessons_pivot WHERE lesson_id = ? AND deleted_at IS NULL', [$lessonId]);
    }
}
