<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\InventoryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateInventoryFromOrder implements ShouldQueue
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        foreach ($order->items as $item) {
            try {
                $this->inventoryService->adjustStock(
                    $item->product_id,
                    1,
                    $item->quantity,
                    'deduction',
                    "Order #{$order->order_number}"
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to update inventory for order', [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
