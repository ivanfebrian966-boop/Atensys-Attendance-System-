@extends('Employee.layouts.main')

@section('title', 'Employee Dashboard — ATTENSYS')
@section('page_title', 'Employee Dashboard')

@section('content')

<!-- ===== WELCOME CARD ===== -->
<div class="welcome-card fade-up d1 mb-6">
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-white" style="font-family:'Sora',sans-serif">Welcome back, {{ $user->name }}! 👋</h2>
        <p class="text-slate-200 text-sm mt-1">Today is {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div class="welcome-badge" id="realtime-clock">{{ now()->format('H:i') }}</div>
</div>

<!-- ===== ATTENDANCE STATUS CARD ===== -->
<div class="grid lg:grid-cols-2 gap-4 mb-6">
    <!-- Today's Attendance -->
    <div class="panel fade-up d2">
        <div class="panel-header">
            <div>
                <h3 class="panel-title">Today's Attendance</h3>
                <p class="panel-subtitle">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
            <span class="badge-rate" id="attendanceStatusBadge">
                {{ $todayAttendance && $todayAttendance->check_in ? ($todayAttendance->check_out ? 'Completed' : 'Checked In') : 'Not Yet' }}
            </span>
        </div>
        <div class="p-5 text-center">
            @if(!$todayAttendance || !$todayAttendance->check_in)
                <div class="mb-4">
                    <div class="text-6xl mb-3">⏰</div>
                    <p class="text-slate-600 mb-4">You haven't checked in yet today</p>
                    <a href="{{ route('employee.attendance') }}" class="btn-primary inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Go to Attendance
                    </a>
                </div>
            @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                <div class="mb-4">
                    <div class="text-6xl mb-3">✅</div>
                    <p class="text-emerald-600 font-semibold">Checked in at {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</p>
                    <p class="text-slate-500 text-sm mt-2">Don't forget to check out</p>
                    <a href="{{ route('employee.attendance') }}" class="btn-secondary inline-flex items-center gap-2 mt-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-4-4m0 0l-4 4m4-4v12"/>
                        </svg>
                        Go to Checkout
                    </a>
                </div>
            @else
                <div class="mb-4">
                    <div class="text-6xl mb-3">🎉</div>
                    <p class="text-green-600 font-semibold">Complete!</p>
                    <p class="text-slate-600 text-sm">In: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }} | Out: {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="panel fade-up d3">
        <div class="panel-header">
            <div>
                <h3 class="panel-title">Your Statistics</h3>
                <p class="panel-subtitle">This month</p>
            </div>
        </div>
        <div class="p-5 grid grid-cols-2 gap-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ $monthStats['present'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Present</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-amber-500">{{ $monthStats['late'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Late</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-400">{{ $monthStats['absent'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Absent</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-500">{{ $monthStats['sick_permission'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Sick/Permission</p>
            </div>
        </div>
    </div>
</div>

<!-- ===== RECENT ATTENDANCE HISTORY ===== -->
<div class="panel fade-up d6">
    <div class="panel-header">
        <div>
            <h3 class="panel-title">Recent Attendance</h3>
            <p class="panel-subtitle">Last 7 days</p>
        </div>
        <a href="{{ route('employee.history') }}" class="text-indigo-600 text-sm hover:underline">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAttendances as $att)
                <tr class="table-row">
                    <td>{{ \Carbon\Carbon::parse($att->check_in)->translatedFormat('d M Y') }}</td>
                    <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($att->status) }}">
                            ● {{ $att->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-slate-400 py-6">No attendance records yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('modals')
<!-- QR Modal -->
<div id="qrModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold" style="font-family:'Sora',sans-serif">Scan QR Code</h3>
            <button onclick="closeQRModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <div id="qr-reader" class="w-full"></div>
        <p class="text-center text-sm text-slate-500 mt-4">Position QR code in front of camera</p>
    </div>
</div>
@endsection
