<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class StudentRepository
{
    // zapytanie o studentów którzy są przypisani do nauczyciela i którzy zaakceptowali zaproszenie
    public function getMyStudents($teacherId, $isSchedule = false)
    {
        $selectedColumns='';

        if ($isSchedule) {
            $selectedColumns = 'users.first_name, users.last_name, users.id';
        } else {
            $selectedColumns = 'users.first_name, users.last_name, users.created_at, users.email, users.phone_number, users.id';
        }

        return DB::select("
           SELECT $selectedColumns
                FROM users
                WHERE role = 'student'
                    AND EXISTS (
                        SELECT 1
                        FROM teachers_students_pivot
                        WHERE teachers_students_pivot.student_id = users.id
                            AND teachers_students_pivot.teacher_id = ?
                            AND teachers_students_pivot.accepted_at IS NOT NULL
                    );
        ", [$teacherId]);
    }

    // zapytanie o studentów którzy są przypisani do nauczyciela i którzy zaakceptowali zaproszenie
    public function getInvitedStudents($teacherId) {
        return DB::select('
            SELECT users.first_name, users.last_name, users.created_at, users.email, users.phone_number, users.id
            FROM users
            WHERE role = "student"
                AND EXISTS (
                    SELECT 1
                    FROM teachers_students_pivot
                    WHERE teachers_students_pivot.student_id = users.id
                    AND teachers_students_pivot.teacher_id = ?
                  AND teachers_students_pivot.accepted_at IS NULL
                );
        ', [$teacherId]);
    }

    //zapytanie do bazy o wylistowanie wszystkich studentów, którzy nie zostali jeszcze zaproszeni przez danego nauczyciela
    public function getNotInvitedStudents($teacherId)
    {
        return DB::select('
            SELECT users.first_name, users.last_name, users.created_at, users.email, users.phone_number, users.id
            FROM users
            WHERE role = "student"
            AND NOT EXISTS (
                SELECT 1
                FROM teachers_students_pivot
                WHERE teachers_students_pivot.student_id = users.id
                AND teachers_students_pivot.teacher_id = ?
            );', [$teacherId]);
    }

    public function getStudentsData($studentsId) {
        return DB::table('users')
            ->select('first_name', 'last_name', 'created_at', 'email', 'phone_number', 'id')
            ->where('id', $studentsId)
            ->first();
    }

    public function getStudentsLessons($studentId, $teacherId): Collection
    {
        return DB::table('lessons')
            ->join('schedule', 'lessons.schedule_id', '=', 'schedule.id')
            ->where('schedule.student_id', $studentId)
            ->where('schedule.teacher_id', $teacherId)
            ->select('lessons.id', 'date', 'topic', 'schedule.classes_time_start', 'schedule.classes_time_start', 'schedule.classes_time_end', 'canceled_by_student', 'canceled_by_teacher')
            ->get();
    }
}

