<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationsRepository;
use App\Repositories\ReportsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ReportsController extends Controller
{
    public function __construct(
        private readonly ReportsRepository $reportsRepository
    )
    {
    }
    public function index(Request $request): Response {
        $userRole = $request->user()->role;
        $userId = $request->user()->id;
        $connectedUsers = $this->reportsRepository->getConnectedUsers($userRole, $userId);

        return Inertia::render('Reports/Reports', [
            'role'=> $userRole,
            'connectedUsers' => $connectedUsers
        ]);
    }

    public function getDataFromDateRange(Request $request) {
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');
        $chosenUserId = $request->input('chosenUserId');
        $role = $request->user()->role;
        $studentId = null;
        $teacherId = null;
        if ($role === 'teacher') {
            $teacherId = $request->user()->id;
            $studentId = $chosenUserId;
        } else {
            $studentId = $request->user()->id;
            $teacherId = $chosenUserId;
        }

        $errors = [];
        $errors += $this->reportsRepository->checkIsNull(['dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'chosenUserId'=>$chosenUserId]);
        $errors += $this->reportsRepository->validateDate($dateFrom, $dateTo);

        if (count($errors) > 0) {
            return response()->json([
                'errors' =>$errors,
            ], 422);
        }
        $lessonsData = $this->reportsRepository->getLessonsDataFromDateRange($studentId, $teacherId, $dateFrom, $dateTo);

        return response()->json([
            'status' => 'ok',
            'lessonsData' => $lessonsData
        ]);
    }
}
