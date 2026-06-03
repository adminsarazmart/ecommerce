<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|exists:coupons,code',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'কুপন কোড প্রয়োজন | Coupon code is required',
            'code.exists' => 'কুপন কোডটি বৈধ নয় | Invalid coupon code',
        ];
    }
}
