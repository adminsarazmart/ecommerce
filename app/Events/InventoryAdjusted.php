<?php

namespace App\Events;

use App\Models\Stock;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryAdjusted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Stock $stock;
    public string $type;
    public int $quantity;

    public function __construct(Stock $stock, string $type, int $quantity)
    {
        $this->stock = $stock;
        $this->type = $type;
        $this->quantity = $quantity;
    }
}
