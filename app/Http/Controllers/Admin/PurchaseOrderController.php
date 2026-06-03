<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseOrderController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'warehouse', 'createdBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchaseOrders = $query->latest()->paginate(15);

        $suppliers = Supplier::where('status', 'active')->get();
        $warehouses = Warehouse::where('is_active', true)->get();

        return Inertia::render('Admin/Inventory/PurchaseOrders', [
            'purchaseOrders' => $purchaseOrders,
            'suppliers' => $suppliers,
            'warehouses' => $warehouses,
            'filters' => $request->only(['status', 'supplier_id']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'expected_at' => 'nullable|date',
        ]);

        try {
            $subtotal = collect($validated['items'])->sum(fn ($item) => $item['quantity'] * $item['unit_cost']);

            $po = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'order_number' => 'PO-' . strtoupper(uniqid()),
                'status' => 'draft',
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
                'notes' => $validated['notes'] ?? null,
                'expected_at' => $validated['expected_at'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $po->items()->create($item);
            }

            activity()->performedOn($po)->causedBy(auth()->user())->log('Purchase order created');

            return redirect()->back()->with('success', 'Purchase order created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        try {
            $this->inventoryService->receivePurchaseOrder($purchaseOrder->id);

            activity()
                ->performedOn($purchaseOrder)
                ->causedBy(auth()->user())
                ->log('Purchase order received');

            return redirect()->back()->with('success', 'Purchase order received successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function markComplete(PurchaseOrder $purchaseOrder)
    {
        try {
            $purchaseOrder->update([
                'status' => 'completed',
                'received_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Purchase order completed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
