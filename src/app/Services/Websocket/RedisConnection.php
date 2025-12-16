<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class RedisConnection
{
    const TO_GO = 'to_go';
    const FROM_GO = 'from_go';

    public static function send($message): void
    {
        try {
            Redis::connection('publish')->publish(self::TO_GO, json_encode($message));
        } catch (\Exception $e) {
            Log::error("Publish error", ['error' => $e->getMessage()]);
        }
    }

    public static function listen(): void
    {
        Log::info("Starting Redis WebSocket listener", ['channel' => self::FROM_GO]);

        $lastReconnect = time();

        while (true) {
            try {
                // Используем Laravel Redis фасад с правильным connection
                Redis::connection('subscribe')->subscribe([self::FROM_GO], function ($message) {
                    static::handleMessage($message);
                });

            } catch (\RedisException $e) {
                $now = time();
                $timeSinceLastReconnect = $now - $lastReconnect;
                $lastReconnect = $now;

                Log::error("Redis connection error", [
                    'error' => $e->getMessage(),
                    'time_since_last_reconnect' => $timeSinceLastReconnect
                ]);

                sleep(5);

            } catch (\Exception $e) {
                Log::error("Unexpected error in Redis listener", [
                    'error' => $e->getMessage(),
                    'class' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);

                sleep(10);
            }
        }
    }

    protected static function handleMessage($message): void
    {
        try {
            $data = json_decode($message, true, 512, JSON_THROW_ON_ERROR);

            Log::debug("Processing WebSocket message", [
                'data_type' => gettype($data),
                'is_array' => is_array($data)
            ]);

            new WebsocketRouter($data);

        } catch (\JsonException $e) {
            // Если это не JSON, пробуем как plain text
            Log::warning("Invalid JSON received", [
                'message' => substr($message, 0, 500),
                'error' => $e->getMessage()
            ]);

            new WebsocketRouter($message);

        } catch (\Exception $e) {
            Log::error("WebsocketRouter error", [
                'error' => $e->getMessage(),
                'message_preview' => substr($message, 0, 200),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
