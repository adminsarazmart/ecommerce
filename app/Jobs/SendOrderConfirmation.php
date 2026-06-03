<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\OrderConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        try {
            if ($this->order->customer) {
                $this->order->customer->notify(new OrderConfirmation($this->order->toArray()));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send order confirmation', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function tags(): array
    {
        return ['notification', 'order', 'order:' . $this->order->id];
    }
}
