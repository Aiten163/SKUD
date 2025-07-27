<?php

namespace App\Console\Commands;

use App\Services\Websocket\RedisWebsocketService;
use Illuminate\Console\Command;

class ListenWebsocket extends Command
{
    protected $signature = 'websocket:listen';
    protected $description = 'Listen for WebSocket messages from Go';

    public function handle()
    {
        $this->info('Listening for WebSocket messages...');
        RedisWebsocketService::listenFromGo();
    }
}
