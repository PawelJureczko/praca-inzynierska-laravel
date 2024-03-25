<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class NotificationsController extends Controller
{
    // tutaj promowanie Repozytorium w konstruktorze

    public function __construct(
        private readonly NotificationsRepository $notificationsRepository
    )
    {
    }

    //przychodzące zaproszenia
    public function invitations(Request $request): Response
    {
        $userId = $request->user()->id;
        $invitationsMapped = $this->notificationsRepository-> getInvitations($userId);

        return inertia('Notifications/Notifications', [ // zamiana na helper inertia
            'invitations' => $invitationsMapped,
            'type' => 'invitations'
        ]);
    }

    //zadania domowe
    public function homeworks(Request $request): Response
    {
        $userId = $request->user()->id;
        $invitationsMapped = $this->notificationsRepository-> getInvitations($userId);;

        return inertia('Notifications/Notifications', [
            'invitations' => $invitationsMapped,
            'type' => 'homeworks'
        ]);
    }

    public function acceptInvitation(Request $request): JsonResponse
    {
        $invitationId = $request->id;
        $user = $request->user();

        if ($user) {
            $this->notificationsRepository->acceptInvitation($invitationId);
            $userId = $user->id;
            $invitationsMapped = $this->notificationsRepository-> getInvitations($userId);

            return response()->json([
                'message' => 'Zaproszenie zostało zaakceptowane',
                'invitations' => $invitationsMapped
            ]);
        } else {
            return response()->json(['error' => 'Brak autoryzacji'], 401);
        }
    }
}
