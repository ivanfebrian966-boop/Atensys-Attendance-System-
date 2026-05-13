@extends('Employee.layouts.main')

@section('title', 'Employee Attendance — ATTENSYS')
@section('page_title', 'Attendance')
@section('page_subtitle', 'Track your attendance & manage leave requests')

@section('content')

{{-- ===== TOP CARDS: TODAY + QR ===== --}}
<div class="grid lg:grid-cols-2 gap-4 mb-6">

    {{-- Today's Attendance --}}
    <div class="panel fade-up d1 flex flex-col h-full">
        <div class="panel-header">
            <div>
                <h3 class="panel-title">Today's Attendance</h3>
                <p class="panel-subtitle">{{ now()->format('l, d F Y') }}</p>
            </div>
            <span class="badge-rate">{{ $todayAttendance && $todayAttendance->check_in ? ($todayAttendance->check_out ? 'Completed' : 'Checked In') : 'Not Yet' }}</span>
        </div>
        <div class="p-5 text-center flex-grow">
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
                        <button onclick="window.openLeaveModal()" class="btn-secondary w-full justify-center items-center flex">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Request Permission / Sick
                        </button>
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
        <div class="bg-indigo-50/50 border-t border-indigo-50 p-3 text-center text-xs text-indigo-600/80 rounded-b-2xl mt-auto">
            @if(!$todayAttendance || !$todayAttendance->check_in)
                <span class="font-semibold">Reminder:</span> Check-in time is <strong>08:00 AM</strong>. Arrivals after 08:00 AM will be marked as late.
            @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                <span class="font-semibold">Reminder:</span> Check-out time is <strong>17:00 (5:00 PM)</strong>. Don't forget to check out before leaving.
            @else
                <span class="font-semibold">Status:</span> Your attendance for today is complete.
            @endif
        </div>
    </div>

    {{-- QR Code --}}
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

{{-- ===== RECENT ATTENDANCE ===== --}}
<div class="panel fade-up d3 mb-6">
    <div class="panel-header flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div>
            <h3 class="panel-title">Recent Attendance</h3>
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

{{-- ===== LEAVE REQUEST HISTORY ===== --}}
<div class="panel fade-up d4">
    <div class="panel-header border-b border-slate-100 mb-0 pb-4">
        <div>
            <h3 class="panel-title">Leave Requests</h3>
            <p class="panel-subtitle">History of your permission / sick requests</p>
        </div>
        <button onclick="window.openLeaveModal()" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Date Range</th>
                    <th>Status</th>
                    <th>Information</th>
                    <th>Attachment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $perm)
                <tr class="table-row border-b border-slate-50 last:border-0 hover:bg-slate-50">
                    <td class="py-3 px-4">
                        <span class="status-badge {{ $perm->type === 'Sick' ? 'status-sick' : 'status-permission' }}">
                            ● {{ $perm->type }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-sm">
                        {{ \Carbon\Carbon::parse($perm->start_date)->format('d M') }} —
                        {{ \Carbon\Carbon::parse($perm->completion_date)->format('d M Y') }}
                    </td>
                    <td class="py-3 px-4">
                        @php
                            $sc = match($perm->permission_status) {
                                'Pending'  => 'bg-amber-100 text-amber-600',
                                'Approved' => 'bg-emerald-100 text-emerald-600',
                                'Rejected' => 'bg-red-100 text-red-600',
                                default    => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sc }}">{{ $perm->permission_status }}</span>
                    </td>
                    <td class="py-3 px-4 max-w-[180px] truncate text-xs text-slate-500" title="{{ $perm->information }}">
                        {{ $perm->information }}
                    </td>
                    <td class="py-3 px-4">
                        @if($perm->file)
                            <a href="{{ asset('storage/' . $perm->file) }}" target="_blank"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                PDF
                            </a>
                        @else
                            <span class="text-slate-400 text-sm">—</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            @if($perm->permission_status === 'Pending')
                                <button onclick='editLeave({
                                    id: {{ $perm->permission_id }},
                                    type: "{{ $perm->type }}",
                                    start_raw: "{{ $perm->start_date }}",
                                    end_raw: "{{ $perm->completion_date }}",
                                    category: "{{ $perm->leave_category ?: $perm->sick_category }}",
                                    information: "{{ addslashes($perm->information) }}",
                                    hasFile: {{ $perm->file ? "true" : "false" }}
                                })' class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors" title="Edit Request">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <button onclick="openDeleteModal({{ $perm->permission_id }})" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Delete Request">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            @else
                                <span class="text-[10px] text-slate-400 font-medium italic">Locked</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-400 py-8 text-sm">No leave requests yet. Click <strong>New Request</strong> to submit one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('Employee.components.leave_modal')

{{-- DELETE CONFIRM MODAL --}}
<div id="deletePermissionModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-[60]" onclick="closeDeleteModal()">
    <div class="bg-white rounded-3xl w-[400px] max-w-[90vw] p-6 shadow-2xl transform transition-all" onclick="event.stopPropagation()">
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">⚠️</div>
        <h3 class="text-xl font-bold text-slate-800 text-center font-sora mb-2">Delete Request?</h3>
        <p class="text-sm text-slate-500 text-center mb-6">This action cannot be undone. Your leave request will be permanently removed.</p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-colors" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-red-500 text-white font-semibold text-sm hover:bg-red-600 transition-shadow shadow-lg shadow-red-500/25">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>

@endsection



@section('scripts')
<script>
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => {
            if(typeof showToast === 'function') showToast('{{ session("success") }}', 'success');
        });
    @endif

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

    function openDeleteModal(id) {
        const modal = document.getElementById('deletePermissionModal');
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('employee/attendance/permission') }}/${id}/delete`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deletePermissionModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
