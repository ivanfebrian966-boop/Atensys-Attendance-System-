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
     * Display the holiday calendar page.
     */
    public function index()
    {
        $holidays = HolidayDate::orderBy('date', 'asc')->get();

        // Format for JS calendar: { "Y-m-d": ["Name1", "Name2"] }
        $holidayMap = [];
        foreach ($holidays as $h) {
            $dateKey = $h->date->format('Y-m-d');
            $holidayMap[$dateKey] = array_merge($holidayMap[$dateKey] ?? [], $h->names ?? []);
        }

        return view('Admin_HR.pages.holidays', compact('holidays', 'holidayMap'));
    }

    /**
     * Save a new holiday.
     * Supports multiple holiday names in one date.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date'    => 'required|date',
            'names'   => 'required|array|min:1',
            'names.*' => 'required|string|max:150',
        ], [
            'date.required'  => 'Holiday date is required.',
            'names.required' => 'Holiday name is required.',
            'names.*.required' => 'Holiday name cannot be empty.',
        ]);

        try {
            DB::beginTransaction();

            $names = array_values(array_filter(array_unique(array_map('trim', $request->names))));
            $created = [];

            foreach ($names as $name) {
                $holiday = HolidayDate::create([
                    'date'  => $request->date,
                    'names' => [$name],
                ]);
                $created[] = [
                    'id'        => $holiday->id,
                    'date'      => $holiday->date->format('Y-m-d'),
                    'names'     => $holiday->names,
                    'label'     => $holiday->names_label,
                    'formatted' => $holiday->date->translatedFormat('l, d F Y'),
                ];
            }

            // Create Holiday attendance for all active employees once per date
            $this->generateHolidayAttendances(Carbon::parse($request->date));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Holiday(s) successfully saved.',
                'holidays' => $created,
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'names' => $names,
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
     * Update the name(s) of the holiday on a specific date.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'names'   => 'required|array|min:1',
            'names.*' => 'required|string|max:150',
        ], [
            'names.required'   => 'At least 1 holiday name must be filled.',
            'names.*.required' => 'Holiday name cannot be empty.',
        ]);

        try {
            DB::beginTransaction();

            $holiday = HolidayDate::findOrFail($id);
            $names   = array_values(array_filter(array_map('trim', $request->names)));

            // Keep the first name on the existing row, and create a new row for each additional name.
            $primaryName = array_shift($names);
            $holiday->update(['names' => [$primaryName]]);

            $createdRows = [];
            foreach ($names as $name) {
                $newHoliday = HolidayDate::create([
                    'date'  => $holiday->date->toDateString(),
                    'names' => [$name],
                ]);

                $createdRows[] = [
                    'id'        => $newHoliday->id,
                    'date'      => $newHoliday->date->format('Y-m-d'),
                    'names'     => $newHoliday->names,
                    'label'     => $newHoliday->names_label,
                    'formatted' => $newHoliday->date->translatedFormat('l, d F Y'),
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Holiday successfully updated.',
                'holiday' => [
                    'id'        => $holiday->id,
                    'date'      => $holiday->date->format('Y-m-d'),
                    'names'     => $holiday->names,
                    'label'     => $holiday->names_label,
                    'formatted' => $holiday->date->translatedFormat('l, d F Y'),
                ],
                'created' => $createdRows,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a holiday along with its auto-generated Holiday attendance.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $holiday = HolidayDate::findOrFail($id);
            $dateStr = $holiday->date->format('Y-m-d');
            $holiday->delete();

            $remaining = HolidayDate::where('date', $dateStr)->exists();
            if (!$remaining) {
                Attendance::whereDate('created_at', $dateStr)
                    ->where('qr_code', 'SYSTEM-HOLIDAY')
                    ->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Holiday successfully deleted.',
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
     * JSON Endpoint: check if a specific date is a holiday.
     */
    public function check(Request $request)
    {
        $date    = $request->input('date', Carbon::today()->toDateString());
        $holidays = HolidayDate::where('date', $date)->get();
        $names = [];

        foreach ($holidays as $holiday) {
            $names = array_merge($names, $holiday->names ?? []);
        }

        return response()->json([
            'is_holiday' => $holidays->isNotEmpty(),
            'names'      => $names,
            'name'       => $holidays->isNotEmpty() ? implode(' & ', $names) : null,
            'date'       => $date,
        ]);
    }

    /**
     * Generate 'Holiday' attendance for all active employees
     * who do not have attendance or approved permission on that date.
     * Skips weekends (Saturday & Sunday).
     */
    private function generateHolidayAttendances(Carbon $date): void
    {
        if ($date->isWeekend()) {
            return;
        }

        $dateStr   = $date->toDateString();
        $employees = Employee::where('role', 'Employee')->where('status', 'Aktif')->get();

        foreach ($employees as $emp) {
            $existsAttendance = Attendance::where('nip', $emp->nip)
                ->whereDate('created_at', $dateStr)
                ->exists();

            if ($existsAttendance) continue;

            $existsPermission = Permission::where('nip', $emp->nip)
                ->where('permission_status', 'Approved')
                ->whereDate('start_date', '<=', $dateStr)
                ->whereDate('completion_date', '>=', $dateStr)
                ->exists();

            if ($existsPermission) continue;

            $attendanceDate = $date->copy()->setTime(7, 0, 0);
            Attendance::create([
                'nip'               => $emp->nip,
                'check_in'          => null,
                'check_out'         => null,
                'attendance_status' => 'Present',
                'qr_code'           => 'SYSTEM-HOLIDAY',
                'created_at'        => $attendanceDate,
                'updated_at'        => $attendanceDate,
            ]);
        }
    }
}
