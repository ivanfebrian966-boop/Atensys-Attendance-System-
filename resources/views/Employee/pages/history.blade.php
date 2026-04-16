@extends('Employee.layouts.main')

@section('title', 'Attendance History — ATTENSYS')
@section('page_title', 'Attendance History')
@section('page_subtitle', 'Riwayat kehadiran karyawan')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card fade-up d1">
        <div class="stat-icon-sm" style="background:#eef2ff;color:#6366f1">👥</div>
        <p class="stat-value text-slate-900">{{ $attendances->count() }}</p>
        <p class="stat-label">Total Records</p>
    </div>
    <div class="stat-card fade-up d2">
        <div class="stat-icon-sm" style="background:#ecfdf5;color:#10b981">✅</div>
        <p class="stat-value text-emerald-600">{{ $counts['present'] }}</p>
        <p class="stat-label">Present</p>
    </div>
    <div class="stat-card fade-up d3">
        <div class="stat-icon-sm" style="background:#fffbeb;color:#f59e0b">⏰</div>
        <p class="stat-value text-amber-500">{{ $counts['late'] }}</p>
        <p class="stat-label">Late</p>
    </div>
    <div class="stat-card fade-up d4">
        <div class="stat-icon-sm" style="background:#fef2f2;color:#ef4444">❌</div>
        <p class="stat-value text-red-500">{{ $counts['absent'] }}</p>
        <p class="stat-label">Absent</p>
    </div>
</div>

<div class="panel fade-up d5">
    <div class="panel-header items-center justify-between">
        <div>
            <h3 class="panel-title">Detailed History</h3>
            <p class="panel-subtitle">All attendance records sorted by date</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('employee.attendance') }}" class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Attendance
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th class="hidden lg:table-cell">Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr class="table-row">
                    <td>{{ \Carbon\Carbon::parse($attendance->check_in)->translatedFormat('d M Y') }}</td>
                    <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($attendance->status) }}">
                            ● {{ $attendance->status }}
                        </span>
                    </td>
                    <td class="hidden lg:table-cell">{{ $attendance->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-400 py-6">No attendance history found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
