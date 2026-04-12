<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin HR — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <style>
        .profile-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .profile-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-slide-up {
            animation: fadeSlideUp 0.5s ease-out forwards;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30">

@include('Admin_HR.sidebar')


<div class="main-content">
    <!-- TOPBAR -->
    @include('Admin_HR.topbarHR', [
        'pageTitle'    => 'Profil Admin HR',
        'pageSubtitle' => 'Kelola informasi akun Anda',
    ])

    <!-- FLASH MESSAGES -->
    @if(session('success'))
    <div class="m-4 md:m-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 text-sm animate-pulse">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="m-4 md:m-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        @foreach($errors->all() as $error)
            <p>⚠️ {{ $error }}</p>
        @endforeach
    </div>
    @endif

    <div class="p-4 md:p-6">
        <!-- Hero Card -->
        <div class="mb-8 fade-slide-up">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-cyan-600 via-blue-600 to-blue-700 shadow-2xl p-8 md:p-12">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/20 rounded-full -mr-48 -mt-48 animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/10 rounded-full -ml-36 -mb-36 animate-pulse delay-1000"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-8">
                    <div class="flex-shrink-0 group">
                        <div class="relative">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-r from-cyan-300 to-blue-300 animate-ping opacity-75"></div>
                            <div class="relative w-32 h-32 rounded-full bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-sm border-4 border-white/50 flex items-center justify-center text-5xl font-bold text-white shadow-2xl transition-transform group-hover:scale-105 duration-300">
                                {{ strtoupper(substr($user->name ?? 'HR', 0, 2)) }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 tracking-tight" style="font-family:'Sora',sans-serif">{{ $user->name ?? 'Admin HR' }}</h1>
                        <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">
                                {{ $user->position ?? 'HR Manager' }}
                            </span>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">
                                {{ $user->division ?? 'HR Department' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 fade-slide-up" style="animation-delay: 0.1s">
            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Email</p>
                        <p class="text-slate-900 font-semibold text-sm truncate max-w-[180px]">{{ $user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Divisi</p>
                        <p class="text-slate-900 font-semibold text-sm">{{ $user->division ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a2.42 2.42 0 10-2.897 2.897l-1.029-2.048a1 1 0 00-.756-.502L7.177 6.73A1 1 0 006.28 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Telepon</p>
                        <p class="text-slate-900 font-semibold text-sm">{{ $user->phone ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-5 profile-card-hover">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500 font-medium">Bergabung</p>
                        <p class="text-slate-900 font-semibold text-sm">{{ isset($user->join_date) && $user->join_date ? \Carbon\Carbon::parse($user->join_date)->translatedFormat('d M Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Profile Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-slide-up" style="animation-delay: 0.2s">
            
            <!-- Left: Detailed Information -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6">
                    <div class="mb-6 pb-4 border-b border-slate-200">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent" style="font-family:'Sora',sans-serif">Informasi Pribadi</h2>
                        <p class="text-sm text-slate-500 mt-1">Data lengkap profil Admin HR Anda</p>
                    </div>

                    <div class="space-y-5">
                        <!-- Nama -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Nama Lengkap</p>
                                <p class="text-base text-slate-900 font-semibold">{{ $user->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Email</p>
                                <p class="text-base text-slate-900 font-semibold break-all">{{ $user->email ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Posisi -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Posisi / Jabatan</p>
                                <p class="text-base text-slate-900 font-semibold">{{ $user->position ?? 'HR Manager' }}</p>
                            </div>
                        </div>

                        <!-- Divisi -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-teal-100 to-teal-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Divisi</p>
                                <p class="text-base text-slate-900 font-semibold">{{ $user->division ?? 'HR Department' }}</p>
                            </div>
                        </div>

                        <!-- Telepon -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a2.42 2.42 0 10-2.897 2.897l-1.029-2.048a1 1 0 00-.756-.502L7.177 6.73A1 1 0 006.28 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Telepon</p>
                                <p class="text-base text-slate-900 font-semibold">{{ $user->phone ?? 'Belum diisi' }}</p>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                            <div class="w-12 h-12 bg-gradient-to-br from-pink-100 to-pink-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Alamat</p>
                                <p class="text-base text-slate-900 font-semibold">{{ $user->address ?? 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Status & Additional Info -->
            <div>
                <div class="glass-card rounded-2xl p-6 sticky top-24">
                    <div class="mb-6 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">Status Akun</h3>
                        <p class="text-xs text-slate-500 mt-1">Informasi status Admin HR</p>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></div>
                                <p class="text-sm font-semibold text-green-900">Akun Aktif</p>
                            </div>
                            <p class="text-xs text-green-700">Akun Admin HR Anda aktif dan berfungsi normal</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                            <p class="text-xs text-slate-600 font-medium mb-2">Tanggal Bergabung</p>
                            <p class="text-sm font-semibold text-slate-900">{{ isset($user->join_date) && $user->join_date ? \Carbon\Carbon::parse($user->join_date)->translatedFormat('d MMMM Y') : 'N/A' }}</p>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                            <p class="text-xs text-slate-600 font-medium mb-2">Role</p>
                            <p class="text-sm font-semibold text-slate-900">Admin HR</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/Admin_HR/shared.js') }}"></script>
</body>
</html>
