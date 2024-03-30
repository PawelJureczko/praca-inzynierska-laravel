<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class LessonRepository
{
    public function getScheduleData($id) {
        return DB::select('SELECT * FROM schedule WHERE id = ?', [$id]);
    }

    public function getLessonData($id) {
        return DB::select('
        SELECT lessons.*, schedule.teacher_id, schedule.student_id
        FROM lessons
        JOIN schedule ON lessons.schedule_id = schedule.id
        WHERE lessons.id = ?
    ', [$id]);
    }
}
