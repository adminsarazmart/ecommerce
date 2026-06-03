<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $query = Order::with(['customer.user', 'vendor', 'items']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer.user', function ($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status', 'payment_status', 'date_from', 'date_to']),
        ]);
    }

    public function show(Order $order)
    {
        $order->load([
            'customer.user.profile',
            'vendor',
            'items.product',
            'items.variant',
            'statusHistories' => function ($q) {
                $q->latest();
            },
            'shipments',
            'refunds',
            'returns',
            'coupon',
        ]);

        return Inertia::render('Admin/Orders/Show', ['order' => $order]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'notes' => 'nullable|string',
        ]);

        try {
            $order = $this->orderService->updateOrderStatus($order->id, $request->status);

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->withProperties(['status' => $request->status])
                ->log("Order status updated to {$request->status}");

            return redirect()->back()->with('success', 'Order status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function processRefund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0|max:' . $order->grand_total,
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $order = $this->orderService->processRefund($order->id, $request->only(['amount', 'reason']));

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Order refunded');

            return redirect()->back()->with('success', 'Refund processed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function processReturn(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'items' => 'nullable|array',
        ]);

        try {
            $order = $this->orderService->processReturn($order->id, $request->only(['reason', 'items']));

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Return processed');

            return redirect()->back()->with('success', 'Return processed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generateInvoice(Order $order)
    {
        $order->load(['customer.user', 'items.product', 'vendor']);

        $pdf = Pdf::loadView('invoices.order', compact('order'));

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
