@extends('Employee.layouts.main')

@section('title', 'Attendance History — ATTENSYS')
@section('page_title', 'Attendance History')
@section('page_subtitle', 'Your complete attendance history')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="stat-card fade-up d1">
        <div class="stat-icon-sm" style="background:#eef2ff;color:#6366f1">👥</div>
        <p class="stat-value text-slate-900">{{ $attendances->total() }}</p>
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
    <div class="stat-card fade-up d6">
        <div class="stat-icon-sm" style="background:#fdf4ff;color:#a855f7">🤒</div>
        <p class="stat-value text-purple-600">{{ $counts['sick'] }}</p>
        <p class="stat-label">Sick</p>
    </div>
    <div class="stat-card fade-up d5">
        <div class="stat-icon-sm" style="background:#f0f9ff;color:#0ea5e9">📝</div>
        <p class="stat-value text-sky-600">{{ $counts['permission'] }}</p>
        <p class="stat-label">Permission</p>
    </div>
</div>

<div class="panel fade-up d7">
    <div class="panel-header flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div>
            <h3 class="panel-title">Detailed History</h3>
            <p class="panel-subtitle">All attendance records sorted by date</p>
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
                <option value="Sick">Sick</option>
                <option value="Permission">Permission</option>
            </select>
            <a href="{{ route('employee.attendance') }}" class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Attendance
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table" id="historyTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr class="table-row attendance-row">
                    <td class="date-cell">{{ \Carbon\Carbon::parse($attendance->created_at)->translatedFormat('d M Y') }}</td>
                    <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                    <td class="status-cell">
                        <span class="status-badge status-{{ strtolower($attendance->status) }}" data-status="{{ $attendance->status }}">
                            ● {{ $attendance->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="4" class="text-center text-slate-400 py-6">No attendance history found.</td>
                </tr>
                @endforelse
                <tr id="noResultsRow" class="hidden">
                    <td colspan="4" class="text-center text-slate-400 py-6">No matching records found</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
        <p class="text-sm text-slate-400">Total {{ $attendances->total() }} records</p>
        
        @if ($attendances->hasPages())
        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($attendances->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $attendances->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Pagination Elements (Simplified) --}}
            @foreach ($attendances->getUrlRange(1, $attendances->lastPage()) as $page => $url)
                @if ($page == $attendances->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-500 text-white font-medium text-sm shadow-sm shadow-indigo-500/20">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-50 hover:text-indigo-600 font-medium text-sm transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($attendances->hasMorePages())
                <a href="{{ $attendances->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif
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
