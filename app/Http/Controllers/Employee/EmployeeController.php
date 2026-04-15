<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Permission;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $guest = false;

        if (!$user) {
            $guest = true;
            $user = (object) [
                'id' => 0,
                'name' => 'Guest',
                'email' => 'guest@attensys.id',
                'division' => 'Employee',
                'role' => 'Guest',
                'created_at' => now(),
            ];
        }

        $todayAttendance = $guest ? null : Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today()->toDateString())
            ->first();
        
        // Monthly stats
        $monthStats = $guest ? [
            'present' => 0,
            'late' => 0,
            'absent' => 0,
            'sick_permission' => 0,
        ] : [
            'present' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Present')->count(),
            'late' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Late')->count(),
            'absent' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Absent')->count(),
            'sick_permission' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->whereIn('status', ['Sick', 'Permission'])->count(),
        ];
        
        $recentAttendances = $guest ? collect() : Attendance::where('user_id', $user->id)->orderBy('date', 'desc')->limit(7)->get();
        
        $qrCodeBaseData = $guest ? 'ATTENSYS:GUEST' : 'ATTENSYS:EMP:' . $user->id;
        $qrCodeData = $qrCodeBaseData . ':' . now()->timestamp;
        
        return view('Employee.pages.dashboard', compact('todayAttendance', 'monthStats', 'recentAttendances', 'user', 'qrCodeData', 'qrCodeBaseData', 'guest'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'check_in' => Carbon::now(),
            'status' => 'Present'
        ]);
        
        return back()->with('success', 'Checked in successfully!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today()->toDateString())
            ->first();
        if ($attendance) {
            $attendance->update(['check_out' => Carbon::now()]);
        }
        
        return back()->with('success', 'Checked out successfully!');
    }

    public function history()
    {
        $user = Auth::user();

        if (!$user) {
            $user = (object) [
                'id' => 0,
                'name' => 'Guest',
                'email' => 'guest@attensys.id',
                'division' => 'Employee',
                'role' => 'Guest',
                'created_at' => now(),
            ];
            $attendances = collect();
        } else {
            $attendances = Attendance::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->get();
        }

        $counts = [
            'present' => $attendances->where('status', 'Present')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'sick' => $attendances->where('status', 'Sick')->count(),
            'permission' => $attendances->where('status', 'Permission')->count(),
        ];

        return view('Employee.pages.history', compact('attendances', 'counts', 'user'));
    }

    public function profile()
    {
        $user = Auth::user();

        if (!$user) {
            $user = (object) [
                'id' => 0,
                'name' => 'Guest',
                'email' => 'guest@attensys.id',
                'division' => 'Employee',
                'role' => 'Guest',
                'created_at' => now(),
            ];
        }

        return view('Employee.pages.profile', compact('user'));
    }

    public function attendance()
    {
        $user = Auth::user();
        $guest = false;

        if (!$user) {
            $guest = true;
            $user = (object) [
                'id' => 0,
                'name' => 'Guest',
                'email' => 'guest@attensys.id',
                'division' => 'Employee',
                'role' => 'Guest',
                'created_at' => now(),
            ];
            $todayAttendance = null;
            $recentAttendances = collect();
        } else {
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->where('date', Carbon::today()->toDateString())
                ->first();
            $recentAttendances = Attendance::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit(7)
                ->get();
        }

        $qrCodeData = $guest ? 'ATTENSYS:GUEST' : 'ATTENSYS:EMP:' . $user->id . ':' . now()->timestamp;

        return view('Employee.pages.attendance', compact('todayAttendance', 'recentAttendances', 'qrCodeData', 'user'));
    }

    public function leave()
    {
        $user = Auth::user();
        $guest = false;

        if (!$user) {
            $guest = true;
            $user = (object) [
                'id' => 0,
                'name' => 'Guest',
                'email' => 'guest@attensys.id',
                'division' => 'Employee',
                'role' => 'Guest',
                'created_at' => now(),
            ];
            $permissions = collect();
        } else {
            $permissions = Permission::whereHas('employee', function($q) use($user) {
                $q->where('user_id', $user->id);
            })->orderBy('created_at', 'desc')->get();
        }

        return view('Employee.pages.leave', compact('user', 'permissions', 'guest'));
    }

    public function storePermission(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $request->validate([
            'type' => 'required|in:Izin,Sakit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'information' => 'required|string|max:255',
            'attachment' => 'required|file|mimes:pdf|max:2048', // Max 2MB PDF
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('permissions', $fileName, 'public');
        }

        Permission::create([
            'employee_id' => $user->employee->id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'information' => $request->information,
            'status' => 'Pending',
            'attachment' => $filePath,
        ]);

        return back()->with('success', 'Leave request submitted successfully!');
    }
}
