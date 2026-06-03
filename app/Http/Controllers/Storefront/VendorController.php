<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VendorController extends Controller
{
    public function show($slug)
    {
        $vendor = Vendor::where('slug', $slug)
            ->where('is_active', true)
            ->with(['ratings', 'products' => function ($q) {
                $q->with('media')->active();
            }])
            ->firstOrFail();

        $products = Product::active()
            ->where('vendor_id', $vendor->id)
            ->with('media', 'category')
            ->paginate(12);

        $averageRating = $vendor->ratings->avg('rating');

        return Inertia::render('Shop/Vendor', [
            'vendor' => $vendor,
            'products' => $products,
            'averageRating' => round($averageRating, 1),
            'ratingCount' => $vendor->ratings->count(),
        ]);
    }
}
