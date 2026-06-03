<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'product', 'order', 'vendor', 'customer', 'category', 'brand',
            'employee', 'shareholder', 'reseller', 'inventory', 'pos', 'erp',
            'report', 'analytics', 'settings', 'cms', 'builder', 'coupon',
            'currency', 'language', 'audit', 'role', 'supplier', 'purchase-order',
            'warehouse', 'warehouse-transfer',
        ];

        $actions = ['list', 'view', 'create', 'update', 'delete', 'restore', 'force-delete'];

        $extraPermissions = [
            'order.update-status', 'order.refund',
            'vendor.verify', 'vendor.approve-kyc',
            'reseller.approve',
            'shareholder.distribute-dividends',
            'report.sales', 'report.profit', 'report.inventory', 'report.vendors', 'report.customers', 'report.export',
            'settings.general', 'settings.payment', 'settings.shipping', 'settings.email', 'settings.seo', 'settings.security',
            'settings.theme',
        ];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}", 'guard_name' => 'web']);
            }
        }

        foreach ($extraPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminPermissions = Permission::whereNotIn('name', [
            'shareholder.force-delete', 'role.force-delete',
        ])->pluck('name')->toArray();
        $admin->syncPermissions($adminPermissions);

        $vendorPermissions = [
            'product.list', 'product.view', 'product.create', 'product.update', 'product.delete',
            'order.list', 'order.view', 'order.update-status',
            'report.list', 'report.sales', 'report.profit', 'report.inventory',
        ];
        $vendor = Role::firstOrCreate(['name' => 'Vendor', 'guard_name' => 'web']);
        $vendor->syncPermissions($vendorPermissions);

        $resellerPermissions = [
            'reseller.list', 'reseller.view',
            'report.list', 'report.sales',
        ];
        $reseller = Role::firstOrCreate(['name' => 'Reseller', 'guard_name' => 'web']);
        $reseller->syncPermissions($resellerPermissions);

        $customerPermissions = [
            'order.list', 'order.view', 'order.create',
        ];
        $customer = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);
        $customer->syncPermissions($customerPermissions);

        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $employeeRole->syncPermissions([]);

        $shareholderRole = Role::firstOrCreate(['name' => 'Shareholder', 'guard_name' => 'web']);
        $shareholderRole->syncPermissions([]);
    }
}
