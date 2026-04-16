<?php

namespace App\Http\Controllers\Super_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Division;

class SuperAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $employees = Employee::with('division')->where('role', 'karyawan')->get();
        $hr_admins = Employee::with('division')->where('role', 'admin_hr')->get();
        $divisions = Division::all();
        $recent_users = Employee::with('division')
            ->whereIn('role', ['karyawan', 'admin_hr'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $status_counts = [
            'aktif' => Employee::where('status', 'Aktif')->count(),
            'pending' => Employee::where('status', 'Pending')->count(),
            'nonaktif' => Employee::where('status', 'Nonaktif')->count(),
        ];

        return view('Super_Admin.super_admin', compact('user', 'employees', 'hr_admins', 'divisions', 'recent_users', 'status_counts'));
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'nip' => 'required|string|max:50|unique:employees',
            'division_id' => 'required|exists:divisions,division_id',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'password' => 'required|string|min:8',
            'status' => 'required|in:Aktif,Pending,Nonaktif',
        ]);

        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'position' => $request->jabatan,
            'division_id' => $request->division_id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function storeHrAdmin(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'nip' => 'required|string|max:50|unique:employees',
                'email' => 'required|string|email|max:255|unique:employees',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'status' => 'required|in:Aktif,Pending,Nonaktif',
                'division_id' => 'required|exists:divisions,division_id',
                'position' => 'required|string',
            ]);

            Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'password' => Hash::make($request->password),
                'role' => 'admin_hr',
                'no_hp' => $request->phone,
                'alamat' => $request->address,
                'status' => $request->status,
                'division_id' => $request->division_id,
                'position' => $request->position,
            ]);

            return redirect()->back()->with('success', 'Akun HR Admin berhasil dibuat!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error_modal', 'modalAddAdmin');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat akun: ' . $e->getMessage())->withInput();
        }
    }

    public function updateEmployee(Request $request, $nip)
    {
        $employee = Employee::findOrFail($nip);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $nip . ',nip',
            'division_id' => 'required|exists:divisions,division_id',
            'jabatan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Pending,Nonaktif',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'position' => $request->jabatan,
            'division_id' => $request->division_id,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function deleteEmployee($nip)
    {
        $employee = Employee::findOrFail($nip);
        $employee->delete();
        return redirect()->back()->with('success', 'Akun karyawan berhasil dihapus!');
    }

    public function updateHrAdmin(Request $request, $nip)
    {
        $employee = Employee::findOrFail($nip);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $nip . ',nip',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Pending,Nonaktif',
            'division_id' => 'required|exists:divisions,division_id',
            'position' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->phone,
            'alamat' => $request->address,
            'status' => $request->status,
            'division_id' => $request->division_id,
            'position' => $request->position,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->back()->with('success', 'Akun HR Admin berhasil diperbarui!');
    }

    public function deleteHrAdmin($nip)
    {
        $employee = Employee::findOrFail($nip);
        $employee->delete();
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
            'division_name' => 'required|string|max:255|unique:divisions,division_name,' . $id . ',division_id',
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
        
        if ($division->employees()->count() > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus divisi yang memiliki karyawan!');
        }

        $division->delete();
        return redirect()->back()->with('success', 'Divisi berhasil dihapus!');
    }

    public function profile()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        // Reuse index logic but with active tab profile
        $employees = Employee::with('division')->where('role', 'karyawan')->get();
        $hr_admins = Employee::with('division')->where('role', 'admin_hr')->get();
        $divisions = Division::all();
        $recent_users = Employee::with('division')
            ->whereIn('role', ['karyawan', 'admin_hr'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $status_counts = [
            'aktif' => Employee::where('status', 'Aktif')->count(),
            'pending' => Employee::where('status', 'Pending')->count(),
            'nonaktif' => Employee::where('status', 'Nonaktif')->count(),
        ];

        return view('Super_Admin.super_admin', compact('user', 'employees', 'hr_admins', 'divisions', 'recent_users', 'status_counts'))->with('active_tab', 'profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $user->nip . ',nip',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->phone,
            'alamat' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
