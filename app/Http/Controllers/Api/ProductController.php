<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {
            $query = Product::active()->with(['category', 'vendor', 'media', 'variants']);

            if ($request->filled('category')) {
                $category = Category::where('slug', $request->category)->first();
                if ($category) {
                    $ids = $category->children()->pluck('id')->push($category->id);
                    $query->whereIn('category_id', $ids);
                }
            }

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('description', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('min_price')) {
                $query->where('unit_price', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('unit_price', '<=', $request->max_price);
            }

            if ($request->filled('vendor_id')) {
                $query->where('vendor_id', $request->vendor_id);
            }

            $perPage = $request->per_page ?? 15;
            $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->getProductWithRelations($id);

            if (!$product || !$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $product->increment('total_views');

            return response()->json([
                'success' => true,
                'data' => $product,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function featured()
    {
        try {
            $products = Product::active()
                ->where('is_featured', true)
                ->with(['media', 'category'])
                ->latest()
                ->take(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function related(Product $product)
    {
        try {
            $related = Product::active()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->with(['media', 'category'])
                ->inRandomOrder()
                ->take(6)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $related,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
