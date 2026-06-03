<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
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
        $query = Product::with(['category', 'vendor', 'brand', 'media']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);

        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $request->only(['search', 'category_id', 'status', 'vendor_id']),
        ]);
    }

    public function create()
    {
        $categories = Category::active()->with('children')->whereNull('parent_id')->get();
        $brands = Brand::active()->get();
        $attributes = Attribute::with('values')->get();

        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->createProduct(
                $request->validated(),
                $request->input('variants', []),
                $request->file('images', []),
                $request->input('attributes', [])
            );

            activity()
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->withProperties(['name' => $product->name, 'sku' => $product->sku])
                ->log('Product created');

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'vendor', 'brand', 'media', 'variants', 'attributes.values', 'reviews']);

        return Inertia::render('Admin/Products/Show', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product)
    {
        $product->load(['variants', 'media', 'attributes']);
        $categories = Category::active()->with('children')->whereNull('parent_id')->get();
        $brands = Brand::active()->get();
        $attributes = Attribute::with('values')->get();

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'attributes' => $attributes,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $product = $this->productService->updateProduct($product->id, $request->validated());

            if ($request->has('variants')) {
                $product->variants()->delete();
                $product->variants()->createMany($request->input('variants', []));
            }

            if ($request->has('attributes')) {
                $product->attributes()->sync($request->input('attributes', []));
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->addMedia($image)->toMediaCollection('products');
                }
            }

            activity()
                ->performedOn($product)
                ->causedBy(auth()->user())
                ->log('Product updated');

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct($product->id);

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['name' => $product->name])
                ->log('Product deleted');

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        try {
            $count = count($request->ids);

            match ($request->action) {
                'delete' => Product::whereIn('id', $request->ids)->delete(),
                'activate' => Product::whereIn('id', $request->ids)->update(['is_active' => true]),
                'deactivate' => Product::whereIn('id', $request->ids)->update(['is_active' => false]),
            };

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['action' => $request->action, 'count' => $count])
                ->log("Bulk {$request->action} on {$count} products");

            return redirect()->back()->with('success', "{$count} products {$request->action}d successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function export()
    {
        $products = Product::with(['category', 'brand', 'vendor'])->get();

        $headers = [
            'Name', 'SKU', 'Category', 'Brand', 'Price', 'Sale Price',
            'Stock', 'Status', 'Vendor',
        ];

        $callback = function () use ($products, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->sku,
                    $product->category?->name,
                    $product->brand?->name,
                    $product->unit_price,
                    $product->sale_price,
                    $product->stock_quantity,
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->vendor?->store_name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = fopen($request->file('file')->getPathname(), 'r');
            $headers = fgetcsv($file);
            $products = [];
            $row = 1;

            while (($data = fgetcsv($file)) !== false) {
                $products[] = array_combine($headers, $data);
                $row++;
            }

            fclose($file);

            $result = $this->productService->bulkImport($products);

            activity()
                ->causedBy(auth()->user())
                ->withProperties($result)
                ->log("Imported {$result['success_count']} products");

            return redirect()->back()->with('success', "Imported {$result['success_count']} products successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
