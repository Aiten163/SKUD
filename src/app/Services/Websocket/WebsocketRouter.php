<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Log;

class WebsocketRouter
{
    private ActionWebsocketService $logicService;

    // Соответствие событий методам
    private const ALIAS_MAP = [
        'test' => 'processTest',
        'ping' => 'processPing',
        'user_connect' => 'processUserConnection'
    ];

    public function __construct()
    {
        $this->logicService = new ActionWebsocketService();
    }

    /**
     * Выбор обработчика по событию
     */
    public function handleEvent(array $data): ?array
    {
        $event = $data['event'] ?? '';
        $method = self::ALIAS_MAP[$event] ?? null;

        if (!$method || !method_exists($this->logicService, $method)) {
            Log::warning('Unknown event or method', ['event' => $event]);
            return null;
        }

        try {
            return $this->logicService->$method($data['data'] ?? []);
        } catch (\Exception $e) {
            Log::error("Handler failed for {$event}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Запуск подписки на события
     */
    public function startListening(): void
    {
        RedisConnection::listen(function (array $data) {
            if ($response = $this->handleEvent($data)) {
                RedisConnection::sendToGo($response);
            }
        });
    }
}
