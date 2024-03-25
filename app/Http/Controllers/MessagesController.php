<?php

namespace App\Http\Controllers;

use App\Repositories\MessagesRepository;
use Illuminate\Http\Request;

class MessagesController extends Controller
{


    public function __construct(private readonly MessagesRepository $messagesRepository)
    {
    }

    public function messagesList(Request $request):Response {
        $userId = $request->user()->id;;
        $usersWithMessage = $this->messagesRepository->getFirstMessage($userId);
        return inertia('Messages/MessagesList', [
            'usersWithMessage'=>$usersWithMessage
        ]);
    }

    public function messagesSingle(Request $request):Response {
        $interlocutorId = $request->route('id');
        $userId = $request->user()->id;;
        $userData = $this->messagesRepository->getUserData($interlocutorId);
        $messages = $this->messagesRepository->getAllMessages($interlocutorId, $userId);

        if (end($messages)->receiver_id === $userId) {
            $this->messagesRepository->updateAllMessages($interlocutorId, $userId);
        }


        return inertia('Messages/MessagesSingle', [
            'type' => 'edit',
            'id'=>$interlocutorId,
            'userData'=>$userData,
            'messages'=>$messages
        ]);
    }

    public function reloadMessages(Request $request):JsonResponse {
        $interlocutorId = $request->query('id');
        $userId = $request->user()->id;;
        $messages = $this->messagesRepository->getAllMessages($interlocutorId, $userId);

        return response()->json([
            'messages' =>$messages,
        ], 200);
    }

    public function messageNew():Response {
        return inertia('Messages/MessagesSingle', [
            'type'=>'new'
        ]);
    }

    public function sendMessage(Request $request):JsonResponse {
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
