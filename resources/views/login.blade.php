<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ATTENSYS</title>

    <!-- Tailwind CSS via CDN -->
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

        .left-panel {
            background: linear-gradient(160deg, #1e1b4b 0%, #312e81 40%, #0e4a6b 100%);
        }
        .blob {
            border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-float-slow {
            animation: float 9s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .animate-fade-up {
            opacity: 0;
            transform: translateY(24px);
            animation: fadeUp 0.7s ease forwards;
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }

        .card-floating {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
        }
        .stat-pill {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .input-field {
            width: 100%;
            padding: 0.78rem 1rem 0.78rem 2.8rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            color: #1e293b;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
            font-family: 'DM Sans', sans-serif;
        }
        .input-field:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        }
        .input-field::placeholder { color: #94a3b8; }
        .input-field.error { border-color: #f87171; }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            box-shadow: 0 8px 24px rgba(99,102,241,0.3);
        }
        .btn-login:hover { opacity: 0.92; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); opacity: 1; }

        /* Override browser autofill yellow background */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #eef2ff inset !important;
            -webkit-text-fill-color: #1e293b !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="min-h-screen flex overflow-hidden bg-slate-50">

    <!-- ===== PANEL KIRI ===== -->
    <div class="hidden lg:flex lg:w-1/2 left-panel relative flex-col justify-between p-12 overflow-hidden">
        <div class="absolute top-[-60px] right-[-60px] w-80 h-80 bg-indigo-700 blob opacity-30 animate-float-slow"></div>
        <div class="absolute bottom-[-40px] left-[-40px] w-72 h-72 bg-cyan-800 blob opacity-25 animate-float"></div>
        <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-purple-800 blob opacity-20 animate-float-slow"></div>

        <!-- Logo -->
        <div class="relative flex items-center gap-3" style="z-index:1">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background:linear-gradient(135deg,#818cf8,#67e8f9)">
                <span class="text-white font-bold" style="font-family:'Sora',sans-serif">A</span>
            </div>
            <span class="font-bold text-white text-xl tracking-tight" style="font-family:'Sora',sans-serif">ATTENSYS</span>
        </div>

        <!-- Konten tengah -->
        <div class="relative flex-1 flex flex-col justify-center py-12" style="z-index:1">
            <div class="mb-8">
                <span class="text-cyan-300 text-xs font-semibold uppercase tracking-widest">Welcome Back</span>

                <h1 class="text-4xl font-bold text-white mt-3 leading-tight" style="font-family:'Sora',sans-serif">
                    Monitor absences<br>
                    <span style="background:linear-gradient(90deg,#a5b4fc,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">more intell  igently.</span>
                </h1>
                <p class="text-slate-300 mt-4 text-sm leading-relaxed max-w-xs">
                    Manage employee check-ins, create instant reports, and give HR teams full control all in one place.
                </p>
            </div>

            <!-- Feature cards -->
            <div class="space-y-3 max-w-xs">
                <div class="card-floating rounded-2xl px-5 py-3.5 flex items-center gap-4 animate-fade-up">
                    <div class="text-2xl">📷</div>
                    <div>
                        <p class="text-white text-sm font-semibold" style="font-family:'Sora',sans-serif">QR Code Check-in</p>
                        <p class="text-slate-400 text-xs">Instant & contact-free</p>
                    </div>
                    <div class="ml-auto w-2 h-2 bg-cyan-400 rounded-full"></div>
                </div>
                <div class="card-floating rounded-2xl px-5 py-3.5 flex items-center gap-4 animate-fade-up delay-1">
                    <div class="text-2xl">📊</div>
                    <div>
                        <p class="text-white text-sm font-semibold" style="font-family:'Sora',sans-serif">Real-time Reports</p>
                        <p class="text-slate-400 text-xs">Instantly updated dashboards</p>
                    </div>
                    <div class="ml-auto w-2 h-2 bg-cyan-400 rounded-full"></div>
                </div>
                <div class="card-floating rounded-2xl px-5 py-3.5 flex items-center gap-4 animate-fade-up delay-2">
                    <div class="text-2xl">👥</div>
                    <div>
                        <p class="text-white text-sm font-semibold" style="font-family:'Sora',sans-serif">Team Management</p>
                        <p class="text-slate-400 text-xs">By division & position</p>
                    </div>
                    <div class="ml-auto w-2 h-2 bg-cyan-400 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="relative flex gap-4" style="z-index:1">
            <div class="stat-pill rounded-xl px-4 py-3 text-center flex-1">
                <p class="font-bold text-white text-lg" style="font-family:'Sora',sans-serif">99%</p>
                <p class="text-slate-400 text-xs mt-0.5">Accuracy</p>
            </div>
            <div class="stat-pill rounded-xl px-4 py-3 text-center flex-1">
                <p class="font-bold text-white text-lg" style="font-family:'Sora',sans-serif">24/7</p>
                <p class="text-slate-400 text-xs mt-0.5">Uptime</p>
            </div>
            <div class="stat-pill rounded-xl px-4 py-3 text-center flex-1">
                <p class="font-bold text-white text-lg" style="font-family:'Sora',sans-serif">100+</p>
                <p class="text-slate-400 text-xs mt-0.5">Perusahaan</p>
            </div>
        </div>
    </div>

    <!-- ===== PANEL KANAN (Form) ===== -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative bg-slate-50">
        <!-- Logo mobile -->
        <div class="absolute top-6 left-6 flex items-center gap-2 lg:hidden">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                <span class="text-white font-bold text-xs">A</span>
            </div>
            <span class="font-bold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">ATTENSYS</span>
        </div>

        <div class="w-full max-w-md animate-fade-up bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10 relative z-10 mb-16 lg:mb-32">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">Login</h2>
                <p class="text-slate-500 text-sm mt-2">Enter your credentials to access the dashboard.</p>
            </div>

            <!-- Alert error validasi -->
            @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 flex gap-2 items-start">
                <span class="mt-0.5 text-base">⚠️</span>
                <div>
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 flex gap-2 items-center">
                <span>⚠️</span> {{ session('error') }}
            </div>
            @endif

            @if (session('success'))
            <div class="mb-5 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 flex gap-2 items-center">
                <span>✅</span> {{ session('success') }}
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="input-field {{ $errors->has('email') ? 'error' : '' }}"
                               placeholder="anda@perusahaan.com" required autofocus>
                    </div>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-sm font-medium text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                            Forgot Password?
                        </a>
                        @endif
                    </div>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" id="pwd"
                               class="input-field {{ $errors->has('password') ? 'error' : '' }}"
                               style="padding-right:3rem"
                               placeholder="••••••••" required>
                        <button type="button" onclick="togglePwd()"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                            <svg id="eye-show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-hide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 rounded border-slate-300 cursor-pointer accent-indigo-600">
                    <label for="remember" class="text-sm text-slate-600 cursor-pointer select-none">
                        Remember me for 30 days
                    </label>
                </div>

                <!-- Submit -->
                <div class="pt-1">
                    <button type="submit" class="btn-login">
                        Login to Dashboard
                    </button>
                </div>
            </form>

            <!-- Kembali ke beranda -->
            <div class="text-center mt-6">
                <a href="/home" class="text-sm text-slate-400 hover:text-indigo-600 transition-colors">
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- ===== TOAST NOTIF ===== -->
    <div id="toast" class="fixed bottom-6 right-6 z-[200] opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-slate-900 text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-medium" style="font-family:'DM Sans',sans-serif">
            <span id="toastIcon">⚠️</span>
            <span id="toastMsg">{{ session('toast_error') }}</span>
        </div>
    </div>

    <script>
        function togglePwd() {
            const input = document.getElementById('pwd');
            const show = document.getElementById('eye-show');
            const hide = document.getElementById('eye-hide');
            if (input.type === 'password') {
                input.type = 'text';
                show.classList.add('hidden');
                hide.classList.remove('hidden');
            } else {
                input.type = 'password';
                show.classList.remove('hidden');
                hide.classList.add('hidden');
            }
        }

        @if(session('toast_error'))
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            toast.classList.remove('opacity-0', 'pointer-events-none');
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 4000);
        });
        @endif
    </script>
</body>
</html>