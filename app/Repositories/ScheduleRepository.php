<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleRepository
{
    public function checkIsNull($arr)
    {
        $errors = [];
        foreach ($arr as $key => $value) {
            if (is_null($value)) {
                $errors[$key] = "Te pole nie może być puste";
            }
        }

        return $errors;
    }

    public function validateTime($beginTime, $endTime)
    {
        $errors = [];

        $preparedBeginTime = intval(str_replace(':', '', $beginTime));
        $preparedEndTime = intval(str_replace(':', '', $endTime));

        if (($preparedBeginTime > $preparedEndTime) && !is_null($beginTime) && !is_null($endTime)) {
            $errors['class_time_end'] = 'Godzina rozpoczęcia nie może być późniejsza niż zakończenia';
        }

        return $errors;
    }

    public function saveScheduleElem(array $formData, $teacher_id)
    {
        $dateBegin = $formData['date_begin'];
        $dateEnd = $formData['date_end'];
        $classWeekday = $formData['class_weekday'];
        $classTimeStart = $formData['class_time_start'];
        $classTimeEnd = $formData['class_time_end'];
        $studentId = $formData['student_id'];

        // Wykonanie zapytania SQL w celu wstawienia danych do bazy danych
        DB::table('schedule')->insert([
            'teacher_id' => $teacher_id,
            'date_begin' => $dateBegin,
            'date_end' => $dateEnd,
            'classes_weekday' => $classWeekday,
            'classes_time_start' => $classTimeStart,
            'classes_time_end' => $classTimeEnd,
            'student_id' => $studentId,
            // Dodaj inne pola formularza, które chcesz zapisać w bazie danych
            'created_at' => now(),
        ]);
    }

    private function getStudentName($studentId)
    {
        return DB::select("
                        SELECT first_name, last_name
                        FROM users WHERE id = $studentId");
    }

    private function getWeekDayName($key)
    {
        $weekdays = [
            '1' => 'poniedziałki',
            '2' => 'wtorki',
            '3' => 'środy',
            '4' => 'czwartki',
            '5' => 'piątki',
            '6' => 'soboty',
            '7' => 'niedziele',
        ];

        return $weekdays[$key];
    }

    public function validateSchedule($request, $teacherId)
    {
        $teacherId = $teacherId;
        $weekday = $request->input('class_weekday');
        $startDate = $request->input('date_begin');
        $endDate = $request->input('date_end');
        $startTime = $request->input('class_time_start');
        $endTime = $request->input('class_time_end');

        // Sprawdzenie, czy istnieją rekordy o podanych teacher_id i weekday
        $existingSchedules = DB::select("
        SELECT *
        FROM schedule
        WHERE teacher_id = :teacherId
        AND classes_weekday = :weekday
    ", [
            'teacherId' => $teacherId,
            'weekday' => $weekday
        ]);
        $errors = [];
//        dd($existingSchedules);
        foreach ($existingSchedules as $schedule) {
//            // Sprawdzenie zakresu dat
            if (($startDate >= $schedule->date_begin && $startDate <= $schedule->date_end) || (($endDate >= $schedule->date_begin && $endDate <= $schedule->date_end))) {
                // Sprawdzenie kolizji godzinowych
                if (($startTime >= $schedule->classes_time_start && $startTime <= $schedule->classes_time_end) ||
                    $endTime >= $schedule->classes_time_start && $endTime <= $schedule->classes_time_end
                ) {
                    $studentId = $schedule->student_id;
                    $userName = $this->getStudentName($studentId);
                    $firstName = $userName[0]->first_name;
                    $lastName = $userName[0]->last_name;
                    $weekday = $this->getWeekDayName($schedule->classes_weekday);
                    $timeRange = substr($schedule->classes_time_start, 0, 5) . ' - ' . substr($schedule->classes_time_end, 0, 5);

                    $errors['general'] = 'Nie można dodać zajęć w tym terminie. Kolidują one z zajęciami z ' . $firstName . ' ' . $lastName . ' odbywającymi się w ' . $weekday . ' ' . 'w godzinach ' . $timeRange . '.';
//                    // Jeśli kolizja występuje, zwracamy komunikat walidacyjny
                    return $errors;
                }
            }
        }
        return $errors;
    }

    public function getLessonsForCurrentDates($request)
    {
        $teacherId = $request->user()->id;
        $userType = $request->user()->role;
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');

        $columnToSearch = $userType === 'teacher' ? 'schedule.teacher_id' : 'schedule.student_id';

        $existingSchedules = DB::select("
            SELECT
                schedule.*,
                teachers.first_name AS teacher_first_name,
                teachers.last_name AS teacher_last_name,
                students.first_name AS student_first_name,
                students.last_name AS student_last_name
            FROM
                schedule
            JOIN
                users AS teachers ON schedule.teacher_id = teachers.id
            JOIN
                users AS students ON schedule.student_id = students.id
            WHERE
                $columnToSearch = ?
                AND (
                    (schedule.date_end IS NULL AND schedule.date_begin <= ? AND schedule.date_begin <= ?)
                    OR
                    (schedule.date_end IS NOT NULL AND schedule.date_begin <= ? AND schedule.date_begin <= ? AND schedule.date_end >= ? AND schedule.date_end >= ?)
                )
        ", [
            $teacherId, $dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo
        ]);

        $lessonsOnWeek = DB::select("
            SELECT
                lessons.*,
                schedule.*,
                lessons.id AS id,
                teachers.first_name AS teacher_first_name,
                teachers.last_name AS teacher_last_name,
                students.first_name AS student_first_name,
                students.last_name AS student_last_name
            FROM lessons
            JOIN schedule ON lessons.schedule_id = schedule.id
            JOIN users AS teachers ON schedule.teacher_id = teachers.id
            JOIN users AS students ON schedule.student_id = students.id
            WHERE $columnToSearch = ?
            AND lessons.date >= ?
            AND lessons.date <= ?;
        ", [
            $teacherId, $dateFrom, $dateTo
        ]);

        $schedules = json_decode(json_encode($existingSchedules), true);
        $lessons = json_decode(json_encode($lessonsOnWeek), true);

        $lessonIds = array_unique(array_column($lessons, 'schedule_id'));

        $schedules = array_filter($schedules, function($schedule) use ($lessonIds) {
            return !in_array($schedule['id'], $lessonIds);
        });

        return [
            'schedules' => $schedules,
            'lessons' => $lessons
        ];
    }

    public function getScheduleData($scheduleId) {
        return DB::select('SELECT * FROM schedule WHERE id = ?', [$scheduleId])[0];
    }

    public function resignFromClasses($scheduleId, $date, $message, $senderId, $recipientId, $userType):void {
        DB::table('schedule')
            ->where('id', $scheduleId)
            ->update(['date_end' => $date, 'resigned_by' => $userType, 'resignation_reason' => $message]);

        $preparedMessage = 'Użytkownik zrezygnował z zajęć z dniem ' . implode('.', array_reverse(explode('-', $date))) . ($message !== '' ? ' i zostawił następującą wiadomość: ' . $message : '');

        DB::table('messages')->insert([
            'sender_id' => $senderId,
            'receiver_id' => $recipientId,
            'content' => $preparedMessage,
            'created_at' => now()
        ]);
    }
}
