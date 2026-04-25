<!DOCTYPE html>
@extends('Admin_HR.layouts.main')

@section('title', 'Attendance — ATTENSYS')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/attendance.css') }}">
    <!-- QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@endsection

@section('main_structure')
@include('Admin_HR.components.sidebar')

<div class="main-content">
    @include('Admin_HR.components.topbar', [
        'pageTitle'    => 'Attendance',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
    ])

    <div class="p-4 md:p-6">

        <!-- QR CODE SCANNER -->
        <div class="panel fade-up d1 mb-6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Scan Attendance QR Code</h3>

                    <p class="panel-subtitle">Point camera at QR code to check in</p>
                </div>
            </div>
            <div class="modal-body pt-4 pb-4">
                <style>#qr-reader video { transform: scaleX(-1); }</style>
                <div id="qr-reader" style="width:100%;max-width:640px;margin:auto;border-radius:12px;overflow:hidden;"></div>
                <div id="qr-result" class="mt-4 text-center text-emerald-600 font-semibold"></div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="stat-card indigo fade-up d1">
                <div class="stat-icon" style="background:#eef2ff">💼</div>
                <p class="stat-value text-slate-900" id="sTotal">0</p>
                <p class="stat-label">Total Attendances</p>
            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon" style="background:#ecfdf5">✅</div>
                <p class="stat-value text-emerald-600" id="sPresent">0</p>
                <p class="stat-label">Present</p>

            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon" style="background:#fef2f2">❌</div>
                <p class="stat-value text-red-500" id="sAbsent">0</p>
                <p class="stat-label">Absent</p>

            </div>
            <div class="stat-card amber fade-up d4">
                <div class="stat-icon" style="background:#fffbeb">⏰</div>
                <p class="stat-value text-amber-500" id="sLate">0</p>
                <p class="stat-label">Late</p>

            </div>
            <div class="stat-card blue fade-up d5">
                <div class="stat-icon" style="background:#eff6ff">🏥</div>
                <p class="stat-value text-blue-500" id="sSick">0</p>
                <p class="stat-label">Sick</p>

            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon" style="background:#faf5ff">📋</div>
                <p class="stat-value text-purple-500" id="sPerm">0</p>
                <p class="stat-label">Permission</p>

            </div>
        </div>
        
        <!-- LEAVE REQUESTS (PENDING ACC) -->
        <div class="panel fade-up d2 mb-6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Pending Leave Requests</h3>
                    <p class="panel-subtitle">Review and approve employee permission/sick requests</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Date Range</th>
                            <th>Information</th>
                            <th>File</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPermissions ?? [] as $perm)
                        <tr class="table-row">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                                        {{ substr($perm->employee->name ?? '?', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm">{{ $perm->employee->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-slate-400">{{ $perm->employee->division->division_name ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $perm->type === 'Sick' ? 'status-sick' : 'status-permission' }}">
                                    ● {{ $perm->type }}
                                </span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    {{ \Carbon\Carbon::parse($perm->start_date)->format('d M') }} — 
                                    {{ \Carbon\Carbon::parse($perm->completion_date)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <p class="text-xs text-slate-500 max-w-xs truncate" title="{{ $perm->information }}">
                                    {{ $perm->information }}
                                </p>
                            </td>
                            <td>
                                @if($perm->file)
                                    <a href="{{ asset('storage/' . $perm->file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold inline-flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        PDF
                                    </a>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin-hr.attendance.permission.approve', $perm->permission_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-approve">ACC</button>
                                    </form>
                                    <form action="{{ route('admin-hr.attendance.permission.reject', $perm->permission_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-reject">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 py-6">No pending requests to review</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ATTENDANCE TABLE -->
        <div class="panel fade-up d2">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Attendance Data</h3>

                    <p class="panel-subtitle">Daily employee attendance summary</p>
                </div>
                <div class="header-actions">
                    <div class="search-wrap">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchAtt" class="search-input" placeholder="Search name..." oninput="filterAtt()">
                    </div>
                    <input type="date" id="filterDate" class="filter-select" value="{{ date('Y-m-d') }}" onchange="loadAttendanceData()" style="padding-left:12px">
                    <select id="filterAttStatus" class="filter-select" onchange="filterAtt()">
                        <option value="">All Status</option>

                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                    <select id="filterAttDiv" class="filter-select" onchange="filterAtt()">
                        <option value="">All Divisions</option>

                        <option>Engineering</option><option>HR</option><option>Finance</option>
                        <option>Marketing</option><option>IT</option><option>Operasional</option>
                    </select>
                    <button class="btn-secondary" onclick="exportAtt()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                    <button class="btn-primary" onclick="openAddAttModal()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th class="hidden sm:table-cell">Date</th>
                            <th class="hidden md:table-cell">Division</th>
                            <th>Status</th>
                            <th class="hidden md:table-cell">Check In</th>
                            <th class="hidden md:table-cell">Check Out</th>
                            <th class="hidden lg:table-cell">Duration</th>
                            <th>Information</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="attBody"></tbody>
                </table>
            </div>

            <div id="attEmpty" class="empty-state hidden">
                <div class="empty-icon">📋</div>
                <p class="empty-title">No attendance data</p>
                <p class="empty-sub">Change date filter or add manual data</p>
            </div>

            <div class="table-footer">
                <p class="table-info" id="attInfo">— data</p>
                <div class="pagination" id="attPagination"></div>
            </div>
        </div>

    </div><!-- end p-4 -->
</div><!-- end main-content -->
@endsection

@section('modals')
<!-- MODAL ADD ATT -->
<div class="modal-overlay" id="modalAddAtt" onclick="closeModalOutside(event,'modalAddAtt')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div><h3 class="modal-title">Add Attendance Data</h3><p class="modal-sub">Manual attendance input</p></div>

            <button class="modal-close" onclick="closeModal('modalAddAtt')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-field col-2" style="position:relative">
                    <label class="form-label">Employee Name *</label>
                    <input type="text" id="aaName" class="form-input" placeholder="Type to search employee..." autocomplete="off" oninput="filterEmployeeDropdown('aaName','aaEmpId','aaDropdown')">
                    <input type="hidden" id="aaEmpId">
                    <ul id="aaDropdown" class="emp-dropdown" style="display:none"></ul>
                    <span class="form-error" id="eaaName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Date *</label>
                    <input type="date" id="aaDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status *</label>
                    <select id="aaStatus" class="form-select">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Check In</label>
                    <input type="time" id="aaCheckIn" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Check Out</label>
                    <input type="time" id="aaCheckOut" class="form-input">
                </div>
                <div class="form-field col-2">
                    <label class="form-label">Notes</label>
                    <textarea id="aaNote" class="form-input" rows="2" placeholder="Additional notes..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalAddAtt')">Cancel</button>
            <button class="btn-primary" onclick="saveAtt()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save
            </button>
        </div>
    </div>
</div>

<!-- MODAL EDIT ATT -->
<div class="modal-overlay" id="modalEditAtt" onclick="closeModalOutside(event,'modalEditAtt')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div><h3 class="modal-title">Correct Attendance</h3><p class="modal-sub">Edit attendance data</p></div>
            <button class="modal-close" onclick="closeModal('modalEditAtt')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="eaId">
            <div class="form-grid">
                <div class="form-field col-2">
                    <label class="form-label">Employee Name</label>
                    <input type="text" id="eaName" class="form-input" readonly style="background:#f8fafc;color:#64748b;cursor:not-allowed">
                </div>
                <div class="form-field">
                    <label class="form-label">Date</label>
                    <input type="date" id="eaDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status *</label>
                    <select id="eaStatus" class="form-select">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Check In</label>
                    <input type="time" id="eaCheckIn" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Check Out</label>
                    <input type="time" id="eaCheckOut" class="form-input">
                </div>
                <div class="form-field col-2" id="eaInfoWrap">
                    <label class="form-label">Information</label>
                    <textarea id="eaInformation" class="form-input" rows="2"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalEditAtt')">Cancel</button>
            <button class="btn-primary" onclick="updateAtt()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal-overlay" id="modalDelAtt" onclick="closeModalOutside(event,'modalDelAtt')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="del-icon-wrap"><div class="del-icon">🗑</div></div>
        <h3 class="del-title">Delete Attendance Data?</h3>
        <p class="del-sub" id="delAttMsg">Data will be permanently deleted.</p>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn-ghost" onclick="closeModal('modalDelAtt')">Cancel</button>
            <button class="btn-danger" onclick="execDelAtt()">Yes, Delete</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const ATTENDANCE_DATA_URL = "{{ route('admin-hr.attendance.data') }}";
    const ATTENDANCE_STATS_URL = "{{ route('admin-hr.attendance.stats') }}";
    const ATTENDANCE_STORE_URL = "{{ route('admin-hr.attendance.store') }}";
    const ATTENDANCE_UPDATE_URL = "{{ url('admin-hr/attendance/update') }}"; // We'll append ID in JS
    const ATTENDANCE_DELETE_URL = "{{ url('admin-hr/attendance/delete') }}"; // We'll append ID in JS
</script>
<script src="{{ asset('js/Admin_HR/attendance.js') }}"></script>
<script src="{{ asset('js/Admin_HR/attendance_qr.js') }}"></script>
@endsection