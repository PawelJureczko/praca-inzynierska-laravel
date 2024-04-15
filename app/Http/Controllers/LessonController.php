<?php

namespace App\Http\Controllers;

use App\Repositories\LessonRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class LessonController extends Controller
{
    public function __construct(private readonly LessonRepository $lessonRepository)
    {
    }
    public function createLesson(Request $request):Response {
        $scheduleId = $request->route('id');
        $lessonDate = $request->query('date');
        $this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate);
        $scheduleData = $this->lessonRepository->getScheduleData($scheduleId);
        $this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate);
        if ($this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate) === false) {
            abort(404);
        }
        return inertia('Lesson/Lesson', [
            'type'=>'new',
            'scheduleData' => $scheduleData[0],
        ]);
    }

    public function editLesson(Request $request):Response {
        $lessonId = $request->route('id');
        $lessonData = $this->lessonRepository->getLessonData($lessonId);

        return inertia('Lesson/Lesson', [
            'type'=>'edit',
            'lessonData'=>$lessonData[0]
        ]);
    }

    //Stworzenie nowej lekcji ze schedule
    public function saveLesson(Request $request):JsonResponse {

        return response()->json([
            'status' =>'ok',
        ], 200);
    }

    //Zaktualizowanie istniejącej lekcji ze schedule
    public function updateLesson(Request $request):JsonResponse {
        return response()->json([
            'status' =>'ok',
        ], 200);
    }
}
