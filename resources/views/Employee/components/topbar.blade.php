{{--
    =====================================================================
    ATTENSYS — Employee Topbar Partial
    Params:
      $pageTitle    : Judul halaman (opsional, default: 'Dashboard')
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
            <button class="lg:hidden p-2 rounded-xl hover:bg-slate-100 transition" onclick="openSidebar()" aria-label="Open Sidebar">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h1 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">
                    @yield('page_title', 'Employee Dashboard')
                </h1>
                <p class="text-xs text-slate-400" id="realtime-date">
                    {{ now()->format('l, d F Y | H:i:s') }}
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

<script>
    (function () {
        const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        function pad(n) { return String(n).padStart(2, '0'); }

        function updateClock() {
            const el = document.getElementById('realtime-date');
            if (!el) return;
            const now = new Date();
            const day   = days[now.getDay()];
            const date  = pad(now.getDate());
            const month = months[now.getMonth()];
            const year  = now.getFullYear();
            const time  = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
            el.textContent = `${day}, ${date} ${month} ${year} | ${time}`;
        }

        updateClock();
        setInterval(updateClock, 1000);
    })();
</script>
