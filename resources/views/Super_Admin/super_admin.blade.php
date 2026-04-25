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
<x-loader></x-loader>
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

        <p class="nav-section-label">Account Management</p>

        <a href="#" class="nav-item" onclick="showTab('employees',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Employee Accounts
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(99,102,241,0.2);color:#a5b4fc">{{ count($employees) }}</span>
        </a>

        <a href="#" class="nav-item" onclick="showTab('admins',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Admin HR Accounts
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(6,182,212,0.2);color:#67e8f9">{{ count($hr_admins) }}</span>
        </a>

        <a href="#" class="nav-item" onclick="showTab('divisions',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Division Data
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(139,92,246,0.2);color:#c4b5fd">{{ count($divisions) }}</span>
        </a>

        <p class="nav-section-label">Account</p>

        <a href="#" class="nav-item {{ (isset($active_tab) && $active_tab == 'profile') ? 'active' : '' }}" onclick="showTab('profile',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
    </nav>

    <!-- User info -->
    <div class="mx-4 mb-4 p-3 rounded-2xl relative" style="z-index:1;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0" style="background:linear-gradient(135deg,#6366f1,#06b6d4);font-family:'Sora',sans-serif">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate" style="font-family:'Sora',sans-serif">{{ Auth::user()->name }}</p>
                <p class="text-slate-400 text-xs truncate">{{ Auth::user()->email }}</p>
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
                    <p class="text-xs text-slate-400" id="realtime-date">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <!-- Profile -->
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-white" style="background:linear-gradient(135deg,#6366f1,#06b6d4);font-family:'Sora',sans-serif">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800" style="font-family:'Sora',sans-serif">{{ Auth::user()->name }}</p>
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
                    <p class="font-bold text-sm">Success!</p>
                    <p class="text-xs">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl flex items-center gap-3 fade-up">
                <span class="text-xl">⚠️</span>
                <div>
                    <p class="font-bold text-sm">Error!</p>
                    <p class="text-xs">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- ===== TAB: DASHBOARD ===== -->
        <div id="tab-dashboard">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Karyawan -->
                <div class="stat-card indigo fade-up d1">
                    <div class="stat-icon" style="background:#eef2ff">📋</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">{{ count($employees) }}</p>
                    <p class="text-sm text-slate-500 mt-1">Total Employees</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">Registered</p>
                </div>
                <!-- Admin HR -->
                <div class="stat-card cyan fade-up d2">
                    <div class="stat-icon" style="background:#ecfeff">🛡️</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">{{ count($hr_admins) }}</p>
                    <p class="text-sm text-slate-500 mt-1">Admin HR</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">Registered</p>
                </div>
                <!-- Total Divisi -->
                <div class="stat-card purple fade-up d3">
                    <div class="stat-icon" style="background:#f5f3ff">🏢</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">{{ count($divisions) }}</p>
                    <p class="text-sm text-slate-500 mt-1">Total Divisions</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">Added</p>
                </div>
                <!-- Status Akun -->
                <div class="panel p-5 fade-up d4">
                    <h3 class="font-bold text-slate-900 text-sm mb-4" style="font-family:'Sora',sans-serif">Account Status</h3>
                    <div class="space-y-3">
                        @php
                        
                            $total_users = max(($status_counts['aktif'] ?? 0) + ($status_counts['pending'] ?? 0) + ($status_counts['nonaktif'] ?? 0), 1);
                            $aktif_p = (($status_counts['aktif'] ?? 0) / $total_users) * 100;
                            $pending_p = (($status_counts['pending'] ?? 0) / $total_users) * 100;
                            $nonaktif_p = (($status_counts['nonaktif'] ?? 0) / $total_users) * 100;
                        @endphp
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                <span class="text-sm text-slate-600">Active</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-800" style="font-family:'Sora',sans-serif">{{ $status_counts['aktif'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                <span class="text-sm text-slate-600">Pending</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-800" style="font-family:'Sora',sans-serif">{{ $status_counts['pending'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <span class="text-sm text-slate-600">Off</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-800" style="font-family:'Sora',sans-serif">{{ $status_counts['nonaktif'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent activity -->
            <div class="grid lg:grid-cols-1 gap-4">
                <!-- Recent Accounts -->
                <div class="panel fade-up d5">
                    <div class="panel-header">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Recent Accounts</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Newly added accounts</p>
                        </div>
                        <button class="btn-primary text-xs" onclick="showTab('employees', document.querySelectorAll('.nav-item')[1])">
                            View All
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Division</th>
                                    <th>Status</th>
                                    <th>Joined</th>
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
                                        @if($ru->role === 'admin_hr')
                                            <span class="badge badge-hr">Admin HR</span>
                                        @else
                                            <span class="badge badge-employee">Employee</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-slate-600 text-xs">{{ $ru->division->division_name ?? '-' }}</span>
                                    </td>
                                    <td>
                                         @if($ru->status === 'Aktif')
                                             <span class="badge badge-active">● Active</span>
                                         @elseif($ru->status === 'Pending')
                                             <span class="badge" style="background:rgba(251,191,36,0.1);color:#d97706">● Pending</span>
                                         @else
                                             <span class="badge" style="background:rgba(239,68,68,0.1);color:#dc2626">● Nonaktif</span>
                                         @endif
                                     </td>
                                    <td><span class="text-slate-400 text-xs">{{ $ru->created_at->format('M Y') }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- ===== TAB: KARYAWAN ===== -->
        <div id="tab-employees" class="hidden">
            <div class="panel fade-up d1">
                <div class="panel-header">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Employee Account Management</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($employees) }} total employees registered</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Search -->
                        <div class="relative">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Search employees..." class="search-input" oninput="filterTable(this,'employee-table')">
                        </div>
                        <!-- Filter -->
                        <select class="search-input" style="padding-left:14px;width:140px" onchange="filterByStatus(this,'employee-table')">
                            <option value="">All Status</option>
                            <option value="Aktif">Active</option>
                            <option value="Nonaktif">Inactive</option>
                            <option value="Pending">Pending</option>
                        </select>
                        <button class="btn-primary" onclick="openModal('modalAddEmployee')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Employee
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table" id="employee-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>NIP</th>
                                <th>Division</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                            <tr data-status="{{ $emp->status }}" 
                                data-id="{{ $emp->nip }}"
                                data-nip="{{ $emp->nip }}"
                                data-name="{{ $emp->name }}"
                                data-email="{{ $emp->email }}"
                                data-division="{{ $emp->division_id }}"
                                data-jabatan="{{ $emp->position }}"
                                data-no_hp="{{ $emp->no_hp }}"
                                data-alamat="{{ $emp->alamat }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                                            {{ strtoupper(substr($emp->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">{{ $emp->name }}</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">{{ $emp->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">{{ $emp->nip }}</span></td>
                                <td><span class="text-slate-600 text-sm">{{ $emp->division->division_name ?? '-' }}</span></td>
                                <td><span class="text-slate-600 text-sm">{{ $emp->position }}</span></td>
                                <td>
                                    @if($emp->status === 'Aktif')
                                        <span class="badge {{ $emp->status === 'Aktif' ? 'badge-active' : ($emp->status === 'Pending' ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">● Active</span>
                                    @elseif($emp->status === 'Pending')
                                        <span class="badge bg-amber-100 text-amber-600">● Pending</span>
                                    @else
                                        <span class="badge bg-red-100 text-red-600">● Inactive</span>
                                    @endif
                                </td>
                                <td><span class="text-slate-400 text-xs">{{ $emp->created_at->format('M Y') }}</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditEmployee(this)">Edit</button>
                                        <button class="btn-ghost py-1.5 px-2 text-xs" style="color:#ef4444" onclick="confirmDelete(this, 'employee')">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Total {{ count($employees) }} records</p>
                </div>
            </div>
        </div>

        <!-- ===== TAB: ADMIN HR ===== -->
        <div id="tab-admins" class="hidden">
            <div class="panel fade-up d1">
                <div class="panel-header">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Admin HR Account Management</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($hr_admins) }} Admin HR actively managing the system</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="relative">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Search HR admin..." class="search-input" oninput="filterTable(this,'admin-table')">
                        </div>
                        <button class="btn-primary" onclick="openModal('modalAddAdmin')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Admin HR
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table" id="admin-table">
                        <thead>
                            <tr>
                                <th>Admin HR</th>
                                <th>NIP</th>
                                <th>Managed</th>
                                <th>Access Rights</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hr_admins as $admin)
                            <tr data-id="{{ $admin->nip }}" 
                                data-nip="{{ $admin->nip }}"
                                data-name="{{ $admin->name }}" 
                                data-email="{{ $admin->email }}"
                                data-phone="{{ $admin->no_hp }}"
                                data-address="{{ $admin->alamat }}"
                                data-status="{{ $admin->status }}"
                                data-division="{{ $admin->division_id }}"
                                data-position="{{ $admin->position }}">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#06b6d4,#0891b2)">
                                            {{ strtoupper(substr($admin->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">{{ $admin->name }}</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">{{ $admin->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">{{ $admin->nip }}</span></td>
                                <td><span class="text-slate-600 text-sm">{{ $admin->division->division_name ?? 'All Divisions' }}</span></td>
                                <td><span class="badge badge-admin">Full Access</span></td>
                                <td>
                                    @if($admin->status === 'Aktif')
                                        <span class="badge badge-active">● Active</span>
                                    @elseif($admin->status === 'Pending')
                                        <span class="badge bg-amber-100 text-amber-600">● Pending</span>
                                    @else
                                        <span class="badge bg-red-100 text-red-600">● Inactive</span>
                                    @endif
                                </td>
                                <td><span class="text-slate-400 text-xs">{{ $admin->created_at->format('M Y') }}</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditAdmin(this)">Edit</button>
                                        <button class="btn-ghost py-1.5 px-2 text-xs" style="color:#ef4444" onclick="confirmDelete(this, 'admin')">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Total {{ count($hr_admins) }} records</p>
                </div>
            </div>
        </div>

        <!-- ===== TAB: DIVISI ===== -->
        <div id="tab-divisions" class="hidden">
            <div class="panel fade-up d1">
                <div class="panel-header">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Division Data Management</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($divisions) }} active divisions in the company</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <div class="relative">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Search division..." class="search-input" oninput="filterTable(this,'division-table')">
                        </div>
                        <button class="btn-primary" onclick="openModal('modalAddDivision')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Division
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table" id="division-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Division Name</th>
                                <th>Member Count</th>
                                <th>Created Date</th>
                                <th>Actions</th>
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
                                    <span class="badge badge-employee">{{ $div->employees->count() }} Members</span>
                                </td>
                                <td><span class="text-slate-400 text-xs">{{ $div->created_at->format('d M Y') }}</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" 
                                                onclick="openEditDivision({{ $div->division_id }}, '{{ $div->division_name }}')">
                                            Edit
                                        </button>
                                        <form action="{{ route('super_admin.delete_division', $div->division_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this division?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost py-1.5 px-2 text-xs" style="color:#ef4444">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Total {{ count($divisions) }} records</p>
                </div>
            </div>
        </div>

        <!-- ===== TAB: PROFILE ===== -->
        <div id="tab-profile" class="{{ (isset($active_tab) && $active_tab == 'profile') ? '' : 'hidden' }}">
            <div class="space-y-6">

                {{-- HERO BANNER --}}
                <div class="relative overflow-hidden rounded-3xl shadow-2xl fade-up d1" 
                     style="background: linear-gradient(135deg, #031a40ff 0%, #04378aff 50%, #1c3f7cff 100%)"> 

                    <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-8 p-8 md:p-10">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 group">
                            <div class="relative">
                                <div class="relative w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/50
                                            flex items-center justify-center text-4xl font-bold text-white" style="font-family:'Sora',sans-serif">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="text-center md:text-left flex-1">
                            <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-1">System Administrator</p>
                            <h1 class="text-3xl font-bold text-white leading-tight" style="font-family:'Sora',sans-serif">
                                {{ $user->name }}
                            </h1>
                            <p class="text-white/70 text-sm mt-1">{{ $user->email }}</p>
                            <div class="flex flex-wrap gap-2 justify-center md:justify-start mt-3">
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">{{ $user->position }}</span>
                                <span class="px-3 py-1 bg-slate-500/80 backdrop-blur-sm rounded-full text-sm text-white font-medium">{{ $user->division->division_name }}</span>  
                            </div>
                        </div>
                    </div>
                </div>

                {{-- GRID: Info + Settings --}}
                <div class="grid lg:grid-cols-2 gap-6 fade-up d3">

                    {{-- Personal Info Card --}}
                    <div class="panel p-6">
                        <div class="mb-5 pb-4 border-b border-slate-100 flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Personal Info</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Account details</p>
                            </div>
                            <span class="px-3 py-1 bg-emerald-500/80 backdrop-blur-sm rounded-full text-sm text-white font-medium">● Active</span>
                        </div>

                        <div class="space-y-4">
                            @php
                                $infoItems = [
                                    ['label'=>'Full Name',  'value'=>$user->name,          'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                    ['label'=>'Email',      'value'=>$user->email,          'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                    ['label'=>'Phone',      'value'=>$user->no_hp ?? 'Not set', 'icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a2.42 2.42 0 10-2.897 2.897l-1.029-2.048a1 1 0 00-.756-.502L7.177 6.73A1 1 0 006.28 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2'],
                                    ['label'=>'Address',    'value'=>$user->alamat ?? 'Not set', 'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                                ];
                            @endphp
                            @foreach($infoItems as $item)
                            <div class="flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-widest">{{ $item['label'] }}</p>
                                    <p class="text-sm text-slate-800 font-semibold truncate">{{ $item['value'] }}</p>
                                </div>
                            </div>
                            @endforeach

                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-3 border border-emerald-200">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                        <p class="text-xs font-bold text-emerald-800">Active Account</p>
                                    </div>
                                    <p class="text-[11px] text-emerald-600">Registered since {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Settings Form --}}
                    <div class="panel p-6">
                        <div class="mb-5 pb-4 border-b border-slate-100">
                            <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Profile Settings</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Update your account information and password</p>
                        </div>

                        <form action="{{ route('super_admin.update_profile') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                <div class="form-field">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="form-field">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="form-field">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="no_hp" class="form-input" value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxx">
                                </div>
                                <div class="form-field">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="alamat" class="form-input" value="{{ old('alamat', $user->alamat) }}" placeholder="Full address">
                                </div>
                            </div>

                            <div class="border-t border-slate-100 pt-5">
                                <h4 class="text-sm font-bold text-slate-900 mb-3">Change Password <span class="text-xs font-normal text-slate-400">(optional)</span></h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-field">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password" class="form-input" placeholder="Min 8 characters">
                                    </div>
                                    <div class="form-field">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password">
                                    </div>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-2 italic">Leave blank if you don't want to change the password.</p>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="btn-primary px-8">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
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
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add Employee Account</h3>
                <p class="text-sm text-slate-400 mt-1">Fill in the data to create a new employee account</p>
            </div>
            <button onclick="closeModal('modalAddEmployee')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_employee') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field col-span-2">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Employee full name" value="{{ old('name') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@attensys.id" value="{{ old('email') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" placeholder="e.g.: 19900101xxxx" value="{{ old('nip') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Division</label>
                    <select name="division_id" class="form-select" required>
                        <option value="">Select Division</option>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position</label>
                    <input type="text" name="jabatan" class="form-input" placeholder="Position / role" value="{{ old('jabatan') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="no_hp" class="form-input" placeholder="08xxxx" value="{{ old('no_hp') }}" required>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="alamat" class="form-input" placeholder="Full address" required>{{ old('alamat') }}</textarea>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Temporary Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Password (min 8 characters)" required>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddEmployee')">Cancel</button>
                <button type="submit" class="btn-primary">Save Account</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Tambah Admin HR ===== -->
<div class="modal-overlay" id="modalAddAdmin" onclick="closeModalOutside(event,'modalAddAdmin')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add Admin HR Account</h3>
                <p class="text-sm text-slate-400 mt-1">Create a new account with HR management access</p>
            </div>
            <button onclick="closeModal('modalAddAdmin')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_hr_admin') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Admin full name" required>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" placeholder="NIP Admin" required>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@attensys.id" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Division</label>
                    <select name="division_id" class="form-select" required>
                        <option value="">Select Division</option>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position</label>
                    <input type="text" name="position" class="form-input" placeholder="e.g.: HR Manager" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" placeholder="08xxxx" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-input" placeholder="Full address" required></textarea>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Temporary Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Password (min 8 characters)" required>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddAdmin')">Cancel</button>
                <button type="submit" class="btn-primary">Save Account</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Tambah Divisi ===== -->
<div class="modal-overlay" id="modalAddDivision" onclick="closeModalOutside(event,'modalAddDivision')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add New Division</h3>
                <p class="text-sm text-slate-400 mt-1">Add a new division to the system</p>
            </div>
            <button onclick="closeModal('modalAddDivision')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_division') }}">
            @csrf
            <div class="form-field">
                <label class="form-label">Division Name</label>
                <input type="text" name="division_name" class="form-input" placeholder="e.g.: IT Support, Marketing, etc." required>
                @error('division_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddDivision')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Division
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
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Division</h3>
                <p class="text-sm text-slate-400 mt-1">Update division name</p>
            </div>
            <button onclick="closeModal('modalEditDivision')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditDivision" method="POST">
            @csrf
            <div class="form-field">
                <label class="form-label">Division Name</label>
                <input type="text" name="division_name" id="edit_division_name" class="form-input" required>
                @error('division_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditDivision')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Edit Karyawan ===== -->
<div class="modal-overlay" id="modalEditEmployee" onclick="closeModalOutside(event,'modalEditEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Employee Account</h3>
                <p class="text-sm text-slate-400 mt-1">Update employee account information</p>
            </div>
            <button onclick="closeModal('modalEditEmployee')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditEmployee" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field col-span-2">
                    <label class="form-label">Full Name</label>
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
                    <label class="form-label">Division</label>
                    <select name="division_id" id="edit_emp_division" class="form-select" required>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position</label>
                    <input type="text" name="jabatan" id="edit_emp_jabatan" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="no_hp" id="edit_emp_no_hp" class="form-input" required>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="alamat" id="edit_emp_alamat" class="form-input" required></textarea>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_emp_status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Change Password (Optional)</label>
                    <input type="password" name="password" class="form-input" placeholder="Leave blank to keep unchanged">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditEmployee')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
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
                <p class="text-sm text-slate-400 mt-1">Update HR admin account</p>
            </div>
            <button onclick="closeModal('modalEditAdmin')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditAdmin" method="POST">
            @csrf
            <div class="form-field mb-4">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" id="edit_admin_name" class="form-input" required>
            </div>
            <div class="form-field mb-4">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" id="edit_admin_nip" class="form-input" required>
            </div>
            <div class="form-field mb-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="edit_admin_email" class="form-input" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-field">
                    <label class="form-label">Division</label>
                    <select name="division_id" id="edit_admin_division" class="form-select" required>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position / Role</label>
                    <input type="text" name="position" id="edit_admin_position" class="form-input" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="edit_admin_phone" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_admin_status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" id="edit_admin_address" class="form-input" required></textarea>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Change Password (Optional)</label>
                    <input type="password" name="password" class="form-input" placeholder="Leave blank to keep unchanged">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditAdmin')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
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
        <span id="toastMsg">Saved successfully!</span>
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