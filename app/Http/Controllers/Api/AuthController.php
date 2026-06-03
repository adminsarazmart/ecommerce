<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            activity()
                ->causedBy($user)
                ->log('User logged in via API');

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user->load('profile', 'customer'),
                    'token' => $token,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $customer = Customer::create(['user_id' => $user->id]);
            $user->assignRole('Customer');

            $token = $user->createToken('api-token')->plainTextToken;

            activity()
                ->causedBy($user)
                ->log('User registered via API');

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => $user->load('profile', 'customer'),
                    'token' => $token,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => ['token' => $token],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            $status = Password::sendResetLink($request->only('email'));

            return response()->json([
                'success' => $status === Password::RESET_LINK_SENT,
                'message' => __($status),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill(['password' => Hash::make($password)])->save();
                }
            );

            return response()->json([
                'success' => $status === Password::PASSWORD_RESET,
                'message' => __($status),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
