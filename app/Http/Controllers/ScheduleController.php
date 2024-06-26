<?php

namespace App\Http\Controllers;

use App\Repositories\StudentRepository;
use App\Repositories\ScheduleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ScheduleController extends Controller
{


    public function __construct(private readonly StudentRepository $studentRepository, private readonly ScheduleRepository $scheduleRepository)
    {
    }

    public function index():Response {
        return inertia('Schedule/Schedule', [
            'test'=>'test'
        ]);
    }

    public function create(Request $request):Response {
        $teacherId = $request->user()->id;
        $users = $this->studentRepository->getMyStudents($teacherId, true);

        return inertia('Schedule/ScheduleCreate', [
            'students' => $users,

        ]);
    }

    public function save(Request $request):JsonResponse {
        $formData = $request->all();
        $teacherId = $request->user()->id;
        $studentId = $request->input('student_id');
        $errors = [];

        $errors += $this->scheduleRepository->checkIsNull($formData);
        $errors += $this->scheduleRepository->validateTime($formData['class_time_start'], $formData['class_time_end']);
        if (count($errors) === 0) {
            $errors += $this->scheduleRepository->validateSchedule($request, $teacherId, $studentId);
        }
        if (count($errors) > 0) {
            return response()->json([
                'errors' =>$errors,
            ], 422);
        } else {
            $this->scheduleRepository->saveScheduleElem($formData, $teacherId);
        }
        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function getScheduleForDateRange(Request $request):JsonResponse {
        $schedule = $this->scheduleRepository->getLessonsForCurrentDates($request);
        return response()->json([
            'schedule' => $schedule
        ]);
    }

    public function resignFromClasses(Request $request):JsonResponse {
        $senderId = $request->user()->id;
        $recipientId = $request->input('recipientId');
        $scheduleId = $request->input('scheduleId');
        $userType = $request->input('userType');
        $date = $request->input('date');
        $desc = $request->input('desc');
        $this->scheduleRepository->resignFromClasses($scheduleId, $date, $desc, $senderId, $recipientId, $userType);
        return response()->json([
            'status' => 'ok'
        ]);
    }
}
