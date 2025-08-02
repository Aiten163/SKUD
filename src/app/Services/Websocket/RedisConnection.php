<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Redis;

class RedisConnection
{
    const TO_GO = 'to_go';
    const FROM_GO = 'from_go';

    /**
     * Отправляет сообщение в канал
     */
    public static function send($message): void
    {
        Redis::publish(self::TO_GO, json_encode($message));
    }

    /**
     * Слушает канал
     */
    public static function listen(): void
    {
        Redis::subscribe(self::FROM_GO, function ($message) {
            $data = json_decode($message, true) ?? $message;
            new WebsocketRouter($data);
        });
    }
}
