<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Profile - ATTENSYS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Sora', sans-serif;
        }

        .menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .menu:hover {
            background: #1e293b;
        }

        .menu.active {
            background: #1e293b;
        }
    </style>
</head>

<body class="bg-slate-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="w-64 bg-slate-900 text-slate-200 flex flex-col p-5 transition-all duration-300 ease-in-out transform -translate-x-full md:translate-x-0 md:static">

            <h1 class="text-xl font-bold mb-8 flex items-center gap-2">
                <span class="text-white">A</span>
                <span class="sidebar-text transition-all duration-300 origin-left">
                    ATTENSYS
                </span>
            </h1>

            <nav class="flex flex-col gap-2 text-sm">

                <a href="/dashboard_hr" class="menu">
                    <span>🏠</span>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="/employees" class="menu">
                    <span>👥</span>
                    <span class="sidebar-text">Employees</span>
                </a>

                <a href="/attendance" class="menu">
                    <span>📷</span>
                    <span class="sidebar-text">Attendance</span>
                </a>

                <a href="/reports" class="menu">
                    <span>📊</span>
                    <span class="sidebar-text">Reports</span>
                </a>

                <a href="/manage" class="menu">
                    <span>⚙️</span>
                    <span class="sidebar-text">Manage</span>
                </a>

                <a href="/profile" class="menu active">
                    <span>👤</span>
                    <span class="sidebar-text">Profile</span>
                </a>

            </nav>

            <div class="mt-auto text-sm text-slate-400">
                <p class="sidebar-text">Admin HR</p>
                <a href="/logout" class="text-red-400 block mt-2 sidebar-text">Logout</a>
            </div>

        </aside>

        <!-- OVERLAY for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

        <!-- MAIN -->
        <main class="flex-1 p-4 md:p-8">

            <!-- HEADER -->
            <div class="flex items-center gap-4 mb-8">
                <button onclick="toggleSidebar()" class="text-2xl hover:scale-110 transition">☰</button>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Profile</h2>
                    <p class="text-slate-500 text-sm">Your personal information</p>
                </div>
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
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth >= 768) {
                // Desktop behavior: static position
                sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-50', '-translate-x-full');
                sidebar.classList.add('static');

                const texts = document.querySelectorAll('.sidebar-text');

                if (sidebar.classList.contains('w-64')) {
                    sidebar.classList.replace('w-64', 'w-20');

                    texts.forEach(t => {
                        t.classList.add('opacity-0', 'scale-95', '-translate-x-2');
                    });
                } else {
                    sidebar.classList.replace('w-20', 'w-64');

                    texts.forEach(t => {
                        t.classList.remove('opacity-0', 'scale-95', '-translate-x-2');
                    });
                }
            } else {
                // Mobile behavior: fixed position
                sidebar.classList.remove('static');
                sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-50');

                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            }
        }

        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('fixed', 'inset-y-0', 'left-0', 'z-50', '-translate-x-full');
                sidebar.classList.add('static', 'translate-x-0');
            } else {
                sidebar.classList.remove('static');
                sidebar.classList.add('fixed', 'inset-y-0', 'left-0', 'z-50', '-translate-x-full');
            }
        });
    </script>

</body>

</html>