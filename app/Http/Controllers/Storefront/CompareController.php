<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\ProductCompare;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompareController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $items = ProductCompare::where('customer_id', $customer->id)
            ->with('product.media', 'product.category', 'product.variants', 'product.attributes')
            ->get();

        return Inertia::render('Compare/Index', ['items' => $items]);
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        try {
            $customer = auth()->user()->customer;
            $count = ProductCompare::where('customer_id', $customer->id)->count();

            if ($count >= 4) {
                return redirect()->back()->with('error', 'You can compare up to 4 products at a time');
            }

            $exists = ProductCompare::where('customer_id', $customer->id)
                ->where('product_id', $request->product_id)
                ->exists();

            if (!$exists) {
                ProductCompare::create([
                    'customer_id' => $customer->id,
                    'product_id' => $request->product_id,
                ]);
            }

            return redirect()->back()->with('success', 'Added to compare list');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function remove($productId)
    {
        try {
            $customer = auth()->user()->customer;
            ProductCompare::where('customer_id', $customer->id)
                ->where('product_id', $productId)
                ->delete();

            return redirect()->back()->with('success', 'Removed from compare list');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function clear()
    {
        try {
            $customer = auth()->user()->customer;
            ProductCompare::where('customer_id', $customer->id)->delete();

            return redirect()->back()->with('success', 'Compare list cleared');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
