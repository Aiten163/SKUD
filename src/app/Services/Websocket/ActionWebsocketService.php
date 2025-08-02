<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Log;

class ActionWebsocketService
{

    public function test(): void
    {
        $this->data = [
            'event' => 'test_response',
            'data' => [
                'message' => 'msg from laravel',
                'status' => 'success'
            ]
        ];
        $this->answer = $this->data;
        $this->returnAnswer();
    }
}
