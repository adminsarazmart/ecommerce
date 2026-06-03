<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseTransfer;
use App\Models\Warehouse;
use App\Models\TransferItem;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WarehouseTransferController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $query = WarehouseTransfer::with(['fromWarehouse', 'toWarehouse', 'createdBy', 'items.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transfers = $query->latest()->paginate(15);

        $warehouses = Warehouse::where('is_active', true)->get();

        return Inertia::render('Admin/Inventory/Transfers', [
            'transfers' => $transfers,
            'warehouses' => $warehouses,
            'filters' => $request->only(['status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $transfer = WarehouseTransfer::create([
                'from_warehouse_id' => $validated['from_warehouse_id'],
                'to_warehouse_id' => $validated['to_warehouse_id'],
                'reference_number' => 'TRF-' . strtoupper(uniqid()),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $transfer->items()->create($item);
            }

            activity()
                ->performedOn($transfer)
                ->causedBy(auth()->user())
                ->log('Warehouse transfer created');

            return redirect()->back()->with('success', 'Transfer created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function approve(WarehouseTransfer $warehouseTransfer)
    {
        try {
            $warehouseTransfer->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
            ]);

            activity()
                ->performedOn($warehouseTransfer)
                ->causedBy(auth()->user())
                ->log('Warehouse transfer approved');

            return redirect()->back()->with('success', 'Transfer approved');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function complete(WarehouseTransfer $warehouseTransfer)
    {
        try {
            foreach ($warehouseTransfer->items as $item) {
                $this->inventoryService->adjustStock(
                    $item->product_id,
                    $warehouseTransfer->from_warehouse_id,
                    $item->quantity,
                    'deduction',
                    "Warehouse transfer #{$warehouseTransfer->id}"
                );

                $this->inventoryService->adjustStock(
                    $item->product_id,
                    $warehouseTransfer->to_warehouse_id,
                    $item->quantity,
                    'addition',
                    "Warehouse transfer #{$warehouseTransfer->id}"
                );
            }

            $warehouseTransfer->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            activity()
                ->performedOn($warehouseTransfer)
                ->causedBy(auth()->user())
                ->log('Warehouse transfer completed');

            return redirect()->back()->with('success', 'Transfer completed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(WarehouseTransfer $warehouseTransfer)
    {
        try {
            if ($warehouseTransfer->status === 'completed') {
                return redirect()->back()->with('error', 'Cannot delete completed transfer');
            }

            $warehouseTransfer->items()->delete();
            $warehouseTransfer->delete();

            return redirect()->back()->with('success', 'Transfer cancelled');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
