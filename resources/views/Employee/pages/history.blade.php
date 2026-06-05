@extends('Employee.layouts.main')

@section('title', 'Attendance History — ATTENSYS')
@section('page_title', 'Attendance History')
@section('page_subtitle', 'Your complete attendance history')

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="stat-card indigo fade-up d1">
        <div class="stat-icon-sm" style="background:#eef2ff;color:#6366f1">👥</div>
        <p class="stat-value text-slate-900">{{ $attendances->total() }}</p>
        <p class="stat-label">Total Records</p>
    </div>
    <div class="stat-card green fade-up d2">
        <div class="stat-icon-sm" style="background:#ecfdf5;color:#10b981">✅</div>
        <p class="stat-value text-emerald-600">{{ $counts['present'] }}</p>
        <p class="stat-label">Present</p>
    </div>
    <div class="stat-card yellow fade-up d3">
        <div class="stat-icon-sm" style="background:#fffbeb;color:#f59e0b">⏰</div>
        <p class="stat-value text-amber-500">{{ $counts['late'] }}</p>
        <p class="stat-label">Late</p>
    </div>
    <div class="stat-card red fade-up d4">
        <div class="stat-icon-sm" style="background:#fef2f2;color:#ef4444">❌</div>
        <p class="stat-value text-red-500">{{ $counts['absent'] }}</p>
        <p class="stat-label">Absent</p>
    </div>
    <div class="stat-card sky fade-up d5">
        <div class="stat-icon-sm" style="background:#f0f9ff;color:#0ea5e9">🏥</div>
        <p class="stat-value text-sky-500">{{ $counts['sick'] }}</p>
        <p class="stat-label">Sick</p>
    </div>
    <div class="stat-card purple fade-up d6">
        <div class="stat-icon-sm" style="background:#faf5ff;color:#8b5cf6">📝</div>
        <p class="stat-value text-purple-500">{{ $counts['permission'] }}</p>
        <p class="stat-label">Leave</p>
    </div>
</div>

<div class="panel fade-up d7" style="overflow: visible !important;">
    <div class="panel-header flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
        <div>
            <h3 class="panel-title">Detailed History</h3>
            <p class="panel-subtitle">All attendance records sorted by date</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative flex-grow md:flex-grow-0">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="attendanceSearch" placeholder="Search date..." class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors">
            </div>
            <select id="attendanceFilter" class="px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors bg-white">
                <option value="">All Status</option>
                <option value="Present">Present</option>
                <option value="Late">Late</option>
                <option value="Absent">Absent</option>
                <option value="Permission">Permission</option>
            </select>
