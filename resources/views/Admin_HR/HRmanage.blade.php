<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage - ATTENSYS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/Admin_HR/DashboardHR.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
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

            <a href="/dashboardHR" class="nav-item" id="nav-dashboard">
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

            <a href="/HRmanage" class="nav-item active" id="nav-manage">
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
                </a>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main-content">

        <!-- HEADER -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">⚙️ Manage Attendance</h2>
            <p class="text-slate-500 text-sm">Approve izin & sakit karyawan</p>
        </div>

        <!-- FILTER -->
        <div class="bg-white p-4 rounded-xl shadow mb-4 flex flex-wrap gap-3">
            <input id="searchInput" type="text" placeholder="🔍 Search name..."
                class="input">

            <select id="statusFilter" class="input">
                <option value="">All Status</option>
                <option>Sick</option>
                <option>Permission</option>
            </select>
        </div>

        <!-- TABLE -->
        <div class="bg-white p-5 rounded-xl shadow overflow-x-auto">

            <table class="w-full text-sm" id="manageTable">
                <thead class="text-left text-slate-500 border-b">
                    <tr>
                        <th class="py-3">👤 Name</th>
                        <th>🏢 Division</th>
                        <th>📌 Status</th>
                        <th>📝 Reason</th>
                        <th>⚡ Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($requests ?? [] as $r)
                    <tr class="border-b hover:bg-slate-50 transition">
                        <td class="py-3 font-medium">{{ $r['name'] }}</td>
                        <td>{{ $r['division'] }}</td>

                        <td class="status">
                            <span class="badge
                            {{ $r['status']=='Sick' ? 'blue' : 'purple' }}">
                                {{ $r['status']=='Sick' ? '🤒 Sick' : '📝 Permission' }}
                            </span>
                        </td>

                        <td>{{ $r['reason'] }}</td>

                        <td class="flex gap-2">
                            <button class="btn-approve">✅ Approve</button>
                            <button class="btn-reject">❌ Reject</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(empty($requests))
            <p class="text-slate-400 text-sm mt-4">No request data.</p>
            @endif

        </div>

    </main>
</div>

<script src="{{ asset('js/Admin_HR/HRmanage.js') }}"></script>

</body>
</html>