<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $user = $request->user()->load('profile', 'customer');

            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $user = $request->user();
            $user->update($request->only(['name', 'email']));

            if ($request->filled('password')) {
                $user->update(['password' => bcrypt($request->password)]);
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $request->only(['first_name', 'last_name', 'phone', 'gender', 'dob', 'bio'])
            );

            activity()->causedBy($user)->log('Profile updated via API');

            return response()->json([
                'success' => true,
                'message' => 'Profile updated',
                'data' => $user->fresh()->load('profile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function orders(Request $request)
    {
        try {
            $customer = $request->user()->customer;

            $orders = $customer->orders()
                ->with('items.product')
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addresses(Request $request)
    {
        try {
            $customer = $request->user()->customer;
            $addresses = $customer->addresses;

            return response()->json([
                'success' => true,
                'data' => $addresses,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
            'is_shipping' => 'boolean',
            'is_billing' => 'boolean',
        ]);

        try {
            $customer = $request->user()->customer;

            if ($request->boolean('is_default')) {
                $customer->addresses()->update(['is_default' => false]);
            }

            $address = $customer->addresses()->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Address added',
                'data' => $address,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
