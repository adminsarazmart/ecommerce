<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShareholderRequest;
use App\Models\Shareholder;
use App\Models\DividendDistribution;
use App\Models\DividendPayout;
use App\Services\ShareholderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareholderController extends Controller
{
    protected ShareholderService $shareholderService;

    public function __construct(ShareholderService $shareholderService)
    {
        $this->shareholderService = $shareholderService;
    }

    public function index(Request $request)
    {
        $shareholders = Shareholder::with('user')
            ->orderBy('share_percentage', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Shareholders/Index', [
            'shareholders' => $shareholders,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Shareholders/Create');
    }

    public function store(StoreShareholderRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password'] ?? 'password'),
            ]);

            $shareholder = Shareholder::create([
                'user_id' => $user->id,
                'shareholder_code' => 'SH-' . strtoupper(substr(uniqid(), -6)),
                'share_percentage' => $validated['share_percentage'],
                'total_investment' => $validated['total_investment'] ?? 0,
                'total_shares' => $validated['total_shares'] ?? 0,
                'join_date' => $validated['join_date'] ?? now(),
                'status' => 'active',
            ]);

            $user->assignRole('Shareholder');

            activity()->performedOn($shareholder)->causedBy(auth()->user())->log('Shareholder created');

            return redirect()->route('admin.shareholders.index')
                ->with('success', 'Shareholder created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Shareholder $shareholder)
    {
        $shareholder->load(['user.profile', 'ledgers' => function ($q) {
            $q->latest()->take(20);
        }, 'dividendPayouts']);

        $totalDividends = $shareholder->dividendPayouts()->sum('amount');
        $totalDistributions = DividendDistribution::count();

        return Inertia::render('Admin/Shareholders/Show', [
            'shareholder' => $shareholder,
            'totalDividends' => $totalDividends,
            'totalDistributions' => $totalDistributions,
        ]);
    }

    public function update(Request $request, Shareholder $shareholder)
    {
        $validated = $request->validate([
            'share_percentage' => 'required|numeric|min:0|max:100',
            'total_investment' => 'nullable|numeric|min:0',
            'total_shares' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        try {
            $shareholder->update($validated);

            return redirect()->back()->with('success', 'Shareholder updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function ledger(Shareholder $shareholder)
    {
        $ledger = $this->shareholderService->getLedger($shareholder->id);

        return Inertia::render('Admin/Shareholders/Ledger', [
            'shareholder' => $shareholder->load('user'),
            'ledger' => $ledger,
        ]);
    }

    public function dividends()
    {
        $distributions = DividendDistribution::with('payouts.shareholder.user')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Shareholders/Dividends', [
            'distributions' => $distributions,
        ]);
    }

    public function distributeDividends(Request $request)
    {
        $validated = $request->validate([
            'total_profit' => 'required|numeric|min:0',
            'period' => 'required|string|max:50',
        ]);

        try {
            $distribution = $this->shareholderService->distributeProfits(
                $validated['total_profit'],
                $validated['period']
            );

            activity()
                ->performedOn($distribution)
                ->causedBy(auth()->user())
                ->log("Dividend distribution for {$validated['period']}");

            return redirect()->back()->with('success', 'Dividends distributed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reports(Request $request)
    {
        $period = $request->period ?? now()->format('Y-m');
        $report = $this->shareholderService->generateReports($period);

        return Inertia::render('Admin/Shareholders/Reports', [
            'report' => $report,
            'period' => $period,
        ]);
    }
}
