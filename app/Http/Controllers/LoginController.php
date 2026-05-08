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
        $request->validate(
            [
                'login'    => 'required',
                'password' => 'required',
            ],
            [
                'login.required'    => 'Email address or NIP is required.',
                'password.required' => 'Password is required.',
            ]
        );

        $loginValue = $request->input('login');
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'nip';

        $credentials = [
            $field => $loginValue,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check account status
            if ($user->role !== 'Super Admin' && $user->status !== 'Aktif') {
                Auth::logout();
                return back()->with('toast_error', 'Your account is not active yet. Please contact the administrator.');
            }

            $request->session()->regenerate();

            if ($user->role === 'Super Admin') {
                return redirect()->route('super_admin.dashboard');
            } elseif ($user->role === 'Admin HR') {
                return redirect()->route('admin-hr.dashboard');
            }

            return redirect()->route('employee.dashboard');
        }

        return back()->withErrors([
            'login' => 'Incorrect email/NIP or password.',
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