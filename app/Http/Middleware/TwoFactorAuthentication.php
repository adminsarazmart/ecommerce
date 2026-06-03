<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorAuthentication
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->two_factor_enabled && !session('two_factor_authenticated')) {
            if (!$request->is('*/two-factor*')) {
                return redirect()->route('two-factor.challenge');
            }
        }

        return $next($request);
    }
}
