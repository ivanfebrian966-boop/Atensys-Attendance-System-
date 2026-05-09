@extends('Employee.layouts.main')

@section('title', 'Employee Profile — ATTENSYS')
@section('body_class', 'bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30')
@section('page_title', 'Profile')
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
<div class="mb-8 fade-up d1">
    <div class="relative overflow-hidden rounded-3xl shadow-2xl p-8 md:p-10"
         style="background: linear-gradient(135deg, #031a40ff 0%, #04378aff 50%, #1c3f7cff 100%)">
        
        <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-8">
            <!-- Avatar -->
            <div class="flex-shrink-0 group">
                <div class="relative">
                    <div class="relative w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/50 flex items-center justify-center text-4xl font-bold text-white" style="font-family:'Sora',sans-serif">
                        {{ strtoupper(substr($user->name ?? 'GE', 0, 2)) }}
                    </div>
                </div>
            </div>
            
            <div class="text-center md:text-left flex-1">
                <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-1">Employee</p>
                <h1 class="text-3xl font-bold text-white leading-tight" style="font-family:'Sora',sans-serif">
                    {{ $user->name ?? 'Guest' }}
                </h1>
                <div class="flex flex-wrap gap-2 justify-center md:justify-start mt-3">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">{{ $user->position ?? 'Staff' }}</span>
                    <span class="px-3 py-1 bg-slate-500/80 backdrop-blur-sm rounded-full text-sm text-white font-medium">{{ $user->division->division_name ?? '-' }}</span>  
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content: Profile Info + Edit Form -->
<div class="grid lg:grid-cols-2 gap-6 fade-up d3" style="animation-delay: 0.2s">
    
    {{-- LEFT: Personal Info --}}
    <div class="panel p-6">
        <div class="mb-5 pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Personal Info</h3>
                <p class="text-xs text-slate-400 mt-0.5">Your employee profile details</p>
            </div>
            <span class="px-3 py-1 bg-emerald-500/80 backdrop-blur-sm rounded-full text-sm text-white font-medium">● Active</span>
        </div>

        @php
            $rows = [
                ['label'=>'Full Name',  'value'=>$user->name ?? 'Guest',     'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'clr'=>'indigo'],
                ['label'=>'Email',      'value'=>$user->email ?? 'guest@attensys.id',    'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'clr'=>'blue'],
                ['label'=>'NIP',        'value'=>$user->nip ?? '-',          'icon'=>'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2',  'clr'=>'purple'],
                ['label'=>'Position',   'value'=>$user->position ?? 'Staff', 'icon'=>'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'clr'=>'emerald'],
                ['label'=>'Division',   'value'=>$user?->division?->division_name ?? 'Not set', 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'clr'=>'cyan'],
                ['label'=>'Phone',      'value'=>$user->no_hp ?? 'Not set',  'icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a2.42 2.42 0 10-2.897 2.897l-1.029-2.048a1 1 0 00-.756-.502L7.177 6.73A1 1 0 006.28 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2', 'clr'=>'pink'],
                ['label'=>'Address',    'value'=>$user->alamat ?? 'Not set', 'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'clr'=>'red'],
            ];
        @endphp

        <div class="space-y-4">
            @foreach($rows as $row)
            <div class="group flex items-center gap-4 p-3.5 rounded-2xl bg-gradient-to-br from-slate-50/80 to-slate-100/40 backdrop-blur-xl border border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.6),_0_4px_12px_rgba(0,0,0,0.03)] hover:bg-white/80 hover:shadow-[inset_0_2px_4px_rgba(255,255,255,0.8),_0_8px_16px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-{{ $row['clr'] }}-100 to-{{ $row['clr'] }}-50 flex items-center justify-center flex-shrink-0 shadow-inner border border-{{ $row['clr'] }}-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-{{ $row['clr'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $row['icon'] }}"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">{{ $row['label'] }}</p>
                    <p class="text-[15px] text-slate-800 font-bold truncate" style="font-family:'Sora',sans-serif">{{ $row['value'] }}</p>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- RIGHT: Status Card --}}
    <div class="panel p-6 sticky top-24">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-800 to-blue-500 rounded-2xl
                        flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Account Status</h3>
            <p class="text-xs text-slate-400 mt-0.5">Employee account information</p>
        </div>

        <div class="space-y-4">
            {{-- Active Status Card --}}
            <div class="group bg-gradient-to-br from-emerald-50/80 to-emerald-100/40 backdrop-blur-xl border border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.6),_0_4px_12px_rgba(0,0,0,0.03)] rounded-2xl p-4 hover:shadow-[inset_0_2px_4px_rgba(255,255,255,0.8),_0_8px_16px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
                <div class="flex items-center gap-2 mb-1.5">
                    <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                    <p class="text-sm font-bold text-emerald-800" style="font-family:'Sora',sans-serif">Active Account</p>
                </div>
                <p class="text-[12px] text-emerald-600/90 font-medium">Your account is active and ready to use</p>
            </div>

            {{-- Registered Date --}}
            <div class="group bg-gradient-to-br from-slate-50/80 to-slate-100/40 backdrop-blur-xl border border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.6),_0_4px_12px_rgba(0,0,0,0.03)] rounded-2xl p-4 hover:bg-white/80 hover:shadow-[inset_0_2px_4px_rgba(255,255,255,0.8),_0_8px_16px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Registered Date</p>
                <p class="text-[15px] text-slate-800 font-bold" style="font-family:'Sora',sans-serif">
                    {{ isset($user->created_at) && $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                </p>
            </div>

            {{-- Last Updated --}}
            <div class="group bg-gradient-to-br from-slate-50/80 to-slate-100/40 backdrop-blur-xl border border-white shadow-[inset_0_2px_4px_rgba(255,255,255,0.6),_0_4px_12px_rgba(0,0,0,0.03)] rounded-2xl p-4 hover:bg-white/80 hover:shadow-[inset_0_2px_4px_rgba(255,255,255,0.8),_0_8px_16px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Last Updated</p>
                <p class="text-[15px] text-slate-800 font-bold" style="font-family:'Sora',sans-serif">
                    {{ isset($user->updated_at) && $user->updated_at ? $user->updated_at->translatedFormat('d F Y') : '-' }}
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
