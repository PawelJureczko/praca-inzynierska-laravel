<?php

namespace App\Http\Controllers;

use App\Repositories\MessagesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MessagesController extends Controller
{

    private $messagesRepository;

    public function __construct(MessagesRepository $messagesRepository)
    {
        $this->messagesRepository = $messagesRepository;
    }

    public function messagesList(Request $request) {
        $userId = $request->user()->id;;
        $usersWithMessage = $this->messagesRepository->getFirstMessage($userId);
        return Inertia::render('Messages/MessagesList', [
            'usersWithMessage'=>$usersWithMessage
        ]);
    }

    public function messagesSingle(Request $request) {
        $interlocutorId = $request->route('id');
        $userData = $this->messagesRepository->getUserData($interlocutorId);
        $userId = $request->user()->id;;
        $messages = $this->messagesRepository->getAllMessages($interlocutorId, $userId);

        return Inertia::render('Messages/MessagesSingle', [
            'type' => 'edit',
            'id'=>$interlocutorId,
            'userData'=>$userData,
            'messages'=>$messages
        ]);
    }

    public function messageNew() {
        return Inertia::render('Messages/MessagesSingle', [
            'type'=>'new'
        ]);
    }
}
