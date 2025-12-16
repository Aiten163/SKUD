<?php

namespace App\Services\Websocket;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use Illuminate\Support\Facades\Log;

class RabbitMQConnection
{
    const EXCHANGE = 'websocket_exchange';
    const TO_GO_ROUTING_KEY = 'to_go';
    const FROM_GO_ROUTING_KEY = 'from_go';

    private static $connection = null;
    private static $channel = null;

    /**
     * Получить соединение с RabbitMQ
     */
    private static function getConnection(): AMQPStreamConnection
    {
        if (self::$connection === null || !self::$connection->isConnected()) {
            self::$connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST', 'rabbitmq'),
                env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASSWORD', 'guest'),
                env('RABBITMQ_VHOST', '/')
            );
        }

        return self::$connection;
    }

    /**
     * Получить канал
     */
    private static function getChannel()
    {
        if (self::$channel === null || !self::$channel->is_open()) {
            self::$channel = self::getConnection()->channel();

            self::$channel->exchange_declare(
                self::EXCHANGE,
                AMQPExchangeType::DIRECT,
                false,  // passive
                true,   // durable
                false   // auto_delete
            );
        }

        return self::$channel;
    }

    /**
     * Отправить сообщение в Go сервер
     */
    public static function send($data): void
    {
        try {
            $channel = self::getChannel();

            $message = new AMQPMessage(
                json_encode($data),
                [
                    'content_type' => 'application/json',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
                ]
            );

            $channel->basic_publish(
                $message,
                self::EXCHANGE,
                self::TO_GO_ROUTING_KEY
            );

            Log::debug('Message sent to RabbitMQ', [
                'routing_key' => self::TO_GO_ROUTING_KEY,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('RabbitMQ send error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Переподключаемся при ошибке
            self::reconnect();
        }
    }

    /**
     * Слушать сообщения от Go сервера
     */
    public static function listen(): void
    {
        Log::info('Starting RabbitMQ listener', [
            'queue' => self::FROM_GO_ROUTING_KEY
        ]);

        $reconnectAttempt = 0;
        $maxReconnectAttempts = 10;

        while (true) {
            try {
                $channel = self::getChannel();

                // Объявляем очередь для получения сообщений
                $channel->queue_declare(
                    self::FROM_GO_ROUTING_KEY . '_queue',
                    false,  // passive
                    true,   // durable
                    false,  // exclusive
                    false   // auto_delete
                );

                // Привязываем очередь к exchange
                $channel->queue_bind(
                    self::FROM_GO_ROUTING_KEY . '_queue',
                    self::EXCHANGE,
                    self::FROM_GO_ROUTING_KEY
                );

                Log::info('RabbitMQ connected and listening', [
                    'exchange' => self::EXCHANGE,
                    'routing_key' => self::FROM_GO_ROUTING_KEY
                ]);

                // Сбрасываем счетчик переподключений
                $reconnectAttempt = 0;

                // Callback для обработки сообщений
                $callback = function (AMQPMessage $message) {
                    try {
                        $body = $message->getBody();
                        $data = json_decode($body, true);

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::warning('Invalid JSON received from RabbitMQ', [
                                'body' => substr($body, 0, 500)
                            ]);
                            $data = $body;
                        }

                        Log::debug('Processing RabbitMQ message', [
                            'body_size' => strlen($body),
                            'data_type' => gettype($data)
                        ]);

                        // Создаем роутер
                        new WebsocketRouter($data);

                        // Подтверждаем обработку сообщения
                        $message->ack();

                    } catch (\Exception $e) {
                        Log::error('Error processing RabbitMQ message', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);

                        // Отказываемся от сообщения и возвращаем его в очередь
                        $message->nack(true);
                    }
                };

                // Настраиваем потребителя
                $channel->basic_qos(null, 1, null); // Обрабатываем по одному сообщению
                $channel->basic_consume(
                    self::FROM_GO_ROUTING_KEY . '_queue',
                    '',     // consumer tag
                    false,  // no_local
                    false,  // no_ack
                    false,  // exclusive
                    false,  // nowait
                    $callback
                );

                // Ожидаем сообщений
                while ($channel->is_consuming()) {
                    $channel->wait();
                }

            } catch (\Exception $e) {
                $reconnectAttempt++;

                Log::error('RabbitMQ listener error', [
                    'attempt' => $reconnectAttempt,
                    'max_attempts' => $maxReconnectAttempts,
                    'error' => $e->getMessage(),
                    'class' => get_class($e)
                ]);

                if ($reconnectAttempt >= $maxReconnectAttempts) {
                    Log::critical('Maximum RabbitMQ reconnection attempts reached');
                    throw $e;
                }

                // Закрываем соединения
                self::closeConnections();

                // Exponential backoff
                $sleepTime = min(60, pow(2, $reconnectAttempt));
                Log::info("Reconnecting to RabbitMQ in {$sleepTime} seconds...");
                sleep($sleepTime);
            }
        }
    }

    /**
     * Переподключение
     */
    private static function reconnect(): void
    {
        self::closeConnections();
        sleep(1);
    }

    /**
     * Закрыть соединения
     */
    private static function closeConnections(): void
    {
        try {
            if (self::$channel && self::$channel->is_open()) {
                self::$channel->close();
            }
        } catch (\Exception $e) {
            // Игнорируем ошибки закрытия
        }

        try {
            if (self::$connection && self::$connection->isConnected()) {
                self::$connection->close();
            }
        } catch (\Exception $e) {
            // Игнорируем ошибки закрытия
        }

        self::$channel = null;
        self::$connection = null;
    }

    /**
     * Деструктор для cleanup
     */
    public function __destruct()
    {
        self::closeConnections();
    }
}
