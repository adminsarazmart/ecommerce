<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'employee_code' => 'nullable|string|max:50|unique:employees,employee_code',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'salary_type' => 'nullable|in:hourly,daily,weekly,monthly,yearly',
            'employment_status' => 'nullable|in:active,inactive,suspended,terminated',
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'নাম প্রয়োজন | Name is required',
            'email.required' => 'ইমেল প্রয়োজন | Email is required',
            'email.unique' => 'এই ইমেল ইতিমধ্যে ব্যবহৃত | This email is already taken',
            'department_id.required' => 'বিভাগ প্রয়োজন | Department is required',
            'position.required' => 'পদবী প্রয়োজন | Position is required',
            'hire_date.required' => 'নিয়োগের তারিখ প্রয়োজন | Hire date is required',
            'salary.required' => 'বেতন প্রয়োজন | Salary is required',
            'salary.numeric' => 'বেতন সংখ্যা হতে হবে | Salary must be a number',
            'employee_code.unique' => 'এই কর্মচারী কোড ইতিমধ্যে ব্যবহৃত | This employee code is already taken',
        ];
    }
}
