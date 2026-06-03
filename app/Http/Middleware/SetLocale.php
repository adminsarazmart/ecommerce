<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', config('app.locale'));

        if ($request->has('locale') && in_array($request->locale, array_keys(config('app.available_locales', ['en' => 'English'])))) {
            $locale = $request->locale;
            $request->session()->put('locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
