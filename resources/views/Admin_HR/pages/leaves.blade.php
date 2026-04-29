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
        .badge-accepted { background: #ecfdf5; color: #059669; }
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

        /* ─── Toast ─── */
        #leaveToast {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            padding: 12px 20px; border-radius: 14px; font-size: 0.875rem;
            font-weight: 600; font-family: 'Sora', sans-serif;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            transform: translateY(80px); opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
        }
        #leaveToast.show { transform: translateY(0); opacity: 1; }
        #leaveToast.success { background: #ecfdf5; color: #059669; border: 1.5px solid #a7f3d0; }
        #leaveToast.error   { background: #fef2f2; color: #ef4444;  border: 1.5px solid #fecaca; }

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
                <p class="stat-value text-emerald-600" id="sc-accepted">{{ $stats['accepted'] }}</p>
                <p class="stat-label">Accepted</p>
            </div>
            <div class="stat-card red fade-up d4">
                <div class="stat-icon" style="background:#fef2f2">❌</div>
                <p class="stat-value text-red-500" id="sc-rejected">{{ $stats['rejected'] }}</p>
                <p class="stat-label">Rejected</p>
            </div>
            <div class="stat-card blue fade-up d5">
                <div class="stat-icon" style="background:#eff6ff">🏥</div>
                <p class="stat-value text-blue-500" id="sc-sick">{{ $stats['sick'] }}</p>
                <p class="stat-label">Sick</p>
            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon" style="background:#faf5ff">🏖️</div>
                <p class="stat-value text-purple-500" id="sc-permission">{{ $stats['permission'] }}</p>
                <p class="stat-label">Permission</p>
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
                                    {{ $perm->type === 'Sick' ? '🏥' : '🏖️' }} {{ $perm->type }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-slate-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($perm->start_date)->format('d M') }} — 
                                    {{ \Carbon\Carbon::parse($perm->completion_date)->format('d M Y') }}
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-xs text-slate-500 info-cell" title="{{ $perm->information }}">
                                    {{ $perm->information }}
                                </p>
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
                                    <button class="btn-approve" onclick="doApprove({{ $perm->permission_id }})">✓ ACC</button>
                                    <button class="btn-reject" onclick="doReject({{ $perm->permission_id }})">✕ Reject</button>
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
                        <option value="Permission">🏖️ Permission</option>
                    </select>
                </div>
                <div class="filter-group">
                    <span class="filter-label">Status</span>
                    <select id="filterStatus" class="filter-select" onchange="loadLeaves()">
                        <option value="all">All Status</option>
                        <option value="Pending">⏳ Pending</option>
                        <option value="Accepted">✅ Accepted</option>
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
                <select id="manageStatus" class="form-select" required>
                    <option value="Pending">Pending</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>

            <div class="flex gap-2 justify-end mt-6">
                <button type="button" class="btn-ghost" onclick="closeManageModal()">Cancel</button>
                <button type="submit" class="btn-primary" id="manageSubmitBtn">Save Changes</button>
            </div>
        </form>
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
<div id="leaveToast"></div>

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

// ─── Toast ───────────────────────────────────
function showToast(msg, type = 'success') {
    const t = document.getElementById('leaveToast');
    t.textContent = (type === 'success' ? '✅ ' : '❌ ') + msg;
    t.className   = `show ${type}`;
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.className = type, 3000);
}

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
                        ${row.type === 'Sick' ? '🏥' : '🏖️'} ${row.type}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <p class="info-cell text-xs text-slate-600" title="${row.information}">${row.information || '—'}</p>
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
                    <div class="flex justify-end items-center gap-2 flex-wrap">
                        <button class="btn-approve" style="color:#6366f1; background:#eef2ff; border-color:#c7d2fe; padding: 5px 8px;" onclick='openManageModal(${JSON.stringify(row).replace(/'/g, "&#39;")})' title="Manage">✎</button>
                        <button class="btn-delete" onclick="openDeleteModal(${row.id})" title="Delete">🗑</button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        renderLeavePagination(_allLeaves.length);
}

function statusBadge(status) {
    const map = { Pending:'badge-pending', Accepted:'badge-accepted', Rejected:'badge-rejected' };
    const icon = { Pending:'⏳', Accepted:'✅', Rejected:'❌' };
    return `<span class="px-3 py-1 rounded-full text-xs font-bold ${map[status]||'bg-slate-100 text-slate-600'}">${icon[status]??''} ${status}</span>`;
}

// ─── Quick Approve / Reject ──────────────────
async function doApprove(id) {
    if (!confirm('Approve this leave request? This will create attendance records.')) return;
    try {
        const res  = await fetch(LEAVE_APPROVE(id), { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) setTimeout(() => window.location.reload(), 800);
    } catch(e) { showToast('Network error: ' + e.message, 'error'); }
}

async function doReject(id) {
    if (!confirm('Reject this leave request?')) return;
    try {
        const res  = await fetch(LEAVE_REJECT(id), { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) setTimeout(() => window.location.reload(), 800);
    } catch(e) { showToast('Network error: ' + e.message, 'error'); }
}

// ─── Manage ──────────────────────────────────
function openManageModal(row) {
    document.getElementById('manageId').value = row.id;
    document.getElementById('manageEmployeeName').textContent = `${row.name} - ${row.type}`;
    document.getElementById('manageStart').value = row.start_raw;
    document.getElementById('manageEnd').value = row.end_raw;
    document.getElementById('manageStatus').value = row.status;
    document.getElementById('manageModal').style.display = 'flex';
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

    try {
        const url = `{{ url('admin-hr/leaves') }}/${id}`;
        const res = await fetch(url, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ start_date, completion_date, status })
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
    closeDeleteModal();
    try {
        const res  = await fetch(LEAVE_DELETE(deleteTargetId), { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) setTimeout(() => window.location.reload(), 800);
    } catch(e) { showToast('Network error: ' + e.message, 'error'); }
}

document.addEventListener('DOMContentLoaded', loadLeaves);
</script>
@endsection
