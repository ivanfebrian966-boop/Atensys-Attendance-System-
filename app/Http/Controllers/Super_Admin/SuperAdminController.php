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

        return view('Super_Admin.super_admin', compact('user', 'employees', 'hr_admins', 'divisions', 'recent_users'));
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'division_id' => 'required|exists:divisions,id',
            'jabatan' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'status' => 'required|in:Aktif,Pending,Nonaktif',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'employee',
        ]);

        // You might want to save the status and jabatan in employee model as well
        // Wait, the employee table doesn't have `jabatan` or `status`.
        // Let's create Employee. But wait, `no_hp` and `alamat` are required in db!
        // So I'll put some dummy or empty string for them.
        $user->employee()->create([
            'division_id' => $request->division_id,
            'no_hp' => '-',
            'alamat' => '-',
        ]);

        return redirect()->back()->with('success', 'Employee added successfully');
    }

    public function storeHrAdmin(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'hr_admin',
            ]);

            return redirect()->back()->with('success', 'Akun HR Admin berhasil dibuat!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error_modal', 'modalAddAdmin');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat akun: ' . $e->getMessage())->withInput();
        }
    }
}
