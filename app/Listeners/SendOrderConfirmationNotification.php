<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\OrderConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmationNotification implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        if ($order->customer) {
            $order->customer->notify(new OrderConfirmation($order->toArray()));
        }

        if ($order->user) {
            $order->user->notify(new OrderConfirmation($order->toArray()));
        }
    }
}
