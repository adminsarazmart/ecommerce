<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore(auth()->id())],
            'password' => 'nullable|string|min:8|confirmed',
            'current_password' => 'nullable|required_with:password|string|min:8',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'নাম প্রয়োজন | Name is required',
            'email.required' => 'ইমেল প্রয়োজন | Email is required',
            'email.unique' => 'এই ইমেল ইতিমধ্যে ব্যবহৃত | This email is already taken',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না | Passwords do not match',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষর হতে হবে | Password must be at least 8 characters',
            'current_password.required_with' => 'বর্তমান পাসওয়ার্ড প্রয়োজন | Current password is required',
            'avatar.image' => 'অবতার একটি ছবি হতে হবে | Avatar must be an image',
            'avatar.max' => 'ছবির সর্বোচ্চ আকার ২এমবি | Image max size is 2MB',
        ];
    }
}
