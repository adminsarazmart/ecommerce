<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\SearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProductToElasticsearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Product $product;
    public string $action;

    public function __construct(Product $product, string $action = 'index')
    {
        $this->product = $product;
        $this->action = $action;
    }

    public function handle(SearchService $searchService): void
    {
        try {
            if ($this->action === 'delete') {
                $searchService->removeFromIndex($this->product->id);
            } else {
                $searchService->indexProduct($this->product);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Elasticsearch sync failed', [
                'product_id' => $this->product->id,
                'action' => $this->action,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function tags(): array
    {
        return ['search', 'elasticsearch', 'product:' . $this->product->id];
    }
}
