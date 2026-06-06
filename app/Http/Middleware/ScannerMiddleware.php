<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScannerMiddleware
{
    /**
     * Handle an incoming request.
     * Check if the scanner device is authenticated via session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('scanner_id')) {
            return redirect()->route('login')->with('toast_error', 'Please log in as a Scanner Device first.');
        }

        return $next($request);
    }
}
