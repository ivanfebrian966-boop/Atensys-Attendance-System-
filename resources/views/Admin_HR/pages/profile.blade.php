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
                        ['label'=>'Gender',     'value'=>($user->gender ? $user->gender->gender_name : 'Not set'), 'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'clr'=>($user->gender && $user->gender->gender_name === 'Woman' ? 'pink' : 'sky')],
                        ['label'=>'Position',   'value'=>$user->position ?? 'HR Manager', 'icon'=>'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'clr'=>'violet'],
                        ['label'=>'Division',   'value'=>$user?->division?->division_name ?? '-', 'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'clr'=>'cyan'],
                        ['label'=>'Phone',      'value'=>$user->no_hp ?? 'Not set',   'icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a2.42 2.42 0 10-2.897 2.897l-1.029-2.048a1 1 0 00-.756-.502L7.177 6.73A1 1 0 006.28 6H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2', 'clr'=>'teal'],
                        ['label'=>'Address',    'value'=>$user->alamat ?? 'Not set',  'icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'clr'=>'pink'],
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

            {{-- RIGHT: Edit Profile Form --}}
            <div class="panel p-6 sticky top-24">
                <div class="mb-5 pb-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-base" style="font-family:'Sora',sans-serif">Edit Profile</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Update your personal information</p>
                </div>

                <form action="{{ route('admin-hr.profile.update') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Phone --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                               placeholder="08xxxxxxxxxx">
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Address</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                               placeholder="Full address">
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Gender</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition-all {{ old('gender', $user->gender) === 'Laki-laki' ? 'border-sky-400 bg-sky-50' : 'border-slate-200 hover:border-sky-200' }}">
                                <input type="radio" name="gender" value="Laki-laki" class="accent-sky-500"
                                       {{ old('gender', $user->gender) === 'Laki-laki' ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-slate-700">👨 Male</span>
                            </label>
                            <label class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition-all {{ old('gender', $user->gender) === 'Perempuan' ? 'border-pink-400 bg-pink-50' : 'border-slate-200 hover:border-pink-200' }}">
                                <input type="radio" name="gender" value="Perempuan" class="accent-pink-500"
                                       {{ old('gender', $user->gender) === 'Perempuan' ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-slate-700">👩 Female</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Change Password <span class="text-slate-300 font-normal">(optional)</span></p>
                        <div class="space-y-3">
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                                   placeholder="Current password">
                            <input type="password" name="new_password"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                                   placeholder="New password (min 8 chars)">
                            <input type="password" name="new_password_confirmation"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                                   placeholder="Confirm new password">
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 rounded-xl font-semibold text-sm text-white transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-0.5"
                            style="background: linear-gradient(135deg, #6366f1, #06b6d4)">
                        💾 Save Changes
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
