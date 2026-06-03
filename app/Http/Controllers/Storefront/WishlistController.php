<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $items = Wishlist::where('customer_id', $customer->id)
            ->with('product.media', 'product.category')
            ->latest()
            ->get();

        return Inertia::render('Account/Wishlist', ['items' => $items]);
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        try {
            $customer = auth()->user()->customer;

            $exists = Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $request->product_id)
                ->exists();

            if (!$exists) {
                Wishlist::create([
                    'customer_id' => $customer->id,
                    'product_id' => $request->product_id,
                ]);
            }

            return redirect()->back()->with('success', 'Added to wishlist');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove($productId)
    {
        try {
            $customer = auth()->user()->customer;
            Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $productId)
                ->delete();

            return redirect()->back()->with('success', 'Removed from wishlist');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        try {
            $customer = auth()->user()->customer;
            $existing = Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($existing) {
                $existing->delete();
                $added = false;
            } else {
                Wishlist::create([
                    'customer_id' => $customer->id,
                    'product_id' => $request->product_id,
                ]);
                $added = true;
            }

            return response()->json([
                'success' => true,
                'added' => $added,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
