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
    private function getNotInvitedStudents()
    {
        return DB::select('SELECT *
            FROM users
            WHERE role = "student"
            AND NOT EXISTS (
                SELECT 1
                FROM teachers_students_pivot
                WHERE teachers_students_pivot.student_id = users.id
                AND teachers_students_pivot.teacher_id = 1
            );');
    }

    //wyswietlenie wszystkich studentów
    public function index() {
        $users = $this->getNotInvitedStudents();

        return Inertia::render('Students/StudentsList', [
            'users'=>$users
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
                $user->students()->attach($studentId, ['isAccepted' => false]);

//                $updatedStudents = $user->students;

                return response()->json(['students' => $this->getNotInvitedStudents()], 200);
            } else {
                return response()->json(['error' => 'Wpis już istnieje'], 422);
            }
        } else {
            return response()->json(['error' => 'Błędne dane wejściowe'], 422);
        }
    }
}
