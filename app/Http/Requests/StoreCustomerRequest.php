<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'membership_level' => 'nullable|string|in:regular,silver,gold,platinum',
            'referred_by' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'নাম প্রয়োজন | Name is required',
            'email.required' => 'ইমেল প্রয়োজন | Email is required',
            'email.unique' => 'এই ইমেল ইতিমধ্যে ব্যবহৃত | This email is already taken',
            'password.required' => 'পাসওয়ার্ড প্রয়োজন | Password is required',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে | Password must be at least 8 characters',
        ];
    }
}
