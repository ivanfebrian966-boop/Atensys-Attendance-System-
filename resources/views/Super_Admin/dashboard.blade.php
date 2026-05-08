@extends('Super_Admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
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
                $total_users = max(($status_counts['aktif'] ?? 0) + ($status_counts['nonaktif'] ?? 0), 1);
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
            <a href="{{ route('super_admin.employees') }}" class="btn-primary text-xs">
                View All
            </a>
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
                            @if($ru->role === 'Admin HR')
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
                             @else
                                 <span class="badge" style="background:rgba(239,68,68,0.1);color:#dc2626">● Inactive</span>
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
@endsection
