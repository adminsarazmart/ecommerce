<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ShareholderSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            VendorSeeder::class,
            OrderSeeder::class,
            CustomerSeeder::class,
            MembershipLevelSeeder::class,
            CurrencySeeder::class,
            LanguageSeeder::class,
            CountrySeeder::class,
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            EmailTemplateSeeder::class,
            AccountSeeder::class,
        ]);
    }
}
