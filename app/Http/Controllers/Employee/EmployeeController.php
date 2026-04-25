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
        if (!$user) return redirect()->route('login');

        $todayAttendance = Attendance::where('nip', $user->nip)
            ->whereDate('check_in', Carbon::today())
            ->first();
        
        $monthStats = [
            'present' => Attendance::where('nip', $user->nip)->whereMonth('created_at', Carbon::now()->month)->where('status', 'Present')->count(),
            'late' => Attendance::where('nip', $user->nip)->whereMonth('created_at', Carbon::now()->month)->where('status', 'Late')->count(),
            'absent' => Attendance::where('nip', $user->nip)->whereMonth('created_at', Carbon::now()->month)->where('status', 'Absent')->count(),
            'sick_permission' => Permission::where('nip', $user->nip)->whereMonth('start_date', Carbon::now()->month)->count(),
        ];
        
        $recentAttendances = Attendance::where('nip', $user->nip)->orderBy('created_at', 'desc')->limit(7)->get();
        
        $qrCodeBaseData = 'ATTENSYS:EMP:' . $user->nip;
        $qrCodeData = $qrCodeBaseData . ':' . now()->timestamp;
        
        return view('Employee.pages.dashboard', compact('todayAttendance', 'monthStats', 'recentAttendances', 'user', 'qrCodeData', 'qrCodeBaseData'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        Attendance::create([
            'nip' => $user->nip,
            'check_in' => Carbon::now(),
            'status' => 'Hadir', // Basic logic, can be refined based on time
            'qr_code' => $request->qr_code ?? 'MANUAL',
        ]);
        
        return back()->with('success', 'Check-in recorded successfully!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $attendance = Attendance::where('nip', $user->nip)
            ->whereDate('check_in', Carbon::today())
            ->first();

        if ($attendance) {
            $attendance->update(['check_out' => Carbon::now()]);
            return back()->with('success', 'Check-out recorded successfully!');
        }
        
        return back()->with('error', 'No check-in record found for today.');
    }

    public function history()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $attendances = Attendance::where('nip', $user->nip)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $counts = [
            'present' => Attendance::where('nip', $user->nip)->where('status', 'Present')->count(),
            'late' => Attendance::where('nip', $user->nip)->where('status', 'Late')->count(),
            'absent' => Attendance::where('nip', $user->nip)->where('status', 'Absent')->count(),
        ];

        return view('Employee.pages.history', compact('attendances', 'counts', 'user'));
    }

    public function profile()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        return view('Employee.pages.profile', compact('user'));
    }

    public function attendance()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $todayAttendance = Attendance::where('nip', $user->nip)
            ->whereDate('check_in', Carbon::today())
            ->first();

        $recentAttendances = Attendance::where('nip', $user->nip)
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();

        $qrCodeBaseData = 'ATTENSYS:EMP:' . $user->nip;
        $qrCodeData = $qrCodeBaseData . ':' . now()->timestamp;

        $permissions = Permission::where('nip', $user->nip)->orderBy('created_at', 'desc')->get();

        return view('Employee.pages.attendance', compact('todayAttendance', 'recentAttendances', 'qrCodeData', 'qrCodeBaseData', 'user', 'permissions'));
    }

    public function leave()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $permissions = Permission::where('nip', $user->nip)->orderBy('created_at', 'desc')->get();

        return view('Employee.pages.leave', compact('user', 'permissions'));
    }

    public function storePermission(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $request->validate([
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'information' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB pdf
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $user->nip . '.pdf';
            $filePath = $request->file('file')->storeAs('permissions', $fileName, 'public');
        }

        Permission::create([
            'nip' => $user->nip,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'completion_date' => $request->end_date,
            'information' => $request->information,
            'file' => $filePath,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully!');
    }
}
