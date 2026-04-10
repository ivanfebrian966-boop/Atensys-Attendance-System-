<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Division;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HRmanageController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->with('employee.division')->get();
        $divisions = Division::withCount('employees')->get();
        $attendances = Attendance::with('user')->orderBy('created_at', 'desc')->get();

        return view('Admin_HR.HRmanage', compact('employees', 'divisions', 'attendances'));
    }

    // --- EMPLOYEE CRUD ---
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'division_id' => 'required|exists:divisions,id',
            'position' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'position' => $request->position,
            'phone' => $request->phone,
            'address' => $request->address,
            'join_date' => now(),
        ]);

        Employee::create([
            'user_id' => $user->id,
            'division_id' => $request->division_id,
            'no_hp' => $request->phone,
            'alamat' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function updateEmployee(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'division_id' => 'required|exists:divisions,id',
            'position' => 'required|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'position' => $request->position,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            $employee->update([
                'division_id' => $request->division_id,
                'no_hp' => $request->phone,
                'alamat' => $request->address,
            ]);
        }

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function deleteEmployee($id)
    {
        $user = User::findOrFail($id);
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete(); // This will cascade to employee table if foreign keys are set correctly
        return redirect()->back()->with('success', 'Karyawan berhasil dihapus');
    }

    // --- DIVISION CRUD ---
    public function storeDivision(Request $request)
    {
        $request->validate(['division_name' => 'required|string|unique:divisions']);
        Division::create(['division_name' => $request->division_name]);
        return redirect()->back()->with('success', 'Divisi berhasil ditambahkan');
    }

    public function updateDivision(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $request->validate(['division_name' => 'required|string|unique:divisions,division_name,' . $id]);
        $division->update(['division_name' => $request->division_name]);
        return redirect()->back()->with('success', 'Divisi berhasil diperbarui');
    }

    public function deleteDivision($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();
        return redirect()->back()->with('success', 'Divisi berhasil dihapus');
    }

    // --- ATTENDANCE CRUD (Optional/Basic) ---
    public function deleteAttendance($id)
    {
        Attendance::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data absensi berhasil dihapus');
    }
}
