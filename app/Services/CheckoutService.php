<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected PaymentService $paymentService;

    public function __construct(
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function processCheckout(int $customerId, array $shippingData, array $paymentData): Order
    {
        DB::beginTransaction();
        try {
            $cart = $this->cartService->getCart($customerId);
            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $this->validateStock($cart);

            $totals = $this->cartService->calculateTotals($cart);
            $shippingCost = $this->calculateShipping($shippingData);

            $order = $this->orderService->createOrder([
                'customer_id' => $customerId,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'subtotal' => $totals['subtotal'],
                'tax_amount' => $totals['tax'],
                'discount_amount' => $totals['discount'],
                'shipping_amount' => $shippingCost,
                'total' => $totals['total'] + $shippingCost,
                'status' => 'pending',
                'shipping_address' => $shippingData,
                'items' => $cart->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'vendor_id' => $item->product->vendor_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->product->price,
                    ];
                })->toArray(),
            ]);

            $this->paymentService->processPayment($order, $paymentData);
            $cart->items()->delete();
            $cart->delete();

            DB::commit();
            return $order->load('items.product');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function validateStock(Cart $cart): void
    {
        foreach ($cart->items as $item) {
            $product = $item->product;
            if (!$product || !$product->is_active) {
                throw new \Exception("Product '{$item->product?->name}' is no longer available");
            }
            if ($product->stock_quantity < $item->quantity) {
                throw new \Exception("Insufficient stock for '{$product->name}'");
            }
        }
    }

    public function calculateShipping(array $shippingData): float
    {
        return 0;
    }

    public function placeOrder(Order $order): Order
    {
        return $this->orderService->updateOrderStatus($order->id, 'confirmed');
    }
}
