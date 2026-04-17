{{--
    =====================================================================
    ATTENSYS — HR Topbar Partial
    Params:
      $pageTitle    : Judul halaman (wajib)
      $pageSubtitle : Subjudul / tanggal (opsional, default: tanggal hari ini)
      $showExport   : bool - tampilkan tombol Export (opsional, default: false)
    =====================================================================
--}}
@php
    use Illuminate\Support\Facades\Auth;
    $authUser = Auth::user();
@endphp

<div class="topbar">
    <div class="px-4 md:px-6 py-4 flex items-center justify-between gap-4 w-full">

        {{-- LEFT: Hamburger + Page Title --}}
        <div class="flex items-center gap-3">
            <button class="topbar-hamburger" onclick="openSidebar()" aria-label="Open Sidebar">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h1 class="page-title">{{ $pageTitle ?? 'Dashboard' }}</h1>
                <p class="text-xs text-slate-400" id="realtime-date">
                    {{ $pageSubtitle ?? now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>

        {{-- RIGHT: Optional Export + Notification + User Profile --}}
        <div class="flex items-center gap-2">

            {{-- Export Button (khusus Reports) --}}
            @if(!empty($showExport))
            <button class="btn-secondary topbar-export-btn" onclick="exportAllReport()" title="Export Report">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span class="hidden sm:inline">Export</span>
            </button>
            @endif



            {{-- Profile Chip — klik untuk ke halaman profil --}}
            <a href="{{ route('admin-hr.profile') }}" class="topbar-profile topbar-profile-link" title="View Profile">
                <div class="topbar-avatar">
                    @if($authUser && $authUser->avatar)
                        <img src="{{ asset('storage/' . $authUser->avatar) }}"
                             alt="{{ $authUser->name }}"
                             class="w-full h-full object-cover rounded-xl">
                    @else
                        {{ strtoupper(substr(optional($authUser)->name ?? 'HR', 0, 2)) }}
                    @endif
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800 sora leading-tight">
                        {{ optional($authUser)->name ?? 'Admin HR' }}
                    </p>
                    <p class="text-xs text-slate-400 leading-tight">
                        {{ optional($authUser)->position ?? 'HR Manager' }}
                    </p>
                </div>
                <svg class="hidden sm:block w-3.5 h-3.5 text-slate-300 ml-1 flex-shrink-0"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

        </div>
    </div>
</div>
