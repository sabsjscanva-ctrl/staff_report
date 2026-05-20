<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        $userAgent = strtolower($request->header('User-Agent', ''));
        
        // Bypass CSRF for Tauri desktop app or Mobile WebViews to prevent 419 errors
        if (str_contains($userAgent, 'tauri') || str_contains($userAgent, 'wv') || str_contains($userAgent, 'android') || str_contains($userAgent, 'mobile')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
