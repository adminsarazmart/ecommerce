<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterBuilder;
use App\Services\BuilderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FooterBuilderController extends Controller
{
    protected BuilderService $builderService;

    public function __construct(BuilderService $builderService)
    {
        $this->builderService = $builderService;
    }

    public function index()
    {
        $footers = FooterBuilder::orderBy('name')->get();
        $activeFooter = FooterBuilder::where('is_active', true)->first();

        return Inertia::render('Admin/Builders/Footer', [
            'footers' => $footers,
            'activeFooter' => $activeFooter,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:footer_builders,slug',
            'columns' => 'required|array',
            'is_default' => 'boolean',
        ]);

        try {
            $footer = $this->builderService->buildFooter($validated);

            activity()->performedOn($footer)->causedBy(auth()->user())->log('Footer layout created');

            return redirect()->back()->with('success', 'Footer layout created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, FooterBuilder $footerBuilder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:footer_builders,slug,' . $footerBuilder->id,
            'columns' => 'required|array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        try {
            $footerBuilder->update($validated);

            if ($request->boolean('is_active')) {
                FooterBuilder::where('id', '!=', $footerBuilder->id)->update(['is_active' => false]);
            }

            activity()->performedOn($footerBuilder)->causedBy(auth()->user())->log('Footer layout updated');

            return redirect()->back()->with('success', 'Footer layout updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getLayout(FooterBuilder $footerBuilder)
    {
        return response()->json([
            'success' => true,
            'data' => $footerBuilder->columns,
        ]);
    }

    public function preview(FooterBuilder $footerBuilder)
    {
        return Inertia::render('Admin/Builders/Preview', [
            'type' => 'footer',
            'data' => $footerBuilder,
        ]);
    }
}
