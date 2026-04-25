<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin') — ATTENSYS</title>
    <link href="{{ asset('css/Super_admin/dashboard_super_admin.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Sora', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body>
    <x-loader></x-loader>
    
    @include('Super_Admin.components.sidebar')

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
        @include('Super_Admin.components.topbar')

        <!-- CONTENT AREA -->
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl flex items-center gap-3 fade-up">
                    <span class="text-xl">✅</span>
                    <div>
                        <p class="font-bold text-sm">Success!</p>
                        <p class="text-xs">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl flex items-center gap-3 fade-up">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <p class="font-bold text-sm">Error!</p>
                        <p class="text-xs">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @include('Super_Admin.components.modals')

    <!-- Form Hapus Hidden -->
    <form id="formDelete" method="POST" style="display:none">
        @csrf
        @method('DELETE')
    </form>

    <!-- ===== TOAST NOTIF ===== -->
    <div id="toast" class="fixed bottom-6 right-6 z-[200] opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-medium" style="font-family:'DM Sans',sans-serif">
            <span id="toastIcon">✅</span>
            <span id="toastMsg">Saved successfully!</span>
        </div>
    </div>

    <script>
        // Auto open modal on error
        @if(session('error_modal'))
            document.addEventListener('DOMContentLoaded', function() {
                openModal('{{ session('error_modal') }}');
            });
        @endif
    </script>

    <script src="{{ asset('js/Super_admin/dashboard_super_admin.js') }}"></script>
    @stack('scripts')
</body>
</html>
