<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResellerRequest extends FormRequest
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
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'parent_reseller_id' => 'nullable|exists:resellers,id',
            'referral_code' => 'nullable|string|max:50|unique:resellers,referral_code',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'নাম প্রয়োজন | Name is required',
            'email.required' => 'ইমেল প্রয়োজন | Email is required',
            'email.unique' => 'এই ইমেল ইতিমধ্যে ব্যবহৃত | This email is already taken',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে | Password must be at least 8 characters',
            'commission_rate.max' => 'কমিশনের হার ১০০% এর বেশি হতে পারবে না | Commission rate cannot exceed 100%',
        ];
    }
}
