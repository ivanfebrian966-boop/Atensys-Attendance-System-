<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check account status
            if ($user->role !== 'super_admin' && $user->status !== 'Aktif') {
                Auth::logout();
                return back()->with('toast_error', 'Akun Anda belum aktif. Silakan hubungi admin.');
            }

            $request->session()->regenerate();

            if ($user->role === 'super_admin') {
                return redirect()->route('super_admin');
            } elseif ($user->role === 'admin_hr') {
                return redirect()->route('admin-hr.dashboard');
            }

            return redirect()->route('employee.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}