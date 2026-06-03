<?php

namespace App\Jobs;

use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanExpiredCarts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Cart::whereNull('customer_id')
            ->where('created_at', '<', now()->subDays(7))
            ->delete();

        Cart::whereHas('customer', function ($query) {
            $query->whereNull('id');
        })->delete();
    }

    public function tags(): array
    {
        return ['cleanup', 'carts'];
    }
}
