<?php

namespace App\Services;

class WebsocketArduinoService
{
    public function onOpen(ConnectionInterface $connection)
    {
        Log::info("Arduino connected: {$connection->resourceId}");
    }

    public function onMessage(ConnectionInterface $connection, $message)
    {
        Log::info("Message received from Arduino: {$message}");

        // Пример обработки JSON-сообщений
        $data = json_decode($message, true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'ping':
                    $response = [
                        'action' => 'pong',
                        'message' => 'Connection is active',
                    ];
                    $connection->send(json_encode($response));
                    break;

                case 'update_status':
                    $response = [
                        'action' => 'status_updated',
                        'status' => $data['status'] ?? 'unknown',
                    ];
                    $connection->send(json_encode($response));
                    break;

                default:
                    $connection->send(json_encode(['error' => 'Unknown action']));
                    break;
            }
        } else {
            $connection->send(json_encode(['error' => 'Invalid message format']));
        }
    }

    public function onClose(ConnectionInterface $connection)
    {
        Log::info("Arduino disconnected: {$connection->resourceId}");
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        Log::error("Error: {$e->getMessage()}");
        $connection->close();
    }
}
