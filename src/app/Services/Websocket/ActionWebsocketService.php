<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Log;

class ActionWebsocketService
{
    private const ALIAS = [
        'test' => 'test'
    ];

    private array $data;
    private string $functionName;
    private mixed $answer;

    public function __construct(array $msgWebsocket)
    {
        $this->functionName = $msgWebsocket['event'] ?? '';
        $this->data = $msgWebsocket['data'] ?? [];

        if (isset(self::ALIAS[$this->functionName])) {
            $methodName = self::ALIAS[$this->functionName];
            if (method_exists($this, $methodName)) {
                $this->$methodName($msgWebsocket['data'] ?? []);
            } else {
                $this->answer = ['error' => 'Method not found'];
                Log::error("Method {$methodName} not found in ActionWebsocketService");
            }
        } else {
            $this->answer = ['error' => 'Unknown event'];
            Log::warning("Unknown event: {$this->functionName}");
        }
    }

    public function getAnswer(): array
    {
        return is_array($this->answer) ? $this->answer : ['response' => $this->answer];
    }

    public function returnAnswer(): void
    {
        try {
            $response = [
                'event' => $this->data['event'] ?? 'response',
                'data' => $this->getAnswer(),
                'timestamp' => now()->toDateTimeString()
            ];

            RedisWebsocketService::sendToGo($response);
        } catch (\Exception $e) {
            Log::error('Failed to return answer: ' . $e->getMessage());
        }
    }

    private function test(): void
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
