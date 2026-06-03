<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $customer = auth()->user()?->customer;
        $cart = $customer ? $this->cartService->getCart($customer->id) : null;
        $totals = $cart ? $this->cartService->calculateTotals($cart) : [];

        return Inertia::render('Cart/Index', [
            'cart' => $cart,
            'totals' => $totals,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        try {
            $customer = auth()->user()->customer;
            $cart = $this->cartService->addToCart(
                $customer->id,
                $request->product_id,
                $request->quantity,
                $request->only(['variant_id'])
            );

            return redirect()->back()->with('success', 'Item added to cart successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request, $itemId)
    {
        try {
            $customer = auth()->user()->customer;
            $this->cartService->removeFromCart($customer->id, $itemId);

            return redirect()->back()->with('success', 'Item removed from cart');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:100']);

        try {
            $customer = auth()->user()->customer;
            $this->cartService->updateQuantity($customer->id, $itemId, $request->quantity);

            return redirect()->back()->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {
        try {
            $customer = auth()->user()->customer;
            $this->cartService->applyCoupon($customer->id, $request->code);

            return redirect()->back()->with('success', 'Coupon applied successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function removeCoupon()
    {
        try {
            $customer = auth()->user()->customer;
            $cart = $this->cartService->getCart($customer->id);
            if ($cart) {
                $cart->update(['coupon_id' => null]);
            }

            return redirect()->back()->with('success', 'Coupon removed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
