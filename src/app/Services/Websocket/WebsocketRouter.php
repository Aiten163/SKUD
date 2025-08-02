<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Log;
use App\Services\Websocket\ActionWebsocketService;

class WebsocketRouter
{
    private array $data;
    private string $functionName;
    private mixed $response;

    public function __construct(array $msgWebsocket)
    {
        $this->functionName = $msgWebsocket['event'] ?? '';
        $this->data = $msgWebsocket['data'] ?? [];

        try {
            if ($this->functionName === '')
                throw new \Exception("Method not written");

            $response = call_user_func(['ActionWebsocketService', $this->functionName], $this->data);

        } catch (\Throwable $e) {
            Log::error($e->getMessage() . print_r($msgWebsocket, true));
            $response = $e->getMessage();
        }
        if ($msgWebsocket['return_response'] === true)
        {
            $this->returnResponse($response);
        }
    }

    public function returnResponse(): void
    {
        try {
            $response = [
                'event' => $this->data['event'] ?? 'response',
                'data' => $this->,
                'timestamp' => now()->toDateTimeString()
            ];

            RedisConnection::send($response);
        } catch (\Exception $e) {
            Log::error('Failed to return answer to Go: ' . $e->getMessage());
        }
    }

}
