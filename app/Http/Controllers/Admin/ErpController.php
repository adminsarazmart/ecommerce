<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\Expense;
use App\Services\ProfitCalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ErpController extends Controller
{
    protected ProfitCalculationService $profitService;

    public function __construct(ProfitCalculationService $profitService)
    {
        $this->profitService = $profitService;
    }

    public function dashboard()
    {
        $accounts = Account::with('children')->whereNull('parent_id')->get();
        $totalAssets = Account::where('type', 'asset')->sum('balance');
        $totalLiabilities = Account::where('type', 'liability')->sum('balance');
        $totalEquity = Account::where('type', 'equity')->sum('balance');
        $totalIncome = Account::where('type', 'income')->sum('balance');
        $totalExpenses = Account::where('type', 'expense')->sum('balance');

        $recentEntries = JournalEntry::with('createdBy')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Admin/ERP/Dashboard', [
            'accounts' => $accounts,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalEquity' => $totalEquity,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'recentEntries' => $recentEntries,
        ]);
    }

    public function chartOfAccounts()
    {
        $accounts = Account::with(['children' => function ($q) {
            $q->with('children');
        }])
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get();

        return Inertia::render('Admin/ERP/Accounts', ['accounts' => $accounts]);
    }

    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:accounts,code',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:accounts,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $account = Account::create($validated);

            activity()->performedOn($account)->causedBy(auth()->user())->log('Account created');

            return redirect()->back()->with('success', 'Account created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function journalEntries(Request $request)
    {
        $query = JournalEntry::with(['createdBy', 'items.account']);

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $entries = $query->latest()->paginate(15);
        $accounts = Account::where('is_active', true)->get();

        return Inertia::render('Admin/ERP/JournalEntries', [
            'entries' => $entries,
            'accounts' => $accounts,
            'filters' => $request->only(['date_from', 'date_to', 'status']),
        ]);
    }

    public function storeJournalEntry(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:500',
            'date' => 'required|date',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:accounts,id',
            'items.*.debit' => 'required|numeric|min:0',
            'items.*.credit' => 'required|numeric|min:0',
            'type' => 'nullable|string|max:100',
        ]);

        try {
            $totalDebit = collect($validated['items'])->sum('debit');
            $totalCredit = collect($validated['items'])->sum('credit');

            if (abs($totalDebit - $totalCredit) > 0.01) {
                return redirect()->back()->with('error', 'Debits must equal credits');
            }

            $entry = JournalEntry::create([
                'entry_number' => 'JE-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
                'description' => $validated['description'],
                'date' => $validated['date'],
                'type' => $validated['type'] ?? 'general',
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $entry->items()->create($item);
            }

            activity()->performedOn($entry)->causedBy(auth()->user())->log('Journal entry created');

            return redirect()->back()->with('success', 'Journal entry created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function postJournalEntry(JournalEntry $journalEntry)
    {
        try {
            $journalEntry->update([
                'status' => 'posted',
                'posted_at' => now(),
            ]);

            foreach ($journalEntry->items as $item) {
                $account = Account::findOrFail($item->account_id);
                $newBalance = $account->balance + $item->debit - $item->credit;
                $account->update(['balance' => $newBalance]);
            }

            activity()->performedOn($journalEntry)->causedBy(auth()->user())->log('Journal entry posted');

            return redirect()->back()->with('success', 'Journal entry posted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function profitLoss(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $end = $request->filled('end_date') ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $profitData = $this->profitService->calculateNetProfit($start, $end);

        return Inertia::render('Admin/ERP/ProfitLoss', [
            'profitData' => $profitData,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
        ]);
    }

    public function balanceSheet()
    {
        $assets = Account::where('type', 'asset')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $liabilities = Account::where('type', 'liability')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $equity = Account::where('type', 'equity')
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        $totalAssets = $assets->sum('balance') + $assets->pluck('children')->flatten()->sum('balance');
        $totalLiabilities = $liabilities->sum('balance') + $liabilities->pluck('children')->flatten()->sum('balance');
        $totalEquity = $equity->sum('balance') + $equity->pluck('children')->flatten()->sum('balance');

        return Inertia::render('Admin/ERP/BalanceSheet', [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalEquity' => $totalEquity,
        ]);
    }

    public function expenses(Request $request)
    {
        $query = Expense::with('account');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $expenses = $query->latest()->paginate(15);
        $accounts = Account::where('type', 'expense')->where('is_active', true)->get();

        return Inertia::render('Admin/ERP/Expenses', [
            'expenses' => $expenses,
            'accounts' => $accounts,
            'filters' => $request->only(['category', 'date_from', 'date_to']),
        ]);
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:255',
        ]);

        try {
            $expense = Expense::create($validated);

            // Update account balance
            $validated['account_id']->decrement('balance', $validated['amount']);

            activity()->performedOn($expense)->causedBy(auth()->user())->log('Expense recorded');

            return redirect()->back()->with('success', 'Expense recorded');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
