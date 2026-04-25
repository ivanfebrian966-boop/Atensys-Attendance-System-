<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATTENSYS - Integrated Employee Attendance System</title>

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
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body class="bg-white text-slate-800 overflow-x-hidden">
    <x-loader></x-loader>

    <!-- ===== NAVBAR ===== -->
    <header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent border-b border-transparent">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg,#6366f1,#06b6d4)">
                    <span class="text-white font-bold text-sm" style="font-family:'Sora',sans-serif">A</span>
                </div>
                <span id="logo-text" class="font-bold text-xl text-white tracking-tight transition-colors duration-300" style="font-family:'Sora',sans-serif">ATTENSYS</span>
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

    <!-- ===== FEATURES ===== -->
    <section id="features" class="py-24 feature-bg">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight text-slate-900 mb-6 drop-shadow-lg animate-fade-up delay-1" style="font-family:'Sora',sans-serif">Main Features</h2>
                <p class="text-slate-500 mt-4 max-w-xl mx-auto sentence-hover" style="font-family:'Sora',sans-serif">All you need for your HR team to manage attendance easily and accurately.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($features as $judul => $deskripsi)
                <div class="bg-white rounded-2xl p-7 card-hover border border-slate-100 shadow-sm">
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
                    <h3 class="font-semibold text-slate-900 text-base mb-2 text-hover" style="font-family:'Sora',sans-serif">{{ $judul }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed sentence-hover">{{ $deskripsi }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section id="howitworks" class="py-24 bg-white" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
        <div class="max-w-5xl mx-auto px-6 relative">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight text-white mb-6 drop-shadow-lg animate-fade-up delay-1" style="font-family:'Sora',sans-serif">Easy & Fast</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center card-hover">
                    <div class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center text-3xl mb-4 shadow-sm border border-indigo-100" style="background:linear-gradient(135deg,#eef2ff,#e0f7fa)">🔐</div>
                    <span class="text-xs font-bold text-indigo-400 tracking-widest">STEP 01</span>
                    <h3 class="font-bold text-slate-900 text-lg mt-1 mb-2 text-hover" style="font-family:'Sora',sans-serif">Login</h3>
                    <p class="text-slate-500 text-sm sentence-hover">Employees log in securely to the ATTENSYS system.</p>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center card-hover">
                    <div class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center text-3xl mb-4 shadow-sm border border-indigo-100" style="background:linear-gradient(135deg,#eef2ff,#e0f7fa)">📷</div>
                    <span class="text-xs font-bold text-indigo-400 tracking-widest">STEP 02</span>
                    <h3 class="font-bold text-slate-900 text-lg mt-1 mb-2 text-hover" style="font-family:'Sora',sans-serif">Scan QR Code</h3>
                    <p class="text-slate-500 text-sm sentence-hover">Scan the unique QR Code to instantly record attendance.</p>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-slate-100 text-center card-hover">
                    <div class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center text-3xl mb-4 shadow-sm border border-indigo-100" style="background:linear-gradient(135deg,#eef2ff,#e0f7fa)">📊</div>
                    <span class="text-xs font-bold text-indigo-400 tracking-widest">STEP 03</span>
                    <h3 class="font-bold text-slate-900 text-lg mt-1 mb-2 text-hover" style="font-family:'Sora',sans-serif">View Reports</h3>
                    <p class="text-slate-500 text-sm sentence-hover">HR access real-time dashboard and downloadable reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ABOUT ===== -->
    <section id="about" class="py-24 about-bg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-900 blob opacity-30"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-900 blob opacity-20"></div>
        <div class="max-w-6xl mx-auto px-6 relative" style="z-index:1">
            <div class="text-center mb-16">
                <span class="text-cyan-400 text-sm font-semibold uppercase tracking-widest text-hover">About Us</span>
                <h2 class="text-4xl font-bold text-white mt-3 sentence-hover" style="font-family:'Sora',sans-serif">About ATTENSYS</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="card-floating rounded-2xl p-8 card-hover bg-white">
                    <div class="text-4xl mb-5">💡</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-3 text-hover" style="font-family:'Sora',sans-serif">What is ATTENSYS?</h3>
                    <p class="text-slate-700 text-sm leading-relaxed sentence-hover">An integrated employee attendance system designed to simplify attendance management using modern digital technology.</p>
                </div>
                <div class="card-floating rounded-2xl p-8 card-hover bg-white">
                    <div class="text-4xl mb-5">🚀</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-3 text-hover" style="font-family:'Sora',sans-serif">why ATTENSYS?</h3>
                    <p class="text-slate-700 text-sm leading-relaxed sentence-hover">Enhance attendance monitoring, reduce manual errors, and generate accurate reports all in one dashboard for HR.</p>
                </div>
                <div class="card-floating rounded-2xl p-8 card-hover bg-white">
                    <div class="text-4xl mb-5">📞</div>
                    <h3 class="font-bold text-slate-900 text-lg mb-3 text-hover" style="font-family:'Sora',sans-serif">Support</h3>
                    <p class="text-slate-700 text-sm leading-relaxed mb-4 sentence-hover">Experiencing issues? Our team is ready to help anytime.</p>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-sm text-slate-700">
                            <span>📧</span> attensys@gmail.com
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-700">
                            <span>📱</span> +62 8X XXX XXX
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="mt-16 rounded-3xl p-10 text-center shadow-2xl" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                <h3 class="text-3xl font-bold text-white mb-3 text-hover" style="font-family:'Sora',sans-serif">Ready to get started?</h3>
                <p class="text-indigo-100 mb-7 text-base sentence-hover">Join the companies already using ATTENSYS.</p>
                <a href="/login" class="inline-block bg-white text-indigo-700 font-bold px-8 py-3.5 rounded-xl hover:bg-indigo-50 transition shadow-lg text-sm" style="font-family:'Sora',sans-serif">
                    Get Started Now
                </a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-slate-400 py-10">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    <span class="text-white font-bold text-xs">A</span>
                </div>
                <span class="font-bold text-white text-sm" style="font-family:'Sora',sans-serif">ATTENSYS</span>
            </div>
            <p class="text-black-500 text-sm sentence-hover">© 2026 ATTENSYS — Integrated Employee Attendance System</p>
            <div class="flex gap-5 text-black-500 text-sm">
                <a href="#features" class="text-black-500 text-sm sentence-hover">Features</a>
                <a href="#about" class="text-black-500 text-sm sentence-hover">About</a>
                <a href="/login" class="text-black-500 text-sm sentence-hover">Login</a>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>