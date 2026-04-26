<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Sora', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    animation: {
                        'blob': 'blob 10s infinite alternate',
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'slide-up': 'slideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '100%': { transform: 'translate(50px, -50px) scale(1.1)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050B14;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        .bg-fluid {
            position: fixed;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            background: #050B14;
        }

        .blob-1 {
            position: absolute;
            top: -20%;
            left: -10%;
            width: 70vw;
            height: 70vw;
            background: radial-gradient(circle, #0A44FF 0%, transparent 70%);
            mix-blend-mode: screen;
            opacity: 0.8;
            filter: blur(80px);
            animation: blob 15s infinite alternate ease-in-out;
        }

        .blob-2 {
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, #00188F 0%, transparent 70%);
            mix-blend-mode: screen;
            opacity: 0.7;
            filter: blur(80px);
            animation: blob 12s infinite alternate-reverse ease-in-out;
            animation-delay: 2s;
        }

        .blob-3 {
            position: absolute;
            top: 20%;
            right: 10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, #407BFF 0%, transparent 70%);
            mix-blend-mode: screen;
            opacity: 0.5;
            filter: blur(80px);
            animation: blob 10s infinite alternate ease-in-out;
            animation-delay: 4s;
        }



        /* Glitch / Sliced effect */
        .sliced-text-container {
            position: relative;
            display: inline-block;
            font-family: 'Playfair Display', serif;
            color: white;
            line-height: 1;
        }
        
        /* Mobile: smaller text, Desktop: huge text */
        .sliced-text-container {
            font-size: 10rem;
        }
        @media (min-width: 768px) {
            .sliced-text-container { font-size: 18rem; }
        }
        @media (min-width: 1024px) {
            .sliced-text-container { font-size: 24rem; }
        }

        .sliced-top {
            position: absolute;
            inset: 0;
            clip-path: polygon(0 0, 100% 0, 100% 46%, 0 46%);
            transform: translateX(10px);
        }
        
        .sliced-bottom {
            position: absolute;
            inset: 0;
            clip-path: polygon(0 54%, 100% 54%, 100% 100%, 0 100%);
            transform: translateX(-10px);
        }

        .sliced-middle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120%;
            text-align: center;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
            z-index: 10;
        }
        
        @media (min-width: 768px) {
            .sliced-middle { font-size: 1rem; letter-spacing: 0.3em; }
        }

        .underline-deco {
            width: 80px;
            height: 4px;
            background-color: white;
            margin: 2rem auto 0;
            position: relative;
        }
        .underline-deco::after {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            width: 60px;
            height: 4px;
            background-color: white;
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-500 { animation-delay: 500ms; }
        .delay-600 { animation-delay: 600ms; }
        .delay-700 { animation-delay: 700ms; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative font-sans text-white opacity-0 animate-fade-in">
    <x-loader></x-loader>

    <!-- Fluid Background -->
    <div class="bg-fluid">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
        <div class="blob-3"></div>
    </div>

    <!-- ===== NAVBAR ===== -->
    <header id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-transparent opacity-0 animate-fade-in delay-200">
        <div class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="z-50">
                <img src="{{ asset('images/LOGO.PNG') }}" alt="ATTENSYS Logo" class="h-16 w-auto object-contain">
            </a>
            
            <nav class="hidden md:flex items-center gap-8 absolute left-1/2 -translate-x-1/2">
                <a href="{{ url('/') }}#features" class="text-white font-semibold hover:text-blue-400 transition-colors">Features</a>
                <a href="{{ url('/') }}#howitworks" class="text-white font-semibold hover:text-blue-400 transition-colors">How It Works</a>
                <a href="{{ url('/') }}#about" class="text-white font-semibold hover:text-blue-400 transition-colors">About</a>
            </nav>
            
            <div class="flex items-center gap-6 z-50">
                <a href="{{ url('/login') }}" class="hidden md:block text-white font-semibold px-6 py-2 rounded-xl transition hover:bg-white/10 border border-white/20">
                    Login
                </a>
                <button id="mobile-menu-btn" class="md:hidden text-white border border-white/30 rounded-xl px-4 py-2 font-semibold">
                    Menu
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden bg-[#050B14]/95 backdrop-blur-md absolute top-full left-0 w-full border-t border-white/10 flex-col items-center py-6 gap-6">
            <a href="{{ url('/') }}#features" class="font-semibold text-white">Features</a>
            <a href="{{ url('/') }}#howitworks" class="font-semibold text-white">How It Works</a>
            <a href="{{ url('/') }}#about" class="font-semibold text-white">About</a>
            <a href="{{ url('/login') }}" class="font-semibold text-white border border-white/30 rounded-xl px-6 py-2 w-max">Login</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center w-full px-4 relative z-10 pt-20">
        
        <div class="sliced-text-container opacity-0 animate-slide-up delay-300">
            <div class="sliced-top">404</div>
            <div class="sliced-bottom">404</div>
            <!-- Invisible text to keep container size -->
            <div class="invisible">404</div>
            
            <div class="sliced-middle opacity-0 animate-fade-in delay-700">
                Sorry, this page is missing.
            </div>
        </div>
        
        <div class="underline-deco opacity-0 animate-slide-up delay-500"></div>
        
        <a href="{{ url('/') }}" class="mt-16 text-xs md:text-sm tracking-[0.2em] uppercase border-b border-white/50 pb-1 hover:border-white transition-colors opacity-0 animate-slide-up delay-600">
            Return to Homepage
        </a>
    </main>

    <script>
        // ===== Loader =====
        window.addEventListener('load', function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.style.transition = 'opacity 0.5s ease';
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    });

        // Mobile menu toggle
        const btnMenu = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (btnMenu && mobileMenu) {
            btnMenu.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('flex');
            });
        }
    </script>
</body>
</html>
