<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('vendor')->middleware(['auth', 'verified', 'role:vendor'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('vendor.dashboard');
    })->name('vendor.index');

    Route::get('/dashboard', function () {
        return Inertia::render('Vendor/Dashboard/Index');
    })->name('vendor.dashboard');

    Route::prefix('products')->name('vendor.products.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Vendor/Products/Index');
        })->name('index');
        Route::get('/create', function () {
            return Inertia::render('Vendor/Products/Create');
        })->name('create');
    });

    Route::prefix('orders')->name('vendor.orders.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Vendor/Orders/Index');
        })->name('index');
    });

    Route::prefix('reports')->name('vendor.reports.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Vendor/Reports/Index');
        })->name('index');
    });

    Route::get('/wallets', function () {
        return Inertia::render('Vendor/Wallets/Index');
    })->name('vendor.wallets');

    Route::get('/analytics', function () {
        return Inertia::render('Vendor/Analytics/Index');
    })->name('vendor.analytics');
});
