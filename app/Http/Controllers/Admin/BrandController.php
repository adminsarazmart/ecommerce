<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $brands = $query->orderBy('sort_order')->paginate(15);

        return Inertia::render('Admin/Brands/Index', [
            'brands' => $brands,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Brands/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $validated['slug'] = $validated['slug'] ?? str($validated['name'])->slug();

            $brand = Brand::create($validated);

            if ($request->hasFile('logo')) {
                $brand->addMedia($request->file('logo'))->toMediaCollection('logos');
            }

            activity()->performedOn($brand)->causedBy(auth()->user())->log('Brand created');

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Brand $brand)
    {
        $brand->load('products');

        return Inertia::render('Admin/Brands/Show', ['brand' => $brand]);
    }

    public function edit(Brand $brand)
    {
        return Inertia::render('Admin/Brands/Edit', ['brand' => $brand]);
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $brand->update($validated);

            if ($request->hasFile('logo')) {
                $brand->clearMediaCollection('logos');
                $brand->addMedia($request->file('logo'))->toMediaCollection('logos');
            }

            activity()->performedOn($brand)->causedBy(auth()->user())->log('Brand updated');

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            if ($brand->products()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete brand with associated products');
            }

            $brand->delete();

            activity()->causedBy(auth()->user())->log('Brand deleted');

            return redirect()->route('admin.brands.index')
                ->with('success', 'Brand deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
