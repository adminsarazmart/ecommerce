<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $query = $request->q;

            if (!$query || strlen($query) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query must be at least 2 characters',
                ], 400);
            }

            $products = Product::active()
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('sku', 'like', "%{$query}%")
                      ->orWhere('tags', 'like', "%{$query}%");
                })
                ->with(['media', 'category'])
                ->take(20)
                ->get();

            $categories = Category::active()
                ->where('name', 'like', "%{$query}%")
                ->take(5)
                ->get();

            $vendors = Vendor::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('store_name', 'like', "%{$query}%")
                      ->orWhere('store_description', 'like', "%{$query}%");
                })
                ->take(5)
                ->get();

            $suggestions = $products->pluck('name')->merge(
                $categories->pluck('name')
            )->take(10)->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $products,
                    'categories' => $categories,
                    'vendors' => $vendors,
                    'suggestions' => $suggestions,
                    'total_results' => $products->count(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
