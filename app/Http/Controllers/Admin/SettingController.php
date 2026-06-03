<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Currency;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        $currencies = Currency::all();
        $languages = Language::all();

        return Inertia::render('Admin/Settings/General', [
            'settings' => $settings,
            'currencies' => $currencies,
            'languages' => $languages,
        ]);
    }

    public function general(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.group' => 'required|string',
        ]);

        try {
            foreach ($validated['settings'] as $setting) {
                Setting::updateOrCreate(
                    ['key' => $setting['key'], 'group' => $setting['group']],
                    ['value' => $setting['value']]
                );
            }

            activity()->causedBy(auth()->user())->log('General settings updated');

            return redirect()->back()->with('success', 'Settings updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function payment()
    {
        $settings = Setting::where('group', 'payment')->get()->pluck('value', 'key');

        return Inertia::render('Admin/Settings/Payment', ['settings' => $settings]);
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'stripe_key' => 'nullable|string|max:255',
            'stripe_secret' => 'nullable|string|max:255',
            'paypal_client_id' => 'nullable|string|max:255',
            'paypal_secret' => 'nullable|string|max:255',
            'razorpay_key' => 'nullable|string|max:255',
            'razorpay_secret' => 'nullable|string|max:255',
            'bkash_username' => 'nullable|string|max:255',
            'bkash_password' => 'nullable|string|max:255',
            'bkash_app_key' => 'nullable|string|max:255',
            'bkash_app_secret' => 'nullable|string|max:255',
            'nagad_merchant_id' => 'nullable|string|max:255',
            'nagad_secret' => 'nullable|string|max:255',
            'cod_enabled' => 'boolean',
            'bank_transfer_enabled' => 'boolean',
        ]);

        try {
            foreach ($validated as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key, 'group' => 'payment'],
                    ['value' => is_bool($value) ? ($value ? '1' : '0') : ($value ?? '')]
                );
            }

            activity()->causedBy(auth()->user())->log('Payment settings updated');

            return redirect()->back()->with('success', 'Payment settings updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function shipping()
    {
        $settings = Setting::where('group', 'shipping')->get()->pluck('value', 'key');

        return Inertia::render('Admin/Settings/Shipping', ['settings' => $settings]);
    }

    public function email()
    {
        $settings = Setting::where('group', 'email')->get()->pluck('value', 'key');

        return Inertia::render('Admin/Settings/Email', ['settings' => $settings]);
    }

    public function seo()
    {
        $settings = Setting::where('group', 'seo')->get()->pluck('value', 'key');

        return Inertia::render('Admin/Settings/SEO', ['settings' => $settings]);
    }

    public function security()
    {
        $settings = Setting::where('group', 'security')->get()->pluck('value', 'key');

        return Inertia::render('Admin/Settings/Security', ['settings' => $settings]);
    }
}
