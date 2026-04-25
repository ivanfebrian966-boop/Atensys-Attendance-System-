<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        $user = \Illuminate\Support\Facades\Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        if ($user->role === 'super_admin') {
            return redirect()->route('super_admin.dashboard');
        } elseif ($user->role === 'admin_hr') {
            return redirect()->route('admin-hr.dashboard');
        } elseif ($user->role === 'karyawan') {
            return redirect()->route('employee.dashboard');
        }

        return redirect()->route('home');
    }
}
