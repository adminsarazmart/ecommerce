<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') instanceof \App\Models\Product
            ? $this->route('product')->id
            : $this->route('product');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId,
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lte:unit_price',
            'cost_price' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'min_wholesale_qty' => 'nullable|integer|min:1',
            'tax' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|in:percentage,fixed',
            'weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'tags' => 'nullable|json',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_new' => 'boolean',
            'allow_backorder' => 'boolean',
            'min_qty' => 'nullable|integer|min:1',
            'max_qty' => 'nullable|integer|min:1',
            'is_virtual' => 'boolean',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required_with:variants|string|max:255',
            'variants.*.sku' => 'required_with:variants|string|distinct',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attributes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'পণ্যের নাম প্রয়োজন | Product name is required',
            'sku.required' => 'SKU প্রয়োজন | SKU is required',
            'sku.unique' => 'এই SKU ইতিমধ্যে ব্যবহৃত | This SKU is already taken',
            'unit_price.required' => 'মূল্য প্রয়োজন | Price is required',
            'sale_price.lte' => 'বিক্রয় মূল্য মূল মূল্যের চেয়ে কম বা সমান হতে হবে | Sale price must be less than or equal to unit price',
        ];
    }
}
