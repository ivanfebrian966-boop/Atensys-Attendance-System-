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
    <div class="panel fade-up d2 flex flex-col h-full">
        <div class="panel-header">
            <div>
                <h3 class="panel-title">Today's Attendance</h3>
                <p class="panel-subtitle">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
            <span class="badge-rate" id="attendanceStatusBadge">
                {{ $todayAttendance && $todayAttendance->check_in ? ($todayAttendance->check_out ? 'Completed' : 'Checked In') : 'Not Yet' }}
            </span>
        </div>
        <div class="p-5 text-center flex-grow">
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
        <div class="bg-indigo-50/50 border-t border-indigo-50 p-3 text-center text-xs text-indigo-600/80 rounded-b-2xl mt-auto">
            <span class="font-semibold">Note:</span> Standard clock-in is at 07:45. Arrivals after this time will be marked as late.
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
        <div class="p-5 grid grid-cols-3 gap-y-6 gap-x-4">
            <div class="text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ $monthStats['present'] ?? 0 }}</p>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mt-1">Present</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-amber-500">{{ $monthStats['late'] ?? 0 }}</p>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mt-1">Late</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-400">{{ $monthStats['absent'] ?? 0 }}</p>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mt-1">Absent</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-emerald-500">{{ $monthStats['sick'] ?? 0 }}</p>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mt-1">Sick</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-sky-500">{{ $monthStats['permission'] ?? 0 }}</p>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mt-1">Leave</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-slate-700">{{ $monthStats['total'] ?? 0 }}</p>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 mt-1">Total</p>
            </div>
        </div>
    </div>
</div>

<!-- ===== RECENT ATTENDANCE HISTORY ===== -->
<div class="panel fade-up d6">
    <div class="panel-header flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div>
            <h3 class="panel-title">Recent Attendances</h3>
            <p class="panel-subtitle">Last 7 days</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative flex-grow md:flex-grow-0">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="attendanceSearch" placeholder="Search date..." class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
            </div>
            <select id="attendanceFilter" class="px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors bg-white">
                <option value="">All Status</option>
                <option value="Present">Present</option>
                <option value="Late">Late</option>
                <option value="Absent">Absent</option>

                <option value="Permission">Permission</option>
            </select>
            <a href="{{ route('employee.history') }}" class="text-indigo-600 text-sm hover:underline ml-auto md:ml-0 whitespace-nowrap">View All →</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table" id="recentAttendanceTable">
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
                <tr class="table-row attendance-row">
                    <td class="date-cell">{{ \Carbon\Carbon::parse($att->created_at)->translatedFormat('d M Y') }}</td>
                    <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '-' }}</td>
                    <td class="status-cell">
                        <span class="status-badge status-{{ strtolower($att->attendance_status) }}" data-status="{{ $att->attendance_status }}">
                            ● {{ $att->attendance_status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="4" class="text-center text-slate-400 py-6">No attendance records yet</td>
                </tr>
                @endforelse
                <tr id="noResultsRow" class="hidden">
                    <td colspan="4" class="text-center text-slate-400 py-6">No matching records found</td>
                </tr>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('attendanceSearch');
        const filterSelect = document.getElementById('attendanceFilter');
        const rows = document.querySelectorAll('.attendance-row');
        const noResultsRow = document.getElementById('noResultsRow');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterStatus = filterSelect.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                const dateText = row.querySelector('.date-cell').textContent.toLowerCase();
                const statusElement = row.querySelector('.status-badge');
                const statusText = statusElement ? statusElement.getAttribute('data-status').toLowerCase() : '';

                const matchesSearch = dateText.includes(searchTerm);
                const matchesFilter = filterStatus === '' || statusText === filterStatus;

                if (matchesSearch && matchesFilter) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResultsRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noResultsRow.classList.remove('hidden');
                } else {
                    noResultsRow.classList.add('hidden');
                }
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterTable);
        if (filterSelect) filterSelect.addEventListener('change', filterTable);
    });
</script>
@endsection