<style>
    /* Premium custom dropdown styles */
    #periodDropdownMenu.show {
        opacity: 1 !important;
        transform: scale(1) !important;
        pointer-events: auto !important;
    }
    
    /* Flyout transition base */
    .flyout-menu {
        opacity: 0;
        pointer-events: none;
    }

    @media (max-width: 1023px) {
        /* Mobile: Accordion styling below parent item */
        .flyout-menu {
            position: relative !important;
            left: 0 !important;
            right: 0 !important;
            top: auto !important;
            margin: 4px 0 0 0 !important;
            width: 100% !important;
            max-height: 0;
            overflow: hidden;
            box-shadow: none !important;
            border: none !important;
            background-color: #f8fafc !important;
            border-radius: 12px !important;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        .group\/sub:hover .flyout-menu {
            max-height: 300px !important;
            opacity: 1 !important;
            pointer-events: auto !important;
            overflow-y: auto !important;
        }
    }
    
    @media (min-width: 1024px) {
        /* Desktop: Flyout menu to the RIGHT of the main menu */
        .flyout-menu {
            position: absolute !important;
            left: 100% !important;
            right: auto !important;
            top: 0 !important;
            margin-left: 4px !important;
            margin-right: 0 !important;
            transform: scale(0.95);
            transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        .group\/sub:hover .flyout-menu {
            transform: scale(1) !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
    }
</style>

            <!-- Custom Premium Nested Dropdown for Period Filter -->
            <div class="relative inline-block text-left z-30" id="customPeriodDropdown">
                <button type="button" id="periodDropdownBtn" class="flex items-center justify-between gap-2 px-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all bg-white hover:border-slate-300 min-w-[140px] text-slate-700 font-medium">
                    <span id="selectedPeriodLabel">All Period</span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="periodDropdownMenu" class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-slate-100 shadow-xl opacity-0 scale-95 pointer-events-none transition-all duration-200 origin-top-right z-30 py-1.5 period-dropdown-menu">
                    <!-- All Period Option -->
                    <button type="button" data-val="" data-label="All Period" class="dropdown-item w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 flex items-center justify-between font-semibold transition-colors">
                        <span>All Period</span>
                    </button>
                    
                    <div class="h-px bg-slate-100 my-1"></div>
                    


                    <!-- Monthly Option -->
                    <div class="relative group/sub">
                        <div class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 flex items-center justify-between cursor-pointer font-semibold group-hover/sub:text-indigo-600 transition-colors">
                            <span>Monthly</span>
                            <svg class="w-4 h-4 text-slate-400 group-hover/sub:translate-x-0.5 group-hover/sub:text-indigo-500 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        
                        <!-- Month Flyout Menu -->
                        <div class="absolute right-full top-0 mr-1 w-44 max-h-64 overflow-y-auto rounded-2xl bg-white border border-slate-100 shadow-xl opacity-0 scale-95 pointer-events-none group-hover/sub:opacity-100 group-hover/sub:scale-100 group-hover/sub:pointer-events-auto transition-all duration-200 origin-top-right py-1.5 z-40 flyout-menu">
                            <button type="button" data-val="month-1" data-label="January" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">January</button>
                            <button type="button" data-val="month-2" data-label="February" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">February</button>
                            <button type="button" data-val="month-3" data-label="March" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">March</button>
                            <button type="button" data-val="month-4" data-label="April" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">April</button>
                            <button type="button" data-val="month-5" data-label="May" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">May</button>
                            <button type="button" data-val="month-6" data-label="June" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">June</button>
                            <button type="button" data-val="month-7" data-label="July" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">July</button>
                            <button type="button" data-val="month-8" data-label="August" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">August</button>
                            <button type="button" data-val="month-9" data-label="September" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">September</button>
                            <button type="button" data-val="month-10" data-label="October" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">October</button>
                            <button type="button" data-val="month-11" data-label="November" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">November</button>
                            <button type="button" data-val="month-12" data-label="December" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">December</button>
                        </div>
                    </div>

                    <!-- Yearly Option -->
                    <div class="relative group/sub">
                        <div class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 flex items-center justify-between cursor-pointer font-semibold group-hover/sub:text-indigo-600 transition-colors">
                            <span>Yearly</span>
                            <svg class="w-4 h-4 text-slate-400 group-hover/sub:translate-x-0.5 group-hover/sub:text-indigo-500 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        
                        <!-- Year Flyout Menu -->
                        <div class="absolute right-full top-0 mr-1 w-44 rounded-2xl bg-white border border-slate-100 shadow-xl opacity-0 scale-95 pointer-events-none group-hover/sub:opacity-100 group-hover/sub:scale-100 group-hover/sub:pointer-events-auto transition-all duration-200 origin-top-right py-1.5 z-40 flyout-menu">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <button type="button" data-val="year-{{ $y }}" data-label="{{ $y }}" class="dropdown-item w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors">{{ $y }}</button>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="attendancePeriod" value="">
            <a href="{{ route('employee.attendance') }}" class="btn-secondary inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Attendance
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="data-table" id="historyTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr class="table-row attendance-row">
                    <td class="date-cell" data-date="{{ \Carbon\Carbon::parse($attendance->created_at)->format('Y-m-d') }}">{{ \Carbon\Carbon::parse($attendance->created_at)->translatedFormat('d M Y') }}</td>
                    <td>{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</td>
                    <td class="status-cell">
                        <span class="status-badge status-{{ strtolower($attendance->attendance_status) }}" data-status="{{ $attendance->attendance_status }}">
                            ● {{ $attendance->attendance_status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="4" class="text-center text-slate-400 py-6">No attendance history found.</td>
                </tr>
                @endforelse
                <tr id="noResultsRow" class="hidden">
                    <td colspan="4" class="text-center text-slate-400 py-6">No matching records found</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-slate-50 flex items-center justify-between">
        <p class="text-sm text-slate-400">Total {{ $attendances->total() }} records</p>
        
        @if ($attendances->hasPages())
        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($attendances->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $attendances->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Pagination Elements (Simplified) --}}
            @foreach ($attendances->getUrlRange(1, $attendances->lastPage()) as $page => $url)
                @if ($page == $attendances->currentPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-500 text-white font-medium text-sm shadow-sm shadow-indigo-500/20">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-50 hover:text-indigo-600 font-medium text-sm transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($attendances->hasMorePages())
                <a href="{{ $attendances->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('attendanceSearch');
        const filterSelect = document.getElementById('attendanceFilter');
        const periodSelect = document.getElementById('attendancePeriod');
        const rows = document.querySelectorAll('.attendance-row');
        const noResultsRow = document.getElementById('noResultsRow');

        function getWeekOfMonth(date) {
            return Math.ceil(date.getDate() / 7);
        }

        function filterTable() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const filterStatus = filterSelect ? filterSelect.value.toLowerCase() : '';
            const periodVal = periodSelect ? periodSelect.value : '';
            let visibleCount = 0;

            rows.forEach(row => {
                const dateText = row.querySelector('.date-cell').textContent.toLowerCase();
                const statusElement = row.querySelector('.status-badge');
                const statusText = statusElement ? statusElement.getAttribute('data-status').toLowerCase() : '';

                const matchesSearch = dateText.includes(searchTerm);
                const matchesFilter = filterStatus === '' || statusText === filterStatus;

                let periodMatch = true;
                if (periodVal) {
                    const dateCell = row.querySelector('.date-cell');
                    const rowDateStr = dateCell ? dateCell.getAttribute('data-date') : null;
                    if (rowDateStr) {
                        const rowDate = new Date(rowDateStr + 'T00:00:00');
                        const [type, val] = periodVal.split('-');
                        const num = parseInt(val);

                        if (type === 'week') {
                            periodMatch = getWeekOfMonth(rowDate) === num;
                        } else if (type === 'month') {
                            periodMatch = (rowDate.getMonth() + 1) === num;
                        } else if (type === 'year') {
                            periodMatch = rowDate.getFullYear() === num;
                        }
                    }
                }

                if (matchesSearch && matchesFilter && periodMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResultsRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noResultsRow.classList.remove('hidden');
                } else {
                    noResultsRow.classList.add('hidden');
                }
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterTable);
        if (filterSelect) filterSelect.addEventListener('change', filterTable);
        if (periodSelect) periodSelect.addEventListener('change', filterTable);

        // Custom Period Dropdown Interactivity
        const dropdownBtn = document.getElementById('periodDropdownBtn');
        const dropdownMenu = document.getElementById('periodDropdownMenu');
        const dropdownArrow = document.getElementById('dropdownArrow');
        const hiddenPeriodInput = document.getElementById('attendancePeriod');
        const selectedLabel = document.getElementById('selectedPeriodLabel');

        if (dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = dropdownMenu.classList.contains('show');
                if (isOpen) {
                    closeDropdown();
                } else {
                    dropdownMenu.classList.add('show');
                    if (dropdownArrow) dropdownArrow.classList.add('rotate-180');
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function() {
                closeDropdown();
            });

            function closeDropdown() {
                dropdownMenu.classList.remove('show');
                if (dropdownArrow) dropdownArrow.classList.remove('rotate-180');
            }

            // Handle custom dropdown item selections
            const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const val = this.getAttribute('data-val');
                    const label = this.getAttribute('data-label');
                    
                    if (hiddenPeriodInput) {
                        hiddenPeriodInput.value = val;
                        // Manually trigger standard change event to execute filterTable()
                        hiddenPeriodInput.dispatchEvent(new Event('change'));
                    }

                    if (selectedLabel) {
                        selectedLabel.textContent = label;
                    }

                    closeDropdown();
                });
            });
        }
    });
</script>
@endsection
