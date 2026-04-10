<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Dashboard — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Employee/EmployeeDashboard.css') }}">
</head>
<body>

<!-- ===== SIDEBAR (Employee Version) ===== -->
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

<!-- ===== MAIN CONTENT ===== -->
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
                    <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Employee Dashboard</h1>
                    <p class="text-xs text-slate-400" id="currentDate"></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="topbar-profile">
                    <div class="topbar-avatar">{{ substr($user->name, 0, 2) }}</div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800" style="font-family:'Sora',sans-serif">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $user->division ?? 'Employee' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6">

        <!-- ===== WELCOME CARD ===== -->
        <div class="welcome-card fade-up d1 mb-6">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-white" style="font-family:'Sora',sans-serif">Welcome back, {{ $user->name }}! 👋</h2>
                <p class="text-slate-200 text-sm mt-1">Today is {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="welcome-badge">{{ now()->format('H:i') }}</div>
        </div>

        <!-- ===== ATTENDANCE STATUS CARD ===== -->
        <div class="grid lg:grid-cols-2 gap-4 mb-6">
            <!-- Today's Attendance -->
            <div class="panel fade-up d2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Today's Attendance</h3>
                        <p class="panel-subtitle">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                    <span class="badge-rate" id="attendanceStatusBadge">
                        {{ $todayAttendance && $todayAttendance->check_in ? ($todayAttendance->check_out ? 'Completed' : 'Checked In') : 'Not Yet' }}
                    </span>
                </div>
                <div class="p-5 text-center">
                    @if(!$todayAttendance || !$todayAttendance->check_in)
                        <div class="mb-4">
                            <div class="text-6xl mb-3">⏰</div>
                            <p class="text-slate-600 mb-4">You haven't checked in yet today</p>
                            <form action="{{ route('employee.attendance.checkin') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-primary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Check In
                                </button>
                            </form>
                        </div>
                    @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                        <div class="mb-4">
                            <div class="text-6xl mb-3">✅</div>
                            <p class="text-emerald-600 font-semibold">Checked in at {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }}</p>
                            <p class="text-slate-500 text-sm mt-2">Don't forget to check out</p>
                            <form action="{{ route('employee.attendance.checkout') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-4-4m0 0l-4 4m4-4v12"/>
                                    </svg>
                                    Check Out
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="text-6xl mb-3">🎉</div>
                            <p class="text-green-600 font-semibold">Complete!</p>
                            <p class="text-slate-600 text-sm">In: {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i') }} | Out: {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="panel fade-up d3">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Your Statistics</h3>
                        <p class="panel-subtitle">This month</p>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-indigo-600">{{ $monthStats['present'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500">Present</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-amber-500">{{ $monthStats['late'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500">Late</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-red-400">{{ $monthStats['absent'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500">Absent</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-500">{{ $monthStats['sick_permission'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500">Sick/Permission</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== QR CODE DISPLAY SECTION ===== -->
        <div class="panel fade-up d4 mb-6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Your QR Code</h3>
                    <p class="panel-subtitle">Show this to check in at office</p>
                </div>
                <span class="badge-rate">Scanned by HR</span>
            </div>
            <div class="qr-section">
                <div class="qr-container">
                    <div class="qr-code-box" id="qrCodeBox">
                        <div class="qr-loading">
                            <div class="qr-loading-spinner"></div>
                            <p>Generating QR Code...</p>
                        </div>
                    </div>
                    <div class="qr-info">
                        ⚠️ Keep this QR code visible for attendance scanning
                    </div>
                    <input type="hidden" id="qrCodeData" value="{{ $qrCodeData }}">
                </div>
            </div>
        </div>

        <!-- ===== QUICK ATTENDANCE (Scan incoming QR) ===== -->
        <div class="panel fade-up d5 mb-6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Quick Attendance</h3>
                    <p class="panel-subtitle">Scan QR Code at office</p>
                </div>
                <button onclick="openQRModal()" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Scan QR Code
                </button>
            </div>
        </div>

        <!-- ===== RECENT ATTENDANCE HISTORY ===== -->
        <div class="panel fade-up d6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Recent Attendance</h3>
                    <p class="panel-subtitle">Last 7 days</p>
                </div>
                <a href="/employee/history" class="text-indigo-600 text-sm hover:underline">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances as $att)
                        <tr class="table-row">
                            <td>{{ \Carbon\Carbon::parse($att->date)->translatedFormat('d M Y') }}</td>
                            <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-' }}</td>
                            <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '-' }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($att->status) }}">
                                    ● {{ $att->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate-400 py-6">No attendance records yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold" style="font-family:'Sora',sans-serif">Scan QR Code</h3>
            <button onclick="closeQRModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <div id="qr-reader" class="w-full"></div>
        <p class="text-center text-sm text-slate-500 mt-4">Position QR code in front of camera</p>
    </div>
</div>

<div id="toast" class="toast">
    <div class="toast-inner">
        <span id="toastIcon">✅</span>
        <span id="toastMsg">Success!</span>
    </div>
</div>

<!-- QR Code Generation Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script src="{{ asset('js/Employee/EmployeeDashboard.js') }}"></script>
</body>
</html>