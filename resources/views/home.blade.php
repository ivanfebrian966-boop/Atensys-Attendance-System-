<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ATTENSYS - Employee Attendance System</title>

    <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav>
        <h2>ATTENSYS</h2>

        <ul>
            <li><a href="#about">About</a></li>
            <li><a href="#features">Features</a></li>
        </ul>
    </nav>


    <!-- HERO -->
    <section class="hero">

        <h1>ATTENSYS</h1>

        <p>
            Integrated Employee Attendance System that helps companies
            manage employee attendance using QR Code technology quickly
            and efficiently.
        </p>

        <div class="hero-btn">

            Login to System
            </a>

            <a href="#features" class="btn-secondary">
                See Features
            </a>

        </div>

    </section>


    <!-- ABOUT -->
    <section class="about" id="about">

        <h2>About ATTENSYS</h2>

        <p>
            ATTENSYS is a web-based attendance management system designed to
            help companies monitor employee attendance more efficiently.
            Instead of using manual attendance books, employees can scan a QR code
            to record their attendance. HR staff can easily manage attendance
            records, verify employee leave requests, and generate attendance reports.
        </p>

    </section>


    <!-- FEATURES -->
    <section class="features" id="features">
        <h2>System Features</h2>
        <div class="feature-grid">
            @foreach($fungsional as $judul => $deskripsi)
            <div class="card">
                <h3>{{ ucfirst($judul) }}</h3>
                <p>{{ $deskripsi }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">

        <h2>Ready to Use ATTENSYS?</h2>

        <p>
            Login now to manage employee attendance quickly and efficiently.
        </p>
        Go to Login Page
        </a>

    </section>


    <footer>

        <p>© 2026 ATTENSYS | Employee Attendance System</p>

    </footer>


    <script src="{{ asset('js/script.js') }}"></script>

</body>

</html>