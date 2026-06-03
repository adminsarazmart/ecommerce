<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $query = Order::whereHas('items', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->with('customer.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return Inertia::render('Vendor/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['status']),
        ]);
    }

    public function show(Order $order)
    {
        $vendor = auth()->user()->vendor;

        $vendorItems = $order->items()->where('vendor_id', $vendor->id)
            ->with('product')
            ->get();

        if ($vendorItems->isEmpty()) {
            abort(403);
        }

        $order->load('customer.user', 'statusHistories');

        return Inertia::render('Vendor/Orders/Show', [
            'order' => $order,
            'vendorItems' => $vendorItems,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $vendor = auth()->user()->vendor;

        $hasVendorItem = $order->items()->where('vendor_id', $vendor->id)->exists();
        if (!$hasVendorItem) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        try {
            $order = $this->orderService->updateOrderStatus($order->id, $request->status);

            if ($request->filled('tracking_number')) {
                $order->shipments()->create([
                    'tracking_number' => $request->tracking_number,
                    'carrier' => $request->carrier ?? 'other',
                    'status' => 'shipped',
                ]);
            }

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log("Vendor updated order status to {$request->status}");

            return redirect()->back()->with('success', 'Order status updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
