<?php

namespace App\Jobs;

use App\Services\ProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BulkImportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $products;
    public int $userId;

    public function __construct(array $products, int $userId)
    {
        $this->products = $products;
        $this->userId = $userId;
    }

    public function handle(ProductService $productService): void
    {
        $result = $productService->bulkImport($this->products);

        activity()
            ->causedBy(\App\Models\User::find($this->userId))
            ->withProperties([
                'total' => $result['total'],
                'imported' => $result['success_count'],
                'failed' => $result['failure_count'],
            ])
            ->log('Bulk product import completed');
    }

    public function tags(): array
    {
        return ['import', 'products', 'bulk'];
    }
}
