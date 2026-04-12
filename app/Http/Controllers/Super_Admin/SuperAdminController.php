<?php

namespace App\Http\Controllers\Super_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Division;

class SuperAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $employees = User::with('employee.division')->where('role', 'employee')->get();
        $hr_admins = User::where('role', 'hr_admin')->get();
        $divisions = Division::all();
        $recent_users = User::with('employee.division')
            ->whereIn('role', ['employee', 'hr_admin'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $status_counts = [
            'aktif' => User::where('status', 'Aktif')->count(),
            'pending' => User::where('status', 'Pending')->count(),
            'nonaktif' => User::where('status', 'Nonaktif')->count(),
        ];

        return view('Super_Admin.super_admin', compact('user', 'employees', 'hr_admins', 'divisions', 'recent_users', 'status_counts'));
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'required|string|max:50|unique:employees',
            'division_id' => 'required|exists:divisions,id',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'password' => 'required|string|min:8',
            'status' => 'required|in:Aktif,Pending,Nonaktif',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'employee',
            'phone' => $request->no_hp,
            'address' => $request->alamat,
            'position' => $request->jabatan,
            'status' => $request->status ?? 'Aktif',
        ]);

        // Save in employee model
        $user->employee()->create([
            'nip' => $request->nip,
            'division_id' => $request->division_id,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function storeHrAdmin(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nip' => 'required|string|max:50|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'status' => 'required|in:Aktif,Pending,Nonaktif',
                'division' => 'required|string',
                'position' => 'required|string',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'hr_admin',
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
                'division' => $request->division,
                'position' => $request->position,
            ]);

            return redirect()->back()->with('success', 'Akun HR Admin berhasil dibuat!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error_modal', 'modalAddAdmin');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat akun: ' . $e->getMessage())->withInput();
        }
    }

    public function updateEmployee(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'nip' => 'required|string|max:50|unique:employees,nip,' . ($user->employee->id ?? 0),
            'division_id' => 'required|exists:divisions,id',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Pending,Nonaktif',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'phone' => $request->no_hp,
            'address' => $request->alamat,
            'position' => $request->jabatan,
            'status' => $request->status ?? 'Aktif',
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }

        $user->employee()->update([
            'nip' => $request->nip,
            'division_id' => $request->division_id,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function deleteEmployee($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Akun karyawan berhasil dihapus!');
    }

    public function updateHrAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nip,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Pending,Nonaktif',
            'division' => 'required|string',
            'position' => 'required|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
            'division' => $request->division,
            'position' => $request->position,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }

        return redirect()->back()->with('success', 'Akun HR Admin berhasil diperbarui!');
    }

    public function deleteHrAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Akun HR Admin berhasil dihapus!');
    }

    public function storeDivision(Request $request)
    {
        $request->validate([
            'division_name' => 'required|string|max:255|unique:divisions',
        ]);

        Division::create([
            'division_name' => $request->division_name,
        ]);

        return redirect()->back()->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function updateDivision(Request $request, $id)
    {
        $request->validate([
            'division_name' => 'required|string|max:255|unique:divisions,division_name,' . $id,
        ]);

        $division = Division::findOrFail($id);
        $division->update([
            'division_name' => $request->division_name,
        ]);

        return redirect()->back()->with('success', 'Divisi berhasil diperbarui!');
    }

    public function deleteDivision($id)
    {
        $division = Division::findOrFail($id);
        
        // Check if division has employees
        if ($division->employees()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus divisi yang memiliki karyawan!');
        }

        $division->delete();
        return redirect()->back()->with('success', 'Divisi berhasil dihapus!');
    }

    public function profile()
    {
        $user = Auth::user();
        $employees = User::with('employee.division')->where('role', 'employee')->get();
        $hr_admins = User::where('role', 'hr_admin')->get();
        $divisions = Division::all();
        $recent_users = User::with('employee.division')
            ->whereIn('role', ['employee', 'hr_admin'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $status_counts = [
            'aktif' => User::where('status', 'Aktif')->count(),
            'pending' => User::where('status', 'Pending')->count(),
            'nonaktif' => User::where('status', 'Nonaktif')->count(),
        ];

        return view('Super_Admin.super_admin', compact('user', 'employees', 'hr_admins', 'divisions', 'recent_users', 'status_counts'))->with('active_tab', 'profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
