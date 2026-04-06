<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/DashboardHR.css') }}">
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-blob-1"></div>
    <div class="sidebar-blob-2"></div>

    <!-- Logo -->
    <div class="px-6 pt-6 pb-4 flex items-center gap-3 relative" style="z-index:1">
        <div class="sidebar-logo-icon">
            <span>A</span>
        </div>
        <div>
            <p class="font-bold text-white text-base leading-tight" style="font-family:'Sora',sans-serif">ATTENSYS</p>
            <p class="text-xs text-slate-400" style="font-family:'Sora',sans-serif">Admin HR</p>
        </div>
    </div>

    <!-- Divider -->
    <div class="mx-6 h-px bg-white/10 mb-2"></div>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto pb-4 relative" style="z-index:1">
        <p class="nav-section-label">Menu Utama</p>

        <a href="/dashboardHR" class="nav-item active" id="nav-dashboard">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="/employees" class="nav-item" id="nav-employees">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Employees
        </a>

        <a href="/attendance" class="nav-item" id="nav-attendance">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Attendance
            <span class="nav-badge">QR</span>
        </a>

        <a href="/reports" class="nav-item" id="nav-reports">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reports
        </a>

        <p class="nav-section-label">Pengaturan</p>

        <a href="/HRmanage" class="nav-item" id="nav-manage">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Manage
        </a>

        <a href="/profileHR" class="nav-item" id="nav-profile">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
    </nav>

    <!-- User info -->
    <div class="sidebar-user">
        <div class="flex items-center gap-3">
            <div class="sidebar-avatar">HR</div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate" style="font-family:'Sora',sans-serif">Admin HR</p>
                <p class="text-slate-400 text-xs truncate">hr@attensys.id</p>
            </div>
            <a href="/logout" class="tooltip-wrap">
                <svg class="w-4 h-4 text-slate-400 hover:text-red-400 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="tooltip-text">Logout</span>
            </a>
        </div>
    </div>
