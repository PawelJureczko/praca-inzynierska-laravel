<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UsersRepository
{
    // sprawdzenie, czy dany nauczyciel i uczeń mają nawiązany kontakt
    public function doesConnectionExist($teacherId, $studentId)
    {
        return DB::table('teachers_students_pivot')
            ->where('teacher_id', $teacherId)
            ->where('student_id', $studentId)
            ->whereNotNull('accepted_at')
            ->exists();
    }
}

