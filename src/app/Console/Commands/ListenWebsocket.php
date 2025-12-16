<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Websocket\RabbitMQConnection;

class ListenWebsocket extends Command
{
    protected $signature = 'websocket:listen';
    protected $description = 'Listen for WebSocket messages via RabbitMQ';

    public function handle()
    {
        $this->info('Starting WebSocket RabbitMQ listener...');

        // Обработка сигналов для graceful shutdown
        if (extension_loaded('pcntl')) {
            pcntl_async_signals(true);
            pcntl_signal(SIGTERM, [$this, 'shutdown']);
            pcntl_signal(SIGINT, [$this, 'shutdown']);
        }

        try {
            RabbitMQConnection::listen();
        } catch (\Exception $e) {
            $this->error('Fatal error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    public function shutdown(): void
    {
        $this->info('Shutting down WebSocket listener gracefully...');
        exit(0);
    }
}
