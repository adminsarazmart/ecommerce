<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['children' => function ($q) {
                $q->active()->withCount('products');
            }])
            ->firstOrFail();

        $childIds = $category->children->pluck('id')->toArray();
        $categoryIds = array_merge([$category->id], $childIds);

        $products = Product::active()
            ->whereIn('category_id', $categoryIds)
            ->with('media', 'vendor', 'category')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Shop/Category', [
            'category' => $category,
            'products' => $products,
            'childCategories' => $category->children,
        ]);
    }
}
