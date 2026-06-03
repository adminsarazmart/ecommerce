<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        $wallet = $vendor->wallet;

        $transactions = VendorTransaction::where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(15);

        $balance = $wallet?->balance ?? 0;
        $pendingWithdrawals = WithdrawalRequest::where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->sum('amount');

        return Inertia::render('Vendor/Wallet', [
            'balance' => $balance,
            'pendingWithdrawals' => $pendingWithdrawals,
            'transactions' => $transactions,
            'withdrawable' => max(0, $balance - $pendingWithdrawals),
        ]);
    }

    public function withdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:bank,bkash,nagad,rocket',
            'account_details' => 'required|string|max:500',
        ]);

        try {
            $vendor = auth()->user()->vendor;
            $wallet = $vendor->wallet;

            if (!$wallet || $wallet->balance < $request->amount) {
                return redirect()->back()->with('error', 'Insufficient balance');
            }

            WithdrawalRequest::create([
                'vendor_id' => $vendor->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'account_details' => $request->account_details,
                'status' => 'pending',
            ]);

            activity()
                ->causedBy(auth()->user())
                ->log("Withdrawal requested: {$request->amount}");

            return redirect()->back()->with('success', 'Withdrawal request submitted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
