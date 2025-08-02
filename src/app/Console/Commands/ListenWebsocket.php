<?php

namespace App\Console\Commands;

use App\Services\Websocket\RedisConnection;
use Illuminate\Console\Command;

class ListenWebsocket extends Command
{
    protected $signature = 'websocket:listen';
    protected $description = 'Listen for WebSocket messages from Go';

    public function handle()
    {
        $this->info('Listening for WebSocket messages...');
        RedisConnection::listen();
    }
}
