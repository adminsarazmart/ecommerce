<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageBuilder;
use App\Services\BuilderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomepageBuilderController extends Controller
{
    protected BuilderService $builderService;

    public function __construct(BuilderService $builderService)
    {
        $this->builderService = $builderService;
    }

    public function index()
    {
        $layouts = HomepageBuilder::orderBy('name')->get();
        $activeLayout = HomepageBuilder::where('is_active', true)->first();

        return Inertia::render('Admin/Builders/Homepage', [
            'layouts' => $layouts,
            'activeLayout' => $activeLayout,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'sections' => 'required|array',
            'sections.*.type' => 'required|string',
            'sections.*.data' => 'required|array',
            'sections.*.sort_order' => 'required|integer|min:0',
            'is_default' => 'boolean',
        ]);

        try {
            $homepage = $this->builderService->buildHomepage($validated['sections']);
            $homepage->update(['name' => $validated['name'], 'slug' => $validated['slug'] ?? str($validated['name'])->slug()]);

            activity()->performedOn($homepage)->causedBy(auth()->user())->log('Homepage layout created');

            return redirect()->back()->with('success', 'Homepage layout created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, HomepageBuilder $homepageBuilder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'sections' => 'required|array',
            'sections.*.type' => 'required|string',
            'sections.*.data' => 'required|array',
            'sections.*.sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        try {
            $homepageBuilder->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? str($validated['name'])->slug(),
                'sections' => $validated['sections'],
                'is_active' => $request->boolean('is_active', $homepageBuilder->is_active),
                'is_default' => $request->boolean('is_default', $homepageBuilder->is_default),
            ]);

            if ($request->boolean('is_active')) {
                HomepageBuilder::where('id', '!=', $homepageBuilder->id)->update(['is_active' => false]);
            }

            activity()->performedOn($homepageBuilder)->causedBy(auth()->user())->log('Homepage layout updated');

            return redirect()->back()->with('success', 'Homepage layout updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getSections(HomepageBuilder $homepageBuilder)
    {
        return response()->json([
            'success' => true,
            'data' => $homepageBuilder->sections,
        ]);
    }

    public function preview(HomepageBuilder $homepageBuilder)
    {
        return Inertia::render('Admin/Builders/Preview', [
            'type' => 'homepage',
            'data' => $homepageBuilder,
        ]);
    }
}
