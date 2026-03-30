<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATTENSYS - Integrated Employee Attendance System</title>
    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
</head>
<body>
    <!-- NAVBAR -->
    <header>
        <div class="container nav">
            <div class="logo-area">
                <img src="{{ asset('images/logo_attensys_removebg.png') }}" class="logo-img">
            </div>
            <nav>
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="/login" class="btn-login">Login</a>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <h1 class="hero-title">ATTENSYS</h1>
                <h3 class="hero-subtitle">
                    Attendance System
                </h3>
                <p>
                    ATTENSYS is a digital attendance system that helps companies manage employee attendance efficiently. Employees can check in and check out using QR codes while HR administrators can monitor attendance records and generate reports easily.
                </p>
                <a href="/login" class="btn-primary">Get Started</a>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/atten.jpg') }}">
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="features">
        <div class="container">
            <h2>Key Features</h2>
            <div class="features-grid">
                @foreach($features as $judul => $deskripsi)
                <div class="feature-card">
                    <div class="feature-icon">
                        @if($judul == 'QR Code Attendance')
                        📷
                        @elseif($judul == 'Attendance History')
                        📅
                        @elseif($judul == 'Employee & Division Management')
                        👥
                        @elseif($judul == 'Attendance Recap & Reports')
                        📊
                        @endif
                    </div>
                    <h3>{{ $judul }}</h3>
                    <p>{{ $deskripsi }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="about">
        <div class="container">
            <h2>About ATTENSYS</h2>
            <div class="about-grid">
                <div class="about-card">
                    <div class="about-icon">💡</div>
                    <h3>What is ATTENSYS?</h3>
                    <p>
                        ATTENSYS is an integrated employee attendance system designed to simplify attendance management in companies using modern digital technology.
                    </p>
                </div>
                <div class="about-card">
                    <div class="about-icon">🚀</div>
                    <h3>Why ATTENSYS?</h3>
                    <p>
                        ATTENSYS helps companies improve attendance monitoring, reduce manual errors, and provide accurate attendance reports for HR administrators.
                    </p>
                </div>
                <div class="about-card">
                    <div class="about-icon">📞</div>
                    <h3>Support</h3>
                    <p>If you experience problems while using ATTENSYS, please contact us.</p>
                    <p>Email : attensys@gmail.com</p>
                    <p>Phone : +62 8X XXX XXX</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container footer">
            <p>© 2026 ATTENSYS - Integrated Employee Attendance System</p>
        </div>
    </footer>
</body>
</html>