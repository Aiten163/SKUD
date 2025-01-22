<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AuthCodeGenerated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public int $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function broadcastOn()
    {
        echo "111111111111";
        // Используем публичный канал для генерации кода.
        return new Channel('auth');
    }
}
