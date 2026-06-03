<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShareholderRequest extends FormRequest
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
            'share_percentage' => 'required|numeric|min:0|max:100',
            'total_investment' => 'nullable|numeric|min:0',
            'total_shares' => 'nullable|integer|min:0',
            'join_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'নাম প্রয়োজন | Name is required',
            'email.required' => 'ইমেল প্রয়োজন | Email is required',
            'email.unique' => 'এই ইমেল ইতিমধ্যে ব্যবহৃত | This email is already taken',
            'share_percentage.required' => 'শেয়ার শতাংশ প্রয়োজন | Share percentage is required',
            'share_percentage.numeric' => 'শেয়ার শতাংশ সংখ্যা হতে হবে | Share percentage must be a number',
            'share_percentage.max' => 'শেয়ার শতাংশ ১০০% এর বেশি হতে পারবে না | Share percentage cannot exceed 100%',
            'total_investment.numeric' => 'বিনিয়োগের পরিমাণ সংখ্যা হতে হবে | Investment amount must be a number',
        ];
    }
}
