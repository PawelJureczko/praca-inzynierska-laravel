<?php

namespace App\Repositories;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;

class LessonRepository
{
    public function __construct(private readonly StudentRepository $studentRepository, private readonly ScheduleRepository $scheduleRepository)
    {
    }
    public function getScheduleData($id) {
        return DB::select('
        SELECT schedule.*, users.first_name AS student_first_name, users.last_name AS student_last_name
        FROM schedule
        JOIN users ON schedule.student_id = users.id
        WHERE schedule.id = ?
    ', [$id]);
    }

    public function getLessonData($id) {
        return DB::select('
        SELECT lessons.*, schedule.teacher_id, schedule.student_id, schedule.classes_time_start, schedule.classes_time_end, users.first_name AS student_first_name, users.last_name AS student_last_name
        FROM lessons
        JOIN schedule ON lessons.schedule_id = schedule.id
        JOIN users ON schedule.student_id = users.id
        WHERE lessons.id = ?
    ', [$id]);
    }

    //sprawdzenie, czy dla danego schedule nie występuje już jakaś instancja lekcji dla wybranej daty
    private function checkIsScheduleLesson($scheduleId, $date): bool
    {
        $record = DB::select('SELECT * FROM lessons WHERE schedule_id = ? AND date = ?', [$scheduleId, $date]);
        return(!empty($record));
    }

    public function isProperLessonDate($scheduleId, $lessonDate):bool {
        $scheduleData = $this->scheduleRepository->getScheduleData($scheduleId);
        $scheduleFrom = $scheduleData->date_begin;
        $scheduleTo = $scheduleData->date_end;

        //sprawdzenie dnia tygodnia dla daty z requestu
        $currentLessonWeekDay = Carbon::create($lessonDate)->dayOfWeek===0 ? 7 : Carbon::create($lessonDate)->dayOfWeek;

        //sprawdzenie czy lekcja jest w wyznaczonym wczesniej zakresie od-do ze schedule
        $lessonInDateRange = ($lessonDate>=$scheduleFrom && (!($scheduleTo !== null) || $lessonDate <= $scheduleTo));

        //sprawdzenie czy schedule week day jest taki sam jak week day z requestu
        $isSameWeekDay = $scheduleData->classes_weekday === $currentLessonWeekDay;

        //sprawdzenie czy dla danej daty nie ma już instancji lekcji (jesli true, znaczy ze jest i odrzucamy)
        $isScheduleLessonForCurrentDate = $this->checkIsScheduleLesson($scheduleData->id, $lessonDate);


        return ($lessonInDateRange && $isSameWeekDay && !$isScheduleLessonForCurrentDate);
//        dd($scheduleData->classes_weekday === $currentLessonWeekDay);
//        if ( && )
//        dd(Carbon::create($lessonDate)->dayOfWeek);
        //tutaj trzeba sprawdzic czy dana lekcja odbywa sie w poprawnym terinie (pomiedzy min i max date zalozonym w schedules + czy jest to odpowiedni dzien tygodnia)
//        return;
    }
}
