<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('membership_level')) {
            $query->where('membership_level', $request->membership_level);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('Admin/Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search', 'membership_level']),
        ]);
    }

    public function show(Customer $customer)
    {
        $customer->load(['user.profile', 'addresses', 'orders' => function ($q) {
            $q->latest()->take(10);
        }, 'wishlistItems.product', 'reviews']);

        return Inertia::render('Admin/Customers/Show', [
            'customer' => $customer,
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        try {
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
                'membership_level' => $request->membership_level ?? 'regular',
            ]);

            $user->assignRole('Customer');

            activity()
                ->performedOn($customer)
                ->causedBy(auth()->user())
                ->log('Customer created');

            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function viewOrders(Customer $customer)
    {
        $orders = $customer->orders()
            ->with('items.product')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Customers/Orders', [
            'customer' => $customer->load('user'),
            'orders' => $orders,
        ]);
    }

    public function manageAddresses(Customer $customer)
    {
        $addresses = $customer->addresses;

        return Inertia::render('Admin/Customers/Addresses', [
            'customer' => $customer->load('user'),
            'addresses' => $addresses,
        ]);
    }
}
