<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children' => function ($q) {
            $q->withCount('products');
        }])
            ->withCount('products')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Categories/Create', [
            'parentCategories' => $parentCategories,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = $data['slug'] ?? str($data['name'])->slug();

            $category = Category::create($data);

            activity()
                ->performedOn($category)
                ->causedBy(auth()->user())
                ->log('Category created');

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create category: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Category $category)
    {
        $category->load(['parent', 'children' => function ($q) {
            $q->withCount('products');
        }, 'products' => function ($q) {
            $q->latest()->take(20);
        }]);

        return Inertia::render('Admin/Categories/Show', [
            'category' => $category,
        ]);
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
            'parentCategories' => $parentCategories,
        ]);
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        try {
            $data = $request->validated();
            $data['slug'] = $data['slug'] ?? str($data['name'])->slug();

            $category->update($data);

            activity()
                ->performedOn($category)
                ->causedBy(auth()->user())
                ->log('Category updated');

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update category: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->children()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete category with subcategories');
            }

            if ($category->products()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete category with products');
            }

            $category->delete();

            activity()
                ->causedBy(auth()->user())
                ->log('Category deleted');

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
