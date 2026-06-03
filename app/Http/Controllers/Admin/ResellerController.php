<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResellerRequest;
use App\Models\Reseller;
use App\Services\ResellerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResellerController extends Controller
{
    protected ResellerService $resellerService;

    public function __construct(ResellerService $resellerService)
    {
        $this->resellerService = $resellerService;
    }

    public function index(Request $request)
    {
        $query = Reseller::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $resellers = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Resellers/Index', [
            'resellers' => $resellers,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function show(Reseller $reseller)
    {
        $reseller->load(['user.profile', 'transactions' => function ($q) {
            $q->latest()->take(20);
        }, 'childResellers.user']);

        $pendingCommissions = $reseller->transactions()
            ->where('type', 'commission')
            ->where('status', 'pending')
            ->sum('amount');

        return Inertia::render('Admin/Resellers/Show', [
            'reseller' => $reseller,
            'pendingCommissions' => $pendingCommissions,
        ]);
    }

    public function store(StoreResellerRequest $request)
    {
        try {
            $reseller = $this->resellerService->registerReseller($request->validated());

            activity()
                ->performedOn($reseller)
                ->causedBy(auth()->user())
                ->log('Reseller created');

            return redirect()->route('admin.resellers.index')
                ->with('success', 'Reseller created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function approve(Reseller $reseller)
    {
        try {
            $reseller->update([
                'status' => 'active',
                'verified_at' => now(),
            ]);

            activity()
                ->performedOn($reseller)
                ->causedBy(auth()->user())
                ->log('Reseller approved');

            return redirect()->back()->with('success', 'Reseller approved');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function suspend(Reseller $reseller)
    {
        try {
            $reseller->update(['status' => 'suspended']);

            return redirect()->back()->with('success', 'Reseller suspended');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function transactions(Reseller $reseller)
    {
        $transactions = $reseller->transactions()
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Resellers/Transactions', [
            'reseller' => $reseller->load('user'),
            'transactions' => $transactions,
        ]);
    }

    public function commissions(Request $request)
    {
        $query = Reseller::with('user')
            ->where('status', 'active');

        $resellers = $query->orderBy('total_earnings', 'desc')->paginate(15);

        return Inertia::render('Admin/Resellers/Commissions', [
            'resellers' => $resellers,
        ]);
    }
}
