<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();

Schedule::call(function () {
    \App\Jobs\CleanExpiredCarts::dispatch();
})->daily();

Schedule::call(function () {
    \App\Jobs\PurgeOldActivityLogs::dispatch(90);
})->weekly();

Schedule::call(function () {
    \App\Jobs\ProcessDividendDistribution::dispatch(
        now()->subMonth()->format('Y-m'),
        now()->subMonth()->startOfMonth()->toDateString(),
        now()->subMonth()->endOfMonth()->toDateString()
    );
})->monthly();

Schedule::command('horizon:snapshot')->everyFiveMinutes();

Schedule::command('model:prune')->daily();
