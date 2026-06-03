<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\LoyaltyPoint;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateCustomerLoyaltyPoints implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        if (!$order->customer_id) {
            return;
        }

        $pointsEarned = (int) floor($order->total);

        LoyaltyPoint::create([
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'points' => $pointsEarned,
            'type' => 'earned',
            'description' => "Points earned from Order #{$order->order_number}",
        ]);
    }
}
