<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

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
    WHERE teachers_students_pivot.student_id = ?
', [$userId]);

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
                'created_at' => $invitation->created_at
            ];

            $invitationsMapped[] = $invitationMapped;

        }
        return Inertia::render('Notifications/Notifications', [
            'invitations' => $invitationsMapped
        ]);
    }
}
