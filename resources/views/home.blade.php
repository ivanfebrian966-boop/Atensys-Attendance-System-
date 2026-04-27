<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATTENSYS - Integrated Employee Attendance System</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

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
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body class="bg-white text-slate-800 overflow-x-hidden">
    <x-loader></x-loader>

    <!-- ===== NAVBAR ===== -->
    <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent border-b border-transparent">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="">
                <img id="main-logo" src="{{ asset('images/LOGO.PNG') }}" alt="ATTENSYS Logo" class="h-14 w-auto object-contain transition-all duration-300">
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a href="#features" class="nav-link border border-white/20 text-white font-semibold px-4 py-2 rounded-full hover:bg-white/20 backdrop-blur-sm transition text-center">Features</a>
                <a href="#howitworks" class="nav-link border border-white/20 text-white font-semibold px-4 py-2 rounded-full hover:bg-white/20 backdrop-blur-sm transition text-center">How It Works</a>
                <a href="#about" class="nav-link border border-white/20 text-white font-semibold px-4 py-2 rounded-full hover:bg-white/20 backdrop-blur-sm transition text-center">About</a>
                <a href="/login" class="text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-opacity hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    Login
                </a>
            </nav>
            <button id="mobile-menu-btn" class="md:hidden text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden px-6 pb-4 flex-col gap-3 bg-white/95 backdrop-blur-md border-t border-slate-100 shadow-lg absolute top-full left-0 w-full md:hidden">
            <a href="#features" class="text-slate-700 font-medium py-2 border-b border-slate-100 mt-2">Features</a>
            <a href="#howitworks" class="text-slate-700 font-medium py-2 border-b border-slate-100">How It Works</a>
            <a href="#about" class="text-slate-700 font-medium py-2 border-b border-slate-100">About</a>
            <a href="/login" class="text-white text-sm font-semibold px-5 py-3 rounded-xl text-center shadow-md mt-2" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">Login</a>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="relative min-h-screen flex items-center pt-24 overflow-hidden">
        <!-- Background Slider -->
        <div id="hero-slider" class="absolute inset-0 z-0">
            <div class="slider-bg absolute inset-0 bg-cover bg-center bg-no-repeat transition-opacity duration-1000 opacity-100" style="background-image: url('{{ asset('images/office 2.jpg.jpeg') }}')"></div>
            <div class="slider-bg absolute inset-0 bg-cover bg-center bg-no-repeat transition-opacity duration-1000 opacity-0" style="background-image: url('{{ asset('images/office 3.jpg.jpeg') }}')"></div>
            <div class="slider-bg absolute inset-0 bg-cover bg-center bg-no-repeat transition-opacity duration-1000 opacity-0" style="background-image: url('{{ asset('images/office 4.jpg.jpeg') }}')"></div>
            <div class="slider-bg absolute inset-0 bg-cover bg-center bg-no-repeat transition-opacity duration-1000 opacity-0" style="background-image: url('{{ asset('images/office 5.jpg.jpeg') }}')"></div>
        </div>
        
        <!-- Dark Overlay for Text Readability -->
        <div class="absolute inset-0 bg-slate-900/65 z-0"></div>

        <div class="max-w-6xl mx-auto px-6 py-16 w-full relative z-10">
            <!-- Text Content -->
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 text-white/90 text-xs font-semibold px-4 py-2 rounded-full mb-6 border border-white/20 bg-white/10 backdrop-blur-sm animate-fade-up">
                    <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></span>
                    Smart Attendance Solution
                </div>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold leading-tight text-white mb-6 drop-shadow-lg animate-fade-up delay-1" style="font-family:'Sora',sans-serif">
                    Attendance<br><span class="text-cyan-400">Made Simple</span>
                </h1>
                <p class="text-slate-200 text-lg md:text-xl leading-relaxed mb-10 drop-shadow animate-fade-up delay-2">
                    ATTENSYS is a digital attendance system that helps companies manage employee attendance efficiently using QR Codes with real-time monitoring and instant reports.
                </p>
                <div class="flex flex-wrap gap-4 animate-fade-up delay-3">
                    <a href="/login" class="text-white font-semibold px-8 py-4 rounded-full shadow-lg transition hover:bg-cyan-500 bg-cyan-600 text-center">
                        Get Started
                    </a>
                    <a href="#features" class="border border-white/40 text-white font-semibold px-8 py-4 rounded-full hover:bg-white/20 backdrop-blur-sm transition text-center">
                        View Features
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Slider Dots -->
        <div class="absolute bottom-10 left-0 right-0 flex justify-center gap-3 z-10" id="slider-dots">
            <button class="w-2.5 h-2.5 rounded-full bg-white opacity-100 transition-opacity drop-shadow" onclick="setSlide(0)"></button>
            <button class="w-2.5 h-2.5 rounded-full bg-white opacity-50 transition-opacity drop-shadow" onclick="setSlide(1)"></button>
            <button class="w-2.5 h-2.5 rounded-full bg-white opacity-50 transition-opacity drop-shadow" onclick="setSlide(2)"></button>
            <button class="w-2.5 h-2.5 rounded-full bg-white opacity-50 transition-opacity drop-shadow" onclick="setSlide(3)"></button>
        </div>
    </section>

    <!-- ===== FEATURES (Horizontal Scroll Carousel) ===== -->
    <section id="features" class="py-24 feature-bg">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-indigo-500 text-sm font-semibold uppercase tracking-widest" style="font-family:'Sora',sans-serif">Our Solutions</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight text-slate-900 mt-3 mb-4" style="font-family:'Sora',sans-serif">Main Features</h2>
                <p class="text-slate-500 mt-2 max-w-xl mx-auto" style="font-family:'DM Sans',sans-serif">All you need for your HR team to manage attendance easily and accurately.</p>
            </div>

            <!-- Carousel wrapper -->
            <div class="carousel-wrapper" id="featuresCarousel">
                <div class="carousel-track" id="featuresTrack">
                    @foreach($features as $judul => $deskripsi)
                    <div class="carousel-card">
                        <div class="bg-white rounded-2xl p-7 border border-slate-100 shadow-sm h-full card-hover-carousel">
                            <div class="w-14 h-14 rounded-2xl mb-5 flex items-center justify-center text-2xl
                                @if($judul == 'QR Code Attendance') bg-indigo-50
                                @elseif($judul == 'Attendance History') bg-cyan-50
                                @elseif($judul == 'Employee & Division Management') bg-purple-50
                                @elseif($judul == 'Attendance Recap & Reports') bg-green-50
                                @else bg-slate-50 @endif">
                                @if($judul == 'QR Code Attendance') 📷
                                @elseif($judul == 'Attendance History') 📅
                                @elseif($judul == 'Employee & Division Management') 👥
                                @elseif($judul == 'Attendance Recap & Reports') 📊
                                @else 🔧 @endif
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-2" style="font-family:'Sora',sans-serif">{{ $judul }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed" style="font-family:'DM Sans',sans-serif">{{ $deskripsi }}</p>
                        </div>
                    </div>
                    @endforeach
                    {{-- Duplicate cards for infinite loop --}}
                    @foreach($features as $judul => $deskripsi)
                    <div class="carousel-card" aria-hidden="true">
                        <div class="bg-white rounded-2xl p-7 border border-slate-100 shadow-sm h-full card-hover-carousel">
                            <div class="w-14 h-14 rounded-2xl mb-5 flex items-center justify-center text-2xl
                                @if($judul == 'QR Code Attendance') bg-indigo-50
                                @elseif($judul == 'Attendance History') bg-cyan-50
                                @elseif($judul == 'Employee & Division Management') bg-purple-50
                                @elseif($judul == 'Attendance Recap & Reports') bg-green-50
                                @else bg-slate-50 @endif">
                                @if($judul == 'QR Code Attendance') 📷
                                @elseif($judul == 'Attendance History') 📅
                                @elseif($judul == 'Employee & Division Management') 👥
                                @elseif($judul == 'Attendance Recap & Reports') 📊
                                @else 🔧 @endif
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-2" style="font-family:'Sora',sans-serif">{{ $judul }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed" style="font-family:'DM Sans',sans-serif">{{ $deskripsi }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS (Horizontal Scroll Carousel) ===== -->
    <section id="howitworks" class="py-24 bg-white" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
        <div class="max-w-6xl mx-auto px-6 relative">
            <div class="text-center mb-16">
                <span class="text-white/70 text-sm font-semibold uppercase tracking-widest" style="font-family:'Sora',sans-serif">Simple Steps</span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight text-white mt-3 mb-4" style="font-family:'Sora',sans-serif">Easy & Fast</h2>
            </div>

            <!-- Carousel wrapper -->
            <div class="carousel-wrapper" id="stepsCarousel">
                <div class="carousel-track" id="stepsTrack">
                    @php
                    $steps = [
                        ['icon' => '🔐', 'step' => '01', 'title' => 'Login', 'desc' => 'Employees log in securely to the ATTENSYS system.'],
                        ['icon' => '📷', 'step' => '02', 'title' => 'Scan QR Code', 'desc' => 'Scan the unique QR Code to instantly record attendance.'],
                        ['icon' => '📊', 'step' => '03', 'title' => 'View Reports', 'desc' => 'HR access real-time dashboard and downloadable reports.'],
                    ];
                    @endphp
                    @foreach($steps as $s)
                    <div class="carousel-card carousel-card-step">
                        <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center h-full card-hover-carousel">
                            <div class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center text-3xl mb-4 shadow-sm border border-indigo-100" style="background:linear-gradient(135deg,#eef2ff,#e0f7fa)">{{ $s['icon'] }}</div>
                            <span class="text-xs font-bold text-indigo-400 tracking-widest" style="font-family:'Sora',sans-serif">STEP {{ $s['step'] }}</span>
                            <h3 class="font-bold text-slate-900 text-lg mt-1 mb-2" style="font-family:'Sora',sans-serif">{{ $s['title'] }}</h3>
                            <p class="text-slate-500 text-sm" style="font-family:'DM Sans',sans-serif">{{ $s['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                    {{-- Duplicate for infinite loop --}}
                    @foreach($steps as $s)
                    <div class="carousel-card carousel-card-step" aria-hidden="true">
                        <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center h-full card-hover-carousel">
                            <div class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center text-3xl mb-4 shadow-sm border border-indigo-100" style="background:linear-gradient(135deg,#eef2ff,#e0f7fa)">{{ $s['icon'] }}</div>
                            <span class="text-xs font-bold text-indigo-400 tracking-widest" style="font-family:'Sora',sans-serif">STEP {{ $s['step'] }}</span>
                            <h3 class="font-bold text-slate-900 text-lg mt-1 mb-2" style="font-family:'Sora',sans-serif">{{ $s['title'] }}</h3>
                            <p class="text-slate-500 text-sm" style="font-family:'DM Sans',sans-serif">{{ $s['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ABOUT (Redesigned) ===== -->
    <section id="about" class="py-28 about-bg relative overflow-hidden">
        <!-- Animated background blobs -->
        <div class="absolute top-[-80px] right-[-60px] w-[400px] h-[400px] rounded-full opacity-20 animate-float" style="background:radial-gradient(circle,rgba(99,102,241,0.4),transparent 70%)"></div>
        <div class="absolute bottom-[-60px] left-[-40px] w-[350px] h-[350px] rounded-full opacity-15 animate-float-slow" style="background:radial-gradient(circle,rgba(6,182,212,0.4),transparent 70%)"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full opacity-10" style="background:radial-gradient(circle,rgba(139,92,246,0.3),transparent 70%)"></div>

        <div class="max-w-6xl mx-auto px-6 relative" style="z-index:1">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 text-cyan-400 text-xs font-bold px-5 py-2 rounded-full mb-5 border border-cyan-400/30 bg-cyan-400/10 backdrop-blur-sm" style="font-family:'Sora',sans-serif">
                    <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></span>
                    ABOUT US
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mt-3 mb-4" style="font-family:'Sora',sans-serif">
                    About <span class="gradient-text-glow">ATTENSYS</span>
                </h2>
                <p class="text-slate-400 text-base max-w-lg mx-auto" style="font-family:'DM Sans',sans-serif">
                    Discover the vision behind our smart attendance platform
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1: What is ATTENSYS -->
                <div class="about-card group">
                    <div class="about-card-inner">
                        <div class="about-icon-wrap about-icon-indigo">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-xl mb-3 group-hover:text-cyan-300 transition-colors" style="font-family:'Sora',sans-serif">What is ATTENSYS?</h3>
                        <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors" style="font-family:'DM Sans',sans-serif">An integrated employee attendance system designed to simplify attendance management using modern digital technology.</p>
                        <div class="about-card-line"></div>
                    </div>
                </div>

                <!-- Card 2: Why ATTENSYS -->
                <div class="about-card group">
                    <div class="about-card-inner">
                        <div class="about-icon-wrap about-icon-cyan">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.58-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-xl mb-3 group-hover:text-cyan-300 transition-colors" style="font-family:'Sora',sans-serif">Why ATTENSYS?</h3>
                        <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors" style="font-family:'DM Sans',sans-serif">Enhance attendance monitoring, reduce manual errors, and generate accurate reports all in one dashboard for HR.</p>
                        <div class="about-card-line"></div>
                    </div>
                </div>

                <!-- Card 3: Support -->
                <div class="about-card group">
                    <div class="about-card-inner">
                        <div class="about-icon-wrap about-icon-purple">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-white text-xl mb-3 group-hover:text-cyan-300 transition-colors" style="font-family:'Sora',sans-serif">Support</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-5 group-hover:text-slate-300 transition-colors" style="font-family:'DM Sans',sans-serif">Experiencing issues? Our team is ready to help anytime.</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-sm text-slate-300 bg-white/5 rounded-xl px-4 py-2.5 border border-white/10">
                                <span class="text-cyan-400">📧</span>
                                <span style="font-family:'DM Sans',sans-serif">attensys@gmail.com</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-slate-300 bg-white/5 rounded-xl px-4 py-2.5 border border-white/10">
                                <span class="text-cyan-400">📱</span>
                                <span style="font-family:'DM Sans',sans-serif">+62 8X XXX XXX</span>
                            </div>
                        </div>
                        <div class="about-card-line"></div>
                    </div>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="mt-20 rounded-3xl p-12 text-center shadow-2xl relative overflow-hidden" style="background:linear-gradient(135deg,#4f46e5,#0891b2)">
                <div class="absolute inset-0 opacity-10" style="background-image:url('data:image/svg+xml,%3Csvg width=&quot;40&quot; height=&quot;40&quot; viewBox=&quot;0 0 40 40&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.3&quot;%3E%3Ccircle cx=&quot;20&quot; cy=&quot;20&quot; r=&quot;3&quot;/%3E%3C/g%3E%3C/svg%3E')"></div>
                <div class="relative z-10">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-white mb-4" style="font-family:'Sora',sans-serif">Ready to get started?</h3>
                    <p class="text-indigo-100 mb-8 text-lg max-w-md mx-auto" style="font-family:'DM Sans',sans-serif">Join the companies already using ATTENSYS to transform their attendance management.</p>
                    <a href="/login" class="inline-block bg-white text-indigo-700 font-bold px-10 py-4 rounded-2xl hover:bg-indigo-50 transition shadow-lg text-sm hover:shadow-xl hover:-translate-y-0.5 transform" style="font-family:'Sora',sans-serif">
                        Get Started Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-slate-400 py-10">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="">
                <img src="{{ asset('images/LOGO.PNG') }}" alt="ATTENSYS Logo" class="h-10 w-auto object-contain">
            </div>
            <p class="text-black-500 text-sm">© 2026 ATTENSYS — Integrated Employee Attendance System</p>
            <div class="flex gap-5 text-black-500 text-sm">
                <a href="#features" class="text-black-500 text-sm">Features</a>
                <a href="#about" class="text-black-500 text-sm">About</a>
                <a href="/login" class="text-black-500 text-sm">Login</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>