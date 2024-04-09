<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

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

    public function isProperLessonDate($scheduleId, $lessonDate) {
        $scheduleData = $this->scheduleRepository->getScheduleData($scheduleId);
//        dd($scheduleData);
        //tutaj trzeba sprawdzic czy dana lekcja odbywa sie w poprawnym terinie (pomiedzy min i max date zalozonym w schedules + czy jest to odpowiedni dzien tygodnia)
        return;
    }
}
