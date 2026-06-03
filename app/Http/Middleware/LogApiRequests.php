<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = microtime(true) - $startTime;

        if ($request->is('api/*')) {
            Log::channel(isset(app()['config']['logging.channels']['api']) ? 'api' : 'stack')->info('API Request', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status' => $response->getStatusCode(),
                'duration' => round($duration * 1000, 2) . 'ms',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => $request->user()?->id,
            ]);
        }

        return $response;
    }
}
