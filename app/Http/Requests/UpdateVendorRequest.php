<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vendorId = $this->route('vendor') instanceof \App\Models\Vendor
            ? $this->route('vendor')->id
            : $this->route('vendor');

        return [
            'store_name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('vendors', 'slug')->ignore($vendorId)],
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
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'store_name.required' => 'দোকানের নাম প্রয়োজন | Store name is required',
            'commission_rate.max' => 'কমিশনের হার ১০০% এর বেশি হতে পারবে না | Commission rate cannot exceed 100%',
        ];
    }
}
