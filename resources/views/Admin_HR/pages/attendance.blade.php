<!DOCTYPE html>
@extends('Admin_HR.layouts.main')
 
@section('title', 'Attendance — ATTENSYS')
 
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/attendance.css') }}">
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

        @php
            $todayHolidayObj = \App\Models\HolidayDate::where('date', today()->toDateString())->first();
        @endphp

        {{-- ── HOLIDAY BANNER (shown only if today is a holiday) ── --}}
        @if($todayHolidayObj)
        <div class="fade-up d1 mb-4" style="background:linear-gradient(135deg,#fee2e2,#fef2f2);border:1.5px solid #fca5a5;border-radius:16px;padding:16px 20px;display:flex;align-items:center;gap:14px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#ef4444;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:22px;">🎉</div>
            <div style="flex:1;">
                <p style="font-size:15px;font-weight:700;color:#b91c1c;margin:0 0 3px;">Today: {{ $todayHolidayObj->name }}</p>
                <p style="font-size:12px;color:#ef4444;margin:0;">The attendance scanning system is <strong>closed</strong>. All employees without leave are marked as present.</p>
            </div>
            <a href="{{ route('admin-hr.holidays') }}" style="flex-shrink:0;font-size:12px;font-weight:600;color:#b91c1c;text-decoration:none;background:#fff;border:1px solid #fca5a5;border-radius:8px;padding:6px 12px;white-space:nowrap;">
                Manage Holidays
            </a>
        </div>
        @endif

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
            <div class="stat-card sky fade-up d5">
                <div class="stat-icon" style="background:#f0f9ff">🏥</div>
                <p class="stat-value text-sky-500" id="sSick">0</p>
                <p class="stat-label">Sick</p>
            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon" style="background:#faf5ff">📋</div>
                <p class="stat-value text-purple-500" id="sPerm">0</p>
                <p class="stat-label">Leave</p>
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
                        <option value="Permission">Permission</option>
                    </select>
                    <select id="filterAttDiv" class="filter-select" onchange="filterAtt()">
                        <option value="">All Divisions</option>
                        @foreach($divisions as $div)
                        <option value="{{ $div->division_name }}">{{ $div->division_name }}</option>
                        @endforeach
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
 
    </div>
</div>
@endsection
 
@section('modals')
 
