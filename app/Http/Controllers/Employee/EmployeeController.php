<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'present' => Attendance::where('nip', $user->nip)->whereMonth('created_at', Carbon::now()->month)->where('attendance_status', 'Present')->count(),
            'late' => Attendance::where('nip', $user->nip)->whereMonth('created_at', Carbon::now()->month)->where('attendance_status', 'Late')->count(),
            'absent' => Attendance::where('nip', $user->nip)->whereMonth('created_at', Carbon::now()->month)->where('attendance_status', 'Absent')->count(),
            'sick' => Permission::where('nip', $user->nip)->where('permission_status', 'Approved')->where('type', 'Sick')->whereMonth('start_date', Carbon::now()->month)->count(),
            'permission' => Permission::where('nip', $user->nip)->where('permission_status', 'Approved')->where('type', 'Leave')->whereMonth('start_date', Carbon::now()->month)->count(),
            'total' => Attendance::where('nip', $user->nip)->whereMonth('created_at', Carbon::now()->month)->count(),
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

        $today = Carbon::today();

        // Check if has permission/sick for today
        $hasPermission = Permission::where('nip', $user->nip)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->whereIn('permission_status', ['Approved', 'Pending'])
            ->exists();

        if ($hasPermission) {
            return back()->with('error', 'Gagal: Anda sudah memiliki pengajuan izin/sakit untuk hari ini.');
        }

        // Check if already checked in today
        $existing = Attendance::where('nip', $user->nip)
            ->whereDate('check_in', $today)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already checked in today.');
        }

        Attendance::create([
            'nip' => $user->nip,
            'check_in' => Carbon::now(),
            'attendance_status' => Carbon::now()->format('H:i') > '08:00' ? 'Late' : 'Present', // Basic threshold logic
            'qr_code' => $request->qr_code ?? 'MANUAL',
        ]);
        
        return back()->with('success', 'Check-in recorded successfully!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        if (!$user) return redirect()->route('login');

        // Check if has permission/sick for today
        $hasPermission = Permission::where('nip', $user->nip)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->where('permission_status', 'Approved')
            ->exists();

        if ($hasPermission) {
            return back()->with('error', 'Gagal: Anda tidak bisa absen karena sedang dalam masa izin/sakit.');
        }

        $attendance = Attendance::where('nip', $user->nip)
            ->whereDate('check_in', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No check-in record found for today.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'You have already checked out today.');
        }

        $attendance->update(['check_out' => Carbon::now()]);
        
        return back()->with('success', 'Check-out recorded successfully!');
    }

    public function history()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $attendances = Attendance::where('nip', $user->nip)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $counts = [
            'present' => Attendance::where('nip', $user->nip)->where('attendance_status', 'Present')->count(),
            'late' => Attendance::where('nip', $user->nip)->where('attendance_status', 'Late')->count(),
            'absent' => Attendance::where('nip', $user->nip)->where('attendance_status', 'Absent')->count(),
            'sick' => Permission::where('nip', $user->nip)->where('type', 'Sick')->count(),
            'permission' => Permission::where('nip', $user->nip)->where('type', 'Leave')->count(),
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
            'completion_date' => 'required|date|after_or_equal:start_date',
            'information' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB pdf
        ]);

        // Check if has attendance for these dates
        $hasAttendance = Attendance::where('nip', $user->nip)
            ->whereDate('check_in', '>=', $request->start_date)
            ->whereDate('check_in', '<=', $request->completion_date)
            ->whereIn('attendance_status', ['Present', 'Late'])
            ->exists();

        if ($hasAttendance) {
            return back()->with('error', 'Gagal: Anda sudah memiliki catatan kehadiran pada rentang tanggal tersebut.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $user->nip . '.pdf';
            $filePath = $request->file('file')->storeAs('permissions', $fileName, 'public');
        }

        Permission::create([
            'nip' => $user->nip,
            'type' => $request->type,
            'leave_category' => $request->leave_category,
            'sick_category' => $request->sick_category,
            'start_date' => $request->start_date,
            'completion_date' => $request->completion_date,
            'information' => $request->information ?: '-',
            'file' => $filePath,
            'permission_status' => 'Pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully!');
    }

    public function updatePermission(Request $request, $id)
    {
        $user = Auth::user();
        $permission = Permission::where('nip', $user->nip)->findOrFail($id);

        if ($permission->permission_status !== 'Pending') {
            return back()->with('error', 'Gagal: Pengajuan yang sudah diproses tidak dapat diubah.');
        }

        $request->validate([
            'type' => 'required',
            'start_date' => 'required|date',
            'completion_date' => 'required|date|after_or_equal:start_date',
            'information' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $filePath = $permission->file;
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $user->nip . '.pdf';
            $filePath = $request->file('file')->storeAs('permissions', $fileName, 'public');
        }

        $permission->update([
            'type' => $request->type,
            'leave_category' => $request->leave_category,
            'sick_category' => $request->sick_category,
            'start_date' => $request->start_date,
            'completion_date' => $request->completion_date,
            'information' => $request->information ?: '-',
            'file' => $filePath,
        ]);

        return back()->with('success', 'Leave request updated successfully!');
    }

    public function destroyPermission($id)
    {
        $user = Auth::user();
        $permission = Permission::where('nip', $user->nip)->findOrFail($id);

        if ($permission->permission_status !== 'Pending') {
            return back()->with('error', 'Gagal: Pengajuan yang sudah diproses tidak dapat dihapus.');
        }

        $permission->delete();
        return back()->with('success', 'Leave request deleted successfully!');
    }
}
