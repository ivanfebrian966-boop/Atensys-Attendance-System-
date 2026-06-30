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
        'pageSubtitle' => 'Manage national holidays & public holidays',
    ])

    <div class="p-4 md:p-6">

        {{-- ── TOP ROW: Calendar + List ──────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-6">

            {{-- Visual Calendar --}}
            <div class="panel fade-up d1 lg:col-span-3">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">📅 Holiday Calendar</h3>
                        <p class="panel-subtitle">Click a date to add / edit a holiday</p>
                    </div>
                    <div class="cal-nav">
                        <button onclick="calPrev()" title="Previous Month">‹</button>
                        <select id="selectMonth" class="filter-select" onchange="onMonthYearChange()" style="font-size:12px;font-weight:600;padding:6px 10px;"></select>
                        <select id="selectYear"  class="filter-select" onchange="onMonthYearChange()" style="font-size:12px;font-weight:600;padding:6px 10px;"></select>
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

                {{-- Legend --}}
                <div class="cal-legend">
                    <span class="legend-item"><span class="legend-dot today-dot"></span>Today</span>
                    <span class="legend-item"><span class="legend-dot holiday-dot"></span>Holiday</span>
                    <span class="legend-item"><span class="legend-dot weekend-dot sun-dot"></span>Sunday</span>
                    <span class="legend-item"><span class="legend-dot weekend-dot sat-dot"></span>Saturday</span>
                </div>
            </div>

            {{-- Holiday List --}}
            <div class="panel fade-up d2 lg:col-span-2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">🔴 Holiday List</h3>
                        <p class="panel-subtitle">Total: <span id="totalHolidays" class="font-semibold text-red-500">{{ $holidays->count() }}</span> holiday entries</p>
                    </div>
                </div>
                <div class="modal-body pb-2" style="max-height:auto;overflow-y:auto;" id="holidayListContainer">
                    @forelse($holidays as $hol)
                    <div class="hol-row" id="hol-row-{{ $hol->id }}"
                         data-hol-date="{{ $hol->date->format('Y-m-d') }}"
                         data-hol-names="{{ json_encode([$hol->name]) }}">
                        <div class="hol-date-badge">
                            <span class="day-num">{{ $hol->date->format('d') }}</span>
                            <span class="month-txt">{{ $hol->date->format('M Y') }}</span>
                        </div>
                        <div class="hol-info">
                            <p class="hol-name">
                                {{ $hol->name }}
                            </p>
                        </div>
                        <div class="hol-actions">
                            <button onclick="openEditHoliday({{ $hol->id }})"
                                class="btn-icon-edit" title="{{ $hol->date->isToday() ? 'View / Add Name' : 'Edit' }}">{{ $hol->date->isToday() ? '🔒' : '✏️' }}</button>
                            @if(!$hol->date->isToday())
                            <button onclick="deleteHoliday({{ $hol->id }}, '{{ addslashes($hol->name) }}')"
                                class="btn-icon-del" title="Delete">🗑</button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div id="emptyHolidays" class="text-center py-10">
                        <div style="font-size:40px;margin-bottom:8px;">🏖️</div>
                        <p class="text-sm font-medium text-slate-500">No holidays registered yet</p>
                        <p class="text-xs text-slate-400 mt-1">Click a date on the calendar to add</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

{{-- ===== MODALS ===== --}}
@section('modals')

{{-- ── Modal Add / Edit Holiday ──────────────────────── --}}
<div class="modal-overlay" id="modalHoliday" onclick="closeModalOutside(event,'modalHoliday')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:500px;width:100%;">

        <div class="modal-header" style="padding:16px 20px 14px;gap:12px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span id="modalHolIcon" style="font-size:16px;">🔴</span>
                </div>
                <div>
                    <h3 class="modal-title" id="modalHolTitle" style="font-size:15px;margin:0;">Add Holiday</h3>
                    <p class="modal-sub" style="font-size:12px;margin:0;">Mark date as a national holiday</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('modalHoliday')"
                style="width:28px;height:28px;border-radius:6px;border:1px solid #e2e8f0;background:transparent;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;color:#94a3b8;">✕</button>
        </div>

        <div class="modal-body" style="padding:16px 20px;">

            <input type="hidden" id="holDate">
            <input type="hidden" id="holEditId">

            {{-- Selected Date Display --}}
            <div style="margin-bottom:14px;background:#f8fafc;padding:10px 12px;border-radius:8px;border:1px solid #e2e8f0;display:flex;align-items:center;gap:8px;">
                <span style="font-size:16px;">📅</span>
                <div>
                    <span style="font-size:10px;text-transform:uppercase;color:#94a3b8;font-weight:600;display:block;letter-spacing:0.02em;">Selected Date</span>
                    <strong id="selectedDateDisplay" style="font-size:13px;color:#1e293b;">-</strong>
                </div>
            </div>

            {{-- Names List --}}
            <div style="margin-bottom:6px;">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#374151;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;">
                    <span>Holiday Name <span style="color:#ef4444;">*</span></span>
                    <button type="button" onclick="addNameField()" class="btn-add-name">+ Add Name</button>
                </label>
                <div id="nameFieldsWrap">
                    {{-- Fields rendered by JS --}}
                </div>
                <span class="form-error" id="eHolNames"></span>
            </div>

        </div>

        <div class="modal-footer" style="padding:12px 20px;gap:8px;">
            <button class="btn-ghost" onclick="closeModal('modalHoliday')" style="font-size:13px;">Cancel</button>
            <button class="btn-primary" onclick="saveHoliday()" id="btnSaveHol"
                style="font-size:13px;display:flex;align-items:center;gap:6px;background:linear-gradient(135deg,#ef4444,#dc2626);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span id="btnSaveHolTxt">Save</span>
            </button>
        </div>
    </div>
</div>

{{-- ── Modal Delete Confirmation ─────────────────────── --}}
<div class="modal-overlay" id="modalDelHoliday" onclick="closeModalOutside(event,'modalDelHoliday')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="del-icon-wrap"><div class="del-icon">🗑</div></div>
        <h3 class="del-title">Delete Holiday?</h3>
        <p class="del-sub" id="delHolMsg">Auto-generated Holiday attendance will also be deleted.</p>
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
    const HOLIDAY_UPDATE_URL  = "{{ url('admin-hr/holidays') }}";
    const HOLIDAY_DESTROY_URL = "{{ url('admin-hr/holidays') }}";
    const CSRF_TOKEN          = document.querySelector('meta[name="csrf-token"]').content;
    // holidayMap: { "Y-m-d": ["Nama1","Nama2", ...] }
    const HOLIDAY_MAP         = @json($holidayMap);
</script>
<script src="{{ asset('js/Admin_HR/holidays.js') }}"></script>
@endsection
