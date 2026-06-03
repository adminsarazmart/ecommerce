<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected CheckoutService $checkoutService;
    protected OrderService $orderService;

    public function __construct(CheckoutService $checkoutService, OrderService $orderService)
    {
        $this->checkoutService = $checkoutService;
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        try {
            $customer = $request->user()->customer;

            $orders = $customer->orders()
                ->with('items.product')
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $customer = $request->user()->customer;

            $order = $this->checkoutService->processCheckout(
                $customer->id,
                $request->input('shipping_address'),
                $request->input('payment')
            );

            activity()
                ->performedOn($order)
                ->causedBy($request->user())
                ->log('Order placed via API');

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => $order->load('items.product'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $customer = $request->user()->customer;

            $order = $customer->orders()
                ->with(['items.product', 'statusHistories', 'shipments'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $customer = $request->user()->customer;

            $order = $customer->orders()->findOrFail($id);

            if (!in_array($order->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be cancelled in its current status',
                ], 400);
            }

            $order = $this->orderService->updateOrderStatus($order->id, 'cancelled');

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
