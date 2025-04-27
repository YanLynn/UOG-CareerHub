<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatRoomId;
    public $userId;
    public $typing; // true or false

    /**
     * Create a new event instance.
     */
    public function __construct($chatRoomId, $userId, $typing = true)
    {
        $this->chatRoomId = $chatRoomId;
        $this->userId = $userId;
        $this->typing = $typing;
    }

    /**
     * Get the name of the event to broadcast as (optional).
     */
    public function broadcastAs()
    {
        return 'UserTyping';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'userId' => $this->userId,
            'typing' => $this->typing,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('chat.' . $this->chatRoomId);
    }
}
