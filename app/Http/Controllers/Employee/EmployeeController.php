<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\HolidayDate;
use App\Models\Permission;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        Attendance::syncMissingCheckouts();

        $todayAttendance = Attendance::where('nip', $user->nip)
            ->whereDate('created_at', Carbon::today())
            ->first();

        $today = Carbon::today();
        $todayPartialLeave = Permission::where('nip', $user->nip)
            ->where('permission_status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->whereNotNull('start_time')
            ->first();

        $todayFullDayLeave = Permission::where('nip', $user->nip)
            ->where('permission_status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->whereNull('start_time')
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
        
        return view('Employee.pages.dashboard', compact('todayAttendance', 'monthStats', 'recentAttendances', 'user', 'qrCodeData', 'qrCodeBaseData', 'todayPartialLeave', 'todayFullDayLeave'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $today = Carbon::today();

        if (HolidayDate::where('date', $today->toDateString())->exists()) {
            return back()->with('error', 'Cannot check in today because it is a holiday.');
        }

        // Check if has full-day permission/sick for today
        $hasFullDayPermission = Permission::where('nip', $user->nip)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->whereIn('permission_status', ['Approved', 'Pending'])
            ->whereNull('start_time')
            ->exists();

        if ($hasFullDayPermission) {
            return back()->with('error', 'Failed: You already have a full-day permission/sick request for today.');
        }

        // Check if already checked in today (check_in is not null)
        $existing = Attendance::where('nip', $user->nip)
            ->whereDate('created_at', $today)
            ->first();

        if ($existing && !is_null($existing->check_in)) {
            return back()->with('error', 'You have already checked in today.');
        }

        $now = Carbon::now();

        // Check if has approved partial leave/sick today
        $partialPermission = Permission::where('nip', $user->nip)
            ->where('permission_status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->whereNotNull('start_time')
            ->first();

        if ($partialPermission) {
            $endTime = Carbon::parse($today->toDateString() . ' ' . $partialPermission->end_time);
            $status = $now->lessThanOrEqualTo($endTime) ? 'Present' : 'Late';

            if ($existing) {
                $existing->update([
                    'check_in' => $now,
                    'attendance_status' => $status,
                    'qr_code' => $request->qr_code ?? 'MANUAL',
                ]);
            } else {
                Attendance::create([
                    'nip' => $user->nip,
                    'check_in' => $now,
                    'attendance_status' => $status,
                    'qr_code' => $request->qr_code ?? 'MANUAL',
                ]);
            }
            return back()->with('success', 'Check-in recorded successfully!');
        }

        if ($existing) {
            $existing->update([
                'check_in' => $now,
                'attendance_status' => $now->format('H:i') > '08:00' ? 'Late' : 'Present',
                'qr_code' => $request->qr_code ?? 'MANUAL',
            ]);
        } else {
            Attendance::create([
                'nip' => $user->nip,
                'check_in' => $now,
                'attendance_status' => $now->format('H:i') > '08:00' ? 'Late' : 'Present',
                'qr_code' => $request->qr_code ?? 'MANUAL',
            ]);
        }
        
        return back()->with('success', 'Check-in recorded successfully!');
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        if (!$user) return redirect()->route('login');

        if (HolidayDate::where('date', $today->toDateString())->exists()) {
            return back()->with('error', 'Cannot check out today because it is a holiday.');
        }

        // Check if has full-day permission/sick for today
        $hasFullDayPermission = Permission::where('nip', $user->nip)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->where('permission_status', 'Approved')
            ->whereNull('start_time')
            ->exists();

        if ($hasFullDayPermission) {
            return back()->with('error', 'Failed: You cannot check in/out during approved leave or sick period.');
        }

        $attendance = Attendance::where('nip', $user->nip)
            ->whereDate('created_at', $today)
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

        Attendance::syncMissingCheckouts();

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

        Attendance::syncMissingCheckouts();

        $todayAttendance = Attendance::where('nip', $user->nip)
            ->whereDate('created_at', Carbon::today())
            ->first();

        $today = Carbon::today();
        $todayPartialLeave = Permission::where('nip', $user->nip)
            ->where('permission_status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->whereNotNull('start_time')
            ->first();

        $todayFullDayLeave = Permission::where('nip', $user->nip)
            ->where('permission_status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('completion_date', '>=', $today)
            ->whereNull('start_time')
            ->first();

        $recentAttendances = Attendance::where('nip', $user->nip)
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();

        $qrCodeBaseData = 'ATTENSYS:EMP:' . $user->nip;
        $qrCodeData = $qrCodeBaseData . ':' . now()->timestamp;

        $permissions = Permission::where('nip', $user->nip)->orderBy('created_at', 'desc')->get();

        return view('Employee.pages.attendance', compact('todayAttendance', 'recentAttendances', 'qrCodeData', 'qrCodeBaseData', 'user', 'permissions', 'todayPartialLeave', 'todayFullDayLeave'));
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

        $mandatoryCategories = [
            'Marriage Leave', 'Maternity Leave',
            'Sick Leave with Medical Certificate', 'Hospitalization', 'Accident'
        ];

        $category = $request->type === 'Leave' ? $request->leave_category : $request->sick_category;
        
        if ($category === 'Official Duty Leave') {
            return back()->with('error', 'Failed: Official Duty Leave is no longer available.');
        }

        $isPartial = $request->filled('start_time') || $request->filled('end_time');
        if ($isPartial) {
            if ($request->start_date !== $request->completion_date) {
                return back()->with('error', 'Failed: Partial requests can only be made for a single day.');
            }
            if (!$request->filled('start_time') || !$request->filled('end_time')) {
                return back()->with('error', 'Failed: Both start and end time must be specified for partial requests.');
            }
            if ($request->start_time >= $request->end_time) {
                return back()->with('error', 'Failed: End time must be after start time.');
            }

            if ($request->type === 'Leave') {
                $disallowed = ['Marriage Leave', 'Annual Leave', 'Hajj Leave', 'Umrah Leave'];
                if (in_array($category, $disallowed)) {
                    return back()->with('error', 'Failed: Selected leave category is not allowed for partial requests.');
                }
            } elseif ($request->type === 'Sick') {
                $disallowed = ['Sick Leave with Medical Certificate', 'Hospitalization'];
                if (in_array($category, $disallowed)) {
                    return back()->with('error', 'Failed: Selected sick category is not allowed for partial requests.');
                }
            }
        }

        $fileRequired = in_array($category, $mandatoryCategories);

        $request->validate([
            'type' => 'required|in:Leave,Sick',
            'start_date' => 'required|date',
            'completion_date' => 'required|date|after_or_equal:start_date',
            'leave_category' => 'nullable|string',
            'sick_category' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'information' => 'nullable|string|max:255',
            'file' => ($fileRequired ? 'required' : 'nullable') . '|file|mimes:pdf|max:2048',
        ]);

        // Maternity leave limit: 1 per year
        if ($category === 'Maternity Leave') {
            $year = Carbon::parse($request->start_date)->year;
            $hasMaternity = Permission::where('nip', $user->nip)
                ->where('leave_category', 'Maternity Leave')
                ->whereIn('permission_status', ['Pending', 'Approved'])
                ->whereYear('start_date', $year)
                ->exists();

            if ($hasMaternity) {
                return back()->with('error', 'Failed: Maternity Leave can only be taken once per year.');
            }
        }

        // Annual leave limit: 12 days per year
        if ($category === 'Annual Leave') {
            $year = Carbon::parse($request->start_date)->year;
            $annualLeaves = Permission::where('nip', $user->nip)
                ->where('leave_category', 'Annual Leave')
                ->whereIn('permission_status', ['Pending', 'Approved'])
                ->whereYear('start_date', $year)
                ->get();

            $totalDaysTaken = 0;
            foreach ($annualLeaves as $leave) {
                $start = Carbon::parse($leave->start_date);
                $end = Carbon::parse($leave->completion_date);
                $totalDaysTaken += $start->diffInDays($end) + 1;
            }

            $currentStart = Carbon::parse($request->start_date);
            $currentEnd = Carbon::parse($request->completion_date);
            $currentDays = $currentStart->diffInDays($currentEnd) + 1;

            if (($totalDaysTaken + $currentDays) > 12) {
                $remaining = max(0, 12 - $totalDaysTaken);
                return back()->with('error', "Failed: Annual Leave limit is 12 days per year. You only have {$remaining} days left.");
            }
        }

        // Check if has attendance for these dates
        $hasAttendance = Attendance::where('nip', $user->nip)
            ->whereDate('check_in', '>=', $request->start_date)
            ->whereDate('check_in', '<=', $request->completion_date)
            ->whereIn('attendance_status', ['Present', 'Late'])
            ->exists();

        if ($hasAttendance) {
            return back()->with('error', 'Failed: You already have attendance records on the specified date range.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $user->nip . '.pdf';
            $filePath = $request->file('file')->storeAs('permissions', $fileName, 'public');
        }

        Permission::create([
            'nip' => $user->nip,
            'type' => $request->type,
            'leave_category' => $request->type === 'Leave' ? $request->leave_category : null,
            'sick_category' => $request->type === 'Sick' ? $request->sick_category : null,
            'start_date' => $request->start_date,
            'completion_date' => $request->completion_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
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
            return back()->with('error', 'Failed: Processed requests cannot be modified.');
        }

        $mandatoryCategories = [
            'Marriage Leave', 'Maternity Leave',
            'Sick Leave with Medical Certificate', 'Hospitalization', 'Accident'
        ];

        $category = $request->type === 'Leave' ? $request->leave_category : $request->sick_category;
        
        if ($category === 'Official Duty Leave') {
            return back()->with('error', 'Failed: Official Duty Leave is no longer available.');
        }

        $isPartial = $request->filled('start_time') || $request->filled('end_time');
        if ($isPartial) {
            if ($request->start_date !== $request->completion_date) {
                return back()->with('error', 'Failed: Partial requests can only be made for a single day.');
            }
            if (!$request->filled('start_time') || !$request->filled('end_time')) {
                return back()->with('error', 'Failed: Both start and end time must be specified for partial requests.');
            }
            if ($request->start_time >= $request->end_time) {
                return back()->with('error', 'Failed: End time must be after start time.');
            }

            if ($request->type === 'Leave') {
                $disallowed = ['Marriage Leave', 'Annual Leave', 'Hajj Leave', 'Umrah Leave'];
                if (in_array($category, $disallowed)) {
                    return back()->with('error', 'Failed: Selected leave category is not allowed for partial requests.');
                }
            } elseif ($request->type === 'Sick') {
                $disallowed = ['Sick Leave with Medical Certificate', 'Hospitalization'];
                if (in_array($category, $disallowed)) {
                    return back()->with('error', 'Failed: Selected sick category is not allowed for partial requests.');
                }
            }
        }

        $fileRequired = in_array($category, $mandatoryCategories) && !$permission->file;

        $request->validate([
            'type' => 'required|in:Leave,Sick',
            'start_date' => 'required|date',
            'completion_date' => 'required|date|after_or_equal:start_date',
            'leave_category' => 'nullable|string',
            'sick_category' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'information' => 'nullable|string|max:255',
            'file' => ($fileRequired ? 'required' : 'nullable') . '|file|mimes:pdf|max:2048',
        ]);

        // Maternity leave limit: 1 per year (excluding this current request)
        if ($category === 'Maternity Leave') {
            $year = Carbon::parse($request->start_date)->year;
            $hasMaternity = Permission::where('nip', $user->nip)
                ->where('permission_id', '!=', $id)
                ->where('leave_category', 'Maternity Leave')
                ->whereIn('permission_status', ['Pending', 'Approved'])
                ->whereYear('start_date', $year)
                ->exists();

            if ($hasMaternity) {
                return back()->with('error', 'Failed: Maternity Leave can only be taken once per year.');
            }
        }

        // Annual leave limit: 12 days per year (excluding this current request)
        if ($category === 'Annual Leave') {
            $year = Carbon::parse($request->start_date)->year;
            $annualLeaves = Permission::where('nip', $user->nip)
                ->where('permission_id', '!=', $id)
                ->where('leave_category', 'Annual Leave')
                ->whereIn('permission_status', ['Pending', 'Approved'])
                ->whereYear('start_date', $year)
                ->get();

            $totalDaysTaken = 0;
            foreach ($annualLeaves as $leave) {
                $start = Carbon::parse($leave->start_date);
                $end = Carbon::parse($leave->completion_date);
                $totalDaysTaken += $start->diffInDays($end) + 1;
            }

            $currentStart = Carbon::parse($request->start_date);
            $currentEnd = Carbon::parse($request->completion_date);
            $currentDays = $currentStart->diffInDays($currentEnd) + 1;

            if (($totalDaysTaken + $currentDays) > 12) {
                $remaining = max(0, 12 - $totalDaysTaken);
                return back()->with('error', "Failed: Annual Leave limit is 12 days per year. You only have {$remaining} days left.");
            }
        }

        $filePath = $permission->file;
        if ($request->hasFile('file')) {
            $fileName = time() . '_' . $user->nip . '.pdf';
            $filePath = $request->file('file')->storeAs('permissions', $fileName, 'public');
        }

        $permission->update([
            'type' => $request->type,
            'leave_category' => $request->type === 'Leave' ? $request->leave_category : null,
            'sick_category' => $request->type === 'Sick' ? $request->sick_category : null,
            'start_date' => $request->start_date,
            'completion_date' => $request->completion_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
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
            return back()->with('error', 'Failed: Processed requests cannot be deleted.');
        }

        $permission->delete();
        return back()->with('success', 'Leave request deleted successfully!');
    }
}
