<?php

namespace App\Repositories;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Collection;

class InventoryRepository extends BaseRepository
{
    public function __construct(Stock $stock)
    {
        parent::__construct($stock);
    }

    public function getStockLevels(int $productId): Collection
    {
        return $this->model->where('product_id', $productId)
            ->with('warehouse')
            ->get();
    }

    public function getLowStock(int $threshold = 10): Collection
    {
        return $this->model->where('quantity', '<=', $threshold)
            ->with(['product', 'warehouse'])
            ->get();
    }

    public function getStockMovements(int $productId, ?int $warehouseId = null): Collection
    {
        $query = StockMovement::where('product_id', $productId);

        if ($warehouseId) {
            $query->where(function ($q) use ($warehouseId) {
                $q->where('from_warehouse_id', $warehouseId)
                    ->orWhere('to_warehouse_id', $warehouseId);
            });
        }

        return $query->with(['fromWarehouse', 'toWarehouse'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
