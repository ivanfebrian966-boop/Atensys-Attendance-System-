<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\HolidayDate;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    /**
     * Tampilkan halaman kelola tanggal merah.
     */
    public function index()
    {
        $holidays = HolidayDate::orderBy('date', 'desc')->get();

        // Format untuk kalender JS
        $holidayDates = $holidays->pluck('date')->map(fn($d) => $d->format('Y-m-d'))->toArray();

        return view('Admin_HR.pages.holidays', compact('holidays', 'holidayDates'));
    }

    /**
     * Store a new holiday and automatically generate Holiday attendance records.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:holiday_dates,date',
            'name' => 'required|string|max:150',
        ], [
            'date.unique' => 'This date is already registered as a holiday.',
            'date.required' => 'Holiday date is required.',
            'name.required' => 'Holiday name is required.',
        ]);

        try {
            DB::beginTransaction();

            $holiday = HolidayDate::create([
                'date' => $request->date,
                'name' => $request->name,
                'description' => null,
            ]);

            // Create Holiday attendance for all active employees
            $this->generateHolidayAttendances($holiday->date);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Holiday "' . $holiday->name . '" saved successfully.',
                'holiday' => [
                    'id'   => $holiday->id,
                    'date' => $holiday->date->format('Y-m-d'),
                    'name' => $holiday->name,
                    'formatted' => $holiday->date->translatedFormat('l, d F Y'),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a holiday and remove associated auto-generated Holiday attendance records.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $holiday = HolidayDate::findOrFail($id);
            $dateStr = $holiday->date->format('Y-m-d');

            // Delete only auto-generated Holiday attendance records (qr_code = SYSTEM-HOLIDAY)
            Attendance::whereDate('created_at', $dateStr)
                ->where('qr_code', 'SYSTEM-HOLIDAY')
                ->delete();

            $holiday->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Holiday deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint JSON: check if a specific date is a holiday.
     * Used by QR scanner JS.
     */
    public function check(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $holiday = HolidayDate::whereDate('date', $date)->first();

        return response()->json([
            'is_holiday' => (bool) $holiday,
            'name'       => $holiday ? $holiday->name : null,
            'date'       => $date,
        ]);
    }

    /**
     * Generate 'Holiday' attendance record for all active employees
     * who do not have an existing attendance or approved permission/leave on that date.
     * Skips weekends (Saturday and Sunday).
     */
    private function generateHolidayAttendances(Carbon $date): void
    {
        $dateStr = $date->toDateString();

        // Saturdays and Sundays are not workdays, do not generate holiday attendance records
        if ($date->isWeekend()) {
            return;
        }

        $employees = Employee::where('role', 'Employee')->where('status', 'Aktif')->get();

        foreach ($employees as $emp) {
            // Check if attendance already exists for that day
            $existsAttendance = Attendance::where('nip', $emp->nip)
                ->whereDate('created_at', $dateStr)
                ->exists();

            if ($existsAttendance) {
                continue; // Skip if record already exists
            }

            // Check if there is an approved permission/leave for that day
            $existsPermission = Permission::where('nip', $emp->nip)
                ->where('permission_status', 'Approved')
                ->whereDate('start_date', '<=', $dateStr)
                ->whereDate('completion_date', '>=', $dateStr)
                ->exists();

            if ($existsPermission) {
                continue; // Skip if approved leave/sick exists
            }

            // Create Holiday attendance
            $attendanceDate = $date->copy()->setTime(7, 0, 0);
            Attendance::create([
                'nip'               => $emp->nip,
                'check_in'          => null,
                'check_out'         => null,
                'attendance_status' => 'Holiday',
                'qr_code'           => 'SYSTEM-HOLIDAY',
                'created_at'        => $attendanceDate,
                'updated_at'        => $attendanceDate,
            ]);
        }
    }
}
