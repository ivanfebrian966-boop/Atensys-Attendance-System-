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

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3, .font-display { font-family: 'Sora', sans-serif; }

        .gradient-text {
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-glow {
            background: radial-gradient(ellipse 80% 60% at 60% 40%, rgba(99,102,241,0.12) 0%, transparent 70%),
                        radial-gradient(ellipse 60% 50% at 20% 80%, rgba(6,182,212,0.08) 0%, transparent 70%);
        }
        .feature-bg {
            background: linear-gradient(135deg, #f8f7ff 0%, #eef2ff 50%, #e0f7fa 100%);
        }
        .about-bg {
            background: linear-gradient(160deg, #0f172a 0%, #1e1b4b 50%, #0e2a4a 100%);
        }
        .blob {
            border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(99,102,241,0.15);
        }
        .nav-blur {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-float-slow {
            animation: float 9s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
        }
        .animate-fade-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s ease forwards;
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .card-floating {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
        }
        .img-glow {
            box-shadow: 0 0 60px rgba(99,102,241,0.25), 0 0 120px rgba(6,182,212,0.08);
        }
        .text-hover {
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .text-hover:hover {
            color: #1b15b3; /* dark green */
            transform: translateY(-5px) scale(1.05);
        }
        .sentence-hover {
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .sentence-hover:hover {
            background: linear-gradient(135deg, #083182); /* dark green to orange */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transform: translateY(-4px) scale(1);
        }
    </style>
</head>
<body class="bg-white text-slate-800 overflow-x-hidden">

    <!-- ===== NAVBAR ===== -->
    <header id="navbar" class="fixed top-0 left-0 right-0 z-50 nav-blur bg-white/80 border-b border-slate-100 transition-shadow duration-300">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg,#6366f1,#06b6d4)">
                    <span class="text-white font-bold text-sm" style="font-family:'Sora',sans-serif">A</span>
                </div>
                <span class="font-bold text-xl text-slate-900 tracking-tight" style="font-family:'Sora',sans-serif">ATTENSYS</span>
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-slate-600 hover:text-indigo-600 text-sm font-medium transition-colors duration-200">Features</a>
                <a href="#howitworks" class="text-slate-600 hover:text-indigo-600 text-sm font-medium transition-colors duration-200">How It Works</a>
                <a href="#about" class="text-slate-600 hover:text-indigo-600 text-sm font-medium transition-colors duration-200">About</a>
                <a href="/login" class="text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-opacity hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    Login
                </a>
            </nav>
            <button class="md:hidden text-slate-600" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 flex flex-col gap-3 bg-white border-t border-slate-100">
            <a href="#features" class="text-slate-700 font-medium py-2 border-b border-slate-100">Features</a>
            <a href="#howitworks" class="text-slate-700 font-medium py-2 border-b border-slate-100">How It Works</a>
            <a href="#about" class="text-slate-700 font-medium py-2 border-b border-slate-100">About</a>
            <a href="/login" class="text-white text-sm font-semibold px-5 py-3 rounded-xl text-center" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">Login</a>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="relative min-h-screen flex items-center pt-24 hero-glow overflow-hidden">
        <div class="absolute top-20 right-0 w-96 h-96 bg-indigo-100 blob opacity-50 animate-float-slow" style="z-index:0"></div>
        <div class="absolute bottom-10 left-10 w-64 h-64 bg-cyan-100 blob opacity-40 animate-float" style="z-index:0"></div>

        <div class="max-w-6xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-16 items-center relative" style="z-index:1">
            <!-- Text -->
            <div>
                <div class="inline-flex items-center gap-2 text-indigo-700 text-xs font-semibold px-4 py-2 rounded-full mb-6 animate-fade-up border border-indigo-100 bg-indigo-50 text-hover">
                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                    Smart Attendance Solution
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold leading-tight text-slate-900 mb-4 animate-fade-up delay-1" style="font-family:'Sora',sans-serif">
                    Attendance<br><span class="gradient-text">Made Simple</span>
                </h1>
                <p class="text-slate-500 text-lg leading-relaxed mb-8 animate-fade-up delay-2 sentence-hover">
                    ATTENSYS is a digital attendance system that helps companies manage employee attendance efficiently using QR Codes with real-time monitoring and instant reports.
                </p>
                <div class="flex flex-wrap gap-4 animate-fade-up delay-3">
                    <a href="/login" class="text-white font-semibold px-7 py-3.5 rounded-xl shadow-lg transition hover:opacity-90" style="background:linear-gradient(135deg,#6366f1,#06b6d4);box-shadow:0 8px 24px rgba(99,102,241,0.25)">
                        Get Started →
                    </a>
                    <a href="#features" class="border border-slate-200 text-slate-700 font-semibold px-7 py-3.5 rounded-xl hover:bg-slate-50 transition">
                        View Features
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mt-12 animate-fade-up delay-4">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-indigo-600 text-hover" style="font-family:'Sora',sans-serif">99%</p>
                        <p class="text-xs text-slate-400 mt-1 sentence-hover">Accuracy</p>
                    </div>
                    <div class="text-center border-x border-slate-100">
                        <p class="text-3xl font-bold text-cyan-500 text-hover" style="font-family:'Sora',sans-serif">24/7</p>
                        <p class="text-xs text-slate-400 mt-1 sentence-hover">Uptime</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-indigo-600 text-hover" style="font-family:'Sora',sans-serif">100+</p>
                        <p class="text-xs text-slate-400 mt-1 sentence-hover">Companies</p>
                    </div>
                </div>
            </div>

            <!-- Image -->
            <div class="relative flex justify-center animate-fade-up delay-2">
                <div class="relative w-full max-w-md">
                    <div class="absolute inset-0 rounded-3xl blur-2xl opacity-20 scale-95" style="background:linear-gradient(135deg,#6366f1,#06b6d4)"></div>
                    <img src="{{ asset('images/atten.jpg') }}"
                         alt="ATTENSYS Dashboard"
                         class="relative rounded-3xl w-full object-cover animate-float img-glow">
                    <!-- Badge bawah kiri -->
                    <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl shadow-xl px-5 py-3 flex items-center gap-3 border border-slate-100">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-xl">✅</div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">Check-in Successful</p>
                            <p class="text-xs text-slate-400">Just now via QR Code</p>
                        </div>
                    </div>
                    <!-- Badge atas kanan -->
                    <div class="absolute -top-5 -right-5 bg-white rounded-2xl shadow-xl px-4 py-3 border border-slate-100">
                        <p class="font-bold text-indigo-600 text-lg">📊</p>
                        <p class="text-xs font-semibold text-slate-700">Live Report</p>
                        <p class="text-xs text-slate-400">42 karyawan hadir</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section id="features" class="py-24 feature-bg">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-indigo-600 text-sm font-semibold uppercase tracking-widest text-hover">What We Offer</span>
                <h2 class="text-4xl font-bold text-slate-900 mt-3 sentence-hover" style="font-family:'Sora',sans-serif">Main Features</h2>
                <p class="text-slate-500 mt-4 max-w-xl mx-auto sentence-hover">All you need for your HR team to manage attendance easily and accurately.</p>
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
    <section id="howitworks" class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-cyan-600 text-sm font-semibold uppercase tracking-widest text-hover">How It Works</span>
                <h2 class="text-4xl font-bold text-slate-900 mt-3 sentence-hover" style="font-family:'Sora',sans-serif">Easy & Fast</h2>
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
    <footer class="bg-slate-950 py-10">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                    <span class="text-white font-bold text-xs">A</span>
                </div>
                <span class="font-bold text-white text-sm" style="font-family:'Sora',sans-serif">ATTENSYS</span>
            </div>
            <p class="text-slate-500 text-sm sentence-hover">© 2026 ATTENSYS — Integrated Employee Attendance System</p>
            <div class="flex gap-5 text-slate-500 text-sm">
                <a href="#features" class="hover:text-white transition">Features</a>
                <a href="#about" class="hover:text-white transition">About</a>
                <a href="/login" class="hover:text-white transition">Login</a>
            </div>
        </div>
    </footer>

    <script>
        // Navbar shadow on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            nav.style.boxShadow = window.scrollY > 20 ? '0 4px 24px rgba(0,0,0,0.08)' : 'none';
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const el = document.querySelector(a.getAttribute('href'));
                if (el) el.scrollIntoView({ behavior: 'smooth' });
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>
</body>
</html>