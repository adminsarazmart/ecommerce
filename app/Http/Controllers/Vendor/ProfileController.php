<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor->load('user.profile');

        return Inertia::render('Vendor/Settings/Profile', ['vendor' => $vendor]);
    }

    public function update(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'store_email' => 'nullable|email|max:255',
            'store_phone' => 'nullable|string|max:20',
            'store_address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
        ]);

        try {
            $vendor->update($validated);

            if ($request->hasFile('store_logo')) {
                $vendor->addMedia($request->file('store_logo'))->toMediaCollection('logos');
            }

            if ($request->hasFile('store_banner')) {
                $vendor->addMedia($request->file('store_banner'))->toMediaCollection('banners');
            }

            activity()
                ->performedOn($vendor)
                ->causedBy(auth()->user())
                ->log('Vendor profile updated');

            return redirect()->back()->with('success', 'Store profile updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
