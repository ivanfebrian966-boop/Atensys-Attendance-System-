<?php

namespace App\Http\Controllers\Admin_HR;

use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function index()
    {
        return view('Admin_HR.pages.reports');
    }

    public function getData()
    {
        $attendances = \App\Models\Attendance::with('employee.division')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($att) {
                return [
                    'name'   => $att->employee->name ?? 'Unknown',
                    'div'    => $att->employee->division->division_name ?? '—',
                    'date'   => \Carbon\Carbon::parse($att->created_at)->toDateString(),
                    'status' => $att->attendance_status,
                    'ci'     => $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-'
                ];
            });

        return response()->json($attendances);
    }
}
