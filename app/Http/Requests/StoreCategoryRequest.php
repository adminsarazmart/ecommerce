<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') instanceof \App\Models\Category
            ? $this->route('category')->id
            : null;

        return [
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'display_mode' => 'nullable|in:products,subcategories,both',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'বিভাগের নাম প্রয়োজন | Category name is required',
            'slug.unique' => 'এই স্লাগ ইতিমধ্যে ব্যবহৃত | This slug is already taken',
            'parent_id.exists' => 'প্যারেন্ট বিভাগটি বিদ্যমান নেই | Parent category does not exist',
        ];
    }
}
