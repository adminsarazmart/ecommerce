<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SetCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        $currency = $request->session()->get('currency', config('app.currency', 'USD'));

        if ($request->has('currency') && in_array(strtoupper($request->currency), array_keys(config('currencies', ['USD' => []])))) {
            $currency = strtoupper($request->currency);
            $request->session()->put('currency', $currency);
        }

        Config::set('app.currency', $currency);

        return $next($request);
    }
}
