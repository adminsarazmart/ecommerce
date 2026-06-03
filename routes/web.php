<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

require __DIR__.'/admin.php';
require __DIR__.'/vendor.php';

Route::get('/', function () {
    return Inertia::render('Storefront/Home/Index');
})->name('home');

Route::get('/shop', function () {
    return Inertia::render('Storefront/Shop/Index');
})->name('shop.index');

Route::get('/shop/{slug}', function ($slug) {
    return Inertia::render('Storefront/Product/Show', ['slug' => $slug]);
})->name('shop.product');

Route::get('/cart', function () {
    return Inertia::render('Storefront/Cart/Index');
})->name('cart.index');

Route::get('/checkout', function () {
    return Inertia::render('Storefront/Checkout/Index');
})->middleware('auth')->name('checkout.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    })->name('login');

    Route::post('/login', function (\App\Http\Requests\LoginRequest $request) {
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
        request()->session()->regenerate();
        if (Auth::user()->hasAnyRole(['super_admin', 'admin'])) {
            return redirect()->intended('/admin/dashboard');
        }
        return redirect()->intended('/');
    })->name('login.store');

    Route::get('/register', function () {
        return Inertia::render('Auth/Register');
    })->name('register');

    Route::post('/register', function (\App\Http\Requests\RegisterRequest $request) {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);
        \App\Models\Customer::create(['user_id' => $user->id]);
        $user->assignRole('Customer');
        Auth::login($user);
        return redirect('/');
    })->name('register.store');

    Route::get('/forgot-password', function () {
        return Inertia::render('Auth/ForgotPassword');
    })->name('password.request');

    Route::get('/reset-password/{token}', function ($token) {
        return Inertia::render('Auth/ResetPassword', ['token' => $token]);
    })->name('password.reset');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/account', function () {
        return Inertia::render('Storefront/Account/Dashboard');
    })->name('account.dashboard');

    Route::get('/account/profile', function () {
        return Inertia::render('Storefront/Account/Profile');
    })->name('account.profile');

    Route::get('/account/orders', function () {
        return Inertia::render('Storefront/Account/Orders');
    })->name('account.orders');

    Route::get('/account/orders/{id}', function ($id) {
        return Inertia::render('Storefront/Account/OrderDetail', ['id' => $id]);
    })->name('account.orders.show');

    Route::get('/account/wishlist', function () {
        return Inertia::render('Storefront/Account/Wishlist');
    })->name('account.wishlist');

    Route::get('/account/reviews', function () {
        return Inertia::render('Storefront/Account/Reviews');
    })->name('account.reviews');

    Route::get('/account/addresses', function () {
        return Inertia::render('Storefront/Account/Addresses');
    })->name('account.addresses');

    Route::get('/account/compare', function () {
        return Inertia::render('Storefront/Account/Compare');
    })->name('account.compare');

    Route::get('/account/loyalty', function () {
        return Inertia::render('Storefront/Account/Loyalty');
    })->name('account.loyalty');

    Route::get('/account/wallet', function () {
        return Inertia::render('Storefront/Account/Wallet');
    })->name('account.wallet');

    Route::get('/account/downloads', function () {
        return Inertia::render('Storefront/Account/Downloadable');
    })->name('account.downloads');
});

Route::get('/search', function (\Illuminate\Http\Request $request) {
    $query = $request->q;
    $results = [];
    if ($query && strlen($query) >= 2) {
        $products = \App\Models\Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'image' => $p->thumbnail ?? '/placeholder.jpg',
                'url' => "/shop/{$p->slug}",
            ]);
        $results = $products->toArray();
    }
    return response()->json(['results' => $results]);
})->name('search');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')->header('Content-Type', 'application/xml');
})->name('sitemap');
