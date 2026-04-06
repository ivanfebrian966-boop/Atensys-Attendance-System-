<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage - ATTENSYS</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('css/Admin_HR/HRmanage.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-64 bg-slate-900 text-slate-200 flex flex-col p-5 transition-all duration-300 ease-in-out fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 md:static md:inset-0">

        <h1 class="text-xl font-bold mb-8 flex items-center gap-2">
            <span class="text-white">A</span>
            <span class="sidebar-text">ATTENSYS</span>
        </h1>

        <nav class="flex flex-col gap-2 text-sm">
            <a href="/dashboardHR" class="menu">🏠 <span class="sidebar-text">Dashboard</span></a>
            <a href="/employees" class="menu">👥 <span class="sidebar-text">Employees</span></a>
            <a href="/attendance" class="menu">📷 <span class="sidebar-text">Attendance</span></a>
            <a href="/reports" class="menu">📊 <span class="sidebar-text">Reports</span></a>
            <a href="/HRmanage" class="menu active">⚙️ <span class="sidebar-text">Manage</span></a>
            <a href="/profileHR" class="menu">👤 <span class="sidebar-text">Profile</span></a>
        </nav>

        <div class="mt-auto text-sm text-slate-400">
            <p class="sidebar-text">Admin HR</p>
            <a href="/logout" class="text-red-400 block mt-2 sidebar-text">Logout</a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-6 md:p-8">

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