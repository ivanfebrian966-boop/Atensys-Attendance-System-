<!DOCTYPE html>
@extends('Admin_HR.layouts.main')

@section('title', 'Leave Management — ATTENSYS')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/attendance.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/leaves.css') }}">
@endsection

@section('main_structure')
@include('Admin_HR.components.sidebar')

<div class="main-content">
    @include('Admin_HR.components.topbar', [
        'pageTitle'    => 'Leave Management',
        'pageSubtitle' => 'Review & manage all employee leave, and sick requests',
    ])

    <div class="p-4 md:p-6">

        {{-- ===== STAT CARDS ===== --}}
        <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="stat-card indigo fade-up d1">
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
                                <span class="status-badge {{ $perm->type === 'Sick' ? 'type-sick' : 'type-leave' }}">
                                    {{ $perm->type === 'Sick' ? '🏥 Sick' : '🏖️ Leave' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-sm text-slate-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($perm->start_date)->format('d M') }} — 
                                    {{ \Carbon\Carbon::parse($perm->completion_date)->format('d M Y') }}
                                    @if($perm->start_time && $perm->end_time)
                                        <br><span class="text-xs text-slate-400">🕒 {{ \Carbon\Carbon::parse($perm->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($perm->end_time)->format('H:i') }}</span>
                                    @endif
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
                        <option value="Leave">🏖️ Leave</option>
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
                            <th>Reason</th>
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

        <div id="manageLockedMessage" class="hidden bg-amber-50 text-amber-700 border border-amber-200 rounded-lg p-3 text-sm mb-4 flex items-start gap-2">
            <span>🔒</span>
            <span>This request is locked because it is older than 3 days.</span>
        </div>

        <form id="manageForm" onsubmit="submitManageForm(event)" novalidate>
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
                 <textarea id="manageRejectReason" class="form-input" rows="3" placeholder="Enter reason for rejection..."></textarea>
                <p id="manageRejectError" class="text-xs text-red-500 mt-1 hidden">Reason is required</p>
            </div>

            <div class="form-group" id="manageApprovalReasonGroup" style="display:none;">
                <textarea id="manageApprovalReason" class="form-input" rows="3" placeholder="Enter reason for approval..."></textarea>
                <p id="manageApprovalError" class="text-xs text-red-500 mt-1 hidden">Reason is required</p>
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

        <form id="rejectForm" onsubmit="submitRejectForm(event)" novalidate>
            <input type="hidden" id="rejectId">
            
            <div class="form-group">
                <label class="form-label">Reason <span class="text-red-500">*</span></label>
                <textarea id="rejectReason" class="form-input" rows="3" required placeholder="Enter reason for rejection..."></textarea>
                <p id="rejectError" class="text-xs text-red-500 mt-1 hidden">Reason is required</p>
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
    <div class="manage-box" onclick="event.stopPropagation()">
        <h3 class="text-lg font-bold text-slate-800 font-sora mb-1">Approve Leave Request</h3>
        <p class="text-sm text-slate-500 mb-4">Please provide a reason for approving this request.</p>

        <form id="approveForm" onsubmit="submitApproveForm(event)" novalidate>
            <div class="form-group">
                <label class="form-label">Approval Reason <span class="text-red-500">*</span></label>
                <textarea id="approvalReason" class="form-input" rows="3" required placeholder="Enter reason for approval..."></textarea>
                <p id="approveError" class="text-xs text-red-500 mt-1 hidden">Reason is required</p>
            </div>

            <div class="flex gap-2 justify-end mt-6">
                <button type="button" class="btn-ghost" onclick="closeApproveModal()">Cancel</button>
                <button type="submit" class="btn-primary" id="confirmApproveBtn" style="background:linear-gradient(135deg,#10b981,#059669); border:none; color:white;">Confirm Approve</button>
            </div>
        </form>
    </div>
</div>

{{-- TOAST --}}


@endsection

@section('scripts')
<script>
    const LEAVE_DATA_URL   = "{{ route('admin-hr.leaves.data') }}";
    const LEAVE_BASE_URL   = "{{ url('admin-hr/leaves') }}";
    const LEAVE_APPROVE    = (id) => `${LEAVE_BASE_URL}/${id}/approve`;
    const LEAVE_REJECT     = (id) => `${LEAVE_BASE_URL}/${id}/reject`;
    const LEAVE_DELETE     = (id) => `${LEAVE_BASE_URL}/${id}`;
    const MARK_ABSENT_URL  = "{{ route('admin-hr.leaves.mark-absent') }}";
    const CSRF             = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/Admin_HR/leaves.js') }}"></script>
@endsection
