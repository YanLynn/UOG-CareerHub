<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Models\Chat;
use App\Models\ChatNotification;
use App\Models\ChatRoom;
use App\Models\Employer;
use App\Models\JobSeeker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function chatContacts(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $userId = $user->id;

        $contacts = [];

        if ($role === 'Employer') {
            $employer = Employer::where('user_id', $userId)->firstOrFail();

            // Create new chat room if jobseeker_id and job_id are passed
            if ($request->has('jobseeker_id')) {
                $jobseekerId = $request->jobseeker_id;
                $jobId = $request->job_id;

                ChatRoom::firstOrCreate([
                    'employer_id' => $employer->id,
                    'jobseeker_id' => $jobseekerId,
                    'job_id' => $jobId
                ]);
            }

            $contacts = ChatRoom::where('employer_id', $employer->id)
                ->with(['jobseeker.user', 'employer.user', 'chats'])
                ->get()
                ->map(function ($room) use ($userId, $employer) {
                    $jobSeekerUser = $room->jobseeker->user;
                    $employerUser = $room->employer->user;

                    $lastSeen = $jobSeekerUser->last_seen_at;
                    $isOnline = $lastSeen && Carbon::parse($lastSeen)->gt(now()->subMinutes(3));

                    $unreadCount = ChatNotification::where('chat_room_id', $room->id)
                        ->where('recipient_user_id', $userId)
                        ->where('is_read', 0)
                        ->count();

                    return [
                        'id' => $room->id,
                        'jobseeker_id' => $room->jobseeker_id,
                        'employer' => [
                            'id' => $room->employer->id,
                            'name' => $employerUser->name,
                            'company_image' => $employerUser->company_image
                        ],
                        'jobSeeker' => [
                            'id' => $room->jobseeker->id,
                            'name' => $jobSeekerUser->name,
                            'profile_img' => $jobSeekerUser->profile_img
                        ],
                        'name' => $jobSeekerUser->name,
                        'avatar' => $jobSeekerUser->profile_img ?: strtoupper(substr($jobSeekerUser->name, 0, 1)),
                        'isOnline' => $isOnline,
                        'statusText' => $isOnline
                            ? 'Online'
                            : ($lastSeen ? 'Last seen ' . Carbon::parse($lastSeen)->diffForHumans() : 'Offline'),
                        'unreadCount' => $unreadCount,
                        'messages' => $room->chats->map(function ($chat) use ($employer) {
                            $isMe = $chat->sender_type === 'employer' && $chat->sender_id === $employer->id;
                            return [
                                'from' => $isMe ? 'me' : 'them',
                                'text' => $chat->message,
                                'time' => $chat->created_at->format('h:i A'),
                            ];
                        }),
                    ];
                });

        } elseif ($role === 'JobSeeker') {
            $jobSeeker = JobSeeker::where('user_id', $userId)->firstOrFail();

            $contacts = ChatRoom::where('jobseeker_id', $jobSeeker->id)
                ->with(['employer.user', 'jobseeker.user', 'chats'])
                ->get()
                ->map(function ($room) use ($userId, $jobSeeker) {
                    $jobSeekerUser = $room->jobseeker->user;
                    $employerUser = $room->employer->user;

                    $lastSeen = $employerUser->last_seen_at;
                    $isOnline = $lastSeen && Carbon::parse($lastSeen)->gt(now()->subMinutes(3));

                    $unreadCount = ChatNotification::where('chat_room_id', $room->id)
                        ->where('recipient_user_id', $userId)
                        ->where('is_read', 0)
                        ->count();

                    return [
                        'id' => $room->id,
                        'jobseeker_id' => $room->jobseeker_id,
                        'employer' => [
                            'id' => $room->employer->id,
                            'name' => $employerUser->name,
                            'company_image' => $employerUser->company_image
                        ],
                        'jobSeeker' => [
                            'id' => $room->jobseeker->id,
                            'name' => $jobSeekerUser->name,
                            'profile_img' => $jobSeekerUser->profile_img
                        ],
                        'name' => $employerUser->name,
                        'avatar' => $employerUser->company_image ?: strtoupper(substr($employerUser->name, 0, 1)),
                        'isOnline' => $isOnline,
                        'statusText' => $isOnline
                            ? 'Online'
                            : ($lastSeen ? 'Last seen ' . Carbon::parse($lastSeen)->diffForHumans() : 'Offline'),
                        'unreadCount' => $unreadCount,
                        'messages' => $room->chats->map(function ($chat) use ($jobSeeker) {
                            $isMe = $chat->sender_type === 'jobseeker' && $chat->sender_id === $jobSeeker->id;
                            return [
                                'from' => $isMe ? 'me' : 'them',
                                'text' => $chat->message,
                                'time' => $chat->created_at->format('h:i A'),
                            ];
                        }),
                    ];
                });
        }

        return response()->json($contacts);
    }


    public function getMessages($chatRoomId)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        $messages = $chatRoom->chats()
            ->orderBy('created_at')
            ->get()
            ->map(function ($msg) {
                return [
                    'from' => $msg->sender_type,
                    'text' => $msg->message,
                    'time' => $msg->created_at->format('h:i A'),
                ];
            });

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $chatRoomId)
    {

        $request->validate([
            'text' => 'required|string'
        ]);

        $chatRoom = ChatRoom::findOrFail($chatRoomId);
        $user = Auth::user();

        $senderType = $user->role;
        if ($senderType === 'Employer') {
            $employer = Employer::where('user_id', $user->id)->first();
        } else if ($senderType === 'JobSeeker') {
            $jobSeeker = JobSeeker::where('user_id', $user->id)->first();
        }

        $senderId = $senderType === 'Employer'
            ? $employer->id
            : $jobSeeker->id;

        $chat = Chat::create([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $senderId,
            'sender_type' => $senderType,
            'message' => $request->text
        ]);

        broadcast(new MessageSent($chat))->toOthers();

        return response()->json(['success' => true, 'message' => 'Message sent']);
    }








    public function userTyping(Request $request)
    {
        $request->validate([
            'chat_room_id' => 'required|exists:chat_rooms,id',
            'typing' => 'required|boolean'
        ]);

        $user = Auth::user();

        broadcast(new UserTyping($request->chat_room_id, $user->id, $request->typing));

        return response()->json(['status' => 'Typing event sent']);
    }
}
