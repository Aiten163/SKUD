<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

use Illuminate\Support\Facades\App;

class RedisConnection
{
    const TO_GO = 'to_go';
    const FROM_GO = 'from_go';

    public static function send($message): void
    {
        try {
            app('redis.publish')->publish(self::TO_GO, json_encode($message));
        } catch (\Exception $e) {
            \Log::error("Publish error: " . $e->getMessage());
        }
    }

    public static function listen(): void
    {
        $redis = app('redis.subscribe');

        while (true) {
            try {
                $redis->subscribe([self::FROM_GO], function ($redis, $channel, $message) {
                    $data = json_decode($message, true) ?? $message;
                    new WebsocketRouter($data);
                });
            } catch (\Exception $e) {
                \Log::error("Redis subscribe error: ".$e->getMessage());
                sleep(5);
            }
        }
    }
}
