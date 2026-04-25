@extends('Super_Admin.layouts.app')

@section('title', 'Employee Accounts')
@section('page_title', 'Employee Management')

@section('content')
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
                            <span class="badge badge-active">● Active</span>
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
@endsection
