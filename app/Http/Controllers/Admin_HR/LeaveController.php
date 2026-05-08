<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    public function index()
    {
        // Auto-run mark absent on page load
        $this->runMarkAbsent();

        $stats = [
            'total'      => Permission::count(),
            'pending'    => Permission::where('permission_status', 'Pending')->count(),
            'approved'   => Permission::where('permission_status', 'Approved')->count(),
            'rejected'   => Permission::where('permission_status', 'Rejected')->count(),
            'sick'       => Permission::where('type', 'Sick')->count(),
            'leave'      => Permission::where('type', 'Leave')->count(),
        ];

        $pendingPermissions = Permission::with('employee.division')
            ->where('permission_status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Admin_HR.pages.leaves', compact('stats', 'pendingPermissions'));
    }

    public function getData(Request $request)
    {
        try {
            $query = Permission::with(['employee.division']);

            if ($request->filled('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('permission_status', $request->status);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                });
            }

            $leaves = $query->orderBy('created_at', 'desc')->get()->map(function ($perm) {
                $start = Carbon::parse($perm->start_date);
                $end   = Carbon::parse($perm->completion_date);
                $days  = $start->diffInDays($end) + 1;

                return [
                    'id'          => $perm->permission_id,
                    'name'        => $perm->employee->name ?? 'Unknown',
                    'nip'         => $perm->nip,
                    'division'    => $perm->employee->division->division_name ?? '—',
                    'type'        => $perm->type,
                    'leave_category' => $perm->leave_category,
                    'sick_category' => $perm->sick_category,
                    'information' => $perm->information,
                    'start_date'  => $start->format('d M Y'),
                    'end_date'    => $end->format('d M Y'),
                    'start_raw'   => $start->format('Y-m-d'),
                    'end_raw'     => $end->format('Y-m-d'),
                    'days'        => $days,
                    'status'          => $perm->permission_status,
                    'reject_reason'   => $perm->reject_reason,
                    'file'            => $perm->file ? asset('storage/' . $perm->file) : null,
                    'submitted'   => Carbon::parse($perm->created_at)->format('d M Y, H:i'),
                    'overdue'     => $perm->permission_status === 'Approved' && Carbon::parse($perm->completion_date)->isPast(),
                ];
            });

            return response()->json(['success' => true, 'data' => $leaves]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);

            if ($permission->permission_status !== 'Pending') {
                return response()->json(['success' => false, 'message' => 'Request is no longer pending.'], 422);
            }

            $permission->update(['permission_status' => 'Approved']);

            // Create attendance records for each day of the approved leave
            $start = Carbon::parse($permission->start_date);
            $end   = Carbon::parse($permission->completion_date);

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $exists = Attendance::where('nip', $permission->nip)
                    ->whereDate('created_at', $date->toDateString())
                    ->exists();

                if (!$exists) {
                    $attendanceDate = $date->copy()->setTime(7, 0, 0);
                    Attendance::create([
                        'nip'        => $permission->nip,
                        'check_in'   => null,
                        'attendance_status'     => 'Permission',
                        'qr_code'    => 'SYSTEM',
                        'created_at' => $attendanceDate,
                        'updated_at' => $attendanceDate,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Leave request approved successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            if ($permission->permission_status !== 'Pending') {
                return response()->json(['success' => false, 'message' => 'Request is no longer pending.'], 422);
            }

            $permission->update([
                'permission_status' => 'Rejected',
                'reject_reason' => $request->reject_reason
            ]);

            // If rejection happens after absence period, mark those days as Absent
            $start = Carbon::parse($permission->start_date);
            $end   = Carbon::parse($permission->completion_date);
            $today = Carbon::today();

            for ($date = $start->copy(); $date->lte($end) && $date->lt($today); $date->addDay()) {
                $exists = Attendance::where('nip', $permission->nip)
                    ->whereDate('created_at', $date->toDateString())
                    ->exists();

                if (!$exists) {
                    $attendanceDate = $date->copy()->setTime(7, 0, 0);
                    Attendance::create([
                        'nip'        => $permission->nip,
                        'attendance_status'     => 'Absent',
                        'qr_code'    => 'SYSTEM',
                        'created_at' => $attendanceDate,
                        'updated_at' => $attendanceDate,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Leave request rejected.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);

            $oldStatus = $permission->permission_status;
            $oldStart  = $permission->start_date;
            $oldEnd    = $permission->completion_date;

            $permission->update([
                'start_date'      => $request->start_date,
                'completion_date' => $request->completion_date,
                'permission_status'          => $request->status,
                'reject_reason'   => $request->status === 'Rejected' ? $request->reject_reason : null,
            ]);

            // If it was accepted previously, we delete the SYSTEM attendance records for the old date range.
            if ($oldStatus === 'Approved') {
                Attendance::where('nip', $permission->nip)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$oldStart, $oldEnd])
                    ->where('qr_code', 'SYSTEM')
                    ->delete();
            }

            // Apply new status logic
            if ($request->status === 'Approved') {
                $start = Carbon::parse($request->start_date);
                $end   = Carbon::parse($request->completion_date);

                for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                    $exists = Attendance::where('nip', $permission->nip)
                        ->whereDate('created_at', $date->toDateString())
                        ->exists();

                    if (!$exists) {
                        $attendanceDate = $date->copy()->setTime(7, 0, 0);
                        Attendance::create([
                            'nip'        => $permission->nip,
                            'check_in'   => $attendanceDate,
                            'attendance_status'     => 'Permission',
                            'qr_code'    => 'SYSTEM',
                            'created_at' => $attendanceDate,
                            'updated_at' => $attendanceDate,
                        ]);
                    } else {
                        // Update to leave type if it was marked as Absent
                        Attendance::where('nip', $permission->nip)
                            ->whereDate('created_at', $date->toDateString())
                            ->where('attendance_status', 'Absent')
                            ->update(['attendance_status' => 'Permission', 'check_in' => $date->copy()->setTime(7, 0, 0)]);
                    }
                }
            } elseif ($request->status === 'Rejected') {
                // If rejection happens after absence period, mark those days as Absent
                $start = Carbon::parse($request->start_date);
                $end   = Carbon::parse($request->completion_date);
                $today = Carbon::today();

                for ($date = $start->copy(); $date->lte($end) && $date->lt($today); $date->addDay()) {
                    $exists = Attendance::where('nip', $permission->nip)
                        ->whereDate('created_at', $date->toDateString())
                        ->exists();

                    if (!$exists) {
                        $attendanceDate = $date->copy()->setTime(7, 0, 0);
                        Attendance::create([
                            'nip'        => $permission->nip,
                            'attendance_status'     => 'Absent',
                            'qr_code'    => 'SYSTEM',
                            'created_at' => $attendanceDate,
                            'updated_at' => $attendanceDate,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Leave request updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $permission = Permission::findOrFail($id);

            // If it was approved, clean up the attendance records for that period
            if ($permission->permission_status === 'Approved') {
                $start = Carbon::parse($permission->start_date);
                $end   = Carbon::parse($permission->completion_date);

                Attendance::where('nip', $permission->nip)
                    ->whereBetween('created_at', [
                        $start->startOfDay()->toDateTimeString(),
                        $end->endOfDay()->toDateTimeString()
                    ])
                    ->where('attendance_status', 'Permission')
                    ->delete();
            }

            $permission->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Leave request and associated attendance records deleted.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark absent for employees who did not return after their leave ended.
     * Called on page load and can be triggered manually.
     */
    public function markAbsent(Request $request = null)
    {
        $marked = $this->runMarkAbsent();
        if ($request) {
            return response()->json(['success' => true, 'marked' => $marked]);
        }
    }

    private function runMarkAbsent(): int
    {
        $marked = 0;
        $today  = Carbon::today();

        // Get all accepted permissions where the leave has fully ended
        $finishedLeaves = Permission::where('permission_status', 'Approved')
            ->where('completion_date', '<', $today->toDateString())
            ->with('employee')
            ->get();

        foreach ($finishedLeaves as $perm) {
            $leaveEnd = Carbon::parse($perm->completion_date);

            // Check each day AFTER the leave ended up to yesterday
            $checkStart = $leaveEnd->copy()->addDay();
            $checkEnd   = $today->copy()->subDay();

            if ($checkStart->gt($checkEnd)) continue;

            for ($date = $checkStart->copy(); $date->lte($checkEnd); $date->addDay()) {
                // Skip weekends (optional — remove if company works weekends)
                // if ($date->isWeekend()) continue;

                $hasRecord = Attendance::where('nip', $perm->nip)
                    ->whereDate('created_at', $date->toDateString())
                    ->exists();

                if (!$hasRecord) {
                    $attendanceDate = $date->copy()->setTime(7, 0, 0);
                    Attendance::create([
                        'nip'        => $perm->nip,
                        'attendance_status'     => 'Absent',
                        'qr_code'    => 'SYSTEM',
                        'created_at' => $attendanceDate,
                        'updated_at' => $attendanceDate,
                    ]);
                    $marked++;
                }
            }
        }

        return $marked;
    }
}
