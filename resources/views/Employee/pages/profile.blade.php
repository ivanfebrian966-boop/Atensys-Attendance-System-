@extends('Employee.layouts.main')

@section('title', 'Employee Profile — ATTENSYS')
@section('body_class', 'bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30')
@section('page_title', 'My Profile')
@section('page_subtitle', 'Manage your personal information')

@section('styles')
<style>
    /* Custom animations & glassmorphism effects */
    .profile-card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .profile-card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
    }
    .edit-field {
        transition: all 0.2s ease;
    }
    .edit-field:focus {
        ring: 2px solid #6366f1;
        transform: scale(1.02);
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
@endsection

@section('content')

<!-- Hero Section with Animated Gradient -->
<div class="mb-8 fade-slide-up">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 shadow-2xl p-8 md:p-12">
        <!-- Animated background blobs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/20 rounded-full -mr-48 -mt-48 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/10 rounded-full -ml-36 -mb-36 animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2 animate-ping"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-8">
            <!-- Avatar with ring animation -->
            <div class="flex-shrink-0 group">
                <div class="relative">
                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-indigo-300 to-purple-300 animate-ping opacity-75"></div>
                    <div class="relative w-32 h-32 rounded-full bg-gradient-to-br from-white/30 to-white/10 backdrop-blur-sm border-4 border-white/50 flex items-center justify-center text-5xl font-bold text-white shadow-2xl transition-transform group-hover:scale-105 duration-300">
                        {{ strtoupper(substr($user->name ?? 'GE', 0, 2)) }}
                    </div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-lg"></div>
                </div>
            </div>
            
            <div class="text-center md:text-left flex-1">
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-3 tracking-tight" style="font-family:'Sora',sans-serif">{{ $user->name ?? 'Guest' }}</h1>
            </div>
            
            <!-- Badge -->
            <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-3 text-center">
                <p class="text-2xl font-bold text-white">{{ $totalAttendance ?? 0 }}</p>
                <p class="text-xs text-white/80">Total Kehadiran</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 fade-slide-up" style="animation-delay: 0.1s">
    <!-- NIP -->
    <div class="glass-card rounded-2xl p-5 profile-card-hover">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-medium">NIP</p>
                <p class="text-slate-900 font-bold text-sm tracking-wider">{{ $user->employee->nip ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Position -->
    <div class="glass-card rounded-2xl p-5 profile-card-hover">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-medium">Jabatan</p>
                <p class="text-slate-900 font-bold text-sm">{{ $user->position ?? 'Staff' }}</p>
            </div>
        </div>
    </div>

    <!-- Division -->
    <div class="glass-card rounded-2xl p-5 profile-card-hover">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-medium">Divisi</p>
                <p class="text-slate-900 font-bold text-sm">{{ isset($user->employee->division->division_name) ? $user->employee->division->division_name : ($user->division ?? 'Not set') }}</p>
            </div>
        </div>
    </div>

    <!-- Joined Date -->
    <div class="glass-card rounded-2xl p-5 profile-card-hover">
        <div class="flex items-center justify-between">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 font-medium">Bergabung</p>
                <p class="text-slate-900 font-bold text-sm">{{ isset($user->created_at) && $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Profile Info + Edit Form -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-slide-up" style="animation-delay: 0.2s">
    
    <!-- Left: Detailed Information (Read-only) -->
    <div class="lg:col-span-2">
        <div class="glass-card rounded-2xl p-6">
            <div class="mb-6 pb-4 border-b border-slate-200">
                <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent" style="font-family:'Sora',sans-serif">Informasi Pribadi</h2>
                <p class="text-sm text-slate-500 mt-1">Data lengkap profil karyawan Anda</p>
            </div>

            <div class="space-y-5">
                <!-- NIP -->
                <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">NIP</p>
                        <p class="text-base text-slate-900 font-semibold tracking-wider">{{ $user->employee->nip ?? '-' }}</p>
                    </div>
                </div>

                <!-- Nama -->
                <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Nama Lengkap</p>
                        <p class="text-base text-slate-900 font-semibold">{{ $user->name ?? 'Guest' }}</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Email</p>
                        <p class="text-base text-slate-900 font-semibold break-all">{{ $user->email ?? 'guest@attensys.id' }}</p>
                    </div>
                </div>

                <!-- Divisi -->
                <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Divisi</p>
                        <p class="text-base text-slate-900 font-semibold">{{ isset($user->employee->division->division_name) ? $user->employee->division->division_name : ($user->division ?? 'Belum diisi') }}</p>
                    </div>
                </div>

                <!-- Role -->
                <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Jabatan</p>
                        <p class="text-base text-slate-900 font-semibold">{{ $user->position ?? 'Staff' }}</p>
                    </div>
                </div>

                <!-- Telepon -->
                <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-100 to-pink-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a2.42 2.42 0 10-2.897 2.897l-1.029-2.048a1 1 0 00-.756-.502L7.177 6.73A1 1 0 006.28 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Telepon</p>
                        <p class="text-base text-slate-900 font-semibold">{{ $user->phone ?? ($user->employee->no_hp ?? 'Belum diisi') }}</p>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-white/50 transition-colors">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Alamat</p>
                        <p class="text-base text-slate-900 font-semibold">{{ $user->address ?? ($user->employee->alamat ?? 'Belum diisi') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Status Card (No Edit for Guests) -->
    <div>
        <div class="glass-card rounded-2xl p-6 sticky top-24">
            <div class="mb-6 text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900" style="font-family:'Sora',sans-serif">Status Akun</h3>
                <p class="text-xs text-slate-500 mt-1">Informasi status karyawan</p>
            </div>

            <div class="space-y-4">
                <!-- Status Badge -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg p-4 border border-emerald-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-2 h-2 bg-emerald-600 rounded-full animate-pulse"></div>
                        <p class="text-sm font-semibold text-emerald-900">Akun Aktif</p>
                    </div>
                    <p class="text-xs text-emerald-700">Akun Anda dalam status aktif dan siap digunakan</p>
                </div>

                <!-- Tanggal Terdaftar -->
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <p class="text-xs text-slate-600 font-medium mb-2">Tanggal Terdaftar</p>
                    <p class="text-sm font-semibold text-slate-900">{{ isset($user->created_at) && $user->created_at ? $user->created_at->translatedFormat('d MMMM Y') : '-' }}</p>
                </div>

                <!-- Last Update -->
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <p class="text-xs text-slate-600 font-medium mb-2">Update Terakhir</p>
                    <p class="text-sm font-semibold text-slate-900">{{ isset($user->updated_at) && $user->updated_at ? $user->updated_at->translatedFormat('d MMMM Y') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
