<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')->middleware(['auth', 'verified', 'role:super_admin|admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('admin.index');

    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard/Index');
    })->name('admin.dashboard');

    Route::get('/marketplace', function () {
        return Inertia::render('Admin/Marketplace/Index');
    })->name('admin.marketplace');

    Route::prefix('products')->name('admin.products.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Products/Index');
        })->name('index');
        Route::get('/create', function () {
            return Inertia::render('Admin/Products/Create');
        })->name('create');
        Route::get('/{product}/edit', function ($product) {
            return Inertia::render('Admin/Products/Edit', ['product' => $product]);
        })->name('edit');
    });

    Route::prefix('vendors')->name('admin.vendors.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Vendors/Index');
        })->name('index');
        Route::get('/create', function () {
            return Inertia::render('Admin/Vendors/Create');
        })->name('create');
        Route::get('/{vendor}', function ($vendor) {
            return Inertia::render('Admin/Vendors/Show', ['vendor' => $vendor]);
        })->name('show');
    });

    Route::prefix('orders')->name('admin.orders.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Orders/Index');
        })->name('index');
        Route::get('/{order}', function ($order) {
            return Inertia::render('Admin/Orders/Show', ['order' => $order]);
        })->name('show');
    });

    Route::prefix('cms')->name('admin.cms.')->group(function () {
        Route::get('/pages', function () {
            return Inertia::render('Admin/Cms/Pages/Index');
        })->name('pages');
        Route::get('/pages/create', function () {
            return Inertia::render('Admin/Cms/Pages/Create');
        })->name('pages.create');

        Route::get('/menus', function () {
            return Inertia::render('Admin/Cms/Menus/Index');
        })->name('menus');

        Route::get('/banners', function () {
            return Inertia::render('Admin/Cms/Banners/Index');
        })->name('banners');

        Route::get('/sliders', function () {
            return Inertia::render('Admin/Cms/Sliders/Index');
        })->name('sliders');
        Route::get('/sliders/create', function () {
            return Inertia::render('Admin/Cms/Sliders/Create');
        })->name('sliders.create');
    });

    Route::prefix('builder')->name('admin.builder.')->group(function () {
        Route::get('/header', function () {
            return Inertia::render('Admin/Builder/HeaderBuilder');
        })->name('header');
        Route::get('/footer', function () {
            return Inertia::render('Admin/Builder/FooterBuilder');
        })->name('footer');
        Route::get('/homepage', function () {
            return Inertia::render('Admin/Builder/HomepageBuilder');
        })->name('homepage');
    });

    Route::prefix('inventory')->name('admin.inventory.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Inventory/Index');
        })->name('index');
        Route::get('/warehouses', function () {
            return Inertia::render('Admin/Inventory/Warehouses/Index');
        })->name('warehouses');
        Route::get('/transfers', function () {
            return Inertia::render('Admin/Inventory/Transfers');
        })->name('transfers');
        Route::get('/suppliers', function () {
            return Inertia::render('Admin/Inventory/Suppliers/Index');
        })->name('suppliers');
        Route::get('/purchase-orders', function () {
            return Inertia::render('Admin/Inventory/PurchaseOrders/Index');
        })->name('purchase-orders');
        Route::get('/purchase-orders/create', function () {
            return Inertia::render('Admin/Inventory/PurchaseOrders/Create');
        })->name('purchase-orders.create');
    });

    Route::prefix('pos')->name('admin.pos.')->group(function () {
        Route::get('/register', function () {
            return Inertia::render('Admin/POS/Register');
        })->name('register');
        Route::get('/sessions', function () {
            return Inertia::render('Admin/POS/Sessions');
        })->name('sessions');
    });

    Route::prefix('erp')->name('admin.erp.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/ERP/Dashboard');
        })->name('dashboard');
        Route::get('/chart-of-accounts', function () {
            return Inertia::render('Admin/ERP/ChartOfAccounts');
        })->name('chart-of-accounts');
        Route::get('/journal-entries', function () {
            return Inertia::render('Admin/ERP/JournalEntries');
        })->name('journal-entries');
        Route::get('/profit-loss', function () {
            return Inertia::render('Admin/ERP/ProfitLoss');
        })->name('profit-loss');
        Route::get('/balance-sheet', function () {
            return Inertia::render('Admin/ERP/BalanceSheet');
        })->name('balance-sheet');
        Route::get('/expenses', function () {
            return Inertia::render('Admin/ERP/Expenses');
        })->name('expenses');
    });

    Route::prefix('employees')->name('admin.employees.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Employees/Index');
        })->name('index');
        Route::get('/{employee}', function ($employee) {
            return Inertia::render('Admin/Employees/Show', ['employee' => $employee]);
        })->name('show');
        Route::get('/payroll', function () {
            return Inertia::render('Admin/Employees/Payroll');
        })->name('payroll');
        Route::get('/attendance', function () {
            return Inertia::render('Admin/Employees/Attendance');
        })->name('attendance');
        Route::get('/leaves', function () {
            return Inertia::render('Admin/Employees/Leaves');
        })->name('leaves');
    });

    Route::prefix('shareholders')->name('admin.shareholders.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Shareholders/Index');
        })->name('index');
        Route::get('/ledger', function () {
            return Inertia::render('Admin/Shareholders/Ledger');
        })->name('ledger');
        Route::get('/dividends', function () {
            return Inertia::render('Admin/Shareholders/Dividends');
        })->name('dividends');
    });

    Route::prefix('reports')->name('admin.reports.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Reports/Index');
        })->name('index');
        Route::get('/sales', function () {
            return Inertia::render('Admin/Reports/Sales');
        })->name('sales');
        Route::get('/profit', function () {
            return Inertia::render('Admin/Reports/Profit');
        })->name('profit');
    });

    Route::prefix('analytics')->name('admin.analytics.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/Analytics/Index');
        })->name('index');
    });
});
