<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('api/v1')->group(function () {
    Route::get('/products', function () {
        return response()->json(['message' => 'Products API']);
    })->name('api.products.index');

    Route::get('/products/{product}', function ($product) {
        return response()->json(['message' => "Product {$product}"]);
    })->name('api.products.show');

    Route::get('/categories', function () {
        return response()->json(['message' => 'Categories API']);
    })->name('api.categories.index');

    Route::get('/categories/{category}', function ($category) {
        return response()->json(['message' => "Category {$category}"]);
    })->name('api.categories.show');

    Route::get('/vendors', function () {
        return response()->json(['message' => 'Vendors API']);
    })->name('api.vendors.index');

    Route::get('/vendors/{vendor}', function ($vendor) {
        return response()->json(['message' => "Vendor {$vendor}"]);
    })->name('api.vendors.show');

    Route::get('/search', function (Request $request) {
        return response()->json(['query' => $request->q, 'message' => 'Search API']);
    })->name('api.search');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('api.user');

        Route::prefix('cart')->name('api.cart.')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Cart']);
            })->name('index');
            Route::post('/add', function () {
                return response()->json(['message' => 'Add to cart']);
            })->name('add');
            Route::put('/{item}', function ($item) {
                return response()->json(['message' => "Update cart item {$item}"]);
            })->name('update');
            Route::delete('/{item}', function ($item) {
                return response()->json(['message' => "Remove cart item {$item}"]);
            })->name('remove');
            Route::post('/apply-coupon', function () {
                return response()->json(['message' => 'Apply coupon']);
            })->name('apply-coupon');
        });

        Route::prefix('checkout')->name('api.checkout.')->group(function () {
            Route::post('/', function () {
                return response()->json(['message' => 'Process checkout']);
            })->name('process');
            Route::post('/validate', function () {
                return response()->json(['message' => 'Validate checkout']);
            })->name('validate');
        });

        Route::prefix('orders')->name('api.orders.')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Orders list']);
            })->name('index');
            Route::post('/', function () {
                return response()->json(['message' => 'Create order']);
            })->name('store');
            Route::get('/{order}', function ($order) {
                return response()->json(['message' => "Order {$order}"]);
            })->name('show');
        });

        Route::prefix('wishlist')->name('api.wishlist.')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Wishlist']);
            })->name('index');
            Route::post('/toggle', function () {
                return response()->json(['message' => 'Toggle wishlist']);
            })->name('toggle');
        });

        Route::prefix('reviews')->name('api.reviews.')->group(function () {
            Route::post('/', function () {
                return response()->json(['message' => 'Create review']);
            })->name('store');
            Route::put('/{review}', function ($review) {
                return response()->json(['message' => "Update review {$review}"]);
            })->name('update');
        });

        Route::prefix('account')->name('api.account.')->group(function () {
            Route::put('/profile', function () {
                return response()->json(['message' => 'Update profile']);
            })->name('profile');
            Route::post('/addresses', function () {
                return response()->json(['message' => 'Add address']);
            })->name('addresses.store');
            Route::put('/addresses/{address}', function ($address) {
                return response()->json(['message' => "Update address {$address}"]);
            })->name('addresses.update');
            Route::delete('/addresses/{address}', function ($address) {
                return response()->json(['message' => "Delete address {$address}"]);
            })->name('addresses.destroy');
        });

        Route::prefix('notifications')->name('api.notifications.')->group(function () {
            Route::get('/', function () {
                return response()->json(['message' => 'Notifications']);
            })->name('index');
            Route::post('/{notification}/read', function ($notification) {
                return response()->json(['message' => "Mark notification {$notification} as read"]);
            })->name('read');
            Route::post('/read-all', function () {
                return response()->json(['message' => 'Mark all as read']);
            })->name('read-all');
        });
    });
});
