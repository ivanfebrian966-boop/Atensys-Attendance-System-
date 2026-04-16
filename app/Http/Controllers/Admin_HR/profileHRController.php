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
     * Update personal information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:employees,email,' . $user->nip . ',nip',
            'no_hp'     => 'nullable|string|max:20',
            'alamat'    => 'nullable|string|max:500',
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
        ]);

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
