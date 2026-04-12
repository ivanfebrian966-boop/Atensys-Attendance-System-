<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — ATTENSYS</title>
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
</head>
<body>

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

        <a href="#" class="nav-item active" onclick="showTab('dashboard',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a4 4 0 014-4h10a4 4 0 014 4v10a4 4 0 01-4 4H7a4 4 0 01-4-4V7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6M9 15h4"/>
            </svg>
            Dashboard
        </a>

        <p class="nav-section-label">Manajemen Akun</p>

        <a href="#" class="nav-item" onclick="showTab('employees',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Akun Karyawan
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(99,102,241,0.2);color:#a5b4fc">{{ count($employees) }}</span>
        </a>

        <a href="#" class="nav-item" onclick="showTab('admins',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Akun Admin HR
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(6,182,212,0.2);color:#67e8f9">{{ count($hr_admins) }}</span>
        </a>

        <a href="#" class="nav-item" onclick="showTab('divisions',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Data Divisi
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(139,92,246,0.2);color:#c4b5fd">{{ count($divisions) }}</span>
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
        <div class="px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <!-- Hamburger (mobile) -->
                <button class="lg:hidden p-2 rounded-xl hover:bg-slate-100 transition" onclick="openSidebar()">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif" id="pageTitle">Dashboard</h1>
                    <p class="text-xs text-slate-400">Sabtu, 04 April 2026</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <!-- Profile -->
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-white" style="background:linear-gradient(135deg,#6366f1,#06b6d4);font-family:'Sora',sans-serif">SA</div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800" style="font-family:'Sora',sans-serif">Super Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- CONTENT AREA -->
    <div class="p-6">

        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl flex items-center gap-3 fade-up">
                <span class="text-xl">✅</span>
                <div>
                    <p class="font-bold text-sm">Berhasil!</p>
                    <p class="text-xs">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl flex items-center gap-3 fade-up">
                <span class="text-xl">⚠️</span>
                <div>
                    <p class="font-bold text-sm">Kesalahan!</p>
                    <p class="text-xs">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- ===== TAB: DASHBOARD ===== -->
        <div id="tab-dashboard">
            <!-- Stat Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="stat-card indigo fade-up d1">
                    <div class="stat-icon" style="background:#eef2ff">📋</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">{{ count($employees) }}</p>
                    <p class="text-sm text-slate-500 mt-1">Total Karyawan</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">Terdaftar</p>
                </div>
                <div class="stat-card cyan fade-up d2">
                    <div class="stat-icon" style="background:#ecfeff">🛡️</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">{{ count($hr_admins) }}</p>
                    <p class="text-sm text-slate-500 mt-1">Admin HR</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">Terdaftar</p>
                </div>
            </div>

            <!-- Recent activity + Summary -->
            <div class="grid lg:grid-cols-3 gap-4">
                <!-- Recent Accounts -->
                <div class="lg:col-span-2 panel fade-up d5">
                    <div class="panel-header">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Akun Terbaru</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Akun yang baru ditambahkan</p>
                        </div>
                        <button class="btn-primary text-xs" onclick="showTab('employees',document.querySelector('.nav-item:nth-child(5)'))">
                            Lihat Semua
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Divisi</th>
                                    <th>Status</th>
                                    <th>Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($recent_users as $ru)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                                                {{ substr($ru->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.8rem">{{ $ru->name }}</p>
                                                <p class="text-slate-400" style="font-size:0.72rem">{{ $ru->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($ru->role === 'hr_admin')
                                            <span class="badge badge-hr">Admin HR</span>
                                        @else
                                            <span class="badge badge-employee">Karyawan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ru->role === 'hr_admin')
                                            <span class="text-slate-600 text-xs">Semua Divisi</span>
                                        @else
                                            <span class="text-slate-600 text-xs">{{ $ru->employee->division->division_name ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-active">● Aktif</span></td>
                                    <td><span class="text-slate-400 text-xs">{{ $ru->created_at->format('M Y') }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="space-y-4 fade-up d6">
                    <!-- Status ringkasan -->
                    <div class="panel p-5">
                        <h3 class="font-bold text-slate-900 text-sm mb-4" style="font-family:'Sora',sans-serif">Status Akun</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm text-slate-600">Aktif</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 rounded-full bg-emerald-100 w-24 overflow-hidden">
                                        <div class="h-full rounded-full bg-emerald-500" style="width:100%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800" style="font-family:'Sora',sans-serif">{{ count($employees) + count($hr_admins) }}</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                    <span class="text-sm text-slate-600">Pending</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 rounded-full bg-amber-100 w-24 overflow-hidden">
                                        <div class="h-full rounded-full bg-amber-400" style="width:10%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800" style="font-family:'Sora',sans-serif">5</span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                    <span class="text-sm text-slate-600">Nonaktif</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 rounded-full bg-red-100 w-24 overflow-hidden">
                                        <div class="h-full rounded-full bg-red-400" style="width:2%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800" style="font-family:'Sora',sans-serif">1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== TAB: KARYAWAN ===== -->
        <div id="tab-employees" class="hidden">
            <div class="panel fade-up d1">
                <div class="panel-header">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Manajemen Akun Karyawan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($employees) }} total karyawan terdaftar</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Search -->
                        <div class="relative">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Cari karyawan..." class="search-input" oninput="filterTable(this,'employee-table')">
                        </div>
                        <!-- Filter -->
                        <select class="search-input" style="padding-left:14px;width:140px" onchange="filterByStatus(this,'employee-table')">
                            <option value="">Semua Status</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                            <option value="Pending">Pending</option>
                        </select>
                        <button class="btn-primary" onclick="openModal('modalAddEmployee')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Karyawan
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table" id="employee-table">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>NIP</th>
                                <th>Divisi</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                            <tr data-status="Aktif" 
                                data-id="{{ $emp->id }}"
                                data-nip="{{ $emp->employee->nip ?? '' }}"
                                data-name="{{ $emp->name }}"
                                data-email="{{ $emp->email }}"
                                data-division="{{ $emp->employee->division_id ?? '' }}"
                                data-jabatan="{{ $emp->position ?? 'Staf' }}"
                                data-no_hp="{{ $emp->employee->no_hp ?? '' }}"
                                data-alamat="{{ $emp->employee->alamat ?? '' }}"
                                data-status="Aktif">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                                            {{ substr($emp->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">{{ $emp->name }}</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">{{ $emp->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">{{ $emp->employee->nip ?? '-' }}</span></td>
                                <td><span class="text-slate-600 text-sm">{{ $emp->employee->division->division_name ?? '-' }}</span></td>
                                <td><span class="text-slate-600 text-sm">{{ $emp->position ?? 'Staf' }}</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">{{ $emp->created_at->format('M Y') }}</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditEmployee(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item danger" onclick="confirmDelete(this, 'employee')">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Total {{ count($employees) }} data</p>
                </div>
            </div>
        </div>

        <!-- ===== TAB: ADMIN HR ===== -->
        <div id="tab-admins" class="hidden">
            <div class="panel fade-up d1">
                <div class="panel-header">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Manajemen Akun Admin HR</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($hr_admins) }} Admin HR aktif mengelola sistem</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="relative">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Cari admin HR..." class="search-input" oninput="filterTable(this,'admin-table')">
                        </div>
                        <button class="btn-primary" onclick="openModal('modalAddAdmin')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Admin HR
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table" id="admin-table">
                        <thead>
                            <tr>
                                <th>Admin HR</th>
                                <th>ID Admin</th>
                                <th>Dikelola</th>
                                <th>Hak Akses</th>
                                <th>Status</th>
                                <th>Terakhir Login</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hr_admins as $admin)
                            <tr data-id="{{ $admin->id }}" data-name="{{ $admin->name }}" data-email="{{ $admin->email }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#06b6d4,#0891b2)">
                                            {{ substr($admin->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">{{ $admin->name }}</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">{{ $admin->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">ADM-{{ sprintf('%03d', $admin->id) }}</span></td>
                                <td><span class="text-slate-600 text-sm">Semua Divisi</span></td>
                                <td><span class="badge badge-admin">Full Access</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">Aktif Hari ini</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditAdmin(this)">Edit</button>
                                        <button class="btn-ghost py-1.5 px-2 text-xs" style="color:#ef4444" onclick="confirmDelete(this, 'admin')">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Total {{ count($hr_admins) }} data</p>
                </div>
            </div>
        </div>

        <!-- ===== TAB: DIVISI ===== -->
        <div id="tab-divisions" class="hidden">
            <div class="panel fade-up d1">
                <div class="panel-header">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Manajemen Data Divisi</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($divisions) }} divisi aktif di perusahaan</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="relative">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Cari divisi..." class="search-input" oninput="filterTable(this,'division-table')">
                        </div>
                        <button class="btn-primary" onclick="openModal('modalAddDivision')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Divisi
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table" id="division-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Divisi</th>
                                <th>Jumlah Karyawan</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($divisions as $index => $div)
                            <tr>
                                <td><span class="text-slate-400 text-xs font-mono">{{ $index + 1 }}</span></td>
                                <td>
                                    <span class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.85rem">{{ $div->division_name }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-employee">{{ $div->employees->count() ?? 0 }} Karyawan</span>
                                </td>
                                <td><span class="text-slate-400 text-xs">{{ $div->created_at->format('d M Y') }}</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" 
                                                onclick="openEditDivision({{ $div->id }}, '{{ $div->division_name }}')">
                                            Edit
                                        </button>
                                        <form action="{{ route('super_admin.delete_division', $div->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost py-1.5 px-2 text-xs" style="color:#ef4444">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Total {{ count($divisions) }} data</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== MODAL: Tambah Karyawan ===== -->
<div class="modal-overlay" id="modalAddEmployee" onclick="closeModalOutside(event,'modalAddEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Tambah Akun Karyawan</h3>
                <p class="text-sm text-slate-400 mt-1">Isi data untuk membuat akun karyawan baru</p>
            </div>
            <button onclick="closeModal('modalAddEmployee')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_employee') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama lengkap karyawan" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@attensys.id" value="{{ old('email') }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" placeholder="Contoh: 19900101xxxx" value="{{ old('nip') }}" required>
                    @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-field">
                    <label class="form-label">Divisi</label>
                    <select name="division_id" class="form-select" required>
                        <option value="">Pilih Divisi</option>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-input" placeholder="Jabatan / posisi" value="{{ old('jabatan') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="no_hp" class="form-input" placeholder="08xxxx" value="{{ old('no_hp') }}" required>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-input" placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Password Sementara</label>
                    <input type="password" name="password" class="form-input" placeholder="Password (min 8 karakter)" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddEmployee')">Batal</button>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Tambah Admin HR ===== -->
<div class="modal-overlay" id="modalAddAdmin" onclick="closeModalOutside(event,'modalAddAdmin')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Tambah Akun Admin HR</h3>
                <p class="text-sm text-slate-400 mt-1">Buat akun baru dengan akses manajemen HR</p>
            </div>
            <button onclick="closeModal('modalAddAdmin')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_hr_admin') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama lengkap admin HR" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@attensys.id" value="{{ old('email') }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Password Sementara</label>
                    <input type="password" name="password" class="form-input" placeholder="Password (min 8 karakter)" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddAdmin')">Batal</button>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Admin HR
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Tambah Divisi ===== -->
<div class="modal-overlay" id="modalAddDivision" onclick="closeModalOutside(event,'modalAddDivision')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Tambah Divisi Baru</h3>
                <p class="text-sm text-slate-400 mt-1">Tambahkan divisi baru ke dalam sistem</p>
            </div>
            <button onclick="closeModal('modalAddDivision')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_division') }}">
            @csrf
            <div class="form-field">
                <label class="form-label">Nama Divisi</label>
                <input type="text" name="division_name" class="form-input" placeholder="Contoh: IT Support, Marketing, dll" required>
                @error('division_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddDivision')">Batal</button>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Divisi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Edit Divisi ===== -->
<div class="modal-overlay" id="modalEditDivision" onclick="closeModalOutside(event,'modalEditDivision')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Divisi</h3>
                <p class="text-sm text-slate-400 mt-1">Perbarui nama divisi</p>
            </div>
            <button onclick="closeModal('modalEditDivision')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditDivision" method="POST">
            @csrf
            <div class="form-field">
                <label class="form-label">Nama Divisi</label>
                <input type="text" name="division_name" id="edit_division_name" class="form-input" required>
                @error('division_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditDivision')">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Edit Karyawan ===== -->
<div class="modal-overlay" id="modalEditEmployee" onclick="closeModalOutside(event,'modalEditEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Akun Karyawan</h3>
                <p class="text-sm text-slate-400 mt-1">Perbarui informasi akun karyawan</p>
            </div>
            <button onclick="closeModal('modalEditEmployee')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditEmployee" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_emp_name" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_emp_email" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" id="edit_emp_nip" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Divisi</label>
                    <select name="division_id" id="edit_emp_division" class="form-select" required>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->id }}">{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" id="edit_emp_jabatan" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="no_hp" id="edit_emp_no_hp" class="form-input" required>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" id="edit_emp_alamat" class="form-input" required></textarea>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_emp_status" class="form-select" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Ganti Password (Opsional)</label>
                    <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditEmployee')">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Edit Admin HR ===== -->
<div class="modal-overlay" id="modalEditAdmin" onclick="closeModalOutside(event,'modalEditAdmin')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Admin HR</h3>
                <p class="text-sm text-slate-400 mt-1">Perbarui akun admin HR</p>
            </div>
            <button onclick="closeModal('modalEditAdmin')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditAdmin" method="POST">
            @csrf
            <div class="form-field mb-4">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="edit_admin_name" class="form-input" required>
            </div>
            <div class="form-field mb-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="edit_admin_email" class="form-input" required>
            </div>
            <div class="form-field mb-4">
                <label class="form-label">Ganti Password (Opsional)</label>
                <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditAdmin')">Batal</button>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Form Hapus Hidden -->
<form id="formDelete" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

<!-- ===== TOAST NOTIF ===== -->
<div id="toast" class="fixed bottom-6 right-6 z-[200] opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-medium" style="font-family:'DM Sans',sans-serif">
        <span id="toastIcon">✅</span>
        <span id="toastMsg">Berhasil disimpan!</span>
    </div>
</div>

<script>
    // Auto open modal on error
    @if(session('error_modal'))
        document.addEventListener('DOMContentLoaded', function() {
            openModal('{{ session('error_modal') }}');
        });
    @endif
</script>

<script src="{{ asset('js/Super_admin/dashboard_super_admin.js') }}"></script>
</body>
</html>