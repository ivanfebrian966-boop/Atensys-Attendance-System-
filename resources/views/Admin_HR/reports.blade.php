<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/reports.css') }}">
</head>
<body>

@include('Admin_HR.sidebar')

<div class="main-content">
    <!-- TOPBAR -->
    @include('Admin_HR.topbarHR', [
        'pageTitle'    => 'Laporan',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
        'showExport'   => true,
    ])

    <div class="p-4 md:p-6">

        <!-- FILTER PERIOD -->
        <div class="filter-bar fade-up d1">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-sm font-semibold text-slate-600 sora">Periode:</span>
                <div class="period-tabs">
                    <button class="period-btn active" onclick="setPeriod('week',this)">Minggu Ini</button>
                    <button class="period-btn" onclick="setPeriod('month',this)">Bulan Ini</button>
                    <button class="period-btn" onclick="setPeriod('quarter',this)">Kuartal</button>
                    <button class="period-btn" onclick="setPeriod('year',this)">Tahun Ini</button>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <input type="date" id="dateFrom" class="filter-select" style="padding-left:12px">
                <span class="text-slate-400 text-sm">s/d</span>
                <input type="date" id="dateTo" class="filter-select" style="padding-left:12px">
                <select id="reportDiv" class="filter-select" onchange="renderReports()">
                    <option value="">Semua Divisi</option>
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
                <p class="stat-label">Hadir</p>
                <p class="stat-trend text-emerald-600" id="rPresentPct">—</p>
            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon" style="background:#fef2f2">❌</div>
                <p class="stat-value text-red-500" id="rAbsent">0</p>
                <p class="stat-label">Absen</p>
                <p class="stat-trend text-red-500" id="rAbsentPct">—</p>
            </div>
            <div class="stat-card amber fade-up d4">
                <div class="stat-icon" style="background:#fffbeb">⏰</div>
                <p class="stat-value text-amber-500" id="rLate">0</p>
                <p class="stat-label">Terlambat</p>
                <p class="stat-trend text-amber-500" id="rLatePct">—</p>
            </div>
            <div class="stat-card purple fade-up d5">
                <div class="stat-icon" style="background:#faf5ff">📋</div>
                <p class="stat-value text-purple-500" id="rOther">0</p>
                <p class="stat-label">Sakit+Izin</p>
                <p class="stat-trend text-purple-500" id="rOtherPct">—</p>
            </div>
        </div>

        <!-- CHARTS ROW -->
        <div class="grid lg:grid-cols-3 gap-4 mb-4">

            <!-- Bar Chart: Daily attendance -->
            <div class="lg:col-span-2 panel fade-up d2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Tren Kehadiran</h3>
                        <p class="panel-subtitle" id="chartSubtitle">Harian periode ini</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="chart-legend">
                            <span class="legend-dot" style="background:#10b981"></span><span class="text-xs text-slate-500">Hadir</span>
                            <span class="legend-dot ml-3" style="background:#ef4444"></span><span class="text-xs text-slate-500">Absen</span>
                            <span class="legend-dot ml-3" style="background:#f59e0b"></span><span class="text-xs text-slate-500">Telat</span>
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
                        <h3 class="panel-title">Komposisi Status</h3>
                        <p class="panel-subtitle">Persentase kehadiran</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="donut-wrap">
                        <svg viewBox="0 0 120 120" class="donut-svg" id="donutSvg"></svg>
                        <div class="donut-center" id="donutCenter">
                            <span class="donut-pct" id="donutPct">—</span>
                            <span class="donut-label">Hadir</span>
                        </div>
                    </div>
                    <div class="donut-legend" id="donutLegend"></div>
                </div>
            </div>
        </div>

        <!-- PER-DIVISION SUMMARY -->
        <div class="panel fade-up d4 mb-4">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Rekap per Divisi</h3>
                    <p class="panel-subtitle">Perbandingan kehadiran antar divisi</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Divisi</th>
                            <th>Total Hadir</th>
                            <th>Absen</th>
                            <th>Terlambat</th>
                            <th>Sakit/Izin</th>
                            <th>% Hadir</th>
                            <th>Tren</th>
                        </tr>
                    </thead>
                    <tbody id="divisionReportBody"></tbody>
                </table>
            </div>
        </div>

        <!-- DETAIL TABLE -->
        <div class="panel fade-up d5">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Rekap Detail Karyawan</h3>
                    <p class="panel-subtitle">Statistik individual periode yang dipilih</p>
                </div>
                <div class="header-actions">
                    <div class="search-wrap">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchReport" class="search-input" placeholder="Cari karyawan..." oninput="filterReport()">
                    </div>
                    <button class="btn-secondary" onclick="exportDetailCSV()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th class="hidden sm:table-cell">Divisi</th>
                            <th>Hadir</th>
                            <th class="hidden sm:table-cell">Absen</th>
                            <th class="hidden md:table-cell">Terlambat</th>
                            <th class="hidden md:table-cell">Sakit</th>
                            <th class="hidden md:table-cell">Izin</th>
                            <th>% Hadir</th>
                            <th class="hidden lg:table-cell">Avg Check In</th>
                        </tr>
                    </thead>
                    <tbody id="reportDetailBody"></tbody>
                </table>
            </div>
            <div id="reportEmpty" class="empty-state hidden">
                <div class="empty-icon">📊</div>
                <p class="empty-title">Tidak ada data laporan</p>
                <p class="empty-sub">Ubah filter periode atau divisi</p>
            </div>
            <div class="table-footer">
                <p class="table-info" id="reportInfo">— data</p>
            </div>
        </div>

    </div>
</div>

<div id="toast" class="toast"><div class="toast-inner"><span id="tIcon">✅</span><span id="tMsg">Berhasil!</span></div></div>

<script src="{{ asset('js/Admin_HR/shared.js') }}"></script>
<script src="{{ asset('js/Admin_HR/reports.js') }}"></script>
</body>
</html>
