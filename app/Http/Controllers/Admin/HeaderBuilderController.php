<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderBuilder;
use App\Services\BuilderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HeaderBuilderController extends Controller
{
    protected BuilderService $builderService;

    public function __construct(BuilderService $builderService)
    {
        $this->builderService = $builderService;
    }

    public function index()
    {
        $headers = HeaderBuilder::orderBy('name')->get();
        $activeHeader = HeaderBuilder::where('is_active', true)->first();

        return Inertia::render('Admin/Builders/Header', [
            'headers' => $headers,
            'activeHeader' => $activeHeader,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:header_builders,slug',
            'layout' => 'required|array',
            'is_default' => 'boolean',
        ]);

        try {
            $header = $this->builderService->buildHeader($validated);

            activity()->performedOn($header)->causedBy(auth()->user())->log('Header layout created');

            return redirect()->back()->with('success', 'Header layout created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, HeaderBuilder $headerBuilder)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:header_builders,slug,' . $headerBuilder->id,
            'layout' => 'required|array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        try {
            $headerBuilder->update($validated);

            if ($request->boolean('is_active')) {
                HeaderBuilder::where('id', '!=', $headerBuilder->id)->update(['is_active' => false]);
            }

            activity()->performedOn($headerBuilder)->causedBy(auth()->user())->log('Header layout updated');

            return redirect()->back()->with('success', 'Header layout updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getLayout(HeaderBuilder $headerBuilder)
    {
        return response()->json([
            'success' => true,
            'data' => $headerBuilder->layout,
        ]);
    }

    public function preview(HeaderBuilder $headerBuilder)
    {
        return Inertia::render('Admin/Builders/Preview', [
            'type' => 'header',
            'data' => $headerBuilder,
        ]);
    }
}
