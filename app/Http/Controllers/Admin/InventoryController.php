<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function dashboard(Request $request)
    {
        $lowStockProducts = Stock::whereColumn('quantity', '<=', 'low_stock_threshold')
            ->with('product', 'warehouse')
            ->get();

        $totalProducts = Product::count();
        $totalStock = Stock::sum('quantity');
        $totalWarehouses = Warehouse::count();
        $recentMovements = StockMovement::with('product', 'warehouse', 'createdBy')
            ->latest()
            ->take(20)
            ->get();

        $stockValue = Stock::join('products', 'stocks.product_id', '=', 'products.id')
            ->selectRaw('SUM(stocks.quantity * products.cost_price) as total_value')
            ->value('total_value');

        return Inertia::render('Admin/Inventory/Index', [
            'lowStockProducts' => $lowStockProducts,
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'totalWarehouses' => $totalWarehouses,
            'stockValue' => round($stockValue ?? 0, 2),
            'recentMovements' => $recentMovements,
        ]);
    }

    public function warehouses()
    {
        $warehouses = Warehouse::orderBy('name')->get();

        return Inertia::render('Admin/Inventory/Warehouses', [
            'warehouses' => $warehouses,
        ]);
    }

    public function warehousesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        try {
            $warehouse = Warehouse::create($validated);

            activity()->performedOn($warehouse)->causedBy(auth()->user())->log('Warehouse created');

            return redirect()->back()->with('success', 'Warehouse created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function warehousesUpdate(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        try {
            $warehouse->update($validated);

            return redirect()->back()->with('success', 'Warehouse updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function warehousesDestroy(Warehouse $warehouse)
    {
        try {
            if ($warehouse->stocks()->where('quantity', '>', 0)->exists()) {
                return redirect()->back()->with('error', 'Cannot delete warehouse with stock');
            }

            $warehouse->delete();

            return redirect()->back()->with('success', 'Warehouse deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function adjustStock(StoreInventoryRequest $request)
    {
        try {
            $stock = $this->inventoryService->adjustStock(
                $request->product_id,
                $request->warehouse_id,
                $request->quantity,
                $request->type,
                $request->reason
            );

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['product_id' => $request->product_id, 'type' => $request->type, 'qty' => $request->quantity])
                ->log('Stock adjusted');

            return redirect()->back()->with('success', 'Stock adjusted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'warehouse', 'fromWarehouse', 'createdBy']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->latest()->paginate(15);

        return Inertia::render('Admin/Inventory/Movements', [
            'movements' => $movements,
            'filters' => $request->only(['product_id', 'type', 'date_from', 'date_to']),
        ]);
    }

    public function lowStock()
    {
        $lowStockItems = Stock::with(['product', 'warehouse'])
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('quantity')
            ->get();

        return Inertia::render('Admin/Inventory/LowStock', [
            'items' => $lowStockItems,
        ]);
    }
}
