<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class NotificationsRepository {
    public function getInvitations($studentId) {
        $invitations = DB::select('
            SELECT teachers_students_pivot.*,
                   users.id AS teacher_id,
                   users.first_name AS teacher_first_name,
                   users.last_name AS teacher_last_name,
                   users.role AS teacher_role,
                   users.email AS teacher_email
            FROM teachers_students_pivot
            JOIN users ON teachers_students_pivot.teacher_id = users.id
            JOIN users AS teachers ON teachers_students_pivot.student_id = teachers.id
        WHERE teachers_students_pivot.student_id = ? AND teachers_students_pivot.accepted_at IS NULL
        ', [$studentId]);

        $invitationsMapped = [];

        foreach ($invitations as $invitation) {
            $teacher = [
                'id' => $invitation->teacher_id,
                'first_name' => $invitation->teacher_first_name,
                'last_name' => $invitation->teacher_last_name,
                'role' => $invitation->teacher_role,
                'email' => $invitation->teacher_email,
                // dodaj inne pola nauczyciela
            ];

            $invitationMapped = [
                'teacher' => $teacher,
                'created_at' => $invitation->created_at,
                'id' => $invitation->id
            ];

            $invitationsMapped[] = $invitationMapped;

        }

        return $invitationsMapped;
    }

    public function acceptInvitation($invitationId) {
        DB::update('UPDATE teachers_students_pivot
                SET accepted_at = NOW()
                WHERE id = ?', [$invitationId]);
    }
}