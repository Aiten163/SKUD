<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Redis;

class RedisConnection
{
    const TO_GO = 'to_go';
    const FROM_GO = 'from_go';

    /**
     * Отправляет сообщение в канал
     * @param mixed $message Сообщение (автоматически конвертируется в JSON)
     */
    public static function send($message): void
    {
        Redis::publish(self::TO_GO, $message);
    }

    /**
     * Слушает канал и вызывает callback при получении сообщения
     * @param callable $callback Функция обработки (получает decoded message)
     */
    public function listen(callable $callback): void
    {
        Redis::subscribe([$this->channel], function ($message) use ($callback) {
            $data = json_decode($message, true) ?? $message;
            $callback($data);
        });
    }
}
