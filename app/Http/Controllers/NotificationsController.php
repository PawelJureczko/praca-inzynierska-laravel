<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class NotificationsController extends Controller
{
    private function getInvitations($id) {
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
        ', [$id]);

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

    //przychodzące zaproszenia
    public function invitations(Request $request)
    {
        $userId = $request->user()->id;
        $invitationsMapped = $this->getInvitations($userId);

        return Inertia::render('Notifications/Notifications', [
            'invitations' => $invitationsMapped,
            'type' => 'invitations'
        ]);
    }

    //zadania domowe
    public function homeworks(Request $request)
    {
        $userId = $request->user()->id;
        $invitationsMapped = $this->getInvitations($userId);

        return Inertia::render('Notifications/Notifications', [
            'invitations' => $invitationsMapped,
            'type' => 'homeworks'
        ]);
    }

    public function acceptInvitation(Request $request)
    {
        $invitationId = $request->request->get('id');
        $user = $request->user();

        if ($user) {
            DB::update('UPDATE teachers_students_pivot
                SET accepted_at = NOW()
                WHERE id = ?', [$invitationId]);

            $userId = $request->user()->id;
            $invitationsMapped = $this->getInvitations($userId);

            return response()->json([
                'message' => 'Zaproszenie zostało zaakceptowane',
                'invitations' => $invitationsMapped
            ], 200);
        } else {
            return response()->json(['error' => 'Brak autoryzacji'], 401);
        }
    }
}
