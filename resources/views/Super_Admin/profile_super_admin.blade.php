<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Super Admin — ATTENSYS</title>
    <link href="{{ asset('css/Super_admin/dashboard_super_admin.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Sora', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .profile-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .profile-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-slide-up {
            animation: fadeSlideUp 0.5s ease-out forwards;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30">

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-blob-1"></div>
    <div class="sidebar-blob-2"></div>

    <!-- Logo -->
    <div class="px-6 pt-6 pb-4 flex items-center gap-3 relative" style="z-index:1">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
            <span class="text-white font-bold text-sm" style="font-family:'Sora',sans-serif">A</span>
        </div>
        <div>
            <p class="font-bold text-white text-base leading-tight" style="font-family:'Sora',sans-serif">ATTENSYS</p>
            <p class="text-xs text-slate-400" style="font-family:'Sora',sans-serif">Super Admin</p>
        </div>
    </div>

    <!-- Divider -->
    <div class="mx-6 h-px bg-white/10 mb-2"></div>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto pb-4 relative" style="z-index:1">
        <p class="nav-section-label">Overview</p>

        <a href="{{ route('super_admin') }}" class="nav-item">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a4 4 0 014-4h10a4 4 0 014 4v10a4 4 0 01-4 4H7a4 4 0 01-4-4V7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6M9 15h4"/>
            </svg>
            Dashboard
        </a>

        <p class="nav-section-label">Account Management</p>


        <a href="{{ route('super_admin') }}" class="nav-item">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Account Management
        </a>


        <a href="#" class="nav-item active">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            My Profile
        </a>

    </nav>

    <!-- User info -->
    <div class="mx-4 mb-4 p-3 rounded-2xl relative" style="z-index:1;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0" style="background:linear-gradient(135deg,#6366f1,#06b6d4);font-family:'Sora',sans-serif">SA</div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate" style="font-family:'Sora',sans-serif">Super Admin</p>
                <p class="text-slate-400 text-xs truncate">admin@attensys.id</p>
            </div>
            <a href="{{ route('logout') }}" class="tooltip-wrap">
                <svg class="w-4 h-4 text-slate-400 hover:text-red-400 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
                    <h1 class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent" style="font-family:'Sora',sans-serif">Profil Saya</h1>
                    <p class="text-xs text-slate-400">Kelola informasi Super Admin Anda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6">
        <!-- Hero Card -->
        <div class="mb-8 fade-slide-up">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl p-8 md:p-12">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/20 rounded-full -mr-48 -mt-48 animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/10 rounded-full -ml-36 -mb-36 animate-pulse delay-1000"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-8">
                    <div class="flex-shrink-0 group">
                        <div class="relative">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-indigo-300 to-purple-300 animate-ping opacity-75"></div>
                            <div class="relative w-32 h-32 rounded-full bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-sm border-4 border-white/50 flex items-center justify-center text-5xl font-bold text-white shadow-2xl transition-transform group-hover:scale-105 duration-300">
                                SA
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 tracking-tight" style="font-family:'Sora',sans-serif">Super Administrator</h1>
                        <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">
                                Full System Access
                            </span>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">
                                Owner
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 fade-slide-up" style="animation-delay: 0.1s">
            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Email</p>
                        <p class="text-slate-900 font-semibold text-sm truncate max-w-[180px]">admin@attensys.id</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Akses Sistem</p>
                        <p class="text-slate-900 font-semibold text-sm">Full Access</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Status</p>
                        <p class="text-slate-900 font-semibold text-sm">Active</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Role</p>
                        <p class="text-slate-900 font-semibold text-sm">Super Admin</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Profile Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-slide-up" style="animation-delay: 0.2s">
            
            <!-- Left: Detailed Information -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6">
                    <div class="mb-6 pb-4 border-b border-slate-200">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent" style="font-family:'Sora',sans-serif">Informasi Sistem</h2>
                        <p class="text-sm text-slate-500 mt-1">Data dan konfigurasi Super Admin</p>
                    </div>

                    <div class="space-y-5">
                        <!-- Akun Super Admin -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Username</p>
                                <p class="text-base text-slate-900 font-semibold">Super Administrator</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Email Sistem</p>
                                <p class="text-base text-slate-900 font-semibold break-all">admin@attensys.id</p>
                            </div>
                        </div>

                        <!-- Akses Sistem -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Tingkat Akses</p>
                                <p class="text-base text-slate-900 font-semibold">Full System Access</p>
                            </div>
                        </div>

                        <!-- Perihal -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-100 to-pink-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Fungsi Utama</p>
                                <p class="text-base text-slate-900 font-semibold">Manajemen Seluruh Sistem</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Status & Permissions -->
            <div>
                <div class="glass-card rounded-2xl p-6 sticky top-24">
                    <div class="mb-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">Status Sistem</h3>
                        <p class="text-xs text-slate-500 mt-1">Status akun Super Admin</p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></div>
                                <p class="text-sm font-semibold text-green-900">Sistem Aktif</p>
                            </div>
                            <p class="text-xs text-green-700">Semua fitur sistem berfungsi normal</p>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <p class="text-xs text-blue-600 font-medium mb-1">PERIZINAN</p>
                            <div class="space-y-1 text-xs text-blue-700">
                                <p>✓ Manajemen Akun Pengguna</p>
                                <p>✓ Manajemen Divisi</p>
                                <p>✓ Akses Laporan Sistem</p>
                                <p>✓ Kontrol Penuh Database</p>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                            <p class="text-xs text-slate-600 font-medium mb-2">Versi Sistem</p>
                            <p class="text-sm font-semibold text-slate-900">ATTENSYS v1.0.0</p>
                        </div>

                        <button class="w-full mt-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-2 rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            Pengaturan Sistem
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard/super_admin_dashboard.js') }}"></script>
</body>
</html>
