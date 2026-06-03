<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(OrderService $orderService): void
    {
        try {
            $orderService->processOrder($this->order->id);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order processing failed', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
            ]);
            $this->fail($e);
        }
    }

    public function tags(): array
    {
        return ['order', 'process', 'order:' . $this->order->id];
    }
}
