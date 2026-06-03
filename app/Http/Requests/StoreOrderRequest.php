<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_address' => 'required|array',
            'shipping_address.first_name' => 'required|string|max:255',
            'shipping_address.last_name' => 'nullable|string|max:255',
            'shipping_address.phone' => 'required|string|max:20',
            'shipping_address.address_line1' => 'required|string|max:255',
            'shipping_address.address_line2' => 'nullable|string|max:255',
            'shipping_address.city' => 'required|string|max:100',
            'shipping_address.state' => 'nullable|string|max:100',
            'shipping_address.zip' => 'nullable|string|max:20',
            'shipping_address.country' => 'required|string|max:100',
            'billing_address' => 'nullable|array',
            'billing_address.first_name' => 'required_with:billing_address|string|max:255',
            'billing_address.last_name' => 'nullable|string|max:255',
            'billing_address.phone' => 'required_with:billing_address|string|max:20',
            'billing_address.address_line1' => 'required_with:billing_address|string|max:255',
            'billing_address.city' => 'required_with:billing_address|string|max:100',
            'billing_address.country' => 'required_with:billing_address|string|max:100',
            'payment' => 'required|array',
            'payment.method' => 'required|string|in:stripe,paypal,bkash,nagad,cod,bank_transfer',
            'payment.transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string|max:50|exists:coupons,code',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address.required' => 'শিপিং ঠিকানা প্রয়োজন | Shipping address is required',
            'shipping_address.first_name.required' => 'নাম প্রয়োজন | First name is required',
            'shipping_address.phone.required' => 'ফোন নম্বর প্রয়োজন | Phone number is required',
            'shipping_address.city.required' => 'শহর প্রয়োজন | City is required',
            'shipping_address.country.required' => 'দেশ প্রয়োজন | Country is required',
            'payment.method.required' => 'পেমেন্ট পদ্ধতি প্রয়োজন | Payment method is required',
            'payment.method.in' => 'অবৈধ পেমেন্ট পদ্ধতি | Invalid payment method',
        ];
    }
}
