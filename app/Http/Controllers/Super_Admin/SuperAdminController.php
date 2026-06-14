<?php

namespace App\Http\Controllers\Super_Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Division;
use App\Models\Gender;

class SuperAdminController extends Controller
{
    private function getCommonData()
    {
        return [
            'employees_count' => Employee::where('role', 'Employee')->count(),
            'hr_admins_count' => Employee::where('role', 'Admin HR')->count(),
            'divisions_count' => Division::count(),
            'genders_count' => Gender::count(),
            'scanners_count' => \App\Models\ScannerDevice::count(),
            'divisions' => Division::all(),
            'genders' => Gender::all(),
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $commonData = $this->getCommonData();
        
        $employees = Employee::with('division')->where('role', 'Employee')->get();
        $hr_admins = Employee::with('division')->where('role', 'Admin HR')->get();
        $recent_users = Employee::with('division')
            ->whereIn('role', ['Employee', 'Admin HR'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $status_counts = [
            'aktif' => Employee::where('status', 'Aktif')->count(),
            'nonaktif' => Employee::where('status', 'Tidak Aktif')->count(),
        ];

        return view('Super_Admin.dashboard', array_merge($commonData, compact('user', 'employees', 'hr_admins', 'recent_users', 'status_counts')));
    }

    public function employees()
    {
        $user = Auth::user();
        $commonData = $this->getCommonData();
        $employees = Employee::with('division')->where('role', 'Employee')->get();

        return view('Super_Admin.employees', array_merge($commonData, compact('user', 'employees')));
    }

    public function admins()
    {
        $user = Auth::user();
        $commonData = $this->getCommonData();
        $hr_admins = Employee::with('division')->where('role', 'Admin HR')->get();

        return view('Super_Admin.admins', array_merge($commonData, compact('user', 'hr_admins')));
    }

    public function divisions()
    {
        $user = Auth::user();
        $commonData = $this->getCommonData();

        return view('Super_Admin.divisions', array_merge($commonData, compact('user')));
    }

    public function storeEmployee(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:75',
                'email' => 'required|string|email|max:50|unique:employees',
                'nip' => 'required|string|size:7|unique:employees',
                'division_id' => 'required|exists:divisions,division_id',
                'jabatan' => 'required|string|max:30',
                'no_hp' => 'required|string|max:15',
                'alamat' => 'required|string|max:500',
                'password' => 'required|string|min:8',
                'status' => 'required|in:Aktif,Tidak Aktif',
                'gender_id' => 'nullable|exists:genders,gender_id',
            ]);

            Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'password' => Hash::make($request->password),
                'role' => 'Employee',
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'position' => $request->jabatan,
                'division_id' => $request->division_id,
                'status' => $request->status,
                'gender_id' => $request->gender_id ?? 1,
            ]);

