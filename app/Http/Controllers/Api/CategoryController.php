<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::active()
                ->with(['children' => function ($q) {
                    $q->active()->withCount('products');
                }])
                ->withCount('products')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
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
            $category = Category::active()
                ->with(['children' => function ($q) {
                    $q->active()->withCount('products');
                }, 'products' => function ($q) {
                    $q->active()->with('media');
                }])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function tree()
    {
        try {
            $categories = Category::active()
                ->with(['children' => function ($q) {
                    $q->active()->with(['children' => function ($q2) {
                        $q2->active();
                    }]);
                }])
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
