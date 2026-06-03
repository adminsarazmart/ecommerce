<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|string|in:stripe,paypal,razorpay,paystack,flutterwave,sslcommerz,bkash,nagad,cod,bank_transfer',
            'amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'card_number' => 'nullable|string|max:19',
            'card_expiry' => 'nullable|string|max:7',
            'card_cvc' => 'nullable|string|max:4',
            'card_holder' => 'nullable|string|max:255',
            'mobile_number' => 'required_if:method,bkash,nagad|nullable|string|max:20',
            'transaction_id' => 'nullable|string|max:255',
            'bank_name' => 'required_if:method,bank_transfer|nullable|string|max:255',
            'bank_account' => 'required_if:method,bank_transfer|nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'অর্ডার আইডি প্রয়োজন | Order ID is required',
            'method.required' => 'পেমেন্ট পদ্ধতি প্রয়োজন | Payment method is required',
            'method.in' => 'অবৈধ পেমেন্ট পদ্ধতি | Invalid payment method',
            'mobile_number.required_if' => 'মোবাইল নম্বর প্রয়োজন | Mobile number is required',
            'bank_name.required_if' => 'ব্যাংকের নাম প্রয়োজন | Bank name is required',
            'bank_account.required_if' => 'ব্যাংক একাউন্ট নম্বর প্রয়োজন | Bank account number is required',
        ];
    }
}