{{-- ===== MODAL ADD ATT ===== --}}
<div class="modal-overlay" id="modalAddAtt" onclick="closeModalOutside(event,'modalAddAtt')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:480px;width:100%;">
 
        {{-- Header --}}
        <div class="modal-header" style="padding:16px 20px 14px;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:16px;height:16px;color:#4f46e5;stroke:#4f46e5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title" style="font-size:15px;margin:0;">Add Attendance</h3>
                    <p class="modal-sub" style="font-size:12px;margin:0;">Manual entry</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('modalAddAtt')" style="width:28px;height:28px;border-radius:6px;border:1px solid #e2e8f0;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;color:#94a3b8;">✕</button>
        </div>
 
        {{-- Body --}}
        <div class="modal-body" style="padding:16px 20px;">
 
            {{-- Employee Search --}}
            <div style="position:relative;margin-bottom:14px;">
                <label class="form-label" style="font-size:12px;font-weight:500;color:#64748b;margin-bottom:5px;display:block;">Employee</label>
                {{-- Employee preview (shown after select) --}}
                <div id="aaEmpPreview" style="display:none;align-items:center;gap:8px;padding:8px 10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:6px;">
                    <div id="aaEmpAvatar" style="width:28px;height:28px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:600;color:#4f46e5;flex-shrink:0;"></div>
                    <div style="flex:1;min-width:0;">
                        <p id="aaEmpNameDisplay" style="font-size:13px;font-weight:500;color:#1e293b;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"></p>
                        <p id="aaEmpSubDisplay" style="font-size:11px;color:#94a3b8;margin:0;"></p>
                    </div>
                    <button onclick="clearEmployeeSelection()" style="background:none;border:none;cursor:pointer;color:#94a3b8;padding:2px;line-height:1;font-size:14px;" title="Clear">✕</button>
                </div>
                <input type="text" id="aaName" class="form-input" placeholder="Search by name or NIP..."
                    autocomplete="off" style="font-size:13px;"
                    oninput="filterEmployeeDropdown('aaName','aaEmpId','aaDropdown')">
                <input type="hidden" id="aaEmpId">
                <ul id="aaDropdown" class="emp-dropdown" style="display:none"></ul>
                <span class="form-error" id="eaaName"></span>
            </div>
 
            {{-- Date (Full Width) --}}
            <div style="margin-bottom:14px;">
                <label class="form-label" style="font-size:12px;font-weight:500;color:#64748b;margin-bottom:5px;display:block;">Date</label>
                <input type="date" id="aaDate" class="form-input" style="font-size:13px;">
                <span class="form-error" id="eaaDate"></span>
            </div>
 
            {{-- Checkout restriction banner (hidden by default, shown by JS) --}}
            <div id="aaCheckoutBanner" style="display:none;align-items:center;gap:10px;padding:10px 14px;background:#fffbeb;border:1.5px solid #fcd34d;border-radius:10px;margin-bottom:12px;">
                <span style="font-size:18px;flex-shrink:0;">⏰</span>
                <p style="font-size:12px;color:#92400e;margin:0;line-height:1.5;">
                    <strong>Checkout is not available yet.</strong><br>
                    Today's checkout data can only be entered after <strong>17:00</strong>.
                </p> 
            </div>

            {{-- Time Inputs (Side by side) --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:6px;">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;">
                    <p style="font-size:11px;font-weight:500;color:#94a3b8;margin:0 0 3px;">CHECK IN</p>
                    <input type="time" id="aaCheckIn" style="border:none;background:transparent;font-size:18px;font-weight:500;color:#1e293b;outline:none;width:100%;padding:0;">
                </div>
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;">
                    <p style="font-size:11px;font-weight:500;color:#94a3b8;margin:0 0 3px;">CHECK OUT</p>
                    <input type="time" id="aaCheckOut" style="border:none;background:transparent;font-size:18px;font-weight:500;color:#1e293b;outline:none;width:100%;padding:0;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <span class="form-error" id="eaaCheckIn" style="display:block;"></span>
                <span class="form-error" id="eaaCheckOut" style="display:block;"></span>
            </div>
 
        </div>
 
        {{-- Footer --}}
        <div class="modal-footer" style="padding:12px 20px;gap:8px;">
            <button class="btn-ghost" onclick="closeModal('modalAddAtt')" style="font-size:13px;">Cancel</button>
            <button class="btn-primary" onclick="saveAtt()" style="font-size:13px;display:flex;align-items:center;gap:6px;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save
            </button>
        </div>
    </div>
</div>
 
{{-- ===== MODAL EDIT ATT ===== --}}
<div class="modal-overlay" id="modalEditAtt" onclick="closeModalOutside(event,'modalEditAtt')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:480px;width:100%;">
        <div class="modal-header" style="padding:16px 20px 14px;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#fef9c3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:16px;height:16px;stroke:#ca8a04;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="modal-title" style="font-size:15px;margin:0;">Correct Attendance</h3>
                    <p class="modal-sub" style="font-size:12px;margin:0;">Edit attendance data</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('modalEditAtt')" style="width:28px;height:28px;border-radius:6px;border:1px solid #e2e8f0;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;color:#94a3b8;">✕</button>
        </div>
        <div class="modal-body" style="padding:16px 20px;">
            <input type="hidden" id="eaId">
 
            {{-- Employee (readonly) --}}
            <div style="margin-bottom:14px;">
                <label class="form-label" style="font-size:12px;font-weight:500;color:#64748b;margin-bottom:5px;display:block;">Employee</label>
                <input type="text" id="eaName" class="form-input" readonly
                    style="background:#f8fafc;color:#64748b;cursor:not-allowed;font-size:13px;">
            </div>
 
            {{-- Date (Full Width, readonly) --}}
            <div style="margin-bottom:14px;">
                <label class="form-label" style="font-size:12px;font-weight:500;color:#64748b;margin-bottom:5px;display:block;">Date</label>
                <input type="date" id="eaDate" class="form-input" readonly
                    style="background:#f8fafc;color:#64748b;cursor:not-allowed;font-size:13px;">
            </div>
 
            {{-- Check In + Check Out side by side --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:6px;">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;">
                    <p style="font-size:11px;font-weight:500;color:#94a3b8;margin:0 0 3px;">CHECK IN</p>
                    <input type="time" id="eaCheckIn" style="border:none;background:transparent;font-size:18px;font-weight:500;color:#1e293b;outline:none;width:100%;padding:0;">
                </div>
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;position:relative;">
                    <p style="font-size:11px;font-weight:500;color:#94a3b8;margin:0 0 3px;">CHECK OUT</p>
                    <input type="time" id="eaCheckOut" style="border:none;background:transparent;font-size:18px;font-weight:500;color:#1e293b;outline:none;width:100%;padding:0;">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <span class="form-error" id="eeaCheckIn" style="display:block;"></span>
                <span class="form-error" id="eeaCheckOut" style="display:block;"></span>
            </div>

            {{-- Checkout restriction banner for Edit modal (hidden by default) --}}
            <div id="eaCheckoutBanner" style="display:none;align-items:center;gap:10px;padding:10px 14px;background:#fffbeb;border:1.5px solid #fcd34d;border-radius:10px;margin-top:12px;">
                <span style="font-size:18px;flex-shrink:0;">⏰</span>
                <p style="font-size:12px;color:#92400e;margin:0;line-height:1.5;">
                    <strong>Check-out is locked.</strong><br>
                    Today's checkout data can only be edited after <strong>17:00</strong>.
                </p>
            </div>
        </div>
        <div class="modal-footer" style="padding:12px 20px;gap:8px;">
            <button class="btn-ghost" onclick="closeModal('modalEditAtt')" style="font-size:13px;">Cancel</button>
            <button class="btn-primary" onclick="updateAtt()" style="font-size:13px;display:flex;align-items:center;gap:6px;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Changes
            </button>
        </div>
    </div>
</div>
 
{{-- ===== MODAL DELETE ===== --}}
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
    const ATTENDANCE_DATA_URL   = "{{ route('admin-hr.attendance.data') }}";
    const ATTENDANCE_STATS_URL  = "{{ route('admin-hr.attendance.stats') }}";
    const ATTENDANCE_STORE_URL  = "{{ route('admin-hr.attendance.store') }}";
    const ATTENDANCE_UPDATE_URL = "{{ url('admin-hr/attendance/update') }}";
    const ATTENDANCE_DELETE_URL = "{{ url('admin-hr/attendance/delete') }}";
</script>
<script src="{{ asset('js/Admin_HR/attendance.js') }}"></script>
<script src="{{ asset('js/Admin_HR/attendance_qr.js') }}"></script>
@endsection
 