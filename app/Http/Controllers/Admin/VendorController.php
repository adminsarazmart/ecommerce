<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Models\Vendor;
use App\Services\VendorService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VendorController extends Controller
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    public function index(Request $request)
    {
        $query = Vendor::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('store_name', 'like', "%{$request->search}%")
                  ->orWhere('store_email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        $vendors = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Vendors/Index', [
            'vendors' => $vendors,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(Vendor $vendor)
    {
        $vendor->load(['user.profile', 'products', 'ratings', 'wallet', 'transactions']);

        return Inertia::render('Admin/Vendors/Show', ['vendor' => $vendor]);
    }

    public function create()
    {
        return Inertia::render('Admin/Vendors/Create');
    }

    public function store(StoreVendorRequest $request)
    {
        try {
            $vendor = $this->vendorService->registerVendor($request->validated());

            activity()
                ->performedOn($vendor)
                ->causedBy(auth()->user())
                ->log('Vendor created');

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Vendor $vendor)
    {
        return Inertia::render('Admin/Vendors/Edit', ['vendor' => $vendor]);
    }

    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        try {
            $vendor->update($request->validated());

            activity()
                ->performedOn($vendor)
                ->causedBy(auth()->user())
                ->log('Vendor updated');

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Vendor $vendor)
    {
        try {
            $vendor->update(['is_active' => false]);

            activity()
                ->causedBy(auth()->user())
                ->log('Vendor suspended');

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor suspended successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function verify(Vendor $vendor)
    {
        try {
            $this->vendorService->verifyVendor($vendor->id);

            activity()
                ->performedOn($vendor)
                ->causedBy(auth()->user())
                ->log('Vendor verified');

            return redirect()->back()->with('success', 'Vendor verified successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function approveKyc(Request $request, Vendor $vendor)
    {
        $request->validate(['notes' => 'nullable|string']);

        try {
            $this->vendorService->approveKyc($vendor->id, $request->input('kyc_data', []));

            activity()
                ->performedOn($vendor)
                ->causedBy(auth()->user())
                ->log('Vendor KYC approved');

            return redirect()->back()->with('success', 'KYC approved successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function suspend(Vendor $vendor)
    {
        try {
            $vendor->update(['is_active' => false, 'verification_status' => 'suspended']);

            activity()
                ->performedOn($vendor)
                ->causedBy(auth()->user())
                ->log('Vendor suspended');

            return redirect()->back()->with('success', 'Vendor suspended');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function viewDashboard(Vendor $vendor)
    {
        $dashboard = $this->vendorService->getVendorDashboard($vendor->id);

        return Inertia::render('Admin/Vendors/Dashboard', $dashboard);
    }
}
