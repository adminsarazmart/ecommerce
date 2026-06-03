<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\HomepageBuilder;
use App\Models\Banner;
use App\Models\Slider;
use App\Services\BuilderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    protected BuilderService $builderService;

    public function __construct(BuilderService $builderService)
    {
        $this->builderService = $builderService;
    }

    public function index()
    {
        $sections = $this->builderService->getHomepageSections();
        $featuredProducts = Product::active()
            ->where('is_featured', true)
            ->with(['category', 'vendor', 'media'])
            ->latest()
            ->take(12)
            ->get();

        $categories = Category::active()
            ->with(['children' => function ($q) {
                $q->active();
            }])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $deals = Product::active()
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'unit_price')
            ->with(['category', 'media'])
            ->latest()
            ->take(8)
            ->get();

        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $sliders = Slider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Homepage', [
            'sections' => $sections,
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'deals' => $deals,
            'banners' => $banners,
            'sliders' => $sliders,
        ]);
    }
}
