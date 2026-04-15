<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Dashboard — ATTENSYS')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Employee/EmployeeDashboard.css') }}">
    
    @yield('styles')
</head>
<body class="@yield('body_class', 'bg-slate-50')">

    <!-- ===== SIDEBAR ===== -->
    @include('Employee.components.sidebar')

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
        <!-- ===== TOPBAR ===== -->
        @include('Employee.components.topbar')

        <div class="p-4 md:p-6">
            @yield('content')
        </div>
    </div>
    
    @yield('modals')

    <!-- ===== TOAST ===== -->
    <div id="toast" class="toast">
        <div class="toast-inner">
            <span id="toastIcon">✅</span>
            <span id="toastMsg">Success!</span>
        </div>
    </div>

    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <!-- Employee Scripts -->
    <script src="{{ asset('js/Employee/EmployeeDashboard.js') }}"></script>
    
    @yield('scripts')

</body>
</html>
