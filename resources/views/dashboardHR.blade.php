<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard - ATTENSYS</title>

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
    </style>
</head>

<body class="bg-slate-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside id="sidebar"
            class="w-64 bg-slate-900 text-slate-200 flex flex-col p-5 transition-all duration-300 ease-in-out fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 md:static md:inset-0">

            <h1 class="text-xl font-bold mb-8 flex items-center gap-2">
                <span class="text-white">A</span>
                <span class="sidebar-text transition-all duration-300 origin-left">
                    ATTENSYS
                </span>
            </h1>

            <nav class="flex flex-col gap-2 text-sm">

                <a href="/dashboard" class="menu active">
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

                <a href="/profile" class="menu">
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
                    <h2 class="text-2xl font-bold text-slate-800">Dashboard</h2>
                    <p class="text-slate-500 text-sm">Employee attendance overview</p>
                </div>
            </div>

            <!-- STATS -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4 mb-8">

                <div class="bg-white p-4 rounded-xl border border-slate-200 hover:shadow transition">
                    <p class="text-xs text-slate-400">Total</p>
                    <h3 class="text-xl font-bold text-slate-800">{{ $totalEmployees ?? 120 }}</h3>
                </div>

                <div class="bg-white p-4 rounded-xl border border-green-200 hover:shadow transition">
                    <p class="text-xs text-green-600">Present</p>
                    <h3 class="text-xl font-bold text-green-600">{{ $present ?? 98 }}</h3>
                </div>

                <div class="bg-white p-4 rounded-xl border border-red-200 hover:shadow transition">
                    <p class="text-xs text-red-500">Absent</p>
                    <h3 class="text-xl font-bold text-red-500">{{ $absent ?? 10 }}</h3>
                </div>

                <div class="bg-white p-4 rounded-xl border border-yellow-200 hover:shadow transition">
                    <p class="text-xs text-yellow-600">Late</p>
                    <h3 class="text-xl font-bold text-yellow-600">{{ $late ?? 12 }}</h3>
                </div>

                <div class="bg-white p-4 rounded-xl border border-blue-200 hover:shadow transition">
                    <p class="text-xs text-blue-600">Sick</p>
                    <h3 class="text-xl font-bold text-blue-600">{{ $sick ?? 5 }}</h3>
                </div>

                <div class="bg-white p-4 rounded-xl border border-purple-200 hover:shadow transition">
                    <p class="text-xs text-purple-600">Permission</p>
                    <h3 class="text-xl font-bold text-purple-600">{{ $permission ?? 7 }}</h3>
                </div>

            </div>

            <!-- FILTER -->
            <div class="bg-white p-4 rounded-xl shadow mb-4 flex flex-wrap gap-3">

                <input id="searchInput" type="text" placeholder="Search..."
                    class="px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-indigo-400 transition">

                <select id="statusFilter" class="px-3 py-2 border rounded-lg text-sm">
                    <option value="">All Status</option>
                    <option>Present</option>
                    <option>Absent</option>
                    <option>Late</option>
                    <option>Sick</option>
                    <option>Permission</option>
                </select>

                <select id="divisionFilter" class="px-3 py-2 border rounded-lg text-sm">
                    <option value="">All Division</option>
                    <option>HR</option>
                    <option>IT</option>
                    <option>Finance</option>
                </select>

            </div>

            <!-- TABLE -->
            <div class="bg-white p-4 md:p-6 rounded-xl shadow hover:shadow-md transition overflow-x-auto">

                <table class="w-full text-sm" id="attendanceTable">
                    <thead class="text-left text-slate-500 border-b">
                        <tr>
                            <th class="py-3">Name</th>
                            <th>Division</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($attendances ?? [] as $data)
                        <tr class="border-b hover:bg-indigo-50 transition-all duration-200">
                            <td class="py-3 font-medium">{{ $data['name'] }}</td>
                            <td class="division">{{ $data['division'] }}</td>
                            <td class="status">
                                <span class="px-2 py-1 text-xs rounded-full font-medium
@if($data['status']=='Present') bg-green-100 text-green-600
@elseif($data['status']=='Absent') bg-red-100 text-red-600
@elseif($data['status']=='Late') bg-yellow-100 text-yellow-600
@elseif($data['status']=='Sick') bg-blue-100 text-blue-600
@else bg-purple-100 text-purple-600
@endif">
                                    {{ $data['status'] }}
                                </span>
                            </td>
                            <td>{{ $data['time'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if(empty($attendances))
                <p class="text-slate-400 text-sm mt-4">No attendance data available.</p>
                @endif

            </div>

        </main>
    </div>

    <!-- STYLE -->
    <style>
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

    <!-- SCRIPT -->
    <script>
        // SIDEBAR SMOOTH
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth >= 768) {
                // Desktop behavior
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
                // Mobile behavior
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

        // FILTER + SEARCH
        function filterTable() {
            let search = document.getElementById('searchInput').value.toLowerCase();
            let status = document.getElementById('statusFilter').value;
            let division = document.getElementById('divisionFilter').value;

            document.querySelectorAll("#attendanceTable tbody tr").forEach(row => {
                let text = row.innerText.toLowerCase();
                let s = row.querySelector(".status").innerText;
                let d = row.querySelector(".division").innerText;

                row.style.display =
                    (text.includes(search)) &&
                    (!status || s == status) &&
                    (!division || d == division) ?
                    "" : "none";
            });
        }

        document.querySelectorAll("#searchInput,#statusFilter,#divisionFilter")
            .forEach(el => el.addEventListener("input", filterTable));
    </script>

</body>

</html>