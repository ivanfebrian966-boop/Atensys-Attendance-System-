<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Profile — ATTENSYS</title>
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

        <a href="{{ route('employee.profile') }}" class="nav-item active" id="nav-profile">
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
                    <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Profile Lengkap</h1>
                    <p class="text-xs text-slate-400">Informasi akun dan detail karyawan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6">
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="panel fade-up d1">
                <div class="text-center py-6">
                    <div class="w-24 h-24 mx-auto rounded-full bg-indigo-600 flex items-center justify-center text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                    <h2 class="mt-5 text-xl font-semibold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ $user->division ?? 'Employee' }}</p>
                </div>
                <div class="border-t border-slate-200 px-6 py-5">
                    <div class="profile-detail">
                        <span>Email</span>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Nama lengkap</span>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Divisi</span>
                        <strong>{{ $user->division ?? 'Belum diisi' }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Role</span>
                        <strong>{{ $user->role ?? 'Employee' }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Terdaftar sejak</span>
                        <strong>{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</strong>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 panel fade-up d2">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Detail Akun</h3>
                        <p class="panel-subtitle">Semua informasi profil Anda</p>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="info-card">
                        <p class="info-label">Nama</p>
                        <p class="info-value">{{ $user->name }}</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Email</p>
                        <p class="info-value">{{ $user->email }}</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Divisi</p>
                        <p class="info-value">{{ $user->division ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Telepon</p>
                        <p class="info-value">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="info-card">
                        <p class="info-label">Alamat</p>
                        <p class="info-value">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script src="{{ asset('js/Employee/EmployeeDashboard.js') }}"></script>
</body>
</html>