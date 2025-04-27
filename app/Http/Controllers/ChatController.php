<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Chat;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getContacts()
    {
        // Sample: get all chat rooms with related employer/jobseeker names
        $contacts = ChatRoom::with(['employer', 'jobseeker'])
            ->withCount('chats')
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->employer->name . ' & ' . $room->jobseeker->name,
                    'lastMessage' => optional($room->chats()->latest()->first())->message ?? '',
                ];
            });

        return response()->json($contacts);
    }

    public function getMessages($chatRoomId)
    {
        $messages = Chat::where('chat_room_id', $chatRoomId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'from' => $msg->sender_type === 'employer' ? 'them' : 'me',
                    'text' => $msg->message,
                    'time' => $msg->created_at->format('h:i A'),
                ];
            });

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $chatRoomId)
    {
        $request->validate([
            'from' => 'required|in:me,them',
            'text' => 'required|string',
        ]);

        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        // Simulating current user type; adjust this based on your auth logic
        $senderType = $request->from === 'me' ? 'jobseeker' : 'employer';
        $senderId = $senderType === 'jobseeker' ? $chatRoom->jobseeker_id : $chatRoom->employer_id;

        $chat = Chat::create([
            'chat_room_id' => $chatRoomId,
            'sender_id' => $senderId,
            'sender_type' => $senderType,
            'message' => $request->text,
        ]);
        broadcast(new MessageSent($chat))->toOthers();
        return response()->json(['success' => true, 'message' => 'Message sent']);
    }

    public function userTyping(Request $request)
    {
        broadcast(new UserTyping($request->chat_room_id, auth()->id(), $request->typing));
        return response()->json(['status' => 'typing event sent']);
    }
}
