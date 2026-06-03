<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Events\ProductUpdated;
use App\Events\ProductDeleted;
use App\Services\SearchService;
use Illuminate\Contracts\Queue\ShouldQueue;

class IndexProductInElasticsearch implements ShouldQueue
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function handle(ProductCreated|ProductUpdated|ProductDeleted $event): void
    {
        if ($event instanceof ProductDeleted) {
            $this->searchService->removeFromIndex($event->product->id);
        } else {
            $this->searchService->indexProduct($event->product);
        }
    }
}
