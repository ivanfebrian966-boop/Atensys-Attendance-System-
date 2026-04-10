<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — ATTENSYS</title>
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
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: #f1f5f9; }
        h1,h2,h3,.font-display { font-family: 'Sora', sans-serif; }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(160deg, #0f172a 0%, #1e1b4b 55%, #0e2a4a 100%);
            width: 260px;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transition: transform 0.3s ease;
        }
        .sidebar-blob-1 {
            position: absolute;
            top: -40px; right: -1px;
            width: 200px; height: 200px;
            background: rgba(99,102,241,0.15);
            border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
            pointer-events: none;
        }
        .sidebar-blob-2 {
            position: absolute;
            bottom: 40px; left: -30px;
            width: 150px; height: 150px;
            background: rgba(6,182,212,0.1);
            border-radius: 40% 60% 45% 55% / 60% 40% 55% 50%;
            pointer-events: none;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            border-radius: 12px;
            color: rgba(255,255,255,0.55);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            margin: 2px 12px;
            position: relative;
        }
        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.9);
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(99,102,241,0.3), rgba(6,182,212,0.15));
            color: white;
            border: 1px solid rgba(99,102,241,0.3);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            border-radius: 0 4px 4px 0;
        }
        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 16px 20px 6px;
            font-family: 'Sora', sans-serif;
        }

        /* Main */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            padding: 0;
        }

        /* Topbar */
        .topbar {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226,232,240,0.8);
            position: sticky;
            top: 0; z-index: 30;
        }

        /* Stat cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(99,102,241,0.12);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 0 20px 0 80px;
            opacity: 0.08;
        }
        .stat-card.indigo::before { background: #6366f1; }
        .stat-card.cyan::before { background: #06b6d4; }
        .stat-card.purple::before { background: #8b5cf6; }
        .stat-card.green::before { background: #10b981; }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 16px;
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-family: 'Sora', sans-serif;
        }
        .data-table td {
            padding: 14px 16px;
            font-size: 0.875rem;
            color: #334155;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr {
            transition: background 0.15s;
            cursor: pointer;
        }
        .data-table tbody tr:hover { background: #f8fafc; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
        }
        .badge-active { background: #ecfdf5; color: #059669; }
        .badge-inactive { background: #fef2f2; color: #dc2626; }
        .badge-pending { background: #fffbeb; color: #d97706; }
        .badge-admin { background: #eef2ff; color: #6366f1; }
        .badge-hr { background: #f0f9ff; color: #0284c7; }
        .badge-employee { background: #f0fdf4; color: #16a34a; }

        /* Avatar */
        .avatar {
            width: 34px; height: 34px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: 'Sora', sans-serif;
            color: white;
            flex-shrink: 0;
        }

        /* Tab */
        .tab-btn {
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-family: 'Sora', sans-serif;
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: white;
            box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        }
        .tab-btn:not(.active) {
            background: transparent;
            color: #64748b;
        }
        .tab-btn:not(.active):hover {
            background: #f1f5f9;
            color: #374151;
        }

        /* Input */
        .search-input {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 9px 16px 9px 40px;
            font-size: 0.875rem;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'DM Sans', sans-serif;
            width: 220px;
        }
        .search-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            background: white;
        }
        .search-input::placeholder { color: #94a3b8; }

        /* Btn */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: white;
            border: none;
            padding: 9px 20px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: 'Sora', sans-serif;
            transition: opacity 0.2s, transform 0.15s;
            box-shadow: 0 4px 14px rgba(99,102,241,0.25);
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-ghost {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            color: #64748b;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-ghost:hover { border-color: #6366f1; color: #6366f1; background: #eef2ff; }
        .btn-danger {
            background: transparent;
            border: 1.5px solid #fecaca;
            color: #dc2626;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-danger:hover { background: #fef2f2; border-color: #dc2626; }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.5);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s;
        }
        .modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }
        .modal-box {
            background: white;
            border-radius: 24px;
            padding: 32px;
            width: 480px;
            max-width: 95vw;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            transform: translateY(20px);
            transition: transform 0.25s ease;
        }
        .modal-overlay.open .modal-box {
            transform: translateY(0);
        }
        .form-field {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            font-family: 'Sora', sans-serif;
        }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            background: white;
        }
        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s;
        }
        .form-select:focus { border-color: #6366f1; }

        /* Card panel */
        .panel {
            background: white;
            border-radius: 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .panel-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Animate */
        .fade-up {
            opacity: 0;
            transform: translateY(16px);
            animation: fadeUp 0.6s ease forwards;
        }
        @keyframes fadeUp { to { opacity:1; transform:translateY(0); } }
        .d1 { animation-delay: 0.05s; }
        .d2 { animation-delay: 0.1s; }
        .d3 { animation-delay: 0.15s; }
        .d4 { animation-delay: 0.2s; }
        .d5 { animation-delay: 0.25s; }
        .d6 { animation-delay: 0.3s; }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.4);
            z-index: 39;
        }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-content { margin-left: 0; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Tooltip */
        .tooltip-wrap { position: relative; }
        .tooltip-wrap .tooltip-text {
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 10;
        }
        .tooltip-wrap:hover .tooltip-text { opacity: 1; }

        /* Dropdown */
        .dropdown-menu {
            position: absolute;
            right: 0; top: calc(100% + 8px);
            background: white;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 8px;
            min-width: 160px;
            z-index: 50;
            display: none;
        }
        .dropdown-menu.open { display: block; }
        .dropdown-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 0.8rem;
            color: #374151;
            cursor: pointer;
            transition: background 0.15s;
            font-family: 'DM Sans', sans-serif;
        }
        .dropdown-item:hover { background: #f8fafc; }
        .dropdown-item.danger { color: #dc2626; }
        .dropdown-item.danger:hover { background: #fef2f2; }

        /* Mini chart bars */
        .chart-bar-wrap {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 36px;
        }
        .chart-bar {
            width: 8px;
            border-radius: 4px 4px 0 0;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .chart-bar:hover { opacity: 1; }
    </style>
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
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(99,102,241,0.2);color:#a5b4fc">48</span>
        </a>

        <a href="#" class="nav-item" onclick="showTab('admins',this)">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Akun Admin HR
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(6,182,212,0.2);color:#67e8f9">5</span>
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
            <a href="/login" class="tooltip-wrap">
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
    </div>

    <!-- CONTENT AREA -->
    <div class="p-6">

        <!-- ===== TAB: DASHBOARD ===== -->
        <div id="tab-dashboard">
            <!-- Stat Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="stat-card indigo fade-up d1">
                    <div class="stat-icon" style="background:#eef2ff">📋</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">48</p>
                    <p class="text-sm text-slate-500 mt-1">Total Karyawan</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">↑ +3 bulan ini</p>
                </div>
                <div class="stat-card cyan fade-up d2">
                    <div class="stat-icon" style="background:#ecfeff">🛡️</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">5</p>
                    <p class="text-sm text-slate-500 mt-1">Admin HR</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">↑ +1 bulan ini</p>
                </div>
                <div class="stat-card purple fade-up d3">
                    <div class="stat-icon" style="background:#ede9fe">🏢</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">7</p>
                    <p class="text-sm text-slate-500 mt-1">Total Divisi</p>
                    <p class="text-xs text-slate-400 font-semibold mt-2">Stabil</p>
                </div>
                <div class="stat-card green fade-up d4">
                    <div class="stat-icon" style="background:#ecfdf5">✅</div>
                    <p class="text-2xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">42</p>
                    <p class="text-sm text-slate-500 mt-1">Hadir Hari Ini</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-2">87.5% kehadiran</p>
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
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar" style="background:linear-gradient(135deg,#6366f1,#818cf8)">AR</div>
                                            <div>
                                                <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.8rem">Andi Rahman</p>
                                                <p class="text-slate-400" style="font-size:0.72rem">andi@attensys.id</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-employee">Karyawan</span></td>
                                    <td><span class="text-slate-600 text-xs">Engineering</span></td>
                                    <td><span class="badge badge-active">● Aktif</span></td>
                                    <td><span class="text-slate-400 text-xs">2 Apr 2026</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar" style="background:linear-gradient(135deg,#06b6d4,#0891b2)">SW</div>
                                            <div>
                                                <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.8rem">Siti Wulandari</p>
                                                <p class="text-slate-400" style="font-size:0.72rem">siti@attensys.id</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-hr">Admin HR</span></td>
                                    <td><span class="text-slate-600 text-xs">HR</span></td>
                                    <td><span class="badge badge-active">● Aktif</span></td>
                                    <td><span class="text-slate-400 text-xs">1 Apr 2026</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">BP</div>
                                            <div>
                                                <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.8rem">Budi Pratama</p>
                                                <p class="text-slate-400" style="font-size:0.72rem">budi@attensys.id</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-employee">Karyawan</span></td>
                                    <td><span class="text-slate-600 text-xs">Finance</span></td>
                                    <td><span class="badge badge-pending">● Pending</span></td>
                                    <td><span class="text-slate-400 text-xs">31 Mar 2026</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="space-y-4 fade-up d6">
                    <!-- Absensi chart -->
                    <div class="panel p-5">
                        <h3 class="font-bold text-slate-900 text-sm mb-1" style="font-family:'Sora',sans-serif">Kehadiran Minggu Ini</h3>
                        <p class="text-xs text-slate-400 mb-4">Senin – Sabtu</p>
                        <div class="chart-bar-wrap mb-2">
                            <div class="chart-bar" style="height:75%"></div>
                            <div class="chart-bar" style="height:90%"></div>
                            <div class="chart-bar" style="height:65%"></div>
                            <div class="chart-bar" style="height:88%"></div>
                            <div class="chart-bar" style="height:70%"></div>
                            <div class="chart-bar" style="height:87%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">Rata-rata</span>
                                <span class="text-sm font-bold gradient-text" style="font-family:'Sora',sans-serif">87.5%</span>
                            </div>
                        </div>
                    </div>

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
                                        <div class="h-full rounded-full bg-emerald-500" style="width:88%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-800" style="font-family:'Sora',sans-serif">47</span>
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
                        <p class="text-xs text-slate-400 mt-0.5">48 total karyawan terdaftar</p>
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
                            <!-- Row 1 -->
                            <tr data-status="Aktif">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#6366f1,#818cf8)">AR</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Andi Rahman</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">andi@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">EMP-001</span></td>
                                <td><span class="text-slate-600 text-sm">Engineering</span></td>
                                <td><span class="text-slate-600 text-sm">Backend Dev</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">Jan 2025</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditEmployee(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item" onclick="resetPassword()">🔑 Reset Password</div>
                                                <div class="dropdown-item" onclick="toggleStatus(this)">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger" onclick="confirmDelete(this)">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr data-status="Aktif">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#06b6d4,#0e7490)">DS</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Dewi Susanti</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">dewi@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">EMP-002</span></td>
                                <td><span class="text-slate-600 text-sm">Marketing</span></td>
                                <td><span class="text-slate-600 text-sm">Marketing Mgr</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">Mar 2024</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditEmployee(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item" onclick="resetPassword()">🔑 Reset Password</div>
                                                <div class="dropdown-item" onclick="toggleStatus(this)">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger" onclick="confirmDelete(this)">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 3 -->
                            <tr data-status="Pending">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">BP</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Budi Pratama</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">budi@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">EMP-003</span></td>
                                <td><span class="text-slate-600 text-sm">Finance</span></td>
                                <td><span class="text-slate-600 text-sm">Akuntan</span></td>
                                <td><span class="badge badge-pending">● Pending</span></td>
                                <td><span class="text-slate-400 text-xs">Mar 2026</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditEmployee(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item" onclick="resetPassword()">🔑 Reset Password</div>
                                                <div class="dropdown-item" onclick="toggleStatus(this)">🔄 Aktifkan</div>
                                                <div class="dropdown-item danger" onclick="confirmDelete(this)">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 4 -->
                            <tr data-status="Nonaktif">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:#94a3b8">RH</div>
                                        <div>
                                            <p class="font-semibold text-slate-400" style="font-family:'Sora',sans-serif;font-size:0.82rem">Rini Handayani</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">rini@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-400 text-xs font-mono">EMP-004</span></td>
                                <td><span class="text-slate-400 text-sm">Operasional</span></td>
                                <td><span class="text-slate-400 text-sm">Staf Ops</span></td>
                                <td><span class="badge badge-inactive">● Nonaktif</span></td>
                                <td><span class="text-slate-400 text-xs">Jun 2023</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditEmployee(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item" onclick="resetPassword()">🔑 Reset Password</div>
                                                <div class="dropdown-item" onclick="toggleStatus(this)">🔄 Aktifkan</div>
                                                <div class="dropdown-item danger" onclick="confirmDelete(this)">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Row 5 -->
                            <tr data-status="Aktif">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#10b981,#059669)">FN</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Fajar Nugroho</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">fajar@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">EMP-005</span></td>
                                <td><span class="text-slate-600 text-sm">Engineering</span></td>
                                <td><span class="text-slate-600 text-sm">Frontend Dev</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">Aug 2024</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditEmployee(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item" onclick="resetPassword()">🔑 Reset Password</div>
                                                <div class="dropdown-item" onclick="toggleStatus(this)">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger" onclick="confirmDelete(this)">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Menampilkan 5 dari 48 data</p>
                    <div class="flex items-center gap-1">
                        <button class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 transition flex items-center justify-center text-sm">‹</button>
                        <button class="w-8 h-8 rounded-lg text-white flex items-center justify-center text-sm font-semibold" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">1</button>
                        <button class="w-8 h-8 rounded-lg text-slate-500 hover:bg-slate-100 transition flex items-center justify-center text-sm">2</button>
                        <button class="w-8 h-8 rounded-lg text-slate-500 hover:bg-slate-100 transition flex items-center justify-center text-sm">3</button>
                        <span class="text-slate-300 text-sm px-1">...</span>
                        <button class="w-8 h-8 rounded-lg text-slate-500 hover:bg-slate-100 transition flex items-center justify-center text-sm">10</button>
                        <button class="w-8 h-8 rounded-lg text-slate-400 hover:bg-slate-100 transition flex items-center justify-center text-sm">›</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== TAB: ADMIN HR ===== -->
        <div id="tab-admins" class="hidden">
            <div class="panel fade-up d1">
                <div class="panel-header">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Manajemen Akun Admin HR</h3>
                        <p class="text-xs text-slate-400 mt-0.5">5 Admin HR aktif mengelola sistem</p>
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
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#06b6d4,#0891b2)">SW</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Siti Wulandari</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">siti@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">ADM-001</span></td>
                                <td><span class="text-slate-600 text-sm">Semua Divisi</span></td>
                                <td><span class="badge badge-admin">Full Access</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">Hari ini, 08:45</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditAdmin(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">🔑 Reset Password</div>
                                                <div class="dropdown-item">🛡 Ubah Hak Akses</div>
                                                <div class="dropdown-item">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">RK</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Rizky Kurniawan</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">rizky@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">ADM-002</span></td>
                                <td><span class="text-slate-600 text-sm">Engineering, Finance</span></td>
                                <td><span class="badge badge-admin">Partial</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">Kemarin, 17:20</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditAdmin(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">🔑 Reset Password</div>
                                                <div class="dropdown-item">🛡 Ubah Hak Akses</div>
                                                <div class="dropdown-item">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706)">NA</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Nita Anjani</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">nita@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">ADM-003</span></td>
                                <td><span class="text-slate-600 text-sm">Marketing, Sales</span></td>
                                <td><span class="badge badge-admin">Partial</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">3 Apr 2026</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditAdmin(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">🔑 Reset Password</div>
                                                <div class="dropdown-item">🛡 Ubah Hak Akses</div>
                                                <div class="dropdown-item">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#10b981,#059669)">HP</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Hendra Putra</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">hendra@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">ADM-004</span></td>
                                <td><span class="text-slate-600 text-sm">Operasional</span></td>
                                <td><span class="badge badge-admin">Partial</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">2 Apr 2026</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditAdmin(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">🔑 Reset Password</div>
                                                <div class="dropdown-item">🛡 Ubah Hak Akses</div>
                                                <div class="dropdown-item">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar" style="background:linear-gradient(135deg,#ec4899,#db2777)">YS</div>
                                        <div>
                                            <p class="font-semibold text-slate-800" style="font-family:'Sora',sans-serif;font-size:0.82rem">Yuni Setiawati</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">yuni@attensys.id</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-slate-500 text-xs font-mono">ADM-005</span></td>
                                <td><span class="text-slate-600 text-sm">HR, Legal</span></td>
                                <td><span class="badge badge-admin">Partial</span></td>
                                <td><span class="badge badge-active">● Aktif</span></td>
                                <td><span class="text-slate-400 text-xs">4 Apr 2026</span></td>
                                <td>
                                    <div class="flex items-center gap-1 relative">
                                        <button class="btn-ghost py-1.5 px-3 text-xs" onclick="openEditAdmin(this)">Edit</button>
                                        <div class="relative">
                                            <button class="btn-ghost py-1.5 px-2 text-xs" onclick="toggleDropdown(this)">⋮</button>
                                            <div class="dropdown-menu">
                                                <div class="dropdown-item">🔑 Reset Password</div>
                                                <div class="dropdown-item">🛡 Ubah Hak Akses</div>
                                                <div class="dropdown-item">🔄 Nonaktifkan</div>
                                                <div class="dropdown-item danger">🗑 Hapus Akun</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
                    <p class="text-sm text-slate-400">Menampilkan 5 dari 5 data</p>
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
        <div class="grid grid-cols-2 gap-4">
            <div class="form-field col-span-2">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-input" placeholder="Nama lengkap karyawan">
            </div>
            <div class="form-field">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" placeholder="email@attensys.id">
            </div>
            <div class="form-field">
                <label class="form-label">Divisi</label>
                <select class="form-select">
                    <option value="">Pilih Divisi</option>
                    <option>Engineering</option>
                    <option>Marketing</option>
                    <option>Finance</option>
                    <option>Operasional</option>
                    <option>HR</option>
                    <option>Legal</option>
                    <option>Sales</option>
                </select>
            </div>
            <div class="form-field">
                <label class="form-label">Jabatan</label>
                <input type="text" class="form-input" placeholder="Jabatan / posisi">
            </div>
            <div class="form-field col-span-2">
                <label class="form-label">Password Sementara</label>
                <input type="password" class="form-input" placeholder="Password awal (akan diminta ganti)">
            </div>
            <div class="form-field">
                <label class="form-label">Status</label>
                <select class="form-select">
                    <option>Aktif</option>
                    <option>Pending</option>
                    <option>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
            <button class="btn-ghost" onclick="closeModal('modalAddEmployee')">Batal</button>
            <button class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Akun
            </button>
        </div>
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
        <div class="grid grid-cols-2 gap-4">
            <div class="form-field col-span-2">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-input" placeholder="Nama lengkap admin HR">
            </div>
            <div class="form-field">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" placeholder="email@attensys.id">
            </div>
            <div class="form-field">
                <label class="form-label">ID Admin</label>
                <input type="text" class="form-input" placeholder="ADM-XXX">
            </div>
            <div class="form-field col-span-2">
                <label class="form-label">Hak Akses Divisi</label>
                <div class="grid grid-cols-3 gap-2 mt-1">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" class="accent-indigo-600 w-4 h-4"> Engineering
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" class="accent-indigo-600 w-4 h-4"> Marketing
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" class="accent-indigo-600 w-4 h-4"> Finance
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" class="accent-indigo-600 w-4 h-4"> Operasional
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" class="accent-indigo-600 w-4 h-4"> HR
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" class="accent-indigo-600 w-4 h-4"> Legal
                    </label>
                </div>
                <label class="flex items-center gap-2 text-sm text-indigo-600 font-semibold cursor-pointer mt-2">
                    <input type="checkbox" class="accent-indigo-600 w-4 h-4"> Full Access (Semua Divisi)
                </label>
            </div>
            <div class="form-field col-span-2">
                <label class="form-label">Password Sementara</label>
                <input type="password" class="form-input" placeholder="Password awal admin HR">
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
            <button class="btn-ghost" onclick="closeModal('modalAddAdmin')">Batal</button>
            <button class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Admin HR
            </button>
        </div>
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
        <div class="grid grid-cols-2 gap-4">
            <div class="form-field col-span-2">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-input" value="Andi Rahman" id="editName">
            </div>
            <div class="form-field">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" value="andi@attensys.id">
            </div>
            <div class="form-field">
                <label class="form-label">NIP</label>
                <input type="text" class="form-input" value="EMP-001">
            </div>
            <div class="form-field">
                <label class="form-label">Divisi</label>
                <select class="form-select">
                    <option selected>Engineering</option>
                    <option>Marketing</option>
                    <option>Finance</option>
                    <option>Operasional</option>
                    <option>HR</option>
                </select>
            </div>
            <div class="form-field">
                <label class="form-label">Jabatan</label>
                <input type="text" class="form-input" value="Backend Dev">
            </div>
            <div class="form-field">
                <label class="form-label">Status</label>
                <select class="form-select">
                    <option selected>Aktif</option>
                    <option>Pending</option>
                    <option>Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
            <button class="btn-danger" onclick="closeModal('modalEditEmployee')">Reset Password</button>
            <button class="btn-ghost" onclick="closeModal('modalEditEmployee')">Batal</button>
            <button class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- ===== TOAST NOTIF ===== -->
<div id="toast" class="fixed bottom-6 right-6 z-[200] opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-medium" style="font-family:'DM Sans',sans-serif">
        <span id="toastIcon">✅</span>
        <span id="toastMsg">Berhasil disimpan!</span>
    </div>
</div>

<script>
// ===== Tab switching =====
function showTab(tab, el) {
    event && event.preventDefault();

    // hide all
    document.querySelectorAll('[id^="tab-"]').forEach(t => t.classList.add('hidden'));
    document.getElementById('tab-' + tab).classList.remove('hidden');

    // update nav active
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    if (el) el.classList.add('active');

    // page title
    const titles = { dashboard: 'Dashboard', employees: 'Akun Karyawan', admins: 'Akun Admin HR' };
    document.getElementById('pageTitle').textContent = titles[tab] || 'Dashboard';

    closeSidebar();
}

// ===== Sidebar mobile =====
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}

// ===== Modal =====
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
function closeModalOutside(e, id) {
    if (e.target.id === id) closeModal(id);
}

// ===== Dropdown =====
function toggleDropdown(btn) {
    // close all others
    document.querySelectorAll('.dropdown-menu.open').forEach(d => {
        if (d !== btn.nextElementSibling) d.classList.remove('open');
    });
    btn.nextElementSibling.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.matches('.btn-ghost') || !e.target.textContent.trim().includes('⋮')) {
        document.querySelectorAll('.dropdown-menu.open').forEach(d => d.classList.remove('open'));
    }
});

// ===== Search filter =====
function filterTable(input, tableId) {
    const val = input.value.toLowerCase();
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(val) ? '' : 'none';
    });
}

function filterByStatus(select, tableId) {
    const val = select.value;
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        if (!val) { row.style.display = ''; return; }
        const status = row.dataset.status || '';
        row.style.display = status === val ? '' : 'none';
    });
}

// ===== Edit handlers =====
function openEditEmployee(btn) {
    const row = btn.closest('tr');
    const name = row.querySelector('.font-semibold').textContent;
    document.getElementById('editName').value = name;
    openModal('modalEditEmployee');
}
function openEditAdmin(btn) {
    openModal('modalEditEmployee');
}

// ===== Actions =====
function resetPassword() {
    closeAllDropdowns();
    showToast('🔑', 'Link reset password telah dikirim ke email!');
}
function toggleStatus(btn) {
    closeAllDropdowns();
    showToast('🔄', 'Status akun berhasil diperbarui!');
}
function confirmDelete(btn) {
    closeAllDropdowns();
    if (confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')) {
        const row = btn.closest('tr');
        row.style.transition = 'opacity 0.3s, transform 0.3s';
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        setTimeout(() => row.remove(), 300);
        showToast('🗑', 'Akun berhasil dihapus!');
    }
}
function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu.open').forEach(d => d.classList.remove('open'));
}

// ===== Toast =====
function showToast(icon, msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastIcon').textContent = icon;
    document.getElementById('toastMsg').textContent = msg;
    toast.style.opacity = '1';
    toast.style.pointerEvents = 'all';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.pointerEvents = 'none';
    }, 3000);
}
</script>
</body>
</html>