<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    public function findByName(string $name): Collection
    {
        return $this->model->where('name', 'LIKE', "%{$name}%")->get();
    }

    public function findByCategory(int $categoryId): Collection
    {
        return $this->model->whereHas('categories', function ($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })->get();
    }

    public function findActive(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function searchProducts(string $term, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where(function ($query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%")
                ->orWhere('sku', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%");
        })->where('is_active', true)->paginate($perPage);
    }

    public function getFeatured(int $limit = 10): Collection
    {
        return $this->model->where('is_featured', true)
            ->where('is_active', true)
            ->limit($limit)
            ->get();
    }

    public function getTrending(int $limit = 10): Collection
    {
        return $this->model->where('is_active', true)
            ->orderBy('total_sales', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getByPriceRange(float $min, float $max): Collection
    {
        return $this->model->where('is_active', true)
            ->whereBetween('price', [$min, $max])
            ->get();
    }

    public function getLowStock(int $threshold = 10): Collection
    {
        return $this->model->where('is_active', true)
            ->where('stock_quantity', '<=', $threshold)
            ->get();
    }
}
