<?php

return [
    'default' => env('REVERB_DRIVER', 'reverb'),

    'apps' => [
        [
            'app_id' => env('REVERB_APP_ID'),
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'host' => env('REVERB_HOST', 'localhost'),
            'port' => env('REVERB_PORT', 8080),
            'scheme' => env('REVERB_SCHEME', 'http'),
            'options' => [
                'tls' => [],
            ],
            'allowed_origins' => explode(',', env('REVERB_ALLOWED_ORIGINS', '*')),
            'ping_interval' => env('REVERB_PING_INTERVAL', 30),
            'max_connections' => env('REVERB_MAX_CONNECTIONS', 1000),
            'max_channels' => env('REVERB_MAX_CHANNELS', 100),
    ],
    ],

    'scaling' => [
        'enabled' => env('REVERB_SCALING_ENABLED', false),
        'channel' => env('REVERB_SCALING_CHANNEL', 'reverb_scaling'),
        'server' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'port' => env('REDIS_PORT', 6379),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'database' => env('REDIS_DB', '0'),
        ],
    ],

    'pulse_ingest_interval' => env('REVERB_PULSE_INGEST_INTERVAL', 15),

    'telescope_ingest_interval' => env('REVERB_TELESCOPE_INGEST_INTERVAL', 15),

    'driver' => env('REVERB_DRIVER', 'reverb'),

    'reverb' => [
        'host' => env('REVERB_SERVER_HOST', '0.0.0.0'),
        'port' => env('REVERB_SERVER_PORT', 8080),
        'scheme' => env('REVERB_SERVER_SCHEME', 'http'),
        'options' => [
            'tls' => [],
        ],
        'max_connections' => env('REVERB_MAX_CONNECTIONS', 1000),
        'max_channels' => env('REVERB_MAX_CHANNELS', 100),
    ],

    'pusher' => [
        'host' => env('PUSHER_HOST', '127.0.0.1'),
        'port' => env('PUSHER_PORT', 443),
        'scheme' => env('PUSHER_SCHEME', 'https'),
        'app_id' => env('PUSHER_APP_ID'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
        ],
    ],

    'socketio' => [
        'host' => env('SOCKETIO_HOST', '127.0.0.1'),
        'port' => env('SOCKETIO_PORT', 6001),
    ],
];
