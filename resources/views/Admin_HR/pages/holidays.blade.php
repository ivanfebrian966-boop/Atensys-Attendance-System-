<!DOCTYPE html>
@extends('Admin_HR.layouts.main')

@section('title', 'Holiday Calendar — ATTENSYS')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/attendance.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/holidays.css') }}">
@endsection

@section('main_structure')
@include('Admin_HR.components.sidebar')

<div class="main-content">
    @include('Admin_HR.components.topbar', [
        'pageTitle'    => 'Holiday Calendar',
        'pageSubtitle' => 'Manage national holidays & days off',
    ])

    <div class="p-4 md:p-6">

        {{-- ── TOP ROW: Calendar + List ───────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

            {{-- Visual Calendar --}}
            <div class="panel fade-up d1 lg:col-span-3">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">📅 Holiday Calendar</h3>
                        <p class="panel-subtitle">National holidays are marked in red</p>
                    </div>
                    <div class="cal-nav">
                        <button onclick="calPrev()" title="Previous Month">‹</button>
                        <select id="selectMonth" class="filter-select" onchange="onMonthYearChange()" style="font-size:12px; font-weight:600; padding:6px 10px;"></select>
                        <select id="selectYear" class="filter-select" onchange="onMonthYearChange()" style="font-size:12px; font-weight:600; padding:6px 10px;"></select>
                        <button onclick="calNext()" title="Next Month">›</button>
                    </div>
                </div>
                <div class="modal-body pt-4 pb-4">
                    <div class="holiday-calendar" id="calDayLabels">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                            <div class="cal-day-label">{{ $d }}</div>
                        @endforeach
                    </div>
                    <div class="holiday-calendar mt-1" id="calGrid"></div>
                </div>
            </div>

            {{-- Holiday List --}}
            <div class="panel fade-up d2 lg:col-span-2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">🔴 Holiday List</h3>
                        <p class="panel-subtitle">Total: <span id="totalHolidays" class="font-semibold text-red-500">{{ $holidays->count() }}</span> days</p>
                    </div>
                </div>
                <div class="modal-body pb-2" style="max-height:420px;overflow-y:auto;" id="holidayListContainer">
                    @forelse($holidays as $hol)
                    <div class="hol-row" id="hol-row-{{ $hol->id }}" data-hol-date="{{ $hol->date->format('Y-m-d') }}" data-hol-name="{{ $hol->name }}">
                        <div class="hol-date-badge">
                            <span class="day-num">{{ $hol->date->format('d') }}</span>
                            <span class="month-txt">{{ $hol->date->format('M Y') }}</span>
                        </div>
                        <div class="hol-info">
                            <p class="hol-name">{{ $hol->name }}</p>
                        </div>
                        <button onclick="deleteHoliday({{ $hol->id }}, '{{ addslashes($hol->name) }}')"
                            style="flex-shrink:0;width:30px;height:30px;border-radius:8px;border:1px solid #fecaca;background:#fff5f5;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:#ef4444;transition:all .15s;"
                            title="Delete">🗑</button>
                    </div>
                    @empty
                    <div id="emptyHolidays" class="text-center py-10">
                        <div style="font-size:40px;margin-bottom:8px;">🏖️</div>
                        <p class="text-sm font-medium text-slate-500">No holidays registered yet</p>
                        <p class="text-xs text-slate-400 mt-1">Click a calendar date to add a holiday</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

{{-- ===== MODAL ADD HOLIDAY ===== --}}
@section('modals')
<div class="modal-overlay" id="modalAddHoliday" onclick="closeModalOutside(event,'modalAddHoliday')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:460px;width:100%;">

        <div class="modal-header" style="padding:16px 20px 14px;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-size:16px;">🔴</span>
                </div>
                <div>
                    <h3 class="modal-title" style="font-size:15px;margin:0;">Add Holiday</h3>
                    <p class="modal-sub" style="font-size:12px;margin:0;">Mark a date as a national holiday</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('modalAddHoliday')" style="width:28px;height:28px;border-radius:6px;border:1px solid #e2e8f0;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;color:#94a3b8;">✕</button>
        </div>

        <div class="modal-body" style="padding:16px 20px;">

            <input type="hidden" id="holDate">

            <div style="margin-bottom:14px; background:#f8fafc; padding:10px 12px; border-radius:8px; border:1px solid #e2e8f0; display:flex; align-items:center; gap:8px;">
                <span style="font-size:16px;">📅</span>
                <div>
                    <span style="font-size:10px; text-transform:uppercase; color:#94a3b8; font-weight:600; display:block; letter-spacing:0.02em;">Selected Date</span>
                    <strong id="selectedDateDisplay" style="font-size:13px; color:#1e293b;">-</strong>
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label class="form-label" style="font-size:12px;font-weight:500;color:#64748b;margin-bottom:5px;display:block;">Holiday Name *</label>
                <input type="text" id="holName" class="form-input" placeholder="e.g. Independence Day" style="font-size:13px;">
                <span class="form-error" id="eHolName"></span>
            </div>

        </div>

        <div class="modal-footer" style="padding:12px 20px;gap:8px;">
            <button class="btn-ghost" onclick="closeModal('modalAddHoliday')" style="font-size:13px;">Cancel</button>
            <button class="btn-primary" onclick="saveHoliday()" style="font-size:13px;display:flex;align-items:center;gap:6px;background:linear-gradient(135deg,#ef4444,#dc2626);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save
            </button>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal-overlay" id="modalDelHoliday" onclick="closeModalOutside(event,'modalDelHoliday')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="del-icon-wrap"><div class="del-icon">🗑</div></div>
        <h3 class="del-title">Delete Holiday?</h3>
        <p class="del-sub" id="delHolMsg">All auto-generated Holiday attendance records for this day will also be deleted.</p>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn-ghost" onclick="closeModal('modalDelHoliday')">Cancel</button>
            <button class="btn-danger" onclick="execDelHoliday()">Yes, Delete</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const HOLIDAY_STORE_URL   = "{{ route('admin-hr.holidays.store') }}";
    const HOLIDAY_DESTROY_URL = "{{ url('admin-hr/holidays') }}";
    const CSRF_TOKEN          = document.querySelector('meta[name="csrf-token"]').content;
    const HOLIDAY_DATES       = @json($holidayDates);
</script>
<script src="{{ asset('js/Admin_HR/holidays.js') }}"></script>
@endsection
