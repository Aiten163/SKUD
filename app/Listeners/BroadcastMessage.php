<?php

namespace App\Listeners;

use App\Events\MessageToLock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Reverb\Events\MessageReceived;

class BroadcastMessage
{
    /**
     * Обработка события MessageReceived.
     */
    public function handle(MessageReceived $event): void
    {
        // Распаковка JSON-сообщения
        $message = json_decode($event->message);

        // Проверяем тип события
        if ($message->event !== 'SendMessage') {
            return; // Если событие не "SendMessage", игнорируем
        }

        // Логируем данные (например, для отладки)
        Log::info("Сообщение от клиента: " . json_encode($message->data));

        // Отправляем ответ обратно клиенту
        broadcast(new MessageToLock("Ответ от Laravel: " . $message->data->message));
    }
}
