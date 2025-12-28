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
        unset($connection['queues']);
        foreach ((array) $queues as $queue) {
            self::$container->set('drivers.queue.rabbitmq.' . $queue, function (ContainerInterface $c) use ($connection, $queue): Queue {
                return new Queue($connection, Logger::get(), $queue, 'kipchak.rabbitmq.driver');
            });
        }

    }

    public static function get(?string $configKey = null): Queue
    {
        return self::$container->get('drivers.queue.rabbitmq.' . $configKey ?? 'default');
    }

}