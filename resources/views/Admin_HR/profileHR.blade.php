<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Profile - ATTENSYS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/Admin_HR/DashboardHR.css') }}">
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

                <a href="/HRmanage" class="nav-item" id="nav-manage">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Manage
                </a>

                <a href="/profileHR" class="nav-item active" id="nav-profile">
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

        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- MAIN -->
        <main class="main-content">

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
                            <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Profile</h1>
                            <p class="text-xs text-slate-400">Your personal information</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HEADER -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Profile</h2>
                <p class="text-slate-500 text-sm">Your personal information</p>
            </div>

            <!-- CARD -->
            <div class="flex justify-center px-4 md:px-0">
                <div class="w-full max-w-4xl">

                    <!-- PROFILE CARD -->
                    <div class="bg-white rounded-2xl shadow-md p-6 border border-slate-200">

                        <!-- HEADER UNGU GELAP -->
                        <div class="bg-gradient-to-r from-[#0f0a19] via-[#1b1430] to-[#120d1d] h-28 rounded-xl relative border border-[#2a2340]">

                            <!-- AVATAR -->
                            <div class="absolute -bottom-10 left-6">
                                <div class="w-20 h-20 rounded-full bg-white shadow flex items-center justify-center border">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-10 h-10 text-[#5b3ea6]"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15.75 6a3.75 3.75 0 11-7.5 0 
                                           3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                                    </svg>
                                </div>
                            </div>

                        </div>

                        <!-- CONTENT -->
                        <div class="pt-14">

                            <!-- NAME -->
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-xl font-semibold text-slate-800">
                                        {{ $user->name ?? 'Name' }}
                                    </h3>
                                    <p class="text-slate-500 text-sm">
                                        {{ $user->position ?? 'HR Admin' }}
                                    </p>
                                </div>

                                <span class="text-xs bg-green-100 text-green-600 px-3 py-1 rounded-full">
                                    Active
                                </span>
                            </div>

                            <!-- DATA -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div class="p-4 bg-slate-50 rounded-xl border hover:border-[#5b3ea6] transition">
                                    <p class="text-xs text-slate-400">Employee ID</p>
                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $user->employee_id ?? '-' }}
                                    </p>
                                </div>

                                <div class="p-4 bg-slate-50 rounded-xl border hover:border-[#5b3ea6] transition">
                                    <p class="text-xs text-slate-400">Email</p>
                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $user->email ?? '-' }}
                                    </p>
                                </div>

                                <div class="p-4 bg-slate-50 rounded-xl border hover:border-[#5b3ea6] transition">
                                    <p class="text-xs text-slate-400">Phone Number</p>
                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $user->phone ?? '-' }}
                                    </p>
                                </div>

                                <div class="p-4 bg-slate-50 rounded-xl border hover:border-[#5b3ea6] transition">
                                    <p class="text-xs text-slate-400">Position</p>
                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $user->position ?? '-' }}
                                    </p>
                                </div>

                                <div class="p-4 bg-slate-50 rounded-xl border col-span-1 md:col-span-2 hover:border-[#5b3ea6] transition">
                                    <p class="text-xs text-slate-400">Division</p>
                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $user->division ?? '-' }}
                                    </p>
                                </div>

                            </div>

                            <!-- INFO -->
                            <div class="mt-6 bg-[#f5f3ff] text-[#4c1d95] p-4 rounded-xl text-sm border border-[#ddd6fe]">
                                🔒 You can only view your profile. Please contact <b>Super Admin</b> for any changes.
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </main>

    </div>

    <!-- SCRIPT SIDEBAR -->
    <script src="{{ asset('js/Admin_HR/ProfileHR.js') }}"></script>

</body>

</html>