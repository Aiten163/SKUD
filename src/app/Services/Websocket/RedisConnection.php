<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class RedisConnection
{
    private const CHANNEL_TO_GO = 'to_go';
    private const CHANNEL_FROM_GO = 'from_go';

    /**
     * Отправка сообщения в Go
     */
    public static function sendToGo(array $data): void
    {
        try {
            $message = json_encode([
                'event' => $data['event'] ?? 'unknown',
                'data' => $data['data'] ?? [],
                'timestamp' => now()->toDateTimeString()
            ], JSON_THROW_ON_ERROR);

            Redis::publish(self::CHANNEL_TO_GO, $message);
        } catch (\Exception $e) {
            Log::error('Redis send failed: ' . $e->getMessage());
        }
    }

    /**
     * Подписка на сообщения от Go
     */
    public static function listen(callable $callback): void
    {
        Redis::subscribe([self::CHANNEL_FROM_GO], function (string $message) use ($callback) {
            try {
                $data = json_decode($message, true, 512, JSON_THROW_ON_ERROR);
                $callback($data);
            } catch (\Exception $e) {
                Log::error('Message processing failed: ' . $e->getMessage());
            }
        });
    }
}
