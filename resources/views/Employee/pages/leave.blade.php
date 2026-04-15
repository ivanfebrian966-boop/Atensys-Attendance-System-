@extends('Employee.layouts.main')

@section('title', 'Leave / Perizinan — ATTENSYS')
@section('page_title', 'Leave / Perizinan')
@section('page_subtitle', 'Manage your leave requests')

@section('content')

<!-- Header Title -->
<div class="mb-6 fade-up d1">
    <h2 class="text-xl font-bold text-slate-800" style="font-family:'Sora',sans-serif">Permohonan Izin / Sakit</h2>
    <p class="text-sm text-slate-500 mt-1">Ajukan surat izin atau keterangan sakit Anda di sini beserta bukti PDF.</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- ===== FORM REQUEST LEAVE ===== -->
    <div class="lg:col-span-1">
        <div class="panel fade-up d2">
            <div class="panel-header border-b border-slate-100 mb-4 pb-4">
                <div>
                    <h3 class="panel-title">Form Pengajuan</h3>
                </div>
            </div>
            <div class="p-5">
                <form action="{{ route('employee.attendance.permission') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div class="form-field">
                            <label class="form-label text-slate-700">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="Izin">Izin (Personal)</option>
                                <option value="Sakit">Sakit (Medical)</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label class="form-label text-slate-700">Start Date *</label>
                            <input type="date" name="start_date" class="form-input" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-field">
                            <label class="form-label text-slate-700">End Date *</label>
                            <input type="date" name="end_date" class="form-input" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-field">
                            <label class="form-label text-slate-700">Information / Reason *</label>
                            <textarea name="information" class="form-input" rows="3" required placeholder="e.g. Taking care of family / Medical appointment..."></textarea>
                        </div>
                        <div class="form-field">
                            <label class="form-label text-slate-700">Attachment (PDF) *</label>
                            <input type="file" name="attachment" class="form-input" accept="application/pdf" required>
                            <p class="text-[10px] text-slate-400 mt-1">Max: 2MB. Format: PDF only. File is REQUIRED.</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Submit Surat Izin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== LEAVE REQUEST HISTORY ===== -->
    <div class="lg:col-span-2">
        <div class="panel fade-up d3">
            <div class="panel-header border-b border-slate-100 mb-0 pb-4">
                <div>
                    <h3 class="panel-title">My Leave Requests</h3>
                    <p class="panel-subtitle">History of your permission/sick requests</p>
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
                                    ● {{ $perm->type }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-sm">
                                    {{ \Carbon\Carbon::parse($perm->start_date)->translatedFormat('d M') }} — 
                                    {{ \Carbon\Carbon::parse($perm->end_date)->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $statusClass = match($perm->status) {
                                        'Pending' => 'bg-amber-100 text-amber-600',
                                        'Approved' => 'bg-emerald-100 text-emerald-600',
                                        'Rejected' => 'bg-red-100 text-red-600',
                                        default => 'bg-slate-100 text-slate-600'
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
                                @if($perm->attachment)
                                    <a href="{{ asset('storage/' . $perm->attachment) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                        </svg>
                                        View PDF
                                    </a>
                                @else
                                    <span class="text-slate-400 text-sm">-</span>
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
    </div>
</div>

@endsection
