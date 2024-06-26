<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class MessagesRepository
{
    public function getUserData($id)
    {
        return DB::select('SELECT first_name, last_name, id FROM  users WHERE id = ?', [$id]);
    }

    public function getFirstMessage($id)
    {

        $firstMessages = DB::select('
            SELECT messages.id AS id, messages.sender_id, messages.receiver_id, messages.content, messages.read_at, messages.created_at,
            u1.id AS sender_id, u1.first_name AS sender_first_name, u1.last_name AS sender_last_name,
            u2.id AS receiver_id, u2.first_name AS receiver_first_name, u2.last_name AS receiver_last_name
            FROM messages
            JOIN users AS u1 ON messages.sender_id = u1.id
            JOIN users AS u2 ON messages.receiver_id = u2.id
            WHERE (messages.sender_id = ? OR messages.receiver_id = ?)
            AND (messages.sender_id, messages.receiver_id, messages.created_at) IN (
                SELECT sender_id, receiver_id, MAX(created_at)
                FROM messages
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY sender_id, receiver_id
            )
            ORDER BY messages.created_at DESC
        ', [$id, $id, $id, $id]);

//        dd($firstMessages);

//        dd($firstMessages);
        $filteredMessages = [];
        foreach ($firstMessages as $message) {
            $userType = $id === $message->sender_id ? 'sender' : 'receiver';
            $userId = $userType === 'receiver' ? $message->sender_id : $message->receiver_id;
            if (!isset($filteredMessages[$userId])) {
                $filteredMessage = [
                    'id' => $userId,
                    'first_name' => $userType === 'receiver' ? $message->sender_first_name : $message->receiver_first_name,
                    'last_name' => $userType === 'receiver' ? $message->sender_last_name : $message->receiver_last_name,
                    'content' => $message->content,
                    'read_at' => $message->read_at,
                    'created_at' => $message->created_at,
                    'user_type' => $userType
                ];
                $filteredMessages[$userId] = $filteredMessage;
            }
        }

        return $filteredMessages;
    }

    public function getAllMessages($interlocutorId, $userId)
    {
//       $interlocutorId - osoba z którą rozmawiam
//       $userId - osoba, która jest zalogowana
        return DB::select('
        SELECT *
        FROM messages
        WHERE sender_id IN (?, ?) AND receiver_id IN (?, ?)
        ORDER BY created_at ASC
    ', [$interlocutorId, $userId, $interlocutorId, $userId]);
    }

    //lista wszystkich uzytkownikow z ktorymi zalogowana osoba jest 'połączona', a z którymi nie ma konwersacji
    public function getConnectedUsersWithoutConversation($request)
    {
        $role = $request->user()->role;
        $userId = $request->user()->id;

        $usersWithConversationsIds = [];
        foreach ($this->getFirstMessage($userId) as $item) {
            $usersWithConversationsIds[] = $item['id'];
        }
//        $allMyUsers = null;
        if ($role === 'teacher') {
            $allMyUsers = DB::select('
                SELECT *
                FROM teachers_students_pivot
                WHERE teacher_id = ?
            ', [$userId]);
        } else {
            $allMyUsers = DB::select('
                SELECT *
                FROM teachers_students_pivot
                WHERE student_id = ?
            ', [$userId]);
        }

        $allMyUsersIds = [];
        foreach ($allMyUsers as $item) {
            if ($role === 'teacher') {
                $allMyUsersIds[] = $item->student_id;
            } else {
                $allMyUsersIds[] = $item->teacher_id;
            }
        }
        $idsWithoutConversation = array_diff($allMyUsersIds, $usersWithConversationsIds);

        return DB::table('users')
            ->whereIn('id', $idsWithoutConversation)
            ->get();
    }

    public function updateAllMessages($interlocutorId, $userId)
    {
        DB::statement("
            UPDATE messages
            SET read_at = NOW()
            WHERE (sender_id = ? OR sender_id = ?)
                AND (receiver_id = ? OR receiver_id = ?)
        ", [$interlocutorId, $userId, $interlocutorId, $userId]);
    }

    public function sendMessage($senderId, $receiverId, $message)
    {
        DB::statement("
        INSERT INTO messages (sender_id, receiver_id, content, created_at)
        VALUES (?, ?, ?, NOW())
    ", [$senderId, $receiverId, $message]);
    }
}

;
