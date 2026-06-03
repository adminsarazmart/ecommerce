<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThemeController extends Controller
{
    public function index()
    {
        $settings = ThemeSetting::all()->groupBy('group');

        return Inertia::render('Admin/Settings/Theme', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.group' => 'required|string',
        ]);

        try {
            foreach ($validated['settings'] as $setting) {
                ThemeSetting::updateOrCreate(
                    ['key' => $setting['key'], 'group' => $setting['group']],
                    ['value' => $setting['value']]
                );
            }

            activity()->causedBy(auth()->user())->log('Theme settings updated');

            return redirect()->back()->with('success', 'Theme settings updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function customize(Request $request)
    {
        $validated = $request->validate([
            'colors' => 'nullable|array',
            'colors.primary' => 'nullable|string|max:7',
            'colors.secondary' => 'nullable|string|max:7',
            'colors.accent' => 'nullable|string|max:7',
            'colors.background' => 'nullable|string|max:7',
            'typography' => 'nullable|array',
            'typography.font_family' => 'nullable|string',
            'typography.heading_font' => 'nullable|string',
            'typography.body_font_size' => 'nullable|string',
            'layout' => 'nullable|array',
            'layout.container_width' => 'nullable|string',
            'layout.sidebar_position' => 'nullable|in:left,right',
        ]);

        try {
            foreach ($validated as $group => $values) {
                foreach ($values as $key => $value) {
                    ThemeSetting::updateOrCreate(
                        ['key' => "{$group}.{$key}", 'group' => $group],
                        ['value' => $value]
                    );
                }
            }

            return redirect()->back()->with('success', 'Theme customized successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
