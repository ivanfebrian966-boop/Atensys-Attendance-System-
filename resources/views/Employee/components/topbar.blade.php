<div class="topbar">
    <div class="px-4 md:px-6 py-4 flex items-center justify-between gap-4 w-full">
        <div class="flex items-center gap-3">
            <button class="topbar-hamburger" onclick="openSidebar()">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h1 class="text-lg font-bold @if(request()->routeIs('employee.profile')) bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent @else text-slate-900 @endif" style="font-family:'Sora',sans-serif">
                    @yield('page_title', 'Employee Dashboard')
                </h1>
                <p class="text-xs text-slate-400" id="currentDate">@yield('page_subtitle', '')</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <div class="topbar-profile">
                <div class="topbar-avatar">{{ substr($user->name ?? 'GE', 0, 2) }}</div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800" style="font-family:'Sora',sans-serif">{{ $user->name ?? 'Guest' }}</p>
                    <p class="text-xs text-slate-400">{{ isset($user->employee->division->division_name) ? $user->employee->division->division_name : ($user->division ?? 'Employee') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
