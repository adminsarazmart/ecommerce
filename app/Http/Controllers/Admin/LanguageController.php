<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('name')->get();

        return Inertia::render('Admin/Languages/Index', ['languages' => $languages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'rtl' => 'boolean',
        ]);

        try {
            $language = Language::create($validated);

            if ($request->boolean('is_default')) {
                Language::where('id', '!=', $language->id)->update(['is_default' => false]);
            }

            activity()->performedOn($language)->causedBy(auth()->user())->log('Language created');

            return redirect()->back()->with('success', 'Language created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code,' . $language->id,
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'rtl' => 'boolean',
        ]);

        try {
            $language->update($validated);

            if ($request->boolean('is_default')) {
                Language::where('id', '!=', $language->id)->update(['is_default' => false]);
            }

            return redirect()->back()->with('success', 'Language updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Language $language)
    {
        try {
            if ($language->is_default) {
                return redirect()->back()->with('error', 'Cannot delete default language');
            }

            $language->delete();

            return redirect()->back()->with('success', 'Language deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
