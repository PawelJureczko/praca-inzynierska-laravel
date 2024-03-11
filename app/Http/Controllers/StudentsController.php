<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentsController extends Controller
{

    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    //wyswietlenie wszystkich studentów
    public function getOtherStudents(Request $request)
    {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getNotInvitedStudents($teacherId);

        return Inertia::render('Students/StudentsList', [
            'users' => $users,
            'title' => 'Pozostali',
            'type' => 'other'
        ]);
    }

    // zapytanie o studentów którzy są przypisani do nauczyciela i którzy zaakceptowali zaproszenie
    public function getMyStudents(Request $request)
    {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getMyStudents($teacherId);

        return Inertia::render('Students/StudentsList', [
            'users' => $users,
            'title' => 'Moja grupa',
            'type' => 'myGroup'
        ]);
    }

    public function getInvitedStudents(Request $request)
    {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getInvitedStudents($teacherId);

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
                    'students' =>$this->studentRepository->getNotInvitedStudents($request->user()->id),
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
