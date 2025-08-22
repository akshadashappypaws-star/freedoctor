<?php
namespace App\Events;

use App\Models\DoctorMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class DoctorMessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(DoctorMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('doctor.' . $this->message->doctor_id); // Private or public channel
    }

    public function broadcastAs()
    {
        return 'doctor-message.sent';
    }
}
