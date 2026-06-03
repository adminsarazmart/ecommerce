<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Assets
            ['name' => 'Current Assets', 'code' => '1000', 'type' => 'asset', 'parent_id' => null, 'description' => 'Current assets'],
            ['name' => 'Cash in Bank', 'code' => '1100', 'type' => 'asset', 'description' => 'Bank accounts'],
            ['name' => 'Cash in Hand', 'code' => '1101', 'type' => 'asset', 'description' => 'Petty cash'],
            ['name' => 'Accounts Receivable', 'code' => '1200', 'type' => 'asset', 'description' => 'Money owed by customers'],
            ['name' => 'Inventory', 'code' => '1300', 'type' => 'asset', 'description' => 'Product inventory value'],
            ['name' => 'Fixed Assets', 'code' => '1500', 'type' => 'asset', 'parent_id' => null, 'description' => 'Fixed assets'],
            ['name' => 'Office Equipment', 'code' => '1510', 'type' => 'asset', 'description' => 'Office equipment and furniture'],
            ['name' => 'Vehicles', 'code' => '1520', 'type' => 'asset', 'description' => 'Company vehicles'],

            // Liabilities
            ['name' => 'Current Liabilities', 'code' => '2000', 'type' => 'liability', 'parent_id' => null, 'description' => 'Current liabilities'],
            ['name' => 'Accounts Payable', 'code' => '2100', 'type' => 'liability', 'description' => 'Money owed to suppliers'],
            ['name' => 'Vendor Payable', 'code' => '2200', 'type' => 'liability', 'description' => 'Amounts payable to vendors'],
            ['name' => 'Tax Payable', 'code' => '2300', 'type' => 'liability', 'description' => 'Taxes owed'],
            ['name' => 'Long Term Liabilities', 'code' => '2500', 'type' => 'liability', 'parent_id' => null, 'description' => 'Long-term liabilities'],
            ['name' => 'Bank Loans', 'code' => '2510', 'type' => 'liability', 'description' => 'Bank loans payable'],

            // Equity
            ['name' => 'Equity', 'code' => '3000', 'type' => 'equity', 'parent_id' => null, 'description' => 'Owner equity'],
            ['name' => 'Share Capital', 'code' => '3100', 'type' => 'equity', 'description' => 'Shareholder capital'],
            ['name' => 'Retained Earnings', 'code' => '3200', 'type' => 'equity', 'description' => 'Retained earnings'],
            ['name' => 'Dividend Payable', 'code' => '3300', 'type' => 'equity', 'description' => 'Dividends declared but not paid'],

            // Income
            ['name' => 'Revenue', 'code' => '4000', 'type' => 'income', 'parent_id' => null, 'description' => 'Operating revenue'],
            ['name' => 'Product Sales', 'code' => '4100', 'type' => 'income', 'description' => 'Revenue from product sales'],
            ['name' => 'Commission Income', 'code' => '4200', 'type' => 'income', 'description' => 'Vendor commission income'],
            ['name' => 'Shipping Income', 'code' => '4300', 'type' => 'income', 'description' => 'Shipping fees collected'],
            ['name' => 'Other Income', 'code' => '4900', 'type' => 'income', 'description' => 'Miscellaneous income'],

            // Expenses
            ['name' => 'Operating Expenses', 'code' => '5000', 'type' => 'expense', 'parent_id' => null, 'description' => 'Operating expenses'],
            ['name' => 'Cost of Goods Sold', 'code' => '5100', 'type' => 'expense', 'description' => 'Direct cost of products sold'],
            ['name' => 'Salaries & Wages', 'code' => '5200', 'type' => 'expense', 'description' => 'Employee salaries'],
            ['name' => 'Rent & Utilities', 'code' => '5300', 'type' => 'expense', 'description' => 'Office rent and utilities'],
            ['name' => 'Marketing & Advertising', 'code' => '5400', 'type' => 'expense', 'description' => 'Marketing costs'],
            ['name' => 'Shipping & Logistics', 'code' => '5500', 'type' => 'expense', 'description' => 'Shipping costs'],
            ['name' => 'Office Supplies', 'code' => '5600', 'type' => 'expense', 'description' => 'Office supplies'],
            ['name' => 'IT & Software', 'code' => '5700', 'type' => 'expense', 'description' => 'Technology costs'],
            ['name' => 'Bank Charges', 'code' => '5800', 'type' => 'expense', 'description' => 'Bank fees and charges'],
            ['name' => 'Tax & Legal', 'code' => '5900', 'type' => 'expense', 'description' => 'Tax and legal fees'],
            ['name' => 'Depreciation', 'code' => '5910', 'type' => 'expense', 'description' => 'Asset depreciation'],
            ['name' => 'Miscellaneous Expenses', 'code' => '5999', 'type' => 'expense', 'description' => 'Other expenses'],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}
