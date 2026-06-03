<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Support\Facades\Cache;

class SearchService
{
    public function searchProducts(string $query, array $filters = [], int $perPage = 20)
    {
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%")
                    ->orWhereHas('tags', function ($tagQuery) use ($query) {
                        $tagQuery->where('name', 'LIKE', "%{$query}%");
                    });
            });

        if (!empty($filters['category_id'])) {
            $products->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if (!empty($filters['vendor_id'])) {
            $products->where('vendor_id', $filters['vendor_id']);
        }

        if (!empty($filters['min_price'])) {
            $products->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $products->where('price', '<=', $filters['max_price']);
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $products->orderBy($sortField, $sortDir);

        return $products->paginate($perPage);
    }

    public function searchVendors(string $query, int $perPage = 20)
    {
        return Vendor::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('shop_name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('tags', 'LIKE', "%{$query}%");
            })
            ->paginate($perPage);
    }

    public function autocomplete(string $query, int $limit = 10): array
    {
        $products = Product::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'price', 'image']);

        return [
            'products' => $products,
            'suggestions' => $products->pluck('name')->toArray(),
        ];
    }

    public function indexProduct(Product $product): void
    {
        $key = "search_index.{$product->id}";
        Cache::put($key, $product->toSearchableArray(), 86400);
    }

    public function removeFromIndex(int $productId): void
    {
        Cache::forget("search_index.{$productId}");
    }
}
