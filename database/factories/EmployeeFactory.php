<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'employee_code' => 'EMP-' . strtoupper(Str::random(6)),
            'department_id' => Department::factory(),
            'position' => fake()->jobTitle(),
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'salary' => fake()->randomFloat(2, 15000, 150000),
            'salary_type' => 'monthly',
            'employment_status' => fake()->randomElement(['active', 'active', 'active', 'inactive']),
            'emergency_contact' => fake()->name(),
            'emergency_phone' => fake()->phoneNumber(),
            'bank_name' => fake()->randomElement(['Sonali Bank', 'BRAC Bank', 'Dutch Bangla Bank', 'HSBC']),
            'bank_account' => fake()->bankAccountNumber(),
            'bank_branch' => fake()->city() . ' Branch',
            'documents' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['employment_status' => 'active']);
    }
}
