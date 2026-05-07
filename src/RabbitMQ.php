<?php

namespace Kipchak\Driver\RabbitMQ;

use Kipchak\Core\Interfaces;
use Psr\Container\ContainerInterface;
use Kipchak\Driver\Config\Config;
use Kipchak\Driver\Logger\Logger;
use Mamluk\Tavshan\Queue;

class RabbitMQ implements Interfaces\Driver
{
    protected static ContainerInterface $container;

    public static function initialise(ContainerInterface $container, ?string $configFileName = null): void
    {
        self::$container = $container;
        self::set($configFileName);
    }

    public static function set(?string $configFileName = null): void
    {
        $configFileName = $configFileName ?? 'kipchak.rabbitmq';
        $connection = Config::get($configFileName);
        $queues = $connection['queues'];

        $tuning = [
            'maxRetries'   => $connection['maxRetries']   ?? 3,
            'heartbeat'    => $connection['heartbeat']    ?? 60,
            'keepalive'    => $connection['keepalive']    ?? true,
            'readTimeout'  => $connection['readTimeout']  ?? 120.0,
            'writeTimeout' => $connection['writeTimeout'] ?? 120.0,
        ];

        foreach (['queues', 'maxRetries', 'heartbeat', 'keepalive', 'readTimeout', 'writeTimeout'] as $k) {
            unset($connection[$k]);
        }

        foreach ((array) $queues as $queue) {
            self::$container->set('drivers.queue.rabbitmq.' . $queue['name'], function (ContainerInterface $c) use ($connection, $queue, $tuning): Queue {
                return new Queue(
                    $connection,
                    Logger::get(),
                    $queue['name'],
                    $queue['channel'],
                    $tuning['maxRetries'],
                    $tuning['heartbeat'],
                    $tuning['keepalive'],
                    $tuning['readTimeout'],
                    $tuning['writeTimeout'],
                );
            });
        }

    }

    public static function get(?string $configKey = null): Queue
    {
        return self::$container->get('drivers.queue.rabbitmq.' . $configKey ?? 'default');
    }

}