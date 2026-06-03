<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('name')->get();

        return Inertia::render('Admin/Currencies/Index', ['currencies' => $currencies]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            $currency = Currency::create($validated);

            if ($request->boolean('is_default')) {
                Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
            }

            activity()->performedOn($currency)->causedBy(auth()->user())->log('Currency created');

            return redirect()->back()->with('success', 'Currency created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ]);

        try {
            $currency->update($validated);

            if ($request->boolean('is_default')) {
                Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
            }

            return redirect()->back()->with('success', 'Currency updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Currency $currency)
    {
        try {
            if ($currency->is_default) {
                return redirect()->back()->with('error', 'Cannot delete default currency');
            }

            $currency->delete();

            return redirect()->back()->with('success', 'Currency deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
