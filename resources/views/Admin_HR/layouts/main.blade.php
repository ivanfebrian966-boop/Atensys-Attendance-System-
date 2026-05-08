<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin HR — ATTENSYS')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @yield('styles')
</head>
<body class="@yield('body_class', 'bg-slate-50')">
    <x-loader></x-loader>
    
    <!-- Konten Utama akan Di-yield di sini -->
    @yield('main_structure')



    <!-- ===== MODALS ===== -->
    @yield('modals')

    <script src="{{ asset('js/Admin_HR/shared.js') }}"></script>
    @yield('scripts')

</body>
</html>
