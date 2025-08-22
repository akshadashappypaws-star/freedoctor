<?php
namespace App\Events;

use App\Models\AdminMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AdminMessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(AdminMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('admin-notifications'); // Channel for admin notifications
    }

    public function broadcastAs()
    {
        return 'admin-message.sent';
    }
}
