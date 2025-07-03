<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Reverb\Events\MessageReceived;

class BroadcastMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageReceived $event): void
    {
        $message = json_decode($event->message);
        $data = $message->data;

        if(!$message->event || !$message->event !== 'SendMessage'){
            return ;
        }

        $data = json_decode($data);
    }
}
