<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        $employees = [
            ['name' => 'Md. Rahim Uddin', 'position' => 'CEO', 'salary' => 250000],
            ['name' => 'Farhana Sultana', 'position' => 'CTO', 'salary' => 200000],
            ['name' => 'Hasan Mahmud', 'position' => 'Sales Manager', 'salary' => 120000],
            ['name' => 'Nusrat Jahan', 'position' => 'Marketing Lead', 'salary' => 100000],
            ['name' => 'Kabir Hossain', 'position' => 'Senior Developer', 'salary' => 90000],
            ['name' => 'Shamim Reza', 'position' => 'Accountant', 'salary' => 70000],
            ['name' => 'Tahmina Akhter', 'position' => 'HR Manager', 'salary' => 85000],
            ['name' => 'Rafiq Islam', 'position' => 'Customer Support Lead', 'salary' => 60000],
            ['name' => 'Jahid Hasan', 'position' => 'Logistics Manager', 'salary' => 75000],
            ['name' => 'Sajeda Begum', 'position' => 'QA Engineer', 'salary' => 65000],
        ];

        foreach ($employees as $i => $empData) {
            $user = User::create([
                'name' => $empData['name'],
                'email' => Str::slug($empData['name']) . '@company.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Employee');

            UserProfile::create([
                'user_id' => $user->id,
                'first_name' => explode(' ', $empData['name'])[0] ?? '',
                'last_name' => explode(' ', $empData['name'])[1] ?? '',
                'phone' => '017' . str_pad($i + 10, 8, '0', STR_PAD_LEFT),
            ]);

            Employee::create([
                'user_id' => $user->id,
                'employee_code' => 'EMP-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'department_id' => $departments->random()->id,
                'position' => $empData['position'],
                'hire_date' => now()->subMonths(rand(3, 36)),
                'salary' => $empData['salary'],
                'salary_type' => 'monthly',
                'employment_status' => 'active',
            ]);
        }
    }
}
