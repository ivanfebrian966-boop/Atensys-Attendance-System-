<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
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
        
        return view('Employee.dashboard', compact('todayAttendance', 'monthStats', 'recentAttendances', 'user', 'qrCodeData', 'qrCodeBaseData', 'guest'));
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
            return redirect()->route('login');
        }

        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        $counts = [
            'present' => $attendances->where('status', 'Present')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'sick' => $attendances->where('status', 'Sick')->count(),
            'permission' => $attendances->where('status', 'Permission')->count(),
        ];

        return view('Employee.history', compact('attendances', 'counts', 'user'));
    }

    public function profile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('Employee.profile', compact('user'));
    }

    public function attendance()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today()->toDateString())
            ->first();
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get();

        $qrCodeData = 'ATTENSYS:EMP:' . $user->id . ':' . now()->timestamp;

        return view('Employee.attendance', compact('todayAttendance', 'recentAttendances', 'qrCodeData', 'user'));
    }
}
