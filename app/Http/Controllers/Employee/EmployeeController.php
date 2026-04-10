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

        if (!$user) {
            return redirect()->route('login');
        }

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', Carbon::today()->toDateString())
            ->first();
        
        // Monthly stats
        $monthStats = [
            'present' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Present')->count(),
            'late' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Late')->count(),
            'absent' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->where('status', 'Absent')->count(),
            'sick_permission' => Attendance::where('user_id', $user->id)->whereMonth('date', Carbon::now()->month)->whereIn('status', ['Sick', 'Permission'])->count(),
        ];
        
        $recentAttendances = Attendance::where('user_id', $user->id)->orderBy('date', 'desc')->limit(7)->get();
        
        // Generate unique QR code data for employee (using user ID)
        // Format: ATTENSYS:EMP:USER_ID:TIMESTAMP for scanning
        $qrCodeData = 'ATTENSYS:EMP:' . $user->id . ':' . now()->timestamp;
        
        return view('Employee.dashboard', compact('todayAttendance', 'monthStats', 'recentAttendances', 'user', 'qrCodeData'));
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
