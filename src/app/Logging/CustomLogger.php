<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CustomLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('custom');
        $logger->pushHandler(
            new StreamHandler(storage_path('logs/custom.log'))
        );

        return $logger;
    }
}
