<!-- TOPBAR -->
<div class="topbar">
    <div class="px-6 py-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <!-- Hamburger (mobile) -->
            <button class="lg:hidden p-2 rounded-xl hover:bg-slate-100 transition" onclick="openSidebar()">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif" id="pageTitle">@yield('page_title', 'Dashboard')</h1>
                <p class="text-xs text-slate-400" id="realtime-date">{{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <!-- Profile -->
            <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold text-white" style="background:linear-gradient(135deg,#6366f1,#06b6d4);font-family:'Sora',sans-serif">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800" style="font-family:'Sora',sans-serif">{{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
