<!-- ===== SIDEBAR ===== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-blob-1"></div>
    <div class="sidebar-blob-2"></div>

    <!-- Logo -->
    <div class="px-6 pt-6 pb-4 flex flex-col gap-1 relative" style="z-index:1">
        <img src="{{ asset('images/LOGO.PNG') }}" alt="ATTENSYS Logo" class="h-14 w-auto object-contain self-start">
        <p class="text-[10px] text-slate-400 font-medium tracking-widest uppercase" style="font-family:'Sora',sans-serif">Super Admin</p>
    </div>

    <!-- Divider -->
    <div class="mx-6 h-px bg-white/10 mb-2"></div>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto pb-4 relative" style="z-index:1">
        <p class="nav-section-label">Overview</p>

        <a href="{{ route('super_admin.dashboard') }}" class="nav-item {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a4 4 0 014-4h10a4 4 0 014 4v10a4 4 0 01-4 4H7a4 4 0 01-4-4V7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6M9 15h4"/>
            </svg>
            Dashboard
        </a>

        <p class="nav-section-label">Account Management</p>

        <a href="{{ route('super_admin.employees') }}" class="nav-item {{ request()->routeIs('super_admin.employees') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Employee Accounts
            @if(isset($employees_count))
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(99,102,241,0.2);color:#a5b4fc">{{ $employees_count }}</span>
            @endif
        </a>

        <a href="{{ route('super_admin.admins') }}" class="nav-item {{ request()->routeIs('super_admin.admins') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Admin HR Accounts
            @if(isset($hr_admins_count))
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(6,182,212,0.2);color:#67e8f9">{{ $hr_admins_count }}</span>
            @endif
        </a>

        <a href="{{ route('super_admin.divisions') }}" class="nav-item {{ request()->routeIs('super_admin.divisions') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Division Data
            @if(isset($divisions_count))
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(139,92,246,0.2);color:#c4b5fd">{{ $divisions_count }}</span>
            @endif
        </a>

        <p class="nav-section-label">Account</p>

        <a href="{{ route('super_admin.profile') }}" class="nav-item {{ request()->routeIs('super_admin.profile') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
    </nav>

    <!-- User info -->
    <div class="mx-4 mb-4 p-3 rounded-2xl relative" style="z-index:1;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1)">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0" style="background:linear-gradient(135deg,#6366f1,#06b6d4);font-family:'Sora',sans-serif">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate" style="font-family:'Sora',sans-serif">{{ Auth::user()->name }}</p>
                <p class="text-slate-400 text-xs truncate">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('logout') }}" class="tooltip-wrap">
                <svg class="w-4 h-4 text-slate-400 hover:text-red-400 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="tooltip-text">Logout</span>
            </a>
        </div>
    </div>
</aside>
