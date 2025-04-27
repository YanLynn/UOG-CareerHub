<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat->chat_room_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->chat->id,
            'chat_room_id' => $this->chat->chat_room_id,
            'sender_id' => $this->chat->sender_id,
            'sender_type' => $this->chat->sender_type,
            'message' => $this->chat->message,
            'created_at' => $this->chat->created_at->format('h:i A'),
        ];
    }
}
