<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'vendor', 'media', 'variants']);

        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $categoryIds = $category->children()->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('tags', 'like', "%{$term}%");
            });
        }

        if ($request->filled('brand')) {
            $query->whereIn('brand_id', (array) $request->brand);
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('sale_price')->where('unit_price', '>=', $request->min_price);
                  });
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('sale_price')->where('unit_price', '<=', $request->max_price);
                  });
            });
        }

        $sortField = match ($request->sort) {
            'price_asc' => 'unit_price',
            'price_desc' => 'unit_price',
            'newest' => 'created_at',
            'bestselling' => 'total_sales',
            'rating' => 'total_ratings',
            default => 'created_at',
        };
        $sortDir = in_array($request->sort, ['price_desc']) ? 'desc' : 'asc';

        if ($request->sort === 'bestselling') {
            $sortDir = 'desc';
        }

        $products = $query->orderBy($sortField, $sortDir)->paginate($request->per_page ?? 12);

        $categories = Category::active()
            ->withCount('products')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Shop/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'brand', 'min_price', 'max_price', 'sort', 'per_page']),
        ]);
    }

    public function show($slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with([
                'category', 'vendor', 'media', 'variants', 'attributes',
                'reviews' => function ($q) {
                    $q->latest()->take(10);
                },
                'crossSells.product',
                'tags',
            ])
            ->firstOrFail();

        $product->increment('total_views');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['media', 'category'])
            ->inRandomOrder()
            ->take(6)
            ->get();

        return Inertia::render('Shop/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'seo' => [
                'title' => $product->meta_title ?: $product->name,
                'description' => $product->meta_description ?: $product->short_description,
            ],
        ]);
    }
}
