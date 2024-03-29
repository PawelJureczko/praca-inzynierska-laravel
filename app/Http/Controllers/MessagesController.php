<?php

namespace App\Http\Controllers;

use App\Repositories\MessagesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Response;

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
        $usersWithoutConversation = $this->messagesRepository->getConnectedUsersWithoutConversation($request);


        if (count($messages) === 0) {
            return inertia('Messages/MessagesSingle', [
                'type' => 'new',
                'id'=>$interlocutorId,
                'userData'=>$userData,
                'usersWithoutConversation' => $usersWithoutConversation
            ]);
        }

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

    public function messageNew(Request $request):Response {
        $usersWithoutConversation = $this->messagesRepository->getConnectedUsersWithoutConversation($request);
        return inertia('Messages/MessagesSingle', [
            'type'=>'new',
            'usersWithoutConversation' => $usersWithoutConversation
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
