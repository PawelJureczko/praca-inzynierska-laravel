<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;
use App\Repositories\ScheduleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ScheduleController extends Controller
{

    private $studentRepository;
    private $scheduleRepository;

    public function __construct(StudentRepository $studentRepository, ScheduleRepository $scheduleRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->scheduleRepository = $scheduleRepository;
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

    public function save(Request $request) {
        $formData = $request->all();
        $teacherId = $request->user()->id;
        $errors = [];

        $errors += $this->scheduleRepository->checkIsNull($formData);
        $errors += $this->scheduleRepository->validateTime($formData['class_time_start'], $formData['class_time_end']);

        if (count($errors) > 0) {
            return response()->json([
                'errors' =>$errors,
            ], 422);
        } else {
            $this->scheduleRepository->saveScheduleElem($formData, $teacherId);
        }
        return response()->json([
            'test' =>$formData['class_time_start'],
        ], 200);
    }
}
