@extends('Super_Admin.layouts.app')

@section('title', 'Scanner Devices')
@section('page_title', 'Scanner Device Management')

@section('content')
<div class="panel fade-up d1">
    <div class="panel-header">
        <div>
            <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Scanner Devices Accounts</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ count($scanners) }} active scanner devices in the system</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <div class="relative">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Search scanner..." class="search-input" oninput="filterTable(this,'scanner-table')">
            </div>
            <button class="btn-primary" onclick="openModal('modalAddScanner')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Scanner
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table" id="scanner-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Scanner ID</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scanners as $index => $scanner)
                <tr>
                    <td><span class="text-slate-400 text-xs font-mono">{{ $index + 1 }}</span></td>
                    <td>
                        <span class="font-semibold text-slate-800 font-mono" style="font-family:'Sora',sans-serif;font-size:0.85rem">{{ $scanner->scanner_id }}</span>
                    </td>
                    <td><span class="text-slate-400 text-xs">{{ $scanner->created_at->format('d M Y') }}</span></td>
                    <td>
                        <div class="flex items-center gap-1 relative">
                            <button class="btn-ghost py-1.5 px-3 text-xs" 
                                    onclick="openEditScanner('{{ $scanner->scanner_id }}')">
                                Edit
                            </button>
                            <form action="{{ route('super_admin.delete_scanner', $scanner->scanner_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this scanner account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-ghost py-1.5 px-2 text-xs" style="color:#ef4444">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
        <p class="text-sm text-slate-400">Total {{ count($scanners) }} records</p>
    </div>
</div>

<script>
    function openEditScanner(scannerId) {
        const form = document.getElementById('formEditScanner');
        form.action = `/super-admin/scanner/${scannerId}`;
        document.getElementById('edit_scanner_id_display').value = scannerId;
        openModal('modalEditScanner');
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initPagination === 'function') {
            initPagination('scanner-table', 10);
        }
    });
</script>
@endsection
