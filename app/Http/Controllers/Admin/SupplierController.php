<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('company_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suppliers = $query->withCount('purchaseOrders')
            ->orderBy('name')
            ->paginate(15);

        return Inertia::render('Admin/Inventory/Suppliers', [
            'suppliers' => $suppliers,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $supplier = Supplier::create($validated);

            activity()->performedOn($supplier)->causedBy(auth()->user())->log('Supplier created');

            return redirect()->back()->with('success', 'Supplier created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $supplier->update($validated);

            return redirect()->back()->with('success', 'Supplier updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Supplier $supplier)
    {
        try {
            if ($supplier->purchaseOrders()->exists()) {
                return redirect()->back()->with('error', 'Cannot delete supplier with purchase orders');
            }

            $supplier->delete();

            return redirect()->back()->with('success', 'Supplier deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
