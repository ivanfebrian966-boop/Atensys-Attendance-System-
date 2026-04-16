<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileHRController extends Controller
{
    /**
     * Show the HR profile page.
     */
    public function index()
    {
        $user = Auth::user();
        return view('Admin_HR.pages.profile', compact('user'));
    }

    /**
     * Update profile info (no_hp, alamat) + optionally change password in one form.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'no_hp'            => 'nullable|string|max:20',
            'alamat'           => 'nullable|string|max:500',
            'current_password' => 'nullable|string',
            'new_password'     => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        $validated = $request->validate($rules);

        // Update basic info
        $user->update([
            'no_hp'  => $validated['no_hp']  ?? $user->no_hp,
            'alamat' => $validated['alamat'] ?? $user->alamat,
        ]);

        // Handle optional password change
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }
            $user->update(['password' => Hash::make($request->new_password)]);
        }

        return redirect()->route('profileHR')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Change password (kept for backward compat).
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profileHR')
            ->with('success', 'Password changed successfully.');
    }
}
