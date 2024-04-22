<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportsRepository
{
    public function getConnectedUsers($userRole, $userId): Collection
    {
        if ($userRole === 'teacher') {
            return DB::table('teachers_students_pivot as tsp')
                ->join('users as u', 'tsp.student_id', '=', 'u.id')
                ->select('u.id', 'u.first_name', 'u.last_name')
                ->where('tsp.teacher_id', $userId)
                ->get();
        } else {
            return DB::table('teachers_students_pivot as tsp')
                ->join('users as u', 'tsp.teacher_id', '=', 'u.id')
                ->select('u.id', 'u.first_name', 'u.last_name')
                ->where('tsp.student_id', $userId)
                ->get();
        }
    }

    public function checkIsNull($arr): array
    {
        $errors = [];
        foreach ($arr as $key => $value) {
            if (is_null($value)) {
                $errors[$key] = "Te pole nie może być puste";
            }
        }

        return $errors;
    }

    public function validateDate($beginDate, $endDate): array
    {
        $errors = [];

        if (($beginDate > $endDate) && !is_null($beginDate) && !is_null($endDate)) {
            $errors['dateTo'] = 'Data końcowa nie może być wcześniejsza niż początkowa';
        }

        return $errors;
    }

    public function getLessonsDataFromDateRange($studentId, $teacherId, $from, $to) {
        $lessonsData = DB::table('lessons')
            ->join('schedule', 'lessons.schedule_id', '=', 'schedule.id')
            ->where('schedule.student_id', $studentId)
            ->where('schedule.teacher_id', $teacherId)
            ->where('lessons.date', '>=', $from)
            ->where('lessons.date', '<=', $to)
            ->select('lessons.id', 'lessons.canceled_by_teacher', 'lessons.canceled_by_student', 'lessons.date')
            ->get();

        foreach ($lessonsData as $lesson) {
            $grades = DB::table('grades')
                ->where('lesson_id', $lesson->id)
                ->select('id', 'grade')
                ->get();

            $lesson->grades = $grades;
        }
        return $lessonsData;
    }
}
