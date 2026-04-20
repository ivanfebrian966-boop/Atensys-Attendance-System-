@extends('Employee.layouts.main')

@section('title', 'Leave Request — ATTENSYS')
@section('page_title', 'Leave Request')
@section('page_subtitle', 'Manage your leave requests')

@section('content')

<!-- Header Title -->
<div class="mb-6 fade-up d1 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-slate-800" style="font-family:'Sora',sans-serif">Leave / Sick Request</h2>
        <p class="text-sm text-slate-500 mt-1">Submit a leave or sick letter along with your PDF proof.</p>
    </div>
    <button onclick="openLeaveModal()" class="btn-primary inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        New Request
    </button>
</div>

<!-- ===== LEAVE REQUEST HISTORY ===== -->
<div class="panel fade-up d2">
    <div class="panel-header border-b border-slate-100 mb-0 pb-4">
        <div>
            <h3 class="panel-title">My Leave Requests</h3>
            <p class="panel-subtitle">History of your permission / sick requests</p>
        </div>
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
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $perm)
                <tr class="table-row border-b border-slate-50 last:border-0 hover:bg-slate-50">
                    <td class="py-3 px-4">
                        <span class="status-badge {{ $perm->type === 'Sakit' ? 'status-sick' : 'status-permission' }}">
                            ● {{ $perm->type === 'Sakit' ? 'Sick' : 'Permission' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="text-sm">
                            {{ \Carbon\Carbon::parse($perm->start_date)->format('d M') }} —
                            {{ \Carbon\Carbon::parse($perm->end_date)->format('d M Y') }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        @php
                            $statusClass = match($perm->status) {
                                'Pending'  => 'bg-amber-100 text-amber-600',
                                'Approved' => 'bg-emerald-100 text-emerald-600',
                                'Rejected' => 'bg-red-100 text-red-600',
                                default    => 'bg-slate-100 text-slate-600'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                            {{ $perm->status }}
                        </span>
                    </td>
                    <td class="py-3 px-4 max-w-xs truncate text-xs text-slate-500" title="{{ $perm->information }}">
                        {{ $perm->information }}
                    </td>
                    <td class="py-3 px-4">
                        @if($perm->file)
                            <a href="{{ asset('storage/' . $perm->file) }}" target="_blank"
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                View PDF
                            </a>
                        @else
                            <span class="text-slate-400 text-sm">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-400 py-8 text-sm">No leave requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('modals')
<!-- ===== LEAVE REQUEST MODAL ===== -->
<div id="leaveModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-opacity"
     onclick="closeLeaveModalOutside(event)">
    <div class="bg-white rounded-[24px] shadow-2xl w-full max-w-lg mx-4 overflow-hidden p-6" onclick="event.stopPropagation()">

        <!-- Modal Header -->
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">New Leave Request</h3>
                <p class="text-[0.85rem] text-slate-400 mt-1">Fill in the details for your leave or sick request</p>
            </div>
            <button onclick="closeLeaveModal()" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('employee.attendance.permission') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4">

                <div class="form-field col-span-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="Izin">Permission (Personal)</option>
                        <option value="Sakit">Sick (Medical)</option>
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-input" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-field">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-input" required min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-field col-span-2">
                    <label class="form-label">Reason / Information</label>
                    <textarea name="information" class="form-input" rows="3" required
                              placeholder="e.g. Taking care of family / Medical appointment..."></textarea>
                </div>

                <div class="form-field col-span-2">
                    <label class="form-label">Attachment (PDF)</label>
                    <div class="relative w-full">
                        <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-xl bg-slate-50 transition-all cursor-pointer focus:outline-none focus:border-indigo-500" accept="application/pdf" required>
                    </div>
                    <p class="text-[10.5px] text-slate-400 mt-1">Max: 2MB. Format: PDF only. File is required.</p>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeLeaveModal()">Cancel</button>
                <button type="submit" class="btn-primary">Submit Request</button>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function openLeaveModal() {
        const m = document.getElementById('leaveModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeLeaveModal() {
        const m = document.getElementById('leaveModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    function closeLeaveModalOutside(e) {
        if (e.target === document.getElementById('leaveModal')) {
            closeLeaveModal();
        }
    }

    // Auto-open modal if there are validation errors (from a failed submission)
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() { openLeaveModal(); });
    @endif
</script>
@endsection
