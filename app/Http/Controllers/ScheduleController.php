<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ScheduleController extends Controller
{

    private $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function index() {
        return Inertia::render('Schedule/Schedule', [
            'test'=>'test'
        ]);
    }

    public function create(Request $request) {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getMyStudents($teacherId, true);

        return Inertia::render('Schedule/ScheduleCreate', [
            'students' => $users,

        ]);
    }
}
