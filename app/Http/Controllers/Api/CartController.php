<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCouponRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        try {
            $customer = $request->user()->customer;
            $cart = $this->cartService->getCart($customer->id);
            $totals = $cart ? $this->cartService->calculateTotals($cart) : [];

            return response()->json([
                'success' => true,
                'data' => [
                    'cart' => $cart,
                    'totals' => $totals,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        try {
            $customer = $request->user()->customer;
            $cart = $this->cartService->addToCart(
                $customer->id,
                $request->product_id,
                $request->quantity,
                $request->only(['variant_id'])
            );

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        try {
            $customer = $request->user()->customer;
            $cart = $this->cartService->updateQuantity($customer->id, $itemId, $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function remove(Request $request, $itemId)
    {
        try {
            $customer = $request->user()->customer;
            $cart = $this->cartService->removeFromCart($customer->id, $itemId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {
        try {
            $customer = $request->user()->customer;
            $cart = $this->cartService->applyCoupon($customer->id, $request->code);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied',
                'data' => $cart,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeCoupon(Request $request)
    {
        try {
            $customer = $request->user()->customer;
            $cart = $this->cartService->getCart($customer->id);

            if ($cart) {
                $cart->update(['coupon_id' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
