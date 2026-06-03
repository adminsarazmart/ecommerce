<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addToCart(int $customerId, int $productId, int $quantity = 1, ?array $variantData = null): Cart
    {
        DB::beginTransaction();
        try {
            $cart = Cart::firstOrCreate(['customer_id' => $customerId]);

            $existingItem = $cart->items()->where('product_id', $productId)->first();
            if ($existingItem) {
                $existingItem->increment('quantity', $quantity);
            } else {
                $cart->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'variant_data' => $variantData,
                ]);
            }

            $cart->touch();
            DB::commit();
            return $cart->load('items.product');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function removeFromCart(int $customerId, int $itemId): Cart
    {
        $cart = Cart::where('customer_id', $customerId)->firstOrFail();
        $cart->items()->where('id', $itemId)->delete();
        return $cart->fresh()->load('items.product');
    }

    public function updateQuantity(int $customerId, int $itemId, int $quantity): Cart
    {
        $cart = Cart::where('customer_id', $customerId)->firstOrFail();
        $item = $cart->items()->where('id', $itemId)->firstOrFail();
        $item->update(['quantity' => max(1, $quantity)]);
        return $cart->fresh()->load('items.product');
    }

    public function applyCoupon(int $customerId, string $code): Cart
    {
        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $cart = Cart::where('customer_id', $customerId)->firstOrFail();
        $cart->update(['coupon_id' => $coupon->id]);
        return $cart->fresh()->load('items.product', 'coupon');
    }

    public function calculateTotals(Cart $cart): array
    {
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $price = $item->product?->price ?? 0;
            $subtotal += $price * $item->quantity;
        }

        $discount = 0;
        if ($cart->coupon) {
            $discount = $cart->coupon->type === 'percentage'
                ? $subtotal * ($cart->coupon->value / 100)
                : min($cart->coupon->value, $subtotal);
        }

        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax - $discount;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'total' => round(max(0, $total), 2),
        ];
    }

    public function getCart(int $customerId): ?Cart
    {
        return Cart::where('customer_id', $customerId)
            ->with('items.product', 'coupon')
            ->first();
    }
}
