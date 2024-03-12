<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class MessagesRepository
{
    public function getUserData($id)
    {
        return DB::select('SELECT first_name, last_name FROM  users WHERE id = ?', [$id]);
    }

    public function getFirstMessage($id)
    {
//        return DB::select('SELECT * FROM messages WHERE sender_id = ? OR receiver_id = ?', [$id, $id]);
//        $firstMessages = DB::select('
//            SELECT messages.*, u1.*, u2.*
//            FROM messages
//            JOIN users AS u1 ON messages.sender_id = u1.id
//            JOIN users AS u2 ON messages.receiver_id = u2.id
//            WHERE (messages.sender_id = ? OR messages.receiver_id = ?)
//            AND (messages.sender_id, messages.receiver_id, messages.created_at) IN (
//                SELECT sender_id, receiver_id, MAX(created_at)
//                FROM messages
//                WHERE sender_id = ? OR receiver_id = ?
//                GROUP BY sender_id, receiver_id
//            )
//        ', [$id, $id, $id, $id]);

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
        ', [$id, $id, $id, $id]);

//        dd($firstMessages);
        $filteredMessages = [];
        foreach ($firstMessages as $message) {
            $userType = $id === $message->sender_id ? 'receiver' : 'sender';
            $filteredMessage = [
                'id' => $userType==='sender' ? $message->sender_id : $message->receiver_id,
                'first_name' =>$userType==='sender' ? $message->sender_first_name : $message->receiver_first_name,
                'last_name' => $userType==='sender' ? $message->sender_last_name : $message->receiver_last_name,
                'content' => $message->content,
                'read_at' => $message->read_at,
                'created_at' => $message->created_at,
                'user_type' => $userType
            ];
            $filteredMessages[] = $filteredMessage;
        }

        return $filteredMessages;
    }

    public function getAllMessages($interlocutorId, $userId)
    {
        return DB::select('
        SELECT *
        FROM messages
        WHERE sender_id IN (?, ?) AND receiver_id IN (?, ?)
    ', [$interlocutorId, $userId, $interlocutorId, $userId]);
    }
}

;
