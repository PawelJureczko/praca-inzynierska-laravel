<?php

namespace App\Http\Controllers;

use App\Repositories\LessonRepository;
use Illuminate\Http\Request;
use Inertia\Response;

class LessonController extends Controller
{
    public function __construct(private readonly LessonRepository $lessonRepository)
    {
    }
    public function createLesson(Request $request):Response {
        $scheduleId = $request->route('id');
        $scheduleData = $this->lessonRepository->getScheduleData($scheduleId);
        return inertia('Lesson/Lesson', [
            'type'=>'new',
            'scheduleData' => $scheduleData
        ]);
    }

    public function editLesson(Request $request):Response {
        $lessonId = $request->route('id');
        $lessonData = $this->lessonRepository->getLessonData($lessonId);

        return inertia('Lesson/Lesson', [
            'type'=>'edit',
            'lessonData'=>$lessonData
        ]);
    }
}
