<?php

use Illuminate\Support\Str;

return [
    'domain' => env('HORIZON_DOMAIN'),
    'path' => env('HORIZON_PATH', 'horizon'),
    'use' => 'default',

    'prefix' => env('HORIZON_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_horizon:'),

    'middleware' => ['web'],

    'waits' => [
        'redis:default' => 60,
    ],

    'trim' => [
        'recent' => 60,
        'pending' => 120,
        'completed' => 60,
        'recent_failures' => 10080,
        'failed' => 10080,
        'monitored' => 10080,
    ],

    'silenced' => [
        'Laravel\Horizon\Events\JobDeleted',
        'Laravel\Horizon\Events\JobPaused',
        'Laravel\Horizon\Events\JobResumed',
        'Laravel\Horizon\Events\JobRetryRequested',
        'Laravel\Horizon\Events\JobRetrying',
    ],

    'environments' => [
        'production' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['high', 'default', 'low'],
                'balance' => 'auto',
                'autoScalingStrategy' => 'time',
                'maxProcesses' => 10,
                'maxTime' => 0,
                'maxJobs' => 0,
                'memory' => 128,
                'tries' => 3,
                'timeout' => 60,
                'nice' => 0,
            ],
        ],

        'local' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['high', 'default', 'low'],
                'balance' => 'simple',
                'maxProcesses' => 3,
                'maxTime' => 0,
                'maxJobs' => 0,
                'memory' => 128,
                'tries' => 3,
                'timeout' => 60,
                'nice' => 0,
            ],
        ],

        'staging' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => ['high', 'default', 'low'],
                'balance' => 'auto',
                'autoScalingStrategy' => 'time',
                'maxProcesses' => 6,
                'maxTime' => 0,
                'maxJobs' => 0,
                'memory' => 128,
                'tries' => 3,
                'timeout' => 60,
                'nice' => 0,
            ],
        ],
    ],

    'defaults' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'autoScalingStrategy' => 'time',
            'maxProcesses' => 1,
            'maxTime' => 0,
            'maxJobs' => 0,
            'memory' => 128,
            'tries' => 1,
            'timeout' => 60,
            'nice' => 0,
        ],
    ],
];
