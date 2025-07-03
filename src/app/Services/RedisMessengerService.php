<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisMessengerService
{
    /**
     * Отправка сообщения в Go-сервер через Redis
     *
     * @param string $event
     * @param mixed $data
     * @return void
     */
    public static function publish(string $event, array | string $data): void
    {
        Redis::publish('from_laravel', json_encode([
            'event' => $event,
            'data' => $data
        ], JSON_THROW_ON_ERROR));
    }
}
