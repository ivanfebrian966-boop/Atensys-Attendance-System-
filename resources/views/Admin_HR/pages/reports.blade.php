<!DOCTYPE html>
@extends('Admin_HR.layouts.main')

@section('title', 'Reports — ATTENSYS')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/reports.css') }}">
@endsection

@section('main_structure')
@include('Admin_HR.components.sidebar')

<div class="main-content">
    @include('Admin_HR.components.topbar', [
        'pageTitle'    => 'Reports',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
    ])

    <div class="p-4 md:p-6">

        <!-- FILTER PERIOD -->
        <div class="filter-bar fade-up d1">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-semibold text-slate-600 sora">Period:</span>
                <div class="period-tabs">
                    <button class="period-btn active" onclick="setPeriod('week',this)">This Week</button>
                    <button class="period-btn" onclick="setPeriod('month',this)">This Month</button>
                    <button class="period-btn" onclick="setPeriod('quarter',this)">Quarter</button>
                    <button class="period-btn" onclick="setPeriod('year',this)">This Year</button>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <input type="date" id="dateFrom" class="filter-select" style="padding-left:12px">
                <span class="text-slate-400 text-sm">to</span>
                <input type="date" id="dateTo" class="filter-select" style="padding-left:12px">
                <select id="reportDiv" class="filter-select" onchange="renderReports()">
                    <option value="">All Divisions</option>
                    <option>Engineering</option><option>HR</option><option>Finance</option>
                    <option>Marketing</option><option>IT</option><option>Operasional</option>
                </select>
            </div>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
            <div class="stat-card indigo fade-up d1">
                <div class="stat-icon" style="background:#eef2ff">📊</div>
                <p class="stat-value gradient-text" id="rTotal">0</p>
                <p class="stat-label">Total Record</p>
            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon" style="background:#ecfdf5">✅</div>
                <p class="stat-value text-emerald-600" id="rPresent">0</p>
                <p class="stat-label">Present</p>
                <p class="stat-trend text-emerald-600" id="rPresentPct">—</p>
            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon" style="background:#fef2f2">❌</div>
                <p class="stat-value text-red-500" id="rAbsent">0</p>
                <p class="stat-label">Absent</p>
                <p class="stat-trend text-red-500" id="rAbsentPct">—</p>
            </div>
            <div class="stat-card amber fade-up d4">
                <div class="stat-icon" style="background:#fffbeb">⏰</div>
                <p class="stat-value text-amber-500" id="rLate">0</p>
                <p class="stat-label">Late</p>
                <p class="stat-trend text-amber-500" id="rLatePct">—</p>
            </div>
            <div class="stat-card purple fade-up d5">
                <div class="stat-icon" style="background:#faf5ff">📋</div>
                <p class="stat-value text-purple-500" id="rOther">0</p>
                <p class="stat-label">Sick+Permission</p>
                <p class="stat-trend text-purple-500" id="rOtherPct">—</p>
            </div>
        </div>

        <!-- CHARTS ROW -->
        <div class="grid lg:grid-cols-3 gap-4 mb-4">

            <!-- Bar Chart: Daily attendance -->
            <div class="lg:col-span-2 panel fade-up d2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Attendance Trend</h3>
                        <p class="panel-subtitle" id="chartSubtitle">Daily this period</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="chart-legend">
                            <span class="legend-dot" style="background:#10b981"></span><span class="text-xs text-slate-500">Present</span>
                            <span class="legend-dot ml-3" style="background:#ef4444"></span><span class="text-xs text-slate-500">Absent</span>
                            <span class="legend-dot ml-3" style="background:#f59e0b"></span><span class="text-xs text-slate-500">Late</span>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="bar-chart-wrap" id="barChart"></div>
                </div>
            </div>

            <!-- Donut: Composition -->
            <div class="panel fade-up d3">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Status Composition</h3>
                        <p class="panel-subtitle">Attendance percentage</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="donut-wrap">
                        <svg viewBox="0 0 120 120" class="donut-svg" id="donutSvg"></svg>
                        <div class="donut-center" id="donutCenter">
                            <span class="donut-pct" id="donutPct">—</span>
                            <span class="donut-label">Present</span>
                        </div>
                    </div>
                    <div class="donut-legend" id="donutLegend"></div>
                </div>
            </div>
        </div>


        <!-- DETAIL TABLE -->
        <div class="panel fade-up d5">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Employee Detail Summary</h3>
                    <p class="panel-subtitle">Individual statistics for the selected period</p>
                </div>
                <div class="header-actions flex-wrap gap-2">
                    <div class="search-wrap">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchReport" class="search-input" placeholder="Search employees..." oninput="filterReport()">
                    </div>
                    <button class="btn-secondary" onclick="exportDetailCSV()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export CSV
                    </button>
                    <!-- View Toggle Buttons -->
                    <button id="btnReportTableView" class="view-btn active" onclick="switchReportView('table')" title="Table View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button id="btnReportCardView" class="view-btn" onclick="switchReportView('card')" title="Card View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4H5a2 2 0 00-2 2v14a2 2 0 002 2h4m0-21v21m0-21h4a2 2 0 012 2v14a2 2 0 01-2 2h-4m0-21v21"/>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- TABLE VIEW -->
            <div id="reportTableView" class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th class="hidden sm:table-cell">Division</th>
                            <th>Present</th>
                            <th class="hidden sm:table-cell">Absent</th>
                            <th class="hidden md:table-cell">Late</th>
                            <th class="hidden md:table-cell">Sick</th>
                            <th class="hidden md:table-cell">Permission</th>
                            <th>% Present</th>
                            <th class="hidden lg:table-cell">Avg Check In</th>
                        </tr>
                    </thead>
                    <tbody id="reportDetailBody"></tbody>
                </table>
            </div>
            <!-- CARD VIEW -->
            <div id="reportCardView" class="hidden p-5">
                <div id="reportCardContainer" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            </div>
            <div id="reportEmpty" class="empty-state hidden">
            </div>
            <div class="table-footer">
                <p class="table-info" id="reportInfo">— data</p>
            </div>
        </div>

    </div><!-- end p-4 -->
</div><!-- end main-content -->
@endsection

@section('scripts')
<script src="{{ asset('js/Admin_HR/reports.js') }}"></script>
@endsection
