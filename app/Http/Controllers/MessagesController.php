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
        $userId = $request->user()->id;;
        $userData = $this->messagesRepository->getUserData($interlocutorId);
        $messages = $this->messagesRepository->getAllMessages($interlocutorId, $userId);

        if (end($messages)->receiver_id === $userId) {
            $this->messagesRepository->updateAllMessages($interlocutorId, $userId);
        }


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

    public function sendMessage(Request $request) {
        $senderId = $request->user()->id;;
        $receiverId = $request->input('receiver_id');
        $message = $request->input('message');

        $this->messagesRepository->sendMessage($senderId, $receiverId, $message);

        $allMessages = $this->messagesRepository->getAllMessages($receiverId, $senderId);

        return response()->json([
            'messages' =>$allMessages,
        ], 200);
    }
}
