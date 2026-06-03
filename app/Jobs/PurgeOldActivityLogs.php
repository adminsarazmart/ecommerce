<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PurgeOldActivityLogs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $days;

    public function __construct(int $days = 90)
    {
        $this->days = $days;
    }

    public function handle(): void
    {
        DB::table('activity_log')
            ->where('created_at', '<', now()->subDays($this->days))
            ->delete();
    }

    public function tags(): array
    {
        return ['cleanup', 'activity_logs'];
    }
}