</aside>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="px-4 md:px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button class="topbar-hamburger" onclick="openSidebar()">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif" id="pageTitle">Dashboard</h1>
                    <p class="text-xs text-slate-400" id="currentDate">Memuat tanggal...</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <!-- Profile -->
                <div class="topbar-profile">
                    <div class="topbar-avatar">HR</div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800" style="font-family:'Sora',sans-serif">Admin HR</p>
                        <p class="text-xs text-slate-400">HR Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CONTENT AREA ===== -->
    <div class="p-4 md:p-6">

        <!-- ===== STAT CARDS ===== -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
            <div class="stat-card fade-up d1">
                <div class="stat-icon-sm" style="background:#eef2ff;color:#6366f1">👥</div>
                <p class="stat-value text-slate-900">{{ $totalEmployees ?? 120 }}</p>
                <p class="stat-label">Total Karyawan</p>
            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon-sm" style="background:#ecfdf5;color:#10b981">✅</div>
                <p class="stat-value text-emerald-600">{{ $present ?? 98 }}</p>
                <p class="stat-label">Hadir</p>
            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon-sm" style="background:#fef2f2;color:#ef4444">❌</div>
                <p class="stat-value text-red-500">{{ $absent ?? 10 }}</p>
                <p class="stat-label">Absen</p>
            </div>
            <div class="stat-card yellow fade-up d4">
                <div class="stat-icon-sm" style="background:#fffbeb;color:#f59e0b">⏰</div>
                <p class="stat-value text-amber-500">{{ $late ?? 12 }}</p>
                <p class="stat-label">Terlambat</p>
            </div>
            <div class="stat-card blue fade-up d5">
                <div class="stat-icon-sm" style="background:#eff6ff;color:#3b82f6">🏥</div>
                <p class="stat-value text-blue-500">{{ $sick ?? 5 }}</p>
                <p class="stat-label">Sakit</p>
            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon-sm" style="background:#faf5ff;color:#8b5cf6">📋</div>
                <p class="stat-value text-purple-500">{{ $permission ?? 7 }}</p>
                <p class="stat-label">Izin</p>
            </div>
        </div>

        <!-- ===== MAIN GRID ===== -->
        <div class="grid lg:grid-cols-3 gap-4 mb-4">

            <!-- Attendance Chart Panel -->
            <div class="panel fade-up d2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Kehadiran Minggu Ini</h3>
                        <p class="panel-subtitle">Senin – Sabtu</p>
                    </div>
                    <span class="badge-rate">87.5%</span>
                </div>
                <div class="p-5">
                    <div class="chart-bar-wrap mb-3">
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:75%"></div>
                            <span class="chart-label">Sen</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:90%"></div>
                            <span class="chart-label">Sel</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:65%"></div>
                            <span class="chart-label">Rab</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:88%"></div>
                            <span class="chart-label">Kam</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:70%"></div>
                            <span class="chart-label">Jum</span>
                        </div>
                        <div class="chart-bar-item">
                            <div class="chart-bar" style="height:87%"></div>
                            <span class="chart-label">Sab</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs text-slate-500">Rata-rata kehadiran</span>
                        <span class="text-sm font-bold gradient-text" style="font-family:'Sora',sans-serif">87.5%</span>
                    </div>
                </div>
            </div>

            <!-- Status Summary -->
            <div class="panel fade-up d3">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Status Absensi Hari Ini</h3>
                        <p class="panel-subtitle">{{ now()->translatedFormat('d F Y') ?? date('d M Y') }}</p>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-emerald-500"></span>
                            <span class="text-sm text-slate-600">Hadir</span>
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
                            <span class="text-sm text-slate-600">Absen</span>
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
                            <span class="text-sm text-slate-600">Terlambat</span>
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
                            <span class="text-sm text-slate-600">Sakit</span>
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
                            <span class="text-sm text-slate-600">Izin</span>
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
                        <h3 class="panel-title">Aksi Cepat</h3>
                        <p class="panel-subtitle">Shortcut menu utama</p>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-2 gap-3">
                    <a href="/attendance" class="quick-action-card" style="--qa-color:#6366f1;--qa-bg:#eef2ff">
                        <span class="text-2xl">📷</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Scan QR</span>
                    </a>
                    <a href="/reports" class="quick-action-card" style="--qa-color:#06b6d4;--qa-bg:#ecfeff">
                        <span class="text-2xl">📊</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Laporan</span>
                    </a>
                    <a href="/employees" class="quick-action-card" style="--qa-color:#10b981;--qa-bg:#ecfdf5">
                        <span class="text-2xl">👥</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Karyawan</span>
                    </a>
                    <a href="/HRmanage" class="quick-action-card" style="--qa-color:#8b5cf6;--qa-bg:#faf5ff">
                        <span class="text-2xl">⚙️</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Kelola</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ===== ATTENDANCE TABLE ===== -->
        <div class="panel fade-up d5">
            <div class="panel-header flex-wrap gap-3">
                <div>
                    <h3 class="panel-title">Data Absensi Karyawan</h3>
                    <p class="panel-subtitle">Rekap kehadiran hari ini</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Search -->
                    <div class="search-wrap">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari karyawan..."
                            class="search-input" oninput="filterTable()">
                    </div>
                    <!-- Status Filter -->
                    <select id="statusFilter" class="filter-select" onchange="filterTable()">
                        <option value="">Semua Status</option>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                    <!-- Division Filter -->
                    <select id="divisionFilter" class="filter-select" onchange="filterTable()">
                        <option value="">Semua Divisi</option>
                        <option value="HR">HR</option>
                        <option value="IT">IT</option>
                        <option value="Finance">Finance</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Marketing">Marketing</option>
                    </select>
                    <!-- Export -->
                    <button class="btn-secondary" onclick="exportTable()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table" id="attendanceTable">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th class="hidden sm:table-cell">NIP</th>
                            <th>Divisi</th>
                            <th>Status</th>
                            <th class="hidden md:table-cell">Check In</th>
                            <th class="hidden md:table-cell">Check Out</th>
                            <th class="hidden lg:table-cell">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($attendances ?? [] as $data)
                        <tr class="table-row" data-status="{{ $data['status'] }}" data-division="{{ $data['division'] }}">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                                        {{ strtoupper(substr($data['name'], 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">{{ $data['name'] }}</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell">
                                <span class="text-slate-400 text-xs font-mono">{{ $data['nip'] ?? 'EMP-000' }}</span>
                            </td>
                            <td>
                                <span class="text-slate-600 text-sm">{{ $data['division'] }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($data['status']) }}">
                                    ● {{ $data['status'] }}
                                </span>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="text-slate-500 text-sm">{{ $data['check_in'] ?? $data['time'] }}</span>
                            </td>
                            <td class="hidden md:table-cell">
                                <span class="text-slate-500 text-sm">{{ $data['check_out'] ?? '-' }}</span>
                            </td>
                            <td class="hidden lg:table-cell">
                                <span class="text-slate-400 text-xs">{{ $data['note'] ?? '-' }}</span>
                            </td>
                        </tr>
                        @empty
                        {{-- Default demo rows when no data --}}
                        <tr class="table-row" data-status="Present" data-division="Engineering">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:linear-gradient(135deg,#6366f1,#818cf8)">AR</div>
                                    <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">Andi Rahman</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">EMP-001</span></td>
                            <td><span class="text-slate-600 text-sm">Engineering</span></td>
                            <td><span class="status-badge status-present">● Present</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">08:02</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">17:10</span></td>
                            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">Tepat waktu</span></td>
                        </tr>
                        <tr class="table-row" data-status="Present" data-division="HR">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:linear-gradient(135deg,#06b6d4,#0891b2)">SW</div>
                                    <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">Siti Wulandari</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">EMP-002</span></td>
                            <td><span class="text-slate-600 text-sm">HR</span></td>
                            <td><span class="status-badge status-present">● Present</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">07:55</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">17:00</span></td>
                            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">Tepat waktu</span></td>
                        </tr>
                        <tr class="table-row" data-status="Late" data-division="Finance">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:linear-gradient(135deg,#f59e0b,#d97706)">BP</div>
                                    <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">Budi Pratama</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">EMP-003</span></td>
                            <td><span class="text-slate-600 text-sm">Finance</span></td>
                            <td><span class="status-badge status-late">● Late</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">09:15</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">17:30</span></td>
                            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">Terlambat 75 mnt</span></td>
                        </tr>
                        <tr class="table-row" data-status="Absent" data-division="Marketing">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:#94a3b8">RH</div>
                                    <span class="font-semibold text-slate-400 text-sm" style="font-family:'Sora',sans-serif">Rini Handayani</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">EMP-004</span></td>
                            <td><span class="text-slate-400 text-sm">Marketing</span></td>
                            <td><span class="status-badge status-absent">● Absent</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-400 text-sm">-</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-400 text-sm">-</span></td>
                            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">Tidak hadir</span></td>
                        </tr>
                        <tr class="table-row" data-status="Sick" data-division="IT">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">FN</div>
                                    <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">Fajar Nugroho</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">EMP-005</span></td>
                            <td><span class="text-slate-600 text-sm">IT</span></td>
                            <td><span class="status-badge status-sick">● Sick</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-400 text-sm">-</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-400 text-sm">-</span></td>
                            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">Sakit + surat dokter</span></td>
                        </tr>
                        <tr class="table-row" data-status="Permission" data-division="Engineering">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar-sm" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">DS</div>
                                    <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">Dewi Susanti</span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">EMP-006</span></td>
                            <td><span class="text-slate-600 text-sm">Engineering</span></td>
                            <td><span class="status-badge status-permission">● Permission</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-400 text-sm">-</span></td>
                            <td class="hidden md:table-cell"><span class="text-slate-400 text-sm">-</span></td>
                            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">Izin keperluan keluarga</span></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Empty state -->
            <div id="emptyState" class="hidden py-12 text-center">
                <div class="text-4xl mb-3">🔍</div>
                <p class="text-slate-500 font-medium" style="font-family:'Sora',sans-serif">Tidak ada data ditemukan</p>
                <p class="text-slate-400 text-sm mt-1">Coba ubah filter pencarian</p>
            </div>

            <!-- Pagination -->
            <div class="px-4 md:px-6 py-4 border-t border-slate-50 flex items-center justify-between flex-wrap gap-3">
                <p class="text-sm text-slate-400" id="tableInfo">Menampilkan semua data</p>
                <div class="flex items-center gap-1">
                    <button class="page-btn" id="prevPage" onclick="changePage(-1)">‹</button>
                    <button class="page-btn active" id="page1">1</button>
                    <button class="page-btn" id="page2">2</button>
                    <button class="page-btn" id="page3">3</button>
                    <button class="page-btn" onclick="changePage(1)">›</button>
                </div>
            </div>
        </div>

    </div><!-- end content area -->
</div><!-- end main-content -->

<!-- ===== TOAST ===== -->
<div id="toast" class="toast">
    <div class="toast-inner">
        <span id="toastIcon">✅</span>
        <span id="toastMsg">Berhasil!</span>
    </div>
</div>

<script src="{{ asset('js/Admin_HR/DashboardHR.js') }}"></script>
</body>
</html>
