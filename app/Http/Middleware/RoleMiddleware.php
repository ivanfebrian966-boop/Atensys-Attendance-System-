<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Role-to-route mapping for redirecting unauthorized users
     * back to their own dashboard.
     */
    private const ROLE_REDIRECTS = [
        'Super Admin' => 'super_admin.dashboard',
        'Admin HR'    => 'admin-hr.dashboard',
        'Employee'    => 'employee.dashboard',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $isScannerSession = $request->session()->has('scanner_id');

        // Scanner role uses session-based auth (not Laravel Auth)
        if (in_array('Scanner', $roles)) {
            return $isScannerSession
                ? $next($request)
                : redirect()->route('login');
        }

        // If not authenticated via Laravel Auth, check for active scanner session
        if (!Auth::check()) {
            return redirect()->route($isScannerSession ? 'scanner.index' : 'login');
        }

        // Authorized — role matches
        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect to the user's own dashboard
        $redirect = self::ROLE_REDIRECTS[$user->role] ?? 'home';

        return redirect()->route($redirect);
    }
}
