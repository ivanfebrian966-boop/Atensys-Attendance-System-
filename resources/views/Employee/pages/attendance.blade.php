@extends('Employee.layouts.main')

@section('title', 'Employee Attendance — ATTENSYS')
@section('page_title', 'Employee Attendance')
@section('page_subtitle', 'Manage your attendance here')

@section('content')

<div class="grid lg:grid-cols-2 gap-4 mb-6">
    <div class="panel fade-up d1">
        <div class="panel-header">
            <div>
                <h3 class="panel-title">Today's Attendance</h3>
                <p class="panel-subtitle">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
            <span class="badge-rate">{{ $todayAttendance && $todayAttendance->check_in ? ($todayAttendance->check_out ? 'Completed' : 'Checked In') : 'Not Yet' }}</span>
        </div>
        <div class="p-5 text-center">
            @if(!$todayAttendance || !$todayAttendance->check_in)
                <div class="mb-4">
                    <div class="text-6xl mb-3">⏰</div>
                    <p class="text-slate-600 mb-4">You haven't checked in yet today</p>
                    <div class="flex flex-col gap-2 max-w-xs mx-auto">
                        <form action="{{ route('employee.attendance.checkin') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary w-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Check In
                            </button>
                        </form>
                        <a href="{{ route('employee.leave') }}" class="btn-secondary w-full justify-center flex">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Request Leave (Izin/Sakit)
                        </a>
                    </div>
                </div>
            @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                <div class="mb-4">
                    <div class="text-6xl mb-3">✅</div>
                    <p class="text-emerald-600 font-semibold">Checked in at {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</p>
                    <p class="text-slate-500 text-sm mt-2">Don't forget to check out</p>
                    <form action="{{ route('employee.attendance.checkout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn-secondary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-4-4m0 0l-4 4m4-4v12"/>
                            </svg>
                            Check Out
                        </button>
                    </form>
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

    <div class="panel fade-up d2">
        <div class="panel-header">
            <div>
                <h3 class="panel-title">Your QR Code</h3>
                <p class="panel-subtitle">Show this to HR for scanning</p>
            </div>
        </div>
        <div class="qr-section">
            <div class="qr-container">
                <div class="qr-code-box" id="qrCodeBox">
                    <div class="qr-loading">
                        <div class="qr-loading-spinner"></div>
                        <p>Generating QR Code...</p>
                    </div>
                </div>
                <div class="qr-info">
                    <p>⚠️ Keep this QR code visible for attendance scanning</p>
                    <p class="text-xs font-semibold mt-1 text-center">Refreshes in: <span id="qr-countdown">10</span>s</p>
                </div>
                <input type="hidden" id="qrCodeBase" value="{{ $qrCodeBaseData ?? '' }}">
                <input type="hidden" id="qrCodeData" value="{{ $qrCodeData }}">
            </div>
        </div>
    </div>
</div>

<div class="panel fade-up d3">
    <div class="panel-header">
        <div>
            <h3 class="panel-title">Recent Attendance</h3>
            <p class="panel-subtitle">Last 7 days</p>
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

