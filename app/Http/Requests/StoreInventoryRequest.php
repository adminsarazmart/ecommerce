<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:addition,deduction',
            'reason' => 'nullable|string|max:500',
            'reference_type' => 'nullable|string|max:100',
            'reference_id' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'পণ্য আইডি প্রয়োজন | Product ID is required',
            'warehouse_id.required' => 'গোডাউন আইডি প্রয়োজন | Warehouse ID is required',
            'quantity.required' => 'পরিমাণ প্রয়োজন | Quantity is required',
            'quantity.min' => 'পরিমাণ কমপক্ষে ১ হতে হবে | Quantity must be at least 1',
            'type.required' => 'ধরন প্রয়োজন | Type is required',
            'type.in' => 'ধরন addition বা deduction হতে হবে | Type must be addition or deduction',
        ];
    }
}
