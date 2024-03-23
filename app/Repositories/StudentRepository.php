<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

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
}

