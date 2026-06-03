<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\PurchaseOrder;
use App\Repositories\InventoryRepository;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    protected InventoryRepository $repository;

    public function __construct(InventoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function adjustStock(int $productId, int $warehouseId, int $quantity, string $type, ?string $reason = null): Stock
    {
        DB::beginTransaction();
        try {
            $stock = Stock::firstOrCreate([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
            ], ['quantity' => 0]);

            $previousQty = $stock->quantity;

            if ($type === 'addition') {
                $stock->increment('quantity', $quantity);
            } elseif ($type === 'deduction') {
                $stock->decrement('quantity', $quantity);
            }

            StockMovement::create([
                'product_id' => $productId,
                'from_warehouse_id' => $type === 'deduction' ? $warehouseId : null,
                'to_warehouse_id' => $type === 'addition' ? $warehouseId : null,
                'quantity' => $quantity,
                'type' => $type,
                'reason' => $reason,
                'previous_quantity' => $previousQty,
                'new_quantity' => $stock->fresh()->quantity,
                'user_id' => auth()->id(),
            ]);

            DB::commit();
            return $stock->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function transferStock(int $productId, int $fromWarehouseId, int $toWarehouseId, int $quantity): array
    {
        DB::beginTransaction();
        try {
            $fromStock = Stock::where('product_id', $productId)
                ->where('warehouse_id', $fromWarehouseId)
                ->firstOrFail();

            if ($fromStock->quantity < $quantity) {
                throw new \Exception('Insufficient stock');
            }

            $fromStock->decrement('quantity', $quantity);

            $toStock = Stock::firstOrCreate([
                'product_id' => $productId,
                'warehouse_id' => $toWarehouseId,
            ], ['quantity' => 0]);
            $toStock->increment('quantity', $quantity);

            WarehouseTransfer::create([
                'product_id' => $productId,
                'from_warehouse_id' => $fromWarehouseId,
                'to_warehouse_id' => $toWarehouseId,
                'quantity' => $quantity,
                'transferred_by' => auth()->id(),
                'status' => 'completed',
            ]);

            DB::commit();
            return [
                'from_stock' => $fromStock->fresh(),
                'to_stock' => $toStock->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createPurchaseOrder(array $data): PurchaseOrder
    {
        return PurchaseOrder::create($data);
    }

    public function receivePurchaseOrder(int $purchaseOrderId): PurchaseOrder
    {
        DB::beginTransaction();
        try {
            $po = PurchaseOrder::with('items')->findOrFail($purchaseOrderId);
            $po->update(['status' => 'received', 'received_at' => now()]);

            foreach ($po->items as $item) {
                $this->adjustStock(
                    $item->product_id,
                    $po->warehouse_id,
                    $item->quantity,
                    'addition',
                    "Purchase Order #{$po->id}"
                );
            }

            DB::commit();
            return $po->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
