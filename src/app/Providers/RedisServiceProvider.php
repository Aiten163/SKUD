<?php

namespace App\Providers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class RedisServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Для публикации
        $this->app->singleton('redis.publish', function() {
            return Redis::client('publish');
        });

        // Для подписки (особая настройка)
        $this->app->singleton('redis.subscribe', function() {
            $client = new \Predis\Client([
                'scheme' => 'tcp',
                'host'   => config('database.redis.subscribe.host'),
                'port'   => config('database.redis.subscribe.port'),
                'timeout' => 0, // Важно для подписки
                'read_write_timeout' => -1 // Бесконечный таймаут
            ]);
            return $client;
        });
    }
}
