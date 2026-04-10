{{-- resources/views/partials/sidebar.blade.php --}}
<button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="openSidebar()" style="position:fixed;top:20px;left:20px;z-index:50;display:none;background:var(--gradient);border:none;border-radius:10px;padding:8px 12px;color:white;font-weight:bold;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
    ☰
</button>
<aside class="sidebar" id="sidebar">
    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
    @endphp
    <div class="sidebar-blob-1"></div>
    <div class="sidebar-blob-2"></div>

    <div class="px-6 pt-6 pb-4 flex items-center gap-3 relative" style="z-index:1">
        <div class="sidebar-logo-icon"><span>A</span></div>
        <div>
            <p class="font-bold text-white text-base leading-tight sora">ATTENSYS</p>
            <p class="text-xs text-slate-400 sora">Admin HR</p>
        </div>
    </div>

    <div class="mx-6 h-px bg-white/10 mb-2"></div>

    <nav class="flex-1 overflow-y-auto pb-4 relative" style="z-index:1">
        <p class="nav-section-label">Menu Utama</p>
        <a href="/dashboardHR" class="nav-item {{ request()->is('dashboardHR') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        <a href="/employees" class="nav-item {{ request()->is('employees') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Employees
        </a>
        <a href="/attendance" class="nav-item {{ request()->is('attendance') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Attendance
        </a>
        <a href="/reports" class="nav-item {{ request()->is('reports') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Reports
        </a>
        <p class="nav-section-label">Pengaturan</p>
        <a href="/HRmanage" class="nav-item {{ request()->is('HRmanage') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Manage
        </a>
        <a href="/profileHR" class="nav-item {{ request()->is('profileHR') ? 'active' : '' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </a>
    </nav>

    <div class="sidebar-user">
        <div class="flex items-center gap-3">
            <div class="sidebar-avatar">HR</div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate sora">{{ optional($user)->name ?? 'Admin HR' }}</p>
                <p class="text-slate-400 text-xs truncate">{{ optional($user)->email ?? 'hr@attensys.id' }}</p>
            </div>
            <a href="{{ route('logout') }}" class="tooltip-wrap">
                <svg class="w-4 h-4 text-slate-400 hover:text-red-400 transition-colors cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="tooltip-text">Keluar</span>
            </a>
        </div>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
<script>
// Sidebar toggle visibility for mobile
function handleSidebarToggleBtn() {
    const btn = document.getElementById('sidebarToggleBtn');
    if (!btn) return;
    if (window.innerWidth <= 1024) {
        btn.style.display = 'block';
    } else {
        btn.style.display = 'none';
    }
}
window.addEventListener('DOMContentLoaded', handleSidebarToggleBtn);
window.addEventListener('resize', handleSidebarToggleBtn);
</script>
