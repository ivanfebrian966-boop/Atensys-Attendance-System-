<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/DashboardHR.css') }}">
</head>
<body>

@include('Admin_HR.sidebar')

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

    <!-- TOPBAR -->
    @include('Admin_HR.topbarHR', [
        'pageTitle'    => 'Dashboard',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
    ])

    <!-- ===== CONTENT AREA ===== -->
    <div class="p-4 md:p-6">

        <!-- ===== STAT CARDS ===== -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
            <div class="stat-card fade-up d1">
                <div class="stat-icon-sm" style="background:#eef2ff;color:#6366f1">👥</div>
                <p class="stat-value text-slate-900">{{ $totalEmployees ?? 120 }}</p>
                <p class="stat-label">Total Employees</p>

            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon-sm" style="background:#ecfdf5;color:#10b981">✅</div>
                <p class="stat-value text-emerald-600">{{ $present ?? 98 }}</p>
                <p class="stat-label">Present</p>

            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon-sm" style="background:#fef2f2;color:#ef4444">❌</div>
                <p class="stat-value text-red-500">{{ $absent ?? 10 }}</p>
                <p class="stat-label">Absent</p>

            </div>
            <div class="stat-card yellow fade-up d4">
                <div class="stat-icon-sm" style="background:#fffbeb;color:#f59e0b">⏰</div>
                <p class="stat-value text-amber-500">{{ $late ?? 12 }}</p>
                <p class="stat-label">Late</p>

            </div>
            <div class="stat-card blue fade-up d5">
                <div class="stat-icon-sm" style="background:#eff6ff;color:#3b82f6">🏥</div>
                <p class="stat-value text-blue-500">{{ $sick ?? 5 }}</p>
                <p class="stat-label">Sick</p>

            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon-sm" style="background:#faf5ff;color:#8b5cf6">📋</div>
                <p class="stat-value text-purple-500">{{ $permission ?? 7 }}</p>
                <p class="stat-label">Permission</p>

            </div>
        </div>

        <!-- ===== MAIN GRID ===== -->
        <div class="grid lg:grid-cols-3 gap-4 mb-4">

            <!-- Attendance Chart Panel -->
            <div class="panel fade-up d2">
                <div class="panel-header">
                    <div>
                    <h3 class="panel-title">Attendance This Week</h3>

                        <p class="panel-subtitle">Mon – Sat</p>
                    </div>
                    <span class="badge-rate">87.5%</span>
                </div>
                <div class="p-5">
                    <div class="chart-bar-wrap mb-3">
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:75%"></div>
                            <span class="chart-label">Mon</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:90%"></div>
                            <span class="chart-label">Tue</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:65%"></div>
                            <span class="chart-label">Wed</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:88%"></div>
                            <span class="chart-label">Thu</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:70%"></div>
                            <span class="chart-label">Fri</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:87%"></div>
                            <span class="chart-label">Sat</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs text-slate-500">Average attendance</span>

                        <span class="text-sm font-bold gradient-text" style="font-family:'Sora',sans-serif">87.5%</span>
                    </div>
                </div>
            </div>

            <!-- Status Summary -->
            <div class="panel fade-up d3">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Attendance Status Today</h3>
                        <p class="panel-subtitle">{{ now()->translatedFormat('d F Y') ?? date('d M Y') }}</p>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-emerald-500"></span>
                        <span class="text-sm text-slate-600">Present</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-emerald-500" style="width:82%"></div>
                            </div>
                            <span class="status-count">{{ $present ?? 98 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-red-400"></span>
                            <span class="text-sm text-slate-600">Absent</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-red-400" style="width:8%"></div>
                            </div>
                            <span class="status-count">{{ $absent ?? 10 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-amber-400"></span>
                            <span class="text-sm text-slate-600">Late</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-amber-400" style="width:10%"></div>
                            </div>
                            <span class="status-count">{{ $late ?? 12 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-blue-400"></span>
                            <span class="text-sm text-slate-600">Sick</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-blue-400" style="width:4%"></div>
                            </div>
                            <span class="status-count">{{ $sick ?? 5 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-purple-400"></span>
                            <span class="text-sm text-slate-600">Permission</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-purple-400" style="width:6%"></div>
                            </div>
                            <span class="status-count">{{ $permission ?? 7 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="panel fade-up d4">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Quick Actions</h3>
                        <p class="panel-subtitle">Main menu shortcuts</p>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-2 gap-3">
                    <a href="/attendance" class="quick-action-card" style="--qa-color:#6366f1;--qa-bg:#eef2ff">
                        <span class="text-2xl">📷</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Scan QR</span>
                    </a>
                    <a href="/reports" class="quick-action-card" style="--qa-color:#06b6d4;--qa-bg:#ecfeff">
                        <span class="text-2xl">📊</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Reports</span>
                    </a>
                </div>
            </div>
        </div>



    </div><!-- end content area -->
</div><!-- end main-content -->

<!-- ===== TOAST ===== -->
<div id="toast" class="toast">
    <div class="toast-inner">
        <span id="toastIcon">✅</span>
        <span id="toastMsg">Success!</span>
    </div>
</div>

<script src="{{ asset('js/Admin_HR/DashboardHR.js') }}"></script>
</body>
</html>
