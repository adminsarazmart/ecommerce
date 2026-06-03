<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function dashboard()
    {
        $customer = auth()->user()->customer;
        $data = $this->customerService->getDashboard($customer->id);

        return Inertia::render('Account/Dashboard', $data);
    }

    public function orders()
    {
        $customer = auth()->user()->customer;
        $orders = $this->customerService->getOrderHistory($customer->id);

        return Inertia::render('Account/Orders', ['orders' => $orders]);
    }

    public function orderDetail($id)
    {
        $customer = auth()->user()->customer;
        $order = $customer->orders()
            ->with(['items.product', 'items.variant', 'statusHistories', 'shipments'])
            ->findOrFail($id);

        return Inertia::render('Account/OrderDetail', ['order' => $order]);
    }

    public function profile()
    {
        $user = auth()->user()->load('profile');

        return Inertia::render('Account/Profile', [
            'user' => $user,
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = auth()->user();
            $user->update($request->only(['name', 'email']));

            if ($request->filled('password')) {
                $user->update(['password' => bcrypt($request->password)]);
            }

            $profileData = $request->only([
                'first_name', 'last_name', 'phone', 'gender', 'dob', 'bio',
            ]);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            activity()
                ->causedBy($user)
                ->log('Profile updated');

            return redirect()->back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function addresses()
    {
        $customer = auth()->user()->customer;
        $addresses = $customer->addresses;

        return Inertia::render('Account/Addresses', ['addresses' => $addresses]);
    }
}
