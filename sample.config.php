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

    // Optional connection tuning. Defaults shown.
    // For long-lived publish-only processes (e.g. FrankenPHP worker mode HTTP APIs)
    // that don't run an active read loop, set 'heartbeat' => 0 to avoid the broker
    // closing idle connections — there is no PHP code running between requests to
    // send/check heartbeat frames, so the protocol-level heartbeat will time out.
    // Consumers running an active basic_consume() loop should keep heartbeat > 0.
    'maxRetries' => 3,
    'heartbeat' => 60,
    'keepalive' => true,
    'readTimeout' => 120.0,
    'writeTimeout' => 120.0,

    'queues' => [
        // Channel must be a unique integer
        ['name' => 'queue-name-1', 'channel' => 1],
        ['name' => 'queue-name-2', 'channel' => 2]
    ]
];