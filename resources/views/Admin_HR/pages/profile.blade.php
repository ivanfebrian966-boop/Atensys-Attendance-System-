@extends('Admin_HR.layouts.main')

@php
    $user = $user ?? Auth::user();
@endphp

@section('title', 'HR Admin Profile — ATTENSYS')
@section('body_class', 'bg-gradient-to-br from-slate-50 via-cyan-50/20 to-blue-50/20')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/Admin_HR/ProfileHR.css') }}">
<style>
    .profile-card-hover { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
    .profile-card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -12px rgba(0,0,0,0.1); }
    .glass-card { background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3); }
    .info-row { display:flex; align-items:flex-start; gap:1rem; padding:0.75rem; border-radius:0.75rem; transition:background 0.2s; }
    .info-row:hover { background: rgba(255,255,255,0.6); }
    @keyframes fadeSlideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .fade-slide-up { animation: fadeSlideUp 0.5s ease-out forwards; }
    .icon-box { width:3rem; height:3rem; border-radius:0.75rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
</style>
@endsection

@section('main_structure')
@include('Admin_HR.components.sidebar')

<div class="main-content">
    @include('Admin_HR.components.topbar', [
        'pageTitle'    => 'Profile',
        'pageSubtitle' => 'View and update your account information',
    ])

    <div class="p-4 md:p-6 space-y-6">

        @if(session('success'))
        <div class="px-4 py-3 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl flex items-center gap-3 fade-slide-up">
            <span class="text-xl">✅</span>
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
        @endif

        {{-- ===== HERO BANNER ===== --}}
        <div class="relative overflow-hidden rounded-3xl shadow-2xl fade-up d1"
             style="background: linear-gradient(135deg, #031a40ff 0%, #04378aff 50%, #1c3f7cff 100%)">
            <div class="absolute top-0 right-0 w-80 h-80 bg-white/10 rounded-full -mr-40 -mt-40 animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full -ml-32 -mb-32"></div>
            <div class="absolute top-1/2 right-1/3 w-32 h-32 bg-white/5 rounded-full animate-ping" style="animation-duration:4s"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center md:items-end gap-8 p-8 md:p-10">
                {{-- Avatar --}}
                <div class="flex-shrink-0 group">
                    <div class="relative">
                        <div class="relative w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/50
                                    flex items-center justify-center text-4xl font-bold text-white" style="font-family:'Sora',sans-serif">
                            {{ strtoupper(substr($user->name ?? 'HR', 0, 2)) }}
                        </div>
                    </div>
                </div>

                {{-- Info --}}
                <div class="text-center md:text-left flex-1">
                    <p class="text-white/70 text-sm font-medium uppercase tracking-widest mb-1">Admin HR</p>
                    <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight" style="font-family:'Sora',sans-serif">
                        {{ $user->name ?? 'Admin HR' }}
                    </h1>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start mt-3">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">
                            {{ $user->position ?? 'HR Manager' }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm text-white font-medium">
                            {{ $user?->division?->division_name ?? 'HR Department' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>


        {{-- ===== MAIN GRID ===== --}}
        <div class="grid lg:grid-cols-2 gap-6 fade-up d3">

            {{-- LEFT: Personal Info --}}
            <div class="panel p-6">
                <div class="mb-5 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Personal Info</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Your HR Admin profile details</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-500/80 backdrop-blur-sm rounded-full text-sm text-white font-medium">● Active</span>
                </div>

                @php
                    $rows = [
                        ['label'=>'Full Name',  'value'=>$user->name ?? 'N/A',       'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'clr'=>'indigo'],
                        ['label'=>'Email',      'value'=>$user->email ?? 'N/A',      'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'clr'=>'blue'],
                        ['label'=>'NIP',        'value'=>$user->nip ?? '-',           'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',  'clr'=>'purple'],
                        ['label'=>'Position',   'value'=>$user->position ?? 'HR Manager', 'icon'=>'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'clr'=>'violet'],
                        ['label'=>'Division',   'value'=>$user?->division?->division_name ?? '-', 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'clr'=>'cyan'],
                        ['label'=>'Phone',      'value'=>$user->no_hp ?? 'Not set',   'icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a2.42 2.42 0 10-2.897 2.897l-1.029-2.048a1 1 0 00-.756-.502L7.177 6.73A1 1 0 006.28 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2', 'clr'=>'teal'],
                        ['label'=>'Address',    'value'=>$user->alamat ?? 'Not set',  'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'clr'=>'pink'],
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($rows as $row)
                    <div class="flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="w-9 h-9 rounded-lg bg-{{ $row['clr'] }}-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-{{ $row['clr'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $row['icon'] }}"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-widest">{{ $row['label'] }}</p>
                            <p class="text-sm text-slate-800 font-semibold truncate">{{ $row['value'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT: Status --}}
            <div class="panel p-6 sticky top-24">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl
                                flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Account Status</h3>
                    <p class="text-xs text-slate-400 mt-0.5">HR Admin account info</p>
                </div>

                <div class="space-y-3">
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                            <p class="text-sm font-bold text-emerald-800">Active Account</p>
                        </div>
                        <p class="text-xs text-emerald-600">Your account is active and fully functional</p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide mb-1">Role</p>
                        <p class="font-bold text-slate-800 text-sm">HR Admin</p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide mb-1">Registered</p>
                        <p class="font-bold text-slate-800 text-sm">
                            {{ $user->created_at ? $user->created_at->format('d F Y') : 'N/A' }}
                        </p>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide mb-1">Last Updated</p>
                        <p class="font-bold text-slate-800 text-sm">
                            {{ $user->updated_at ? $user->updated_at->format('d F Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
