<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Vendor;
use App\Notifications\LowStockAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendLowStockAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(): void
    {
        try {
            $threshold = $this->product->low_stock_threshold ?? 10;

            if ($this->product->stock_quantity <= $threshold && $this->product->vendor) {
                $this->product->vendor->notify(new LowStockAlert([
                    [
                        'id' => $this->product->id,
                        'name' => $this->product->name,
                        'sku' => $this->product->sku,
                        'stock_quantity' => $this->product->stock_quantity,
                    ],
                ]));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send low stock alert', [
                'product_id' => $this->product->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function tags(): array
    {
        return ['inventory', 'alert', 'product:' . $this->product->id];
    }
}
