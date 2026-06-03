<?php

use Laravel\Octane\Contracts\OperationTerminated;
use Laravel\Octane\Events\RequestHandled;
use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\RequestTerminated;
use Laravel\Octane\Events\TaskReceived;
use Laravel\Octane\Events\TaskTerminated;
use Laravel\Octane\Events\TickReceived;
use Laravel\Octane\Events\TickTerminated;
use Laravel\Octane\Events\WorkerErrorOccurred;
use Laravel\Octane\Events\WorkerStarting;
use Laravel\Octane\Events\WorkerStopping;
use Laravel\Octane\Listeners\CollectGarbage;
use Laravel\Octane\Listeners\DisconnectFromDatabases;
use Laravel\Octane\Listeners\EnsureUploadedFilesAreValid;
use Laravel\Octane\Listeners\FlushTemporaryContainerInstances;
use Laravel\Octane\Listeners\FlushUploadedFiles;
use Laravel\Octane\Listeners\ReportException;
use Laravel\Octane\Listeners\StopWorkerIfNecessary;
use Laravel\Octane\Octane;

return [
    'server' => env('OCTANE_SERVER', 'swoole'),

    'https' => env('OCTANE_HTTPS', false),

    'server_options' => [
        'worker_num' => env('OCTANE_WORKER_NUM', 4),
        'task_worker_num' => env('OCTANE_TASK_WORKER_NUM', 0),
        'max_request' => env('OCTANE_MAX_REQUEST', 1000),
        'max_request_grace' => env('OCTANE_MAX_REQUEST_GRACE', 0),
        'max_execution_time' => env('OCTANE_MAX_EXECUTION_TIME', 30),
        'log_level' => env('OCTANE_LOG_LEVEL', 4),
        'log_file' => env('OCTANE_LOG_FILE', storage_path('logs/octane.log')),
        'pid_file' => env('OCTANE_PID_FILE', storage_path('logs/octane.pid')),
    ],

    'max_execution_time' => env('OCTANE_MAX_EXECUTION_TIME', 30),

    'host' => env('OCTANE_HOST', '0.0.0.0'),
    'port' => env('OCTANE_PORT', 8000),

    'roadrunner' => [
        'binary' => env('RR_BINARY', base_path('rr')),
        'config' => env('RR_CONFIG', base_path('.rr.yaml')),
    ],

    'swoole' => [
        'options' => [
            'log_file' => env('OCTANE_SWOOLE_LOG_FILE', storage_path('logs/swoole.log')),
            'package_max_length' => 10 * 1024 * 1024,
            'enable_coroutine' => env('OCTANE_ENABLE_COROUTINE', true),
        ],
    ],

    'listeners' => [
        WorkerStarting::class => [
            EnsureUploadedFilesAreValid::class,
        ],

        RequestReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
            ...Octane::prepareApplicationForNextRequest(),
        ],

        RequestHandled::class => [],

        RequestTerminated::class => [
            FlushUploadedFiles::class,
        ],

        TaskReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
        ],

        TaskTerminated::class => [],

        TickReceived::class => [
            ...Octane::prepareApplicationForNextOperation(),
        ],

        TickTerminated::class => [],

        WorkerErrorOccurred::class => [
            ReportException::class,
            StopWorkerIfNecessary::class,
        ],

        WorkerStopping::class => [
            DisconnectFromDatabases::class,
            CollectGarbage::class,
        ],
    ],

    'warm' => [
        ...Octane::defaultServicesToWarm(),
        \Spatie\Permission\PermissionServiceProvider::class,
    ],

    'flush' => [
        \Spatie\Permission\PermissionRegistrar::class,
    ],
];
