<?php
namespace App\Events;

use App\Models\UserMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UserMessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(UserMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->message->user_id); // Public channel like doctor notifications
    }

    public function broadcastAs()
    {
        return 'user-message.sent';
    }
}
