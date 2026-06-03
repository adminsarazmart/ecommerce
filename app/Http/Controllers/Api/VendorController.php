<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Vendor::where('is_active', true);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('store_name', 'like', "%{$request->search}%")
                      ->orWhere('store_description', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('featured')) {
                $query->where('is_featured', true);
            }

            $vendors = $query->withCount('products')
                ->orderBy('total_ratings', 'desc')
                ->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $vendors,
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
            $vendor = Vendor::where('is_active', true)
                ->with(['products' => function ($q) {
                    $q->active()->with('media', 'category');
                }, 'ratings'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $vendor,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found',
            ], 404);
        }
    }
}
