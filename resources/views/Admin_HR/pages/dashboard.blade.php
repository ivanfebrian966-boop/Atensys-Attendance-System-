<!DOCTYPE html>
@extends('Admin_HR.layouts.main')

@section('title', 'HR Dashboard — ATTENSYS')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/DashboardHR.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@endsection

@section('main_structure')
@include('Admin_HR.components.sidebar')

<div class="main-content">
    @include('Admin_HR.components.topbar', [
        'pageTitle'    => 'Dashboard',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
    ])

    <div class="p-4 md:p-6">
        
        <!-- ===== WELCOME BANNER ===== -->
        <div class="panel fade-up d1 mb-6 welcome-banner-gradient">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold sora mb-2">Welcome back, {{ Auth::user()->name ?? 'HR Admin' }} 👋</h2>
                    <p class="text-indigo-100 opacity-90 max-w-xl">Here is a summary of today's attendance. Keep up the great work managing the team's data!</p>
                </div>
                <div class="hidden md:block">
                    <span class="text-5xl opacity-80 drop-shadow-sm">🏢</span>
                </div>
            </div>
        </div>

        <!-- ===== STAT CARDS ===== -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
            <div class="stat-card fade-up d1">
                <div class="stat-icon-sm" style="background:#eef2ff;color:#6366f1">👥</div>
                <p class="stat-value text-slate-900">{{ $totalEmployees ?? 120 }}</p>
                <p class="stat-label">Total Employees</p>

            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon-sm" style="background:#ecfdf5;color:#10b981">✅</div>
                <p class="stat-value text-emerald-600">{{ $stats['present'] ?? 0 }}</p>
                <p class="stat-label">Present</p>

            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon-sm" style="background:#fef2f2;color:#ef4444">❌</div>
                <p class="stat-value text-red-500">{{ $stats['absent'] ?? 0 }}</p>
                <p class="stat-label">Absent</p>

            </div>
            <div class="stat-card yellow fade-up d4">
                <div class="stat-icon-sm" style="background:#fffbeb;color:#f59e0b">⏰</div>
                <p class="stat-value text-amber-500">{{ $stats['late'] ?? 0 }}</p>
                <p class="stat-label">Late</p>

            </div>
            <div class="stat-card blue fade-up d5">
                <div class="stat-icon-sm" style="background:#eff6ff;color:#3b82f6">🏥</div>
                <p class="stat-value text-blue-500">{{ $stats['sick'] ?? 0 }}</p>
                <p class="stat-label">Sick</p>

            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon-sm" style="background:#faf5ff;color:#8b5cf6">📋</div>
                <p class="stat-value text-purple-500">{{ $pendingPermissionsCount ?? 0 }}</p>
                <p class="stat-label">Permission</p>
            </div>
        </div>

        <!-- ===== MAIN GRID ===== -->
        <div class="grid lg:grid-cols-3 gap-4 mb-4">

            <div class="panel fade-up d2 h-full flex flex-col">
                <div class="panel-header">
                    <div>
                    <h3 class="panel-title">Attendance This Week</h3>
                        <p class="panel-subtitle">Mon – Sat</p>
                    </div>
                    <span class="badge-rate">{{ $avgAttendance ?? 0 }}%</span>
                </div>
                <div class="p-5 flex-1 w-full">
                    <div style="position:relative;height:160px;width:100%;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs text-slate-500">Average attendance</span>
                        <span class="text-sm font-bold gradient-text" style="font-family:'Sora',sans-serif">{{ $avgAttendance ?? 0 }}%</span>
                    </div>
                </div>
            </div>

            <!-- Status Summary -->
            <div class="panel fade-up d3 h-full flex flex-col">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Attendance Status Today</h3>
                        <p class="panel-subtitle">{{ now()->translatedFormat('d F Y') ?? date('d M Y') }}</p>
                    </div>
                </div>
                <div class="p-5 flex-grow space-y-4">
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-emerald-500"></span>
                        <span class="text-sm text-slate-600">Present</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-emerald-500" style="width:{{ $presentPct ?? 0 }}%"></div>
                            </div>
                            <span class="status-count">{{ $present ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-red-400"></span>
                            <span class="text-sm text-slate-600">Absent</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-red-400" style="width:{{ $absentPct ?? 0 }}%"></div>
                            </div>
                            <span class="status-count">{{ $absent ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-amber-400"></span>
                            <span class="text-sm text-slate-600">Late</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-amber-400" style="width:{{ $latePct ?? 0 }}%"></div>
                            </div>
                            <span class="status-count">{{ $late ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-blue-400"></span>
                            <span class="text-sm text-slate-600">Sick</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-blue-400" style="width:{{ $sickPct ?? 0 }}%"></div>
                            </div>
                            <span class="status-count">{{ $sick ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="status-row">
                        <div class="flex items-center gap-2">
                            <span class="status-dot bg-purple-400"></span>
                            <span class="text-sm text-slate-600">Permission</span>

                        </div>
                        <div class="flex items-center gap-3">
                            <div class="status-bar-wrap">
                                <div class="status-bar bg-purple-400" style="width:{{ $permissionPct ?? 0 }}%"></div>
                            </div>
                            <span class="status-count">{{ $permission ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="panel fade-up d4 h-full flex flex-col">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Quick Actions</h3>
                        <p class="panel-subtitle">Main menu shortcuts</p>
                    </div>
                </div>
                <div class="p-5 flex-grow grid grid-cols-1 sm:grid-cols-3 gap-3 items-stretch">
                    <a href="{{ route('admin-hr.attendance') }}" class="quick-action-card flex-grow" style="--qa-color:#6366f1;--qa-bg:#eef2ff">
                        <span class="text-2xl">📷</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Scan QR</span>
                    </a>
                    <a href="{{ route('admin-hr.leaves') }}" class="quick-action-card flex-grow" style="--qa-color:#8b5cf6;--qa-bg:#faf5ff">
                        <span class="text-2xl">📋</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Leaves</span>
                    </a>
                    <a href="{{ route('admin-hr.reports') }}" class="quick-action-card flex-grow" style="--qa-color:#06b6d4;--qa-bg:#ecfeff">
                        <span class="text-2xl">📊</span>
                        <span class="text-xs font-semibold text-slate-700 mt-1" style="font-family:'Sora',sans-serif">Reports</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ===== RECENT ATTENDANCE ===== -->
        <div class="panel fade-up d5 mb-6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Recent Check-ins Today</h3>
                    <p class="panel-subtitle">Latest attendance activities</p>
                </div>
                <button class="btn-secondary text-sm px-3 py-1.5" onclick="window.location.href='{{ route('admin-hr.attendance') }}'">View All</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 uppercase text-xs font-semibold text-slate-500 tracking-wider">
                            <th class="p-4 rounded-tl-xl w-1/3">Employee</th>
                            <th class="p-4 w-1/4">Division</th>
                            <th class="p-4 w-1/5">Time</th>
                            <th class="p-4 rounded-tr-xl w-1/5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAttendances ?? [] as $att)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                        {{ substr(strtoupper($att->employee->name ?? '?'), 0, 2) }}
                                    </div>
                                    <span class="font-semibold text-slate-800 text-sm sora">{{ $att->employee->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-sm text-slate-600">{{ $att->employee->division->division_name ?? '-' }}</td>
                            <td class="p-4 text-sm text-slate-600 font-medium">{{ $att->check_in ? Carbon\Carbon::parse($att->check_in)->format('H:i') : '—' }}</td>
                            <td class="p-4 text-center">
                                @if($att->status === 'Present')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600">✅ Present</span>
                                @elseif($att->status === 'Late')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-600">⏰ Late</span>
                                @elseif($att->status === 'Sick')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-600">🏥 Sick</span>
                                @elseif($att->status === 'Permission')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-600">📋 Permission</span>
                                @elseif($att->status === 'Absent')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600">❌ Absent</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center">
                                <div class="text-4xl mb-3 opacity-50">📋</div>
                                <p class="text-sm font-semibold text-slate-800 sora">No check-ins yet</p>
                                <p class="text-sm text-slate-500 mt-1">Check-in activities for today will appear here.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($recentAttendances, 'links'))
            <div class="p-4 border-t border-slate-100">
                {{ $recentAttendances->links() }}
            </div>
            @endif
        </div>

    </div><!-- end p-4 -->
</div><!-- end main-content -->
@endsection

@section('scripts')
<script src="{{ asset('js/Admin_HR/DashboardHR.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('weeklyChart');
    if (!ctx) return;
    const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const data   = [
        {{ $chartData['Mon'] ?? 0 }},
        {{ $chartData['Tue'] ?? 0 }},
        {{ $chartData['Wed'] ?? 0 }},
        {{ $chartData['Thu'] ?? 0 }},
        {{ $chartData['Fri'] ?? 0 }},
        {{ $chartData['Sat'] ?? 0 }}
    ];
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Attendance %',
                data,
                backgroundColor: data.map(v =>
                    v >= 80 ? 'rgba(99,102,241,0.85)' :
                    v >= 50 ? 'rgba(99,102,241,0.55)' :
                              'rgba(99,102,241,0.3)'
                ),
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(99,102,241,1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y}% present`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 11 } } },
                y: {
                    min: 0, max: 100,
                    ticks: { callback: v => v + '%', font: { family: 'DM Sans', size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            },
            animation: { duration: 800, easing: 'easeOutQuart' }
        }
    });
});
</script>
@endsection
