<?php

namespace App\Http\Controllers;

use App\Repositories\LessonRepository;
use App\Repositories\ScheduleRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class LessonController extends Controller
{
    public function __construct(private readonly LessonRepository $lessonRepository, private readonly ScheduleRepository $scheduleRepository)
    {
    }

    public function createLesson(Request $request): Response
    {
        $scheduleId = $request->route('id');
        $lessonDate = $request->query('date');
        $this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate);
        $scheduleData = $this->lessonRepository->getScheduleData($scheduleId);
        $this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate);
        if ($this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate) === false) {
            abort(404);
        }
        return inertia('Lesson/Lesson', [
            'type' => 'new',
            'lessonDate' => $lessonDate,
            'scheduleData' => $scheduleData[0],
        ]);
    }

    public function editLesson(Request $request): Response
    {
        $lessonId = $request->route('id');
        $lessonData = $this->lessonRepository->getLessonData($lessonId);

        return inertia('Lesson/Lesson', [
            'type' => 'edit',
            'lessonData' => $lessonData[0]
        ]);
    }

    //Stworzenie nowej lekcji ze schedule
    public function saveLesson(Request $request): JsonResponse
    {
        $topic = $request->all()['topic'];
        $notes = $request->all()['notes'];
        $scheduleId = $request->all()['schedule_id'];
        $lessonDate = $request->all()['lessonDate'];

        $errors = [];
        $errors += $this->scheduleRepository->checkIsNull($request->all());

        if (count($errors) > 0) {
            return response()->json([
                'errors' =>$errors,
            ], 422);
        } else {
            $lessonId = $this->lessonRepository->saveNewLesson($topic, $notes, $scheduleId, $lessonDate);

            return response()->json([
                'status' => 'ok',
                'lessonId' => $lessonId
            ], 200);
        }
    }

    public function addAbsence(Request $request): JsonResponse
    {
        $newLessonId = $this->lessonRepository->addAbsence($request->all()['date'], $request->all()['schedule_id'], $request->all()['absent_person'], $request->all()['absence_reason']);

        return response()->json([
            'status' => 'ok',
            'lessonId' => $newLessonId
        ], 200);
    }

    //Zaktualizowanie istniejącej lekcji ze schedule
    public function updateLesson(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
        ], 200);
    }
}
