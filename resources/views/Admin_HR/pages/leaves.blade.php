<!DOCTYPE html>
@extends('Admin_HR.layouts.main')

@section('title', 'Leave Management — ATTENSYS')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/attendance.css') }}">
    <style>
        /* ─── Filter Bar ─── */
        .filter-bar {
            display: flex; flex-wrap: wrap; gap: 12px; align-items: center;
            padding: 16px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
        }
        .filter-group { display: flex; align-items: center; gap: 8px; }
        .filter-label { font-size: 0.75rem; font-weight: 600; color: #94a3b8; font-family: 'Sora', sans-serif; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        .filter-select {
            padding: 7px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.82rem; color: #334155; background: white; outline: none;
            cursor: pointer; transition: border-color 0.2s; font-family: 'DM Sans', sans-serif;
            min-width: 130px;
        }
        .filter-select:focus { border-color: #6366f1; }

        .search-wrap-lg {
            display: flex; align-items: center; gap: 8px;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            background: white; padding: 7px 12px; flex: 1; min-width: 200px;
            transition: border-color 0.2s;
        }
        .search-wrap-lg:focus-within { border-color: #6366f1; }
        .search-wrap-lg input {
            border: none; outline: none; font-size: 0.82rem;
            color: #334155; width: 100%; font-family: 'DM Sans', sans-serif; background: transparent;
        }
        .search-wrap-lg svg { color: #94a3b8; flex-shrink: 0; }

        /* ─── Type Badge ─── */
        .type-sick       { background: #eff6ff; color: #3b82f6; }
        .type-permission { background: #faf5ff; color: #8b5cf6; }

        /* ─── Status Badges ─── */
        .badge-pending  { background: #fffbeb; color: #d97706; }
        .badge-approved { background: #ecfdf5; color: #059669; }
        .badge-rejected { background: #fef2f2; color: #ef4444; }

        /* ─── Action buttons ─── */
        .btn-approve {
            padding: 5px 12px; background: #ecfdf5; color: #059669;
            border: 1.5px solid #a7f3d0; border-radius: 8px; font-size: 0.75rem;
            font-weight: 700; cursor: pointer; transition: all 0.2s;
            font-family: 'Sora', sans-serif; white-space: nowrap;
        }
        .btn-approve:hover { background: #059669; color: white; border-color: #059669; }
        .btn-reject {
            padding: 5px 12px; background: #fef2f2; color: #ef4444;
            border: 1.5px solid #fecaca; border-radius: 8px; font-size: 0.75rem;
            font-weight: 700; cursor: pointer; transition: all 0.2s;
            font-family: 'Sora', sans-serif; white-space: nowrap;
        }
        .btn-reject:hover { background: #ef4444; color: white; border-color: #ef4444; }
        .btn-delete {
            padding: 5px 10px; background: #f8fafc; color: #94a3b8;
            border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 0.75rem;
            font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        .btn-delete:hover { background: #fef2f2; color: #ef4444; border-color: #fecaca; }

        /* ─── Info cell ─── */
        .info-cell { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ─── Days badge ─── */
        .days-badge { background: #f1f5f9; color: #475569; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; }

        /* ─── Loading skeleton ─── */
        .skeleton-cell { height: 14px; border-radius: 8px; background: linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%); background-size: 200% 100%; animation: shimmer 1.4s infinite; }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        /* ─── File link ─── */
        .file-link { color: #6366f1; font-size: 0.78rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
        .file-link:hover { color: #4338ca; text-decoration: underline; }

        /* ─── Delete confirm modal ─── */
        #deleteConfirmModal {
            position: fixed; inset: 0; background: rgba(15,23,42,0.5);
            display: none; align-items: center; justify-content: center; z-index: 9998;
            backdrop-filter: blur(4px);
        }
        #deleteConfirmModal.open { display: flex; }
        .del-box {
            background: white; border-radius: 20px; padding: 32px 28px;
            width: 380px; max-width: 90vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            text-align: center; animation: popIn 0.25s ease;
        }
        @keyframes popIn { from{transform:scale(0.92);opacity:0} to{transform:scale(1);opacity:1} }
        .del-icon-big { font-size: 40px; margin-bottom: 12px; }
        .del-title { font-size: 1rem; font-weight: 700; color: #0f172a; font-family: 'Sora', sans-serif; margin-bottom: 6px; }
        .del-sub { font-size: 0.84rem; color: #64748b; margin-bottom: 24px; }
        .del-actions { display: flex; gap: 12px; justify-content: center; }
        /* ─── Manage modal ─── */
        .manage-box {
            background: white; border-radius: 20px; padding: 24px 28px;
            width: 440px; max-width: 90vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: popIn 0.25s ease;
        }
        .form-group { margin-bottom: 16px; text-align: left; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #475569; margin-bottom: 6px; font-family: 'Sora', sans-serif; }
        .form-input, .form-select {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: 0.85rem; color: #334155; outline: none; transition: border-color 0.2s;
            font-family: 'DM Sans', sans-serif; background: #f8fafc;
        }
        .form-input:focus, .form-select:focus { border-color: #6366f1; background: white; }
    </style>
@endsection

@section('main_structure')
@include('Admin_HR.components.sidebar')

<div class="main-content">
    @include('Admin_HR.components.topbar', [
        'pageTitle'    => 'Leave Management',
        'pageSubtitle' => 'Review & manage all employee leave, sick, and permission requests',
    ])

    <div class="p-4 md:p-6">

        {{-- ===== STAT CARDS ===== --}}
        <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="stat-card fade-up d1">
                <div class="stat-icon" style="background:#eef2ff">📋</div>
                <p class="stat-value text-slate-800" id="sc-total">{{ $stats['total'] }}</p>
                <p class="stat-label">Total</p>
            </div>
            <div class="stat-card yellow fade-up d2">
                <div class="stat-icon" style="background:#fffbeb">⏳</div>
                <div class="flex items-center gap-2">
                    <p class="stat-value text-amber-500" id="sc-pending">{{ $stats['pending'] }}</p>
                    @if($stats['pending'] > 0)
                    @endif
                </div>
                <p class="stat-label">Pending</p>
            </div>
            <div class="stat-card green fade-up d3">
                <div class="stat-icon" style="background:#ecfdf5">✅</div>
                <p class="stat-value text-emerald-600" id="sc-approved">{{ $stats['approved'] }}</p>
                <p class="stat-label">Approved</p>
            </div>
            <div class="stat-card red fade-up d4">
                <div class="stat-icon" style="background:#fef2f2">❌</div>
                <p class="stat-value text-red-500" id="sc-rejected">{{ $stats['rejected'] }}</p>
                <p class="stat-label">Rejected</p>
            </div>
            <div class="stat-card sky fade-up d5">
                <div class="stat-icon" style="background:#f0f9ff">🏥</div>
                <p class="stat-value text-sky-500" id="sc-sick">{{ $stats['sick'] }}</p>
                <p class="stat-label">Sick</p>
            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon" style="background:#faf5ff">📝</div>
                <p class="stat-value text-purple-500" id="sc-perm">{{ $stats['leave'] }}</p>
                <p class="stat-label">Leave</p>
            </div>
        </div>

        {{-- ===== PENDING LEAVE REQUESTS ===== --}}
        <div class="panel fade-up d2 mb-6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title text-amber-600">Pending Leave Requests</h3>
                    <p class="panel-subtitle">Needs your immediate review</p>
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
                        @php
                            $rowJson = json_encode([
                                'id' => $perm->permission_id,
                                'name' => $perm->employee->name ?? 'Unknown',
                                'type' => $perm->type,
                                'start_raw' => \Carbon\Carbon::parse($perm->start_date)->format('Y-m-d'),
                                'end_raw' => \Carbon\Carbon::parse($perm->completion_date)->format('Y-m-d'),
                                'status' => $perm->status
                            ]);
                        @endphp
                        <tr class="table-row">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                                        {{ substr($perm->employee->name ?? '?', 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm font-sora">{{ $perm->employee->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-slate-400">{{ $perm->employee->division->division_name ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="status-badge {{ $perm->type === 'Sick' ? 'type-sick' : 'type-permission' }}">
                                    {{ $perm->type === 'Sick' ? '🏥 Sick' : '🏖️ Permission' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-slate-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($perm->start_date)->format('d M') }} — 
                                    {{ \Carbon\Carbon::parse($perm->completion_date)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-xs font-bold text-slate-700 mb-1">
                                    {{ $perm->leave_category ?: $perm->sick_category ?: '-' }}
                                </p>
                                @if($perm->information && $perm->information !== '-')
                                <p class="text-[10px] text-slate-500 info-cell" title="{{ $perm->information }}">
                                    {{ $perm->information }}
                                </p>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($perm->file)
                                    <a href="{{ asset('storage/' . $perm->file) }}" target="_blank" class="file-link">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        PDF
                                    </a>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="btn-approve" onclick="doApprove({{ $perm->permission_id }})">✓ Approve</button>
                                    <button class="btn-reject" onclick="doReject({{ $perm->permission_id }})">✕ Reject</button>
                                    <button class="bg-red-50 text-red-500 hover:bg-red-100 p-2 rounded-lg transition" onclick="openDeleteModal({{ $perm->permission_id }})" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
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
            @if(method_exists($pendingPermissions, 'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $pendingPermissions->links() }}
            </div>
            @endif
        </div>

        {{-- ===== ALL LEAVE TABLE ===== --}}
        <div class="panel fade-up d3">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">All Leave Requests</h3>
                    <p class="panel-subtitle">Manage leave status and dates</p>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="filter-bar">
                <div class="filter-group">
                    <span class="filter-label">Type</span>
                    <select id="filterType" class="filter-select" onchange="loadLeaves()">
                        <option value="all">All Types</option>
                        <option value="Sick">🏥 Sick</option>
                        <option value="Leave">🏖️ Permission</option>
                    </select>
                </div>
                <div class="filter-group">
                    <span class="filter-label">Status</span>
                    <select id="filterStatus" class="filter-select" onchange="loadLeaves()">
                        <option value="all">All Status</option>
                        <option value="Pending">⏳ Pending</option>
                        <option value="Approved">✅ Approved</option>
                        <option value="Rejected">❌ Rejected</option>
                    </select>
                </div>
                <div class="search-wrap-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="leaveSearch" placeholder="Search employee name or NIP..." oninput="loadLeaves()">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Category / Reason</th>
                            <th>Date Range</th>
                            <th>Days</th>
                            <th>Attachment</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Reject Reason</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="leaveBody">
                        {{-- Skeleton rows --}}
                        @for($i = 0; $i < 4; $i++)
                        <tr>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:130px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:70px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:150px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:110px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:40px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:40px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:90px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:70px"></div></td>
                            <td class="py-3 px-4"><div class="skeleton-cell" style="width:100px"></div></td>
                            <td></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <div id="leaveEmpty" class="hidden text-center py-12">
                <div class="text-4xl mb-3 opacity-30">📭</div>
                <p class="text-sm font-bold text-slate-600 font-sora">No leave requests found</p>
                <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or search term.</p>
            </div>

            <div class="table-footer">
                <p class="table-info" id="leaveInfo">Loading…</p>
            </div>
        </div>

    </div>
</div>

{{-- MANAGE MODAL --}}
<div id="manageModal" onclick="closeManageModal()" style="position:fixed; inset:0; background:rgba(15,23,42,0.5); display:none; align-items:center; justify-content:center; z-index:9998; backdrop-filter:blur(4px);">
    <div class="manage-box" onclick="event.stopPropagation()">
        <h3 class="text-lg font-bold text-slate-800 font-sora mb-1">Manage Leave Request</h3>
        <p class="text-sm text-slate-500 mb-4" id="manageEmployeeName">Employee Name</p>

        <form id="manageForm" onsubmit="submitManageForm(event)">
            <input type="hidden" id="manageId">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" id="manageStart" class="form-input" readonly style="background:#f1f5f9; cursor:not-allowed;">
                </div>
                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input type="date" id="manageEnd" class="form-input" readonly style="background:#f1f5f9; cursor:not-allowed;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="manageStatus" class="form-select" required onchange="toggleManageRejectReason()">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>

            <div class="form-group" id="manageRejectReasonGroup" style="display:none;">
                <label class="form-label">Rejection Reason</label>
                <textarea id="manageRejectReason" class="form-input" rows="3" placeholder="Enter reason for rejection..."></textarea>
            </div>

            <div class="flex gap-2 justify-end mt-6">
                <button type="button" class="btn-ghost" onclick="closeManageModal()">Cancel</button>
                <button type="submit" class="btn-primary" id="manageSubmitBtn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

{{-- REJECT REASON MODAL --}}
<div id="rejectModal" onclick="closeRejectModal()" style="position:fixed; inset:0; background:rgba(15,23,42,0.5); display:none; align-items:center; justify-content:center; z-index:9998; backdrop-filter:blur(4px);">
    <div class="manage-box" onclick="event.stopPropagation()">
        <h3 class="text-lg font-bold text-slate-800 font-sora mb-1">Reject Leave Request</h3>
        <p class="text-sm text-slate-500 mb-4">Please provide a reason for rejecting this request.</p>

        <form id="rejectForm" onsubmit="submitRejectForm(event)">
            <input type="hidden" id="rejectId">
            
            <div class="form-group">
                <label class="form-label">Reason</label>
                <textarea id="rejectReason" class="form-input" rows="3" required placeholder="Enter reason for rejection..."></textarea>
            </div>

            <div class="flex gap-2 justify-end mt-6">
                <button type="button" class="btn-ghost" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn-danger" id="rejectSubmitBtn">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

{{-- APPROVE CONFIRM MODAL --}}
<div id="approveConfirmModal" onclick="closeApproveModal()" style="position:fixed; inset:0; background:rgba(15,23,42,0.5); display:none; align-items:center; justify-content:center; z-index:9998; backdrop-filter:blur(4px);">
    <div class="del-box" onclick="event.stopPropagation()">
        <div class="del-icon-big" style="background:#ecfdf5;color:#10b981">✓</div>
        <p class="del-title">Approve Leave Request?</p>
        <p class="del-sub">This will approve the request and automatically create attendance records for the selected dates.</p>
        <div class="del-actions">
            <button class="btn-ghost" onclick="closeApproveModal()">Cancel</button>
            <button class="btn-primary" id="confirmApproveBtn" onclick="execApprove()" style="background:linear-gradient(135deg,#10b981,#059669); border:none; color:white; padding:10px 20px; border-radius:12px; font-weight:600; cursor:pointer;">Yes, Approve</button>
        </div>
    </div>
</div>

{{-- DELETE CONFIRM MODAL --}}
<div id="deleteConfirmModal" onclick="closeDeleteModal()">
    <div class="del-box" onclick="event.stopPropagation()">
        <div class="del-icon-big">🗑️</div>
        <p class="del-title">Delete Leave Request?</p>
        <p class="del-sub">This action cannot be undone. The request will be permanently removed.</p>
        <div class="del-actions">
            <button class="btn-ghost" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-danger" id="confirmDeleteBtn" onclick="execDelete()">Yes, Delete</button>
        </div>
    </div>
</div>

{{-- TOAST --}}


@endsection

@section('scripts')
<script>
const LEAVE_DATA_URL   = "{{ route('admin-hr.leaves.data') }}";
const LEAVE_APPROVE    = (id) => `{{ url('admin-hr/leaves') }}/${id}/approve`;
const LEAVE_REJECT     = (id) => `{{ url('admin-hr/leaves') }}/${id}/reject`;
const LEAVE_DELETE     = (id) => `{{ url('admin-hr/leaves') }}/${id}`;
const MARK_ABSENT_URL  = "{{ route('admin-hr.leaves.mark-absent') }}";
const CSRF             = "{{ csrf_token() }}";

let deleteTargetId = null;



// ─── Pagination ──────────────────────────────
let currentLeavePage = 1;
const LEAVE_PAGE_SIZE = 10;
let _allLeaves = [];

function changeLeavePage(page) {
    currentLeavePage = page;
    renderLeaves();
}

function renderLeavePagination(totalItems) {
    const totalPages = Math.ceil(totalItems / LEAVE_PAGE_SIZE) || 1;
    let pag = document.getElementById('leavePagination');
    if (!pag) {
        const info = document.getElementById('leaveInfo');
        pag = document.createElement('div');
        pag.id = 'leavePagination';
        pag.className = 'pagination flex gap-1 items-center justify-end w-full';
        info.parentNode.classList.add('flex', 'justify-between', 'items-center', 'w-full');
        info.parentNode.appendChild(pag);
    }
    
    if (totalPages <= 1) {
        pag.innerHTML = '';
        return;
    }
    
    let html = '';
    html += `<button class="px-3 py-1 border rounded text-xs ${currentLeavePage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeLeavePage(${currentLeavePage - 1})" ${currentLeavePage === 1 ? 'disabled' : ''}>Prev</button>`;
    
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentLeavePage - 1 && i <= currentLeavePage + 1)) {
            html += `<button class="px-3 py-1 border rounded text-xs ${i === currentLeavePage ? 'bg-indigo-50 text-indigo-600 border-indigo-200 font-bold' : 'hover:bg-slate-50'}" onclick="changeLeavePage(${i})">${i}</button>`;
        } else if (i === currentLeavePage - 2 || i === currentLeavePage + 2) {
            html += `<span class="px-2 py-1 text-slate-400">...</span>`;
        }
    }
    
    html += `<button class="px-3 py-1 border rounded text-xs ${currentLeavePage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeLeavePage(${currentLeavePage + 1})" ${currentLeavePage === totalPages ? 'disabled' : ''}>Next</button>`;
    pag.innerHTML = html;
}

// ─── Load Table ──────────────────────────────
async function loadLeaves() {
    const type   = document.getElementById('filterType').value;
    const status = document.getElementById('filterStatus').value;
    const search = document.getElementById('leaveSearch').value;
    const params = new URLSearchParams({ type, status, search });

    const tbody = document.getElementById('leaveBody');
    const empty = document.getElementById('leaveEmpty');
    const info  = document.getElementById('leaveInfo');

    try {
        const res  = await fetch(`${LEAVE_DATA_URL}?${params}`);
        const json = await res.json();
        
        if (!json.success) throw new Error(json.message);
        
        _allLeaves = json.data;
        currentLeavePage = 1; // reset page on new load
        renderLeaves();
    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-6 text-red-400 text-sm">Failed to load: ${e.message}</td></tr>`;
        empty.classList.add('hidden');
    }
}

function renderLeaves() {
    const tbody = document.getElementById('leaveBody');
    const empty = document.getElementById('leaveEmpty');
    const info  = document.getElementById('leaveInfo');

    if (!_allLeaves || !_allLeaves.length) {
        tbody.innerHTML = '';
        empty.classList.remove('hidden');
        info.textContent = '0 records';
        renderLeavePagination(0);
        return;
    }

    empty.classList.add('hidden');
    info.textContent = `${_allLeaves.length} record(s)`;
    
    const totalPages = Math.ceil(_allLeaves.length / LEAVE_PAGE_SIZE) || 1;
    if (currentLeavePage > totalPages) currentLeavePage = totalPages;
    const start = (currentLeavePage - 1) * LEAVE_PAGE_SIZE;
    const paginated = _allLeaves.slice(start, start + LEAVE_PAGE_SIZE);

    tbody.innerHTML = paginated.map(row => `
            <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                             style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                            ${row.name.substring(0,2).toUpperCase()}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">${row.name}</p>
                            <p class="text-xs text-slate-400">${row.division}</p>
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4">
                    <span class="status-badge ${row.type === 'Sick' ? 'type-sick' : 'type-permission'}">
                        ${row.type === 'Sick' ? '🏥 Sick' : '🏖️ Permission'}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <p class="text-xs font-bold text-slate-700 mb-1">${row.leave_category || row.sick_category || '-'}</p>
                    ${row.information && row.information !== '-' 
                        ? `<p class="info-cell text-[10px] text-slate-500" title="${row.information}">${row.information}</p>` 
                        : ''}
                </td>
                <td class="py-3 px-4 text-sm text-slate-600 whitespace-nowrap">
                    ${row.start_date} — ${row.end_date}
                </td>
                <td class="py-3 px-4">
                    <span class="days-badge">${row.days}d</span>
                </td>
                <td class="py-3 px-4">
                    ${row.file
                        ? `<a href="${row.file}" target="_blank" class="file-link">
                               <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                               PDF
                           </a>`
                        : `<span class="text-slate-300 text-sm">—</span>`
                    }
                </td>
                <td class="py-3 px-4 text-xs text-slate-400 whitespace-nowrap">${row.submitted}</td>
                <td class="py-3 px-4">${statusBadge(row.status)}</td>
                <td class="py-3 px-4">
                    ${row.status === 'Rejected' && row.reject_reason 
                        ? `<p class="text-[10px] text-red-400 font-semibold italic" title="${row.reject_reason}">${row.reject_reason}</p>` 
                        : '<span class="text-slate-300">—</span>'}
                </td>
                <td class="py-3 px-4">
                    <div class="flex justify-end items-center gap-2 flex-wrap">
                        <button class="btn-approve flex items-center justify-center rounded-lg hover:bg-indigo-100 transition" style="color:#6366f1; background:#eef2ff; border-color:#c7d2fe; padding: 6px; width:32px; height:32px;" onclick='openManageModal(${JSON.stringify(row).replace(/'/g, "&#39;")})' title="Manage">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button class="btn-delete flex items-center justify-center rounded-lg hover:bg-red-50 transition" style="padding: 6px; width:32px; height:32px;" onclick="openDeleteModal(${row.id})" title="Delete">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        renderLeavePagination(_allLeaves.length);
}

function statusBadge(status) {
    const map = { Pending:'badge-pending', Approved:'badge-approved', Rejected:'badge-rejected' };
    const icon = { Pending:'⏳', Approved:'✅', Rejected:'❌' };
    const label = status === 'Approved' ? 'Approved' : status;
    return `<span class="px-3 py-1 rounded-full text-xs font-bold ${map[status]||'bg-slate-100 text-slate-600'}">${icon[status]??''} ${label}</span>`;
}

// ─── Quick Approve / Reject ──────────────────
let approveTargetId = null;

function doApprove(id) {
    approveTargetId = id;
    document.getElementById('approveConfirmModal').style.display = 'flex';
}

function closeApproveModal() {
    document.getElementById('approveConfirmModal').style.display = 'none';
}

async function execApprove() {
    if (!approveTargetId) return;
    const btn = document.getElementById('confirmApproveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Approving...';

    try {
        const res  = await fetch(LEAVE_APPROVE(approveTargetId), { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) {
            closeApproveModal();
            setTimeout(() => window.location.reload(), 800);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'Yes, Approve';
        }
    } catch(e) { 
        showToast('Network error: ' + e.message, 'error');
        btn.disabled = false;
        btn.innerHTML = 'Yes, Approve';
    }
}

// Rejection with Modal
function doReject(id) {
    document.getElementById('rejectId').value = id;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

async function submitRejectForm(e) {
    e.preventDefault();
    const id = document.getElementById('rejectId').value;
    const reason = document.getElementById('rejectReason').value;
    const btn = document.getElementById('rejectSubmitBtn');

    btn.disabled = true;
    btn.textContent = 'Processing...';

    try {
        const res = await fetch(LEAVE_REJECT(id), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ reject_reason: reason })
        });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) {
            closeRejectModal();
            setTimeout(() => window.location.reload(), 800);
        }
    } catch(e) {
        showToast('Network error: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Confirm Reject';
    }
}

// ─── Manage ──────────────────────────────────
function openManageModal(row) {
    document.getElementById('manageId').value = row.id;
    document.getElementById('manageEmployeeName').textContent = `${row.name} - ${row.type}`;
    document.getElementById('manageStart').value = row.start_raw;
    document.getElementById('manageEnd').value = row.end_raw;
    document.getElementById('manageStatus').value = row.status;
    document.getElementById('manageRejectReason').value = row.reject_reason || '';
    
    toggleManageRejectReason();
    document.getElementById('manageModal').style.display = 'flex';
}
function toggleManageRejectReason() {
    const status = document.getElementById('manageStatus').value;
    const group = document.getElementById('manageRejectReasonGroup');
    group.style.display = status === 'Rejected' ? 'block' : 'none';
}
function closeManageModal() {
    document.getElementById('manageModal').style.display = 'none';
}

async function submitManageForm(e) {
    e.preventDefault();
    const btn = document.getElementById('manageSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const id = document.getElementById('manageId').value;
    const start_date = document.getElementById('manageStart').value;
    const completion_date = document.getElementById('manageEnd').value;
    const status = document.getElementById('manageStatus').value;
    const reject_reason = status === 'Rejected' ? document.getElementById('manageRejectReason').value : null;

    try {
        const url = `{{ url('admin-hr/leaves') }}/${id}`;
        const res = await fetch(url, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ start_date, completion_date, status, reject_reason })
        });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) {
            closeManageModal();
            setTimeout(() => window.location.reload(), 800); // Reload to update Pending table
        }
    } catch(err) {
        showToast('Network error: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Changes';
    }
}

// ─── Delete ──────────────────────────────────
function openDeleteModal(id) { deleteTargetId = id; document.getElementById('deleteConfirmModal').classList.add('open'); }
function closeDeleteModal()   { document.getElementById('deleteConfirmModal').classList.remove('open'); deleteTargetId = null; }
async function execDelete() {
    if (!deleteTargetId) return;
    const id = deleteTargetId;
    closeDeleteModal();
    try {
        const res  = await fetch(LEAVE_DELETE(id), { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) setTimeout(() => window.location.reload(), 800);
    } catch(e) { showToast('Network error: ' + e.message, 'error'); }
}

document.addEventListener('DOMContentLoaded', loadLeaves);
</script>
@endsection
