<?php

return [
    'models' => [
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null,
        'permission_pivot_key' => null,
        'model_morph_key' => 'model_id',
        'team_foreign_key' => 'team_id',
    ],

    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'teams' => false,
    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],

    'permissions' => [
        'product.view', 'product.create', 'product.edit', 'product.delete', 'product.import', 'product.export',
        'category.view', 'category.create', 'category.edit', 'category.delete',
        'brand.view', 'brand.create', 'brand.edit', 'brand.delete',
        'order.view', 'order.create', 'order.edit', 'order.delete', 'order.process', 'order.refund', 'order.return',
        'vendor.view', 'vendor.create', 'vendor.edit', 'vendor.delete', 'vendor.verify', 'vendor.approve',
        'customer.view', 'customer.create', 'customer.edit', 'customer.delete',
        'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete', 'inventory.transfer',
        'warehouse.view', 'warehouse.create', 'warehouse.edit', 'warehouse.delete',
        'purchase-order.view', 'purchase-order.create', 'purchase-order.edit', 'purchase-order.receive',
        'supplier.view', 'supplier.create', 'supplier.edit', 'supplier.delete',
        'cms.page.view', 'cms.page.create', 'cms.page.edit', 'cms.page.delete',
        'cms.menu.view', 'cms.menu.create', 'cms.menu.edit', 'cms.menu.delete',
        'cms.banner.view', 'cms.banner.create', 'cms.banner.edit', 'cms.banner.delete',
        'cms.slider.view', 'cms.slider.create', 'cms.slider.edit', 'cms.slider.delete',
        'builder.header', 'builder.footer', 'builder.homepage',
        'pos.access', 'pos.create', 'pos.view', 'pos.close',
        'erp.accounts.view', 'erp.accounts.create', 'erp.accounts.edit',
        'erp.journal.view', 'erp.journal.create', 'erp.expenses.manage',
        'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
        'employee.attendance', 'employee.payroll', 'employee.leaves',
        'shareholder.view', 'shareholder.create', 'shareholder.edit',
        'shareholder.dividends', 'shareholder.distribute',
        'reseller.view', 'reseller.create', 'reseller.edit', 'reseller.commissions',
        'report.sales', 'report.profit', 'report.inventory', 'report.vendors', 'report.customers',
        'analytics.view', 'analytics.revenue', 'analytics.products', 'analytics.customers',
        'settings.view', 'settings.edit', 'settings.payment', 'settings.shipping',
        'settings.email', 'settings.seo', 'settings.theme',
        'user.view', 'user.create', 'user.edit', 'user.delete', 'user.roles',
        'backup.create', 'backup.restore', 'backup.delete',
    ],

    'role_permissions' => [
        'Super Admin' => ['*'],

        'Admin' => [
            'product.*', 'category.*', 'brand.*', 'order.*',
            'vendor.*', 'customer.*',
            'inventory.*', 'warehouse.*', 'purchase-order.*', 'supplier.*',
            'cms.*', 'builder.*',
            'pos.*',
            'erp.*',
            'employee.*',
            'shareholder.*',
            'reseller.*',
            'report.*',
            'analytics.*',
            'settings.*',
            'user.view', 'backup.*',
        ],

        'Vendor' => [
            'product.view', 'product.create', 'product.edit',
            'order.view', 'order.process',
            'inventory.view',
            'report.sales', 'report.profit', 'report.inventory',
        ],

        'Reseller' => [
            'order.view',
            'report.sales',
        ],

        'Customer' => [
            'order.view', 'order.create',
        ],
    ],
];
