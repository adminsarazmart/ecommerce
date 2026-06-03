<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'agree_terms' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'নাম প্রয়োজন | Name is required',
            'email.required' => 'ইমেল প্রয়োজন | Email is required',
            'email.unique' => 'এই ইমেল ইতিমধ্যে নিবন্ধিত | This email is already registered',
            'password.required' => 'পাসওয়ার্ড প্রয়োজন | Password is required',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে | Password must be at least 8 characters',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না | Passwords do not match',
            'agree_terms.required' => 'শর্তাবলী গ্রহণ করুন | Please accept the terms and conditions',
            'agree_terms.accepted' => 'শর্তাবলী গ্রহণ করুন | Please accept the terms and conditions',
        ];
    }
}
