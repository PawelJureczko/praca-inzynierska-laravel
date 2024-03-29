<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\StudentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentsController extends Controller
{


    public function __construct(private readonly StudentRepository $studentRepository)
    {
    }

    //wyswietlenie wszystkich studentów
    public function getOtherStudents(Request $request):Response
    {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getNotInvitedStudents($teacherId);

        return inertia('Students/StudentsList', [
            'users' => $users,
            'title' => 'Pozostali',
            'type' => 'other'
        ]);
    }

    // zapytanie o studentów którzy są przypisani do nauczyciela i którzy zaakceptowali zaproszenie
    public function getMyStudents(Request $request):Response
    {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getMyStudents($teacherId);

        return inertia('Students/StudentsList', [
            'users' => $users,
            'title' => 'Moja grupa',
            'type' => 'myGroup'
        ]);
    }

    public function getInvitedStudents(Request $request):Response
    {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getInvitedStudents($teacherId);

        return inertia('Students/StudentsList', [
            'users' => $users,
            'title' => 'Zaproszeni',
            'type' => 'invited'
        ]);
    }

    //obsługa zaproszenia studenta i na sukces zwrócenia zaktualizowanej listy niezaproszonych studentów
    public function inviteStudent(Request $request):JsonResponse
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

    public function showStudentDetails(Request $request):Response {
        $studentId = $request->route('id');
        $studentData = $this->studentRepository->getStudentsData($studentId);
        return inertia('Students/StudentDetails', [
            'studentData' => $studentData,
        ]);
    }
}
