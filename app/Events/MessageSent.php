<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        // I-access ang chat_id mula sa unang item ng $message
        $this->message = $message;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // Using Channel class for broadcasting
        return new Channel((env('CHANNEL_NAME')?: 'dswdqrt-chat-uat-staging') .'.' . ($this->message->isNotEmpty() ? $this->message->first()->chat_id : 'default_chat_id'));
    }

    /**
     * Get the event name that should be broadcast.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message-sent';
    }

    /**
     * Get the data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['message' => $this->message];
    }
}
