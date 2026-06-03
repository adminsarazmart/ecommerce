<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareInertiaData
{
    public function handle(Request $request, Closure $next): Response
    {
        Inertia::share([
            'user' => function () {
                return Auth::user() ? [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'roles' => Auth::user()->getRoleNames(),
                    'permissions' => Auth::user()->getAllPermissions()->pluck('name'),
                ] : null;
            },
            'settings' => function () {
                return cache()->remember('site_settings', 3600, function () {
                    return \App\Models\Setting::pluck('value', 'key')->toArray();
                });
            },
            'cart_count' => function () use ($request) {
                if ($customer = $request->user()?->customer) {
                    return $customer->cart?->items()->count() ?? 0;
                }
                return 0;
            },
            'wishlist_count' => function () use ($request) {
                if ($customer = $request->user()?->customer) {
                    return $customer->wishlist()->count() ?? 0;
                }
                return 0;
            },
            'notification_count' => function () {
                return Auth::user()?->unreadNotifications()->count() ?? 0;
            },
            'current_route' => function () use ($request) {
                return $request->route()?->getName();
            },
            'theme' => function () use ($request) {
                return $request->session()->get('theme', 'light');
            },
            'locale' => function () {
                return app()->getLocale();
            },
            'currency' => function () {
                return config('app.currency', 'USD');
            },
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'warning' => $request->session()->get('warning'),
                    'info' => $request->session()->get('info'),
                ];
            },
        ]);

        return $next($request);
    }
}
