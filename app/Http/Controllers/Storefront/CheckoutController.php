<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;
    protected CartService $cartService;

    public function __construct(CheckoutService $checkoutService, CartService $cartService)
    {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
    }

    public function index()
    {
        $customer = auth()->user()->customer;
        $cart = $this->cartService->getCart($customer->id);

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $totals = $this->cartService->calculateTotals($cart);
        $addresses = $customer->addresses;

        return Inertia::render('Checkout/Index', [
            'cart' => $cart->load('items.product.media'),
            'totals' => $totals,
            'addresses' => $addresses,
            'paymentGateways' => [
                'stripe' => 'Stripe',
                'paypal' => 'PayPal',
                'bkash' => 'bKash',
                'nagad' => 'Nagad',
                'cod' => 'Cash on Delivery',
            ],
        ]);
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $customer = auth()->user()->customer;

            $order = $this->checkoutService->processCheckout(
                $customer->id,
                $request->input('shipping_address'),
                $request->input('payment')
            );

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->withProperties(['order_number' => $order->order_number])
                ->log('Order placed successfully');

            return redirect()->route('checkout.success', $order->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function success(Order $order)
    {
        if ($order->customer_id !== auth()->user()->customer->id) {
            abort(403);
        }

        return Inertia::render('Checkout/Success', [
            'order' => $order->load('items.product'),
        ]);
    }
}
