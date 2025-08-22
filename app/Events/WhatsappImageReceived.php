<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WhatsappImageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $imageData;

    public function __construct(array $imageData)
    {
        $this->imageData = $imageData;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('whatsapp.images');
    }

    public function broadcastAs()
    {
        return 'image.received';
    }
}
