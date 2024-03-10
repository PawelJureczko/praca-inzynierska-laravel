<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentsController extends Controller
{
    //zapytanie do bazy o wylistowanie wszystkich studentów, którzy nie zostali jeszcze zaproszeni przez danego nauczyciela
    private function queryNotInvitedStudents($id)
    {
        return DB::select('SELECT *
            FROM users
            WHERE role = "student"
            AND NOT EXISTS (
                SELECT 1
                FROM teachers_students_pivot
                WHERE teachers_students_pivot.student_id = users.id
                AND teachers_students_pivot.teacher_id = ?
            );', [$id]);
    }

    //wyswietlenie wszystkich studentów
    public function getOtherStudents(Request $request)
    {
        $teacherId = $request->user()->id;
        $users = $this->queryNotInvitedStudents($teacherId);

        return Inertia::render('Students/StudentsList', [
            'users' => $users,
            'title' => 'Pozostali',
            'type' => 'other'
        ]);
    }

    // zapytanie o studentów którzy są przypisani do nauczyciela i którzy zaakceptowali zaproszenie
    private function queryMyStudents($id)
    {
        return DB::select('SELECT *
            FROM users
            WHERE role = "student"
                AND EXISTS (
                    SELECT 1
                    FROM teachers_students_pivot
                    WHERE teachers_students_pivot.student_id = users.id
                    AND teachers_students_pivot.teacher_id = ?
                  AND teachers_students_pivot.accepted_at IS NOT NULL
                );
        ', [$id]);
    }

    public function getMyStudents(Request $request)
    {
        $teacherId = $request->user()->id;
        $users = $this->queryMyStudents($teacherId);

        return Inertia::render('Students/StudentsList', [
            'users' => $users,
            'title' => 'Moja grupa',
            'type' => 'myGroup'
        ]);
    }

    // zapytanie o studentów którzy są przypisani do nauczyciela i którzy zaakceptowali zaproszenie
    private function queryInvitedStudents($id)
    {
        return DB::select('SELECT *
            FROM users
            WHERE role = "student"
                AND EXISTS (
                    SELECT 1
                    FROM teachers_students_pivot
                    WHERE teachers_students_pivot.student_id = users.id
                    AND teachers_students_pivot.teacher_id = ?
                  AND teachers_students_pivot.accepted_at IS NULL
                );
        ', [$id]);
    }

    public function getInvitedStudents(Request $request)
    {
        $teacherId = $request->user()->id;
        $users = $this->queryInvitedStudents($teacherId);

        return Inertia::render('Students/StudentsList', [
            'users' => $users,
            'title' => 'Zaproszeni',
            'type' => 'invited'
        ]);
    }






    //obsługa zaproszenia studenta i na sukces zwrócenia zaktualizowanej listy niezaproszonych studentów
    public function inviteStudent(Request $request)
    {
        $user = $request->user();
        $studentId = $request->input('student_id');

        if ($user && $studentId) {
            // Sprawdź, czy wpis nie istnieje przed dodaniem
            if (!$user->students()->where('student_id', $studentId)->exists()) {
                // Dodaj wpis do tabeli teacher_students_pivot
                $user->students()->attach($studentId, ['accepted_at' => null, 'created_at' => now()]);

                return response()->json([
                    'students' => $this->queryNotInvitedStudents($request->user()->id),
                    'message' => 'Zaproszenie wysłane pomyślnie!'
                ], 200);
            } else {
                return response()->json(['error' => 'Zaproszenie zostało już wcześniej wysłane'], 422);
            }
        } else {
            return response()->json(['error' => 'Błędne dane wejściowe'], 422);
        }
    }
}
