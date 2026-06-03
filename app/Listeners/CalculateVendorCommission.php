<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\VendorTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateVendorCommission implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        $vendorOrders = $order->items->groupBy('vendor_id');

        foreach ($vendorOrders as $vendorId => $items) {
            if (!$vendorId) {
                continue;
            }

            $subtotal = $items->sum('total_price');
            $commissionRate = $order->vendor?->commission_rate ?? 10;
            $commissionAmount = $subtotal * ($commissionRate / 100);

            VendorTransaction::create([
                'vendor_id' => $vendorId,
                'order_id' => $order->id,
                'type' => 'commission',
                'amount' => -$commissionAmount,
                'description' => "Commission for Order #{$order->order_number}",
                'status' => 'pending',
            ]);
        }
    }
}
