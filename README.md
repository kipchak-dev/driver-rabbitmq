# CouchDB Driver for Kipchak

This driver is based on the [Tavshan - simplified Rabbit MQ Client](https://1x.ax/mamluk/tavshan) from [Mamluk](https://mamluk.net).

It supports open connection to multiple queues.

#### Composer Package

`kipchak/driver-rabbitmq`

#### Sample Config File:

This config file should be placed in your Kipchak project's config directory, as in the starter
project at https://1x.ax/mamluk/kipchak/starter/~files/master/config/kipchak.rabbitmq.php.

The config file should look like this (also avalable in sample.config.php):

```
<?php

use function Kipchak\Core\env;

return [
    'hostname' => env('RABBITMQ_HOST', ''),
    'port' => env('RABBITMQ_PORT', 5672),
    'username' => env('RABBITMQ_USERNAME', ''),
    'password' => env('RABBITMQ_PASSWORD', ''),
    'vhost' => env('RABBITMQ_VHOST', '/'),
    'isSecure' => env('RABBITMQ_IS_SECURE', false),
    'sslVerify' => env('RABBITMQ_SSL_VERIFY', false),
    'queues' => [
        // Channel must be a unique integer
        ['name' => 'queue-name-1', 'channel' => 1],
        ['name' => 'queue-name-2', 'channel' => 2]
    ]
];
```

## How to use it?

Install it via composer: `composer require kipchak/driver-rabbitmq`.

### Example Usage

```php
// Initialise the driver in drivers/drivers.php
\Mamluk\Kipchak\Driver\RabbitMQ\RabbitMQ::iniitalise($container);

// Load the HTTP driver in a controller or service
$queue = \Mamluk\Kipchak\Driver\RabbitMQ\RabbitMQ::get('queue-name-1');
// Use the $queue client as you would the mamluk/tavshan client.
```

## Detailed Documentation

https://kipchak.dev/docs/driver-rabbitmq

## What is a Kipchak Driver?

Kipchak Drivers are used to connect Kipchak to various data sources or storage systems.

They provide a standardized interface for interacting with different data sources, allowing developers to
focus on building their applications rather than dealing with the complexities of each data source.

Drivers were introduced as a part of the Kipchak 2.0 release.

Drivers are basically Container Dependencies injected into Kipchak's Service Container.

## How do Kipchak Drivers work?

Some Kipchak drivers are wired into the Service Container via a config file (where required).

If applicable, you will find a sample config file in this repository as well as in the starter project at https://1x.ax/mamluk/kipchak/starter.

Each driver defines an implementation of Kipchak's Driver Interface. Where applicable, the dependency may
accept a parameter for a specific instance of the driver specified as a config property, for instance, in the case
of multiple database connections or S3 buckets.

So you may access the driver by invoking \Mamluk\Kipchak\Driver\Memcached\Memcached::get('cache'), where
`cache` is the name of one of the memcached connection pools specified in the config file.