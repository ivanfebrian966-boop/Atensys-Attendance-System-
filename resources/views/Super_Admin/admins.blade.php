@extends('Super_Admin.layouts.app')

@section('title', 'Admin HR Accounts')
@section('page_title', 'Admin HR Management')

@section('content')
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
                    <th>Division</th>
                    <th>Position</th>
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
                    <td><span class="text-slate-600 text-sm font-medium">{{ $admin->position }}</span></td>
                    <td>
                        @if($admin->status === 'Aktif')
                            <span class="badge badge-active">● Active</span>
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
@endsection
