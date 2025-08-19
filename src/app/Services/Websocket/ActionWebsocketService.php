<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Log;

class ActionWebsocketService
{

    public static function test(): array
    {
        Log::info(message: 'msg from laravel');
        return [
            'message' => 'msg from laravel',
            'status' => 'success'
        ];
    }
}
