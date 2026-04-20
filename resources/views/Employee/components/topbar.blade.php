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
                <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">
                    @yield('page_title', 'Employee Dashboard')
                </h1>
                <p class="text-xs text-slate-400" id="realtime-date">
                    @yield('page_subtitle', now()->format('l, d F Y'))
                </p>
            </div>
        </div>

        {{-- RIGHT: Profile Chip → links to employee profile --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('employee.profile') }}" class="topbar-profile topbar-profile-link" title="View Profile">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(optional($authUser)->name ?? 'EM', 0, 2)) }}
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800 leading-tight" style="font-family:'Sora',sans-serif">
                        {{ optional($authUser)->name ?? 'Employee' }}
                    </p>
                    <p class="text-xs text-slate-400 leading-tight">
                        {{ optional($authUser)->division->division_name ?? 'Employee' }}
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
