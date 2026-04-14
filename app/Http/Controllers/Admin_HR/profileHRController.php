<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
     * Update personal information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'     => 'nullable|string|max:20',
            'division'  => 'nullable|string|max:255',
            'position'  => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'address'   => 'nullable|string|max:500',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan oleh akun lain.',
        ]);

        $user->update($validated);

        return redirect()->route('profileHR')
            ->with('success', 'Profil berhasil diperbarui.');
    }


    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.confirmed'    => 'Konfirmasi password tidak cocok.',
            'new_password.min'          => 'Password minimal 8 karakter.',
        ]);

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput()
                ->with('open_tab', 'security');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profileHR')
            ->with('success', 'Password berhasil diubah.')
            ->with('open_tab', 'security');
    }
}
