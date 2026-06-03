<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogOrderActivity implements ShouldQueue
{
    public function handle(OrderCreated|OrderStatusChanged $event): void
    {
        $order = $event->order;
        $description = '';

        if ($event instanceof OrderCreated) {
            $description = "Order #{$order->order_number} was created";
        } elseif ($event instanceof OrderStatusChanged) {
            $description = "Order #{$order->order_number} status changed from {$event->oldStatus} to {$event->newStatus}";
        }

        activity()
            ->performedOn($order)
            ->causedBy(auth()->user())
            ->withProperties([
                'order_number' => $order->order_number,
                'status' => $order->status,
            ])
            ->log($description);
    }
}
