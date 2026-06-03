<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\VendorNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVendorNewOrderNotification implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        if ($order->vendor) {
            $order->vendor->notify(new VendorNotification(
                'New Order Received',
                "You have received a new order #{$order->order_number} for {$order->total}."
            ));
        }
    }
}
