<?php

namespace App\Console\Commands;

use App\Services\DoorActionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ListenToGoMessages extends  Command
{
    protected $signature = 'redis:websocket';
    protected $description = 'Listen to messages from Go server';

    public function handle()
    {
        Redis::subscribe(['from_go'], function ($message) {
            $data = json_decode($message, true, 512, JSON_THROW_ON_ERROR);
            $this->info("Received from Go: " . print_r($data, true));
//
//            if ($data['event'] === 'new_message') {
//            }
        });
    }
}
