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
        $teacherId = $scheduleData[0]->teacher_id;
        $teacherAttachments = $this->lessonRepository->getTeacherAttachments($teacherId);
        $this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate);
        if ($this->lessonRepository->isProperLessonDate($scheduleId, $lessonDate) === false) {
            abort(404);
        }
        return inertia('Lesson/Lesson', [
            'type' => 'new',
            'lessonDate' => $lessonDate,
            'scheduleData' => $scheduleData[0],
            'teacherAttachments' => $teacherAttachments
        ]);
    }

    public function editLesson(Request $request): Response
    {
        $lessonId = $request->route('id');
        $lessonData = $this->lessonRepository->getLessonData($lessonId);
        $grades = $this->lessonRepository->getGradesForLesson($lessonId);
        $homeworks = $this->lessonRepository->getHomeworksForLesson($lessonId);
        $teacherId = $lessonData[0]->teacher_id;
        $teacherAttachments = $this->lessonRepository->getTeacherAttachments($teacherId);
        $lessonAttachments = $this->lessonRepository->getLessonAttachments($lessonId);

        return inertia('Lesson/Lesson', [
            'type' => 'edit',
            'lessonData' => $lessonData[0],
            'grades'=>$grades,
            'homeworks'=>$homeworks,
            'teacherAttachments'=>$teacherAttachments,
            'lessonAttachments'=>$lessonAttachments
        ]);
    }

    //dodanie nieobecności
    public function addAbsence(Request $request): JsonResponse
    {
        $newLessonId = $this->lessonRepository->addAbsence($request->all()['date'], $request->all()['schedule_id'], $request->all()['absent_person'], $request->all()['absence_reason']);

        return response()->json([
            'status' => 'ok',
            'lessonId' => $newLessonId
        ], 200);
    }

    //Stworzenie nowej lekcji ze schedule
    public function saveLesson(Request $request): JsonResponse
    {
        $topic = $request->input('topic');
        $notes = $request->input('notes');
        $scheduleId = $request->input('schedule_id');
        $lessonDate = $request->input('lessonDate');
        $grades = $request->input('grades');
        $homeworks = $request->input('homeworks');
        $filesIds = $request->input('filesIds');

        $errors = [];
        $errors += $this->scheduleRepository->checkIsNull($request->all());

        if (count($errors) > 0) {
            return response()->json([
                'errors' => $errors,
            ], 422);
        } else {
            $lessonId = $this->lessonRepository->saveNewLesson($topic, $notes, $scheduleId, $lessonDate, $grades, $homeworks, $filesIds);

            return response()->json([
                'status' => 'ok',
                'lessonId' => $lessonId
            ], 200);
        }
    }

    //Zaktualizowanie istniejącej lekcji ze schedule
    public function updateLesson(Request $request): JsonResponse
    {
        $topic = $request->input('topic');
        $notes = $request->input('notes');
        $lessonId = $request->input('lessonId');
        $lessonDate = $request->input('lessonDate');
        $canceledByStudent = $request->input('canceledByStudent');
        $canceledByTeacher = $request->input('canceledByTeacher');
        $absenceReason = $request->input('absenceReason');
        $grades = $request->input('grades');
        $homeworks = $request->input('homeworks');
        $filesIds = $request->input('filesIds');

        $errors = [];
        $errors += $this->scheduleRepository->checkIsNull($request->only(['topic', 'notes']));

        if (count($errors) > 0) {
            return response()->json([
                'errors' => $errors,
            ], 422);
        } else {
            $lessonId = $this->lessonRepository->updateLesson($topic, $notes, $lessonId, $lessonDate, $canceledByStudent, $canceledByTeacher, $absenceReason, $grades, $homeworks, $filesIds);

            return response()->json([
                'status' => 'ok',
                'lessonId' => $lessonId
            ], 200);
        }
    }

    public function markHomeworkAsDone(Request $request): JsonResponse{
        $homeworkId = $request->input('homeworkId');
        $lessonId = $request->input('lessonId');
//        dd($lessonId);
        $this->lessonRepository->setHomeworkAsCompleted($homeworkId);
        $homeworks = $this->lessonRepository->getHomeworksForLesson($lessonId);
        return response()->json([
            'status' => 'ok',
            'homeworks' => $homeworks
        ], 200);
    }
}
