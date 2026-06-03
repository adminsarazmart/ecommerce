<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $query = Product::where('vendor_id', $vendor->id)->with('category', 'media');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Vendor/Products/Index', [
            'products' => $products,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create()
    {
        $categories = Category::active()->with('children')->whereNull('parent_id')->get();
        $brands = Brand::active()->get();

        return Inertia::render('Vendor/Products/Create', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();
            $data['vendor_id'] = auth()->user()->vendor->id;

            $product = $this->productService->createProduct(
                $data,
                $request->input('variants', []),
                $request->file('images', [])
            );

            activity()
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->log('Vendor created product');

            return redirect()->route('vendor.products.index')
                ->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $vendor = auth()->user()->vendor;

        if ($product->vendor_id !== $vendor->id) {
            abort(403);
        }

        $product->load(['variants', 'media']);
        $categories = Category::active()->with('children')->whereNull('parent_id')->get();
        $brands = Brand::active()->get();

        return Inertia::render('Vendor/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $vendor = auth()->user()->vendor;

        if ($product->vendor_id !== $vendor->id) {
            abort(403);
        }

        try {
            $product = $this->productService->updateProduct($product->id, $request->validated());

            if ($request->has('variants')) {
                $product->variants()->delete();
                $product->variants()->createMany($request->input('variants', []));
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->addMedia($image)->toMediaCollection('products');
                }
            }

            activity()->performedOn($product)->causedBy(auth()->user())->log('Vendor updated product');

            return redirect()->route('vendor.products.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        $vendor = auth()->user()->vendor;

        if ($product->vendor_id !== $vendor->id) {
            abort(403);
        }

        try {
            $product->delete();

            return redirect()->route('vendor.products.index')
                ->with('success', 'Product deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
