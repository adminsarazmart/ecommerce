<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values', 'group')->orderBy('name')->get();
        $groups = AttributeGroup::orderBy('name')->get();

        return Inertia::render('Admin/Attributes/Index', [
            'attributes' => $attributes,
            'groups' => $groups,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:attributes,slug',
            'type' => 'required|in:text,select,color,image',
            'is_filterable' => 'boolean',
            'is_required' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'attribute_group_id' => 'nullable|exists:attribute_groups,id',
        ]);

        try {
            $validated['slug'] = $validated['slug'] ?? str($validated['name'])->slug();

            $attribute = Attribute::create($validated);

            activity()->performedOn($attribute)->causedBy(auth()->user())->log('Attribute created');

            return redirect()->back()->with('success', 'Attribute created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:attributes,slug,' . $attribute->id,
            'type' => 'required|in:text,select,color,image',
            'is_filterable' => 'boolean',
            'is_required' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'attribute_group_id' => 'nullable|exists:attribute_groups,id',
        ]);

        try {
            $attribute->update($validated);

            activity()->performedOn($attribute)->causedBy(auth()->user())->log('Attribute updated');

            return redirect()->back()->with('success', 'Attribute updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Attribute $attribute)
    {
        try {
            $attribute->values()->delete();
            $attribute->delete();

            activity()->causedBy(auth()->user())->log('Attribute deleted');

            return redirect()->back()->with('success', 'Attribute deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeValue(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'image' => 'nullable|image|max:1024',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $attribute->values()->create($validated);

            return redirect()->back()->with('success', 'Attribute value added');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyValue(AttributeValue $value)
    {
        try {
            $value->delete();

            return redirect()->back()->with('success', 'Attribute value deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:attribute_groups,slug',
        ]);

        try {
            $group = AttributeGroup::create($validated);

            return redirect()->back()->with('success', 'Attribute group created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyGroup(AttributeGroup $group)
    {
        try {
            $group->attributes()->delete();
            $group->delete();

            return redirect()->back()->with('success', 'Attribute group deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
