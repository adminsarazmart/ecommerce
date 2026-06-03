<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store_name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:vendors,slug',
            'store_description' => 'nullable|string',
            'store_email' => 'nullable|email|max:255',
            'store_phone' => 'nullable|string|max:20',
            'store_address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'commission_type' => 'nullable|in:percentage,fixed',
            'tax_id' => 'nullable|string|max:100',
            'business_registration' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255|unique:users,email',
            'password' => 'required_without:user_id|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'দোকানের নাম প্রয়োজন | Store name is required',
            'slug.unique' => 'এই স্লাগ ইতিমধ্যে ব্যবহৃত | This slug is already taken',
            'commission_rate.numeric' => 'কমিশনের হার সংখ্যা হতে হবে | Commission rate must be a number',
            'commission_rate.max' => 'কমিশনের হার ১০০% এর বেশি হতে পারবে না | Commission rate cannot exceed 100%',
            'name.required_without' => 'নাম প্রয়োজন | Name is required',
            'email.required_without' => 'ইমেল প্রয়োজন | Email is required',
            'email.unique' => 'এই ইমেল ইতিমধ্যে ব্যবহৃত | This email is already taken',
            'password.required_without' => 'পাসওয়ার্ড প্রয়োজন | Password is required',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে | Password must be at least 8 characters',
        ];
    }
}
