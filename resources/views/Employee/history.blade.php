<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance History — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Employee/EmployeeDashboard.css') }}">
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-blob-1"></div>
    <div class="sidebar-blob-2"></div>

    <div class="px-6 pt-6 pb-4 flex items-center gap-3 relative" style="z-index:1">
        <div class="sidebar-logo-icon"><span>A</span></div>
        <div>
            <p class="font-bold text-white text-base leading-tight" style="font-family:'Sora',sans-serif">ATTENSYS</p>
            <p class="text-xs text-slate-400" style="font-family:'Sora',sans-serif">Employee</p>
        </div>
    </div>

    <div class="mx-6 h-px bg-white/10 mb-2"></div>

    <nav class="flex-1 overflow-y-auto pb-4 relative" style="z-index:1">
        <p class="nav-section-label">Menu Utama</p>

        <a href="{{ route('employee.dashboard') }}" class="nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}" id="nav-dashboard">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('employee.attendance') }}" class="nav-item {{ request()->routeIs('employee.attendance') ? 'active' : '' }}" id="nav-attendance">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Attendance
            <span class="nav-badge">QR</span>
        </a>

        <a href="{{ route('employee.history') }}" class="nav-item {{ request()->routeIs('employee.history') ? 'active' : '' }}" id="nav-history">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            History
        </a>

        <p class="nav-section-label">Pengaturan</p>

        <a href="{{ route('employee.profile') }}" class="nav-item {{ request()->routeIs('employee.profile') ? 'active' : '' }}" id="nav-profile">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
    </nav>

    <div class="sidebar-user">
        <div class="flex items-center gap-3">
            <div class="sidebar-avatar">{{ substr($user->name, 0, 2) }}</div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate" style="font-family:'Sora',sans-serif">{{ $user->name }}</p>
                <p class="text-slate-400 text-xs truncate">{{ $user->email }}</p>
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

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="main-content">
    <div class="topbar">
        <div class="px-4 md:px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button class="topbar-hamburger" onclick="openSidebar()">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Attendance History</h1>
                    <p class="text-xs text-slate-400">Riwayat kehadiran karyawan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <div class="stat-card fade-up d1">
                <div class="stat-icon-sm" style="background:#eef2ff;color:#6366f1">👥</div>
                <p class="stat-value text-slate-900">{{ $attendances->count() }}</p>
                <p class="stat-label">Total Records</p>
            </div>
            <div class="stat-card fade-up d2">
                <div class="stat-icon-sm" style="background:#ecfdf5;color:#10b981">✅</div>
                <p class="stat-value text-emerald-600">{{ $counts['present'] }}</p>
                <p class="stat-label">Present</p>
            </div>
            <div class="stat-card fade-up d3">
                <div class="stat-icon-sm" style="background:#fffbeb;color:#f59e0b">⏰</div>
                <p class="stat-value text-amber-500">{{ $counts['late'] }}</p>
                <p class="stat-label">Late</p>
            </div>
            <div class="stat-card fade-up d4">
                <div class="stat-icon-sm" style="background:#fef2f2;color:#ef4444">❌</div>
                <p class="stat-value text-red-500">{{ $counts['absent'] }}</p>
                <p class="stat-label">Absent</p>
            </div>
        </div>

        <div class="panel fade-up d5">
            <div class="panel-header items-center justify-between">
                <div>
                    <h3 class="panel-title">Detailed History</h3>
                    <p class="panel-subtitle">All attendance records sorted by date</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('employee.attendance') }}" class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Attendance
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th class="hidden lg:table-cell">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr class="table-row">
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') }}</td>
                            <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                            <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($attendance->status) }}">
                                    ● {{ $attendance->status }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell">{{ $attendance->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-400 py-6">No attendance history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script src="{{ asset('js/Employee/EmployeeDashboard.js') }}"></script>
</body>
</html>