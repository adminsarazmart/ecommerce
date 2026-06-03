<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\VendorAnalytic;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateVendorAnalytics implements ShouldQueue
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        $vendorOrders = $order->items->groupBy('vendor_id');

        foreach ($vendorOrders as $vendorId => $items) {
            if (!$vendorId) {
                continue;
            }

            $total = $items->sum('total_price');
            $quantity = $items->sum('quantity');

            VendorAnalytic::updateOrCreate(
                ['vendor_id' => $vendorId, 'date' => now()->toDateString()],
                [
                    'total_sales' => \DB::raw("total_sales + {$total}"),
                    'total_orders' => \DB::raw('total_orders + 1'),
                    'total_items_sold' => \DB::raw("total_items_sold + {$quantity}"),
                ]
            );
        }
    }
}