            return redirect()->back()->with('success', 'Employee Added Successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error_modal', 'modalAddEmployee');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create account: ' . $e->getMessage())->withInput();
        }
    }

    public function storeHrAdmin(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:75',
                'nip' => 'required|string|size:7|unique:employees',
                'email' => 'required|string|email|max:50|unique:employees',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:15',
                'address' => 'required|string|max:500',
                'status' => 'required|in:Aktif,Tidak Aktif',
                'division_id' => 'required|exists:divisions,division_id',
                'position' => 'required|string|max:30',
                'gender_id' => 'nullable|exists:genders,gender_id',
            ]);

            Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'nip' => $request->nip,
                'password' => Hash::make($request->password),
                'role' => 'Admin HR',
                'no_hp' => $request->phone,
                'alamat' => $request->address,
                'status' => $request->status,
                'division_id' => $request->division_id,
                'position' => $request->position,
                'gender_id' => $request->gender_id ?? 1,
            ]);

            return redirect()->back()->with('success', 'HR Admin Account Created Successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error_modal', 'modalAddAdmin');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create account: ' . $e->getMessage())->withInput();
        }
    }

    public function updateEmployee(Request $request, $nip)
    {
        $employee = Employee::findOrFail($nip);

        $request->validate([
            'name' => 'required|string|max:75',
            'email' => 'required|string|email|max:50|unique:employees,email,' . $nip . ',nip',
            'division_id' => 'required|exists:divisions,division_id',
            'jabatan' => 'required|string|max:30',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'gender_id' => 'nullable|exists:genders,gender_id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'position' => $request->jabatan,
            'division_id' => $request->division_id,
            'status' => $request->status,
            'gender_id' => $request->gender_id ?? $employee->gender_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->back()->with('success', 'Employee Updated Successfully!');
    }

    public function deleteEmployee($nip)
    {
        $employee = Employee::findOrFail($nip);
        $employee->delete();
        return redirect()->back()->with('success', 'Employee Account Deleted Successfully!');
    }

    public function updateHrAdmin(Request $request, $nip)
    {
        $employee = Employee::findOrFail($nip);

        $request->validate([
            'name' => 'required|string|max:75',
            'email' => 'required|string|email|max:50|unique:employees,email,' . $nip . ',nip',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'division_id' => 'required|exists:divisions,division_id',
            'position' => 'required|string|max:30',
            'gender_id' => 'nullable|exists:genders,gender_id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->phone,
            'alamat' => $request->address,
            'status' => $request->status,
            'division_id' => $request->division_id,
            'position' => $request->position,
            'gender_id' => $request->gender_id ?? $employee->gender_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->back()->with('success', 'HR Admin Account Updated Successfully!');
    }

    public function deleteHrAdmin($nip)
    {
        $employee = Employee::findOrFail($nip);
        $employee->delete();
        return redirect()->back()->with('success', 'HR Admin Account Deleted Successfully!');
    }

    public function storeDivision(Request $request)
    {
        $request->validate([
            'division_name' => 'required|string|max:255|unique:divisions',
        ]);

        Division::create([
            'division_name' => $request->division_name,
        ]);

        return redirect()->back()->with('success', 'Division added successfully!');
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

        return redirect()->back()->with('success', 'Division Updated Successfully!');
    }

    public function deleteDivision($id)
    {
        $division = Division::findOrFail($id);

        if ($division->employees()->count() > 0) {
            return redirect()->back()->with('error', 'Can\'t delete this division because it has employees!');
        }

        $division->delete();
        return redirect()->back()->with('success', 'Division Deleted Successfully!');
    }

    public function genders()
    {
        $user = Auth::user();
        $commonData = $this->getCommonData();
        $genders = Gender::all();

        return view('Super_Admin.genders', array_merge($commonData, compact('user', 'genders')));
    }

    public function storeGender(Request $request)
    {
        $request->validate([
            'gender_name' => 'required|string|max:30|unique:genders',
        ]);

        Gender::create([
            'gender_name' => $request->gender_name,
        ]);

        return redirect()->back()->with('success', 'Gender added successfully!');
    }

    public function updateGender(Request $request, $id)
    {
        $request->validate([
            'gender_name' => 'required|string|max:30|unique:genders,gender_name,' . $id . ',gender_id',
        ]);

        $gender = Gender::findOrFail($id);
        $gender->update([
            'gender_name' => $request->gender_name,
        ]);

        return redirect()->back()->with('success', 'Gender Updated Successfully!');
    }

    public function deleteGender($id)
    {
        $gender = Gender::findOrFail($id);

        if ($gender->employees()->count() > 0) {
            return redirect()->back()->with('error', 'Can\'t delete this gender because it has employees!');
        }

        $gender->delete();
        return redirect()->back()->with('success', 'Gender Deleted Successfully!');
    }

    public function profile()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $commonData = $this->getCommonData();

        return view('Super_Admin.profile', array_merge($commonData, compact('user')));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $user->nip . ',nip',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'gender_id' => 'nullable|exists:genders,gender_id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->phone,
            'alamat' => $request->address,
            'gender_id' => $request->gender_id ?? $user->gender_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile Updated Successfully!');
    }
    public function scanners()
    {
        $user = Auth::user();
        $commonData = $this->getCommonData();
        $scanners = \App\Models\ScannerDevice::all();

        return view('Super_Admin.scanners', array_merge($commonData, compact('user', 'scanners')));
    }

    public function storeScanner(Request $request)
    {
        $request->validate([
            'scanner_id' => 'required|string|max:7|unique:scanner_devices,scanner_id',
            'password' => 'required|string|min:8',
        ]);

        \App\Models\ScannerDevice::create([
            'scanner_id' => $request->scanner_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Scanner Account Created Successfully!');
    }

    public function updateScanner(Request $request, $id)
    {
        $scanner = \App\Models\ScannerDevice::findOrFail($id);

        $request->validate([
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $scanner->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'Scanner Account Updated Successfully!');
    }

    public function deleteScanner($id)
    {
        $scanner = \App\Models\ScannerDevice::findOrFail($id);
        $scanner->delete();

        return redirect()->back()->with('success', 'Scanner Account Deleted Successfully!');
    }
}
