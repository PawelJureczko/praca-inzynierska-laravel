<?php

namespace App\Http\Middleware;

use App\Repositories\LessonRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LessonParticipant
{

    public function __construct(private readonly LessonRepository $lessonRepository)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();
        $role = auth()->user()->role;
        $userId = auth()->user()->id;
        $requestId = $request->route('id');
        if ($routeName === 'lesson.create') {
            if (empty($this->lessonRepository->getScheduleData($requestId))) {
                abort(404, '');
            }
            $scheduleDetails = $this->lessonRepository->getScheduleData($requestId)[0];
            if ($role === 'teacher') {
                if ($scheduleDetails->teacher_id === $userId) {
                    return $next($request);
                }
            } else {
                if ($scheduleDetails->student_id === $userId) {
                    return $next($request);
                }
            }
        } else if ($routeName === 'lesson.edit') {
            if (empty($this->lessonRepository->getLessonData($requestId))) {
                abort(404, '');
            }
            $lessonDetails = $this->lessonRepository->getLessonData($requestId)[0];
            if ($role === 'teacher') {
                if ($lessonDetails->teacher_id === $userId) {
                    return $next($request);
                }
            } else {
                if ($lessonDetails->student_id === $userId) {
                    return $next($request);
                }
            }
        }
//        dd($id);
        abort(403, 'Unauthorized'); // Przypadek braku dostępu

    }
}
