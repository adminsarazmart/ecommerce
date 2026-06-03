<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Administration', 'slug' => 'administration', 'description' => 'General administration and management'],
            ['name' => 'Sales & Marketing', 'slug' => 'sales-marketing', 'description' => 'Sales and marketing operations'],
            ['name' => 'IT & Development', 'slug' => 'it-development', 'description' => 'Technology and software development'],
            ['name' => 'Finance & Accounting', 'slug' => 'finance-accounting', 'description' => 'Financial management and accounting'],
            ['name' => 'Human Resources', 'slug' => 'human-resources', 'description' => 'HR and personnel management'],
            ['name' => 'Customer Support', 'slug' => 'customer-support', 'description' => 'Customer service and support'],
            ['name' => 'Logistics & Supply Chain', 'slug' => 'logistics-supply-chain', 'description' => 'Shipping and inventory management'],
            ['name' => 'Quality Assurance', 'slug' => 'quality-assurance', 'description' => 'Product and service quality control'],
            ['name' => 'Legal & Compliance', 'slug' => 'legal-compliance', 'description' => 'Legal affairs and regulatory compliance'],
            ['name' => 'Operations', 'slug' => 'operations', 'description' => 'Day-to-day business operations'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
