<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Coupons/Index', [
            'coupons' => $coupons,
            'filters' => $request->only(['search', 'is_active']),
        ]);
    }

    public function create()
    {
        $categories = Category::active()->get();
        $products = Product::active()->get();

        return Inertia::render('Admin/Coupons/Create', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_per_user' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'applies_to' => 'nullable|in:all,category,product,vendor',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
        ]);

        try {
            Coupon::create($validated);

            activity()
                ->causedBy(auth()->user())
                ->log('Coupon created: ' . $validated['code']);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Coupon $coupon)
    {
        $categories = Category::active()->get();
        $products = Product::active()->get();

        return Inertia::render('Admin/Coupons/Edit', [
            'coupon' => $coupon,
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_per_user' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'applies_to' => 'nullable|in:all,category,product,vendor',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
        ]);

        try {
            $coupon->update($validated);

            activity()
                ->performedOn($coupon)
                ->causedBy(auth()->user())
                ->log('Coupon updated');

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();

            activity()
                ->causedBy(auth()->user())
                ->log('Coupon deleted');

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
