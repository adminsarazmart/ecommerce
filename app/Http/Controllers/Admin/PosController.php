<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosSession;
use App\Models\PosOrder;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PosController extends Controller
{
    public function sessions(Request $request)
    {
        $query = PosSession::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sessions = $query->latest()->paginate(15);

        return Inertia::render('Admin/POS/Sessions', [
            'sessions' => $sessions,
            'filters' => $request->only(['status']),
        ]);
    }

    public function openSession(Request $request)
    {
        $validated = $request->validate([
            'cash_start' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $activeSession = PosSession::where('user_id', auth()->id())
                ->where('status', 'open')
                ->first();

            if ($activeSession) {
                return redirect()->back()->with('error', 'You already have an open session');
            }

            $session = PosSession::create([
                'user_id' => auth()->id(),
                'status' => 'open',
                'opened_at' => now(),
                'cash_start' => $validated['cash_start'],
                'notes' => $validated['notes'] ?? null,
            ]);

            activity()->performedOn($session)->causedBy(auth()->user())->log('POS session opened');

            return redirect()->back()->with('success', 'POS session opened');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function closeSession(PosSession $session)
    {
        try {
            $cashSales = $session->posOrders()
                ->where('payment_method', 'cash')
                ->where('status', 'completed')
                ->sum('grand_total');

            $cardSales = $session->posOrders()
                ->where('payment_method', '!=', 'cash')
                ->where('status', 'completed')
                ->sum('grand_total');

            $session->update([
                'status' => 'closed',
                'closed_at' => now(),
                'cash_sales' => $cashSales,
                'card_sales' => $cardSales,
                'total_sales' => $cashSales + $cardSales,
            ]);

            activity()->performedOn($session)->causedBy(auth()->user())->log('POS session closed');

            return redirect()->back()->with('success', 'POS session closed');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function register()
    {
        $session = PosSession::where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        $products = Product::active()
            ->with('media', 'variants')
            ->orderBy('name')
            ->get();

        $customers = Customer::with('user')->get();

        return Inertia::render('Admin/POS/Register', [
            'session' => $session,
            'products' => $products,
            'customers' => $customers,
        ]);
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:pos_sessions,id',
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_banking',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            $subtotal = collect($validated['items'])->sum(fn ($i) => $i['quantity'] * $i['unit_price']);
            $changeAmount = max(0, $validated['paid_amount'] - $subtotal);

            $posOrder = PosOrder::create([
                'session_id' => $validated['session_id'],
                'customer_id' => $validated['customer_id'] ?? null,
                'pos_number' => 'POS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
                'type' => 'sale',
                'payment_method' => $validated['payment_method'],
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $changeAmount,
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $posOrder->items()->create($item);
            }

            activity()->performedOn($posOrder)->causedBy(auth()->user())->log('POS order created');

            return redirect()->back()->with('success', 'Order completed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function orders(Request $request)
    {
        $query = PosOrder::with(['session.user', 'customer.user', 'createdBy']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15);

        return Inertia::render('Admin/POS/Orders', [
            'orders' => $orders,
            'filters' => $request->only(['date_from', 'date_to']),
        ]);
    }

    public function reports()
    {
        $totalSales = PosOrder::where('status', 'completed')->sum('grand_total');
        $todaySales = PosOrder::whereDate('created_at', today())->where('status', 'completed')->sum('grand_total');
        $totalOrders = PosOrder::where('status', 'completed')->count();
        $averageOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        $salesByMethod = PosOrder::where('status', 'completed')
            ->selectRaw('payment_method, SUM(grand_total) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return Inertia::render('Admin/POS/Reports', [
            'totalSales' => $totalSales,
            'todaySales' => $todaySales,
            'totalOrders' => $totalOrders,
            'averageOrder' => round($averageOrder, 2),
            'salesByMethod' => $salesByMethod,
        ]);
    }
}
