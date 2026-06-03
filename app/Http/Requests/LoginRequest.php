<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'ইমেল প্রয়োজন | Email is required',
            'email.email' => 'বৈধ ইমেল ঠিকানা দিন | Please provide a valid email address',
            'password.required' => 'পাসওয়ার্ড প্রয়োজন | Password is required',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে | Password must be at least 8 characters',
        ];
    }
}
