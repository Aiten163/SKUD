<?php

namespace App\Services\Websocket;

use Illuminate\Support\Facades\Log;
use App\Services\Websocket\ActionWebsocketService;

class WebsocketRouter
{
    private array $dataFromClient;
    private string $functionName;
    private bool $return_answer;

    public function __construct(array $msgWebsocket)
    {
        $this->functionName = $msgWebsocket['event'] ?? '';
        $this->dataFromClient = $msgWebsocket['data'] ?? [];
        $this->return_answer = $msgWebsocket['return_answer'] ?? false;

        $this->findFunction();

    }

    private function findFunction()
    {
        try {
            if ($this->functionName === '')
                throw new \Exception("Method not written");

            $dataFromServer = 3;//app(ActionWebsocketService::class)->{$this->functionName}($this->dataFromClient);

        } catch (\Throwable $e) {
            Log::error($e->getMessage() . print_r([$this->functionName, $this->dataFromClient], true));
            $dataFromServer = $e->getMessage();
        }

        if ($this->return_answer === true) {
            $this->returnResponse($dataFromServer);
        }
    }

    public function returnResponse($data): void
    {
        try {
            $response = [
                'event' => $this->functionName,
                'data' => $data ?? 'Data is null',
                'timestamp' => now()->toDateTimeString()
            ];

            RedisConnection::send($response);
        } catch (\Exception $e) {
            Log::error('Failed to return answer to Go: ' . $e->getMessage());
        }
    }

}
