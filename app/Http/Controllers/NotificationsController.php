<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationsRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class NotificationsController extends Controller
{

    private $notificationsRepository;

    public function __construct(NotificationsRepository $notificationsRepository)
    {
        $this->notificationsRepository = $notificationsRepository;
    }

    //przychodzące zaproszenia
    public function invitations(Request $request)
    {
        $userId = $request->user()->id;
        $invitationsMapped = $this->notificationsRepository-> getInvitations($userId);

        return Inertia::render('Notifications/Notifications', [
            'invitations' => $invitationsMapped,
            'type' => 'invitations'
        ]);
    }

    //zadania domowe
    public function homeworks(Request $request)
    {
        $userId = $request->user()->id;
        $invitationsMapped = $this->notificationsRepository-> getInvitations($userId);;

        return Inertia::render('Notifications/Notifications', [
            'invitations' => $invitationsMapped,
            'type' => 'homeworks'
        ]);
    }

    public function acceptInvitation(Request $request)
    {
        $invitationId = $request->request->get('id');
        $user = $request->user();

        if ($user) {
            $this->notificationsRepository->acceptInvitation($invitationId);
            $userId = $request->user()->id;
            $invitationsMapped = $this->notificationsRepository-> getInvitations($userId);

            return response()->json([
                'message' => 'Zaproszenie zostało zaakceptowane',
                'invitations' => $invitationsMapped
            ], 200);
        } else {
            return response()->json(['error' => 'Brak autoryzacji'], 401);
        }
    }
}
