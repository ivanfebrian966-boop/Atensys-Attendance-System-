<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/HRprofile.css') }}">
</head>
<body>

@include('Admin_HR.sidebar')

<div class="main-content">
    <!-- TOPBAR -->
    @include('Admin_HR.topbarHR', [
        'pageTitle'    => 'Profil Pengguna',
        'pageSubtitle' => 'Pengaturan akun Admin HR',
    ])

    <!-- FLASH MESSAGES -->
    @if(session('success'))
    <div id="flashToast" class="flash-toast flash-success">
        <span>✅</span>
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('flashToast').remove()" class="flash-close">×</button>
    </div>
    @endif

    @if($errors->any())
    <div id="flashToastErr" class="flash-toast flash-error">
        <span>⚠️</span>
        <span>{{ $errors->first() }}</span>
        <button onclick="document.getElementById('flashToastErr').remove()" class="flash-close">×</button>
    </div>
    @endif

    <!-- CONTENT -->
    <div class="p-4 md:p-6">

        <!-- TAB NAVIGATION -->
        <div class="tab-nav mb-6">
            <button class="tab-btn {{ !session('open_tab') || session('open_tab') === 'profile' ? 'active' : '' }}"
                    onclick="switchTab('profile', this)" id="tabBtnProfile">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Pribadi
            </button>
            <button class="tab-btn {{ session('open_tab') === 'security' ? 'active' : '' }}"
                    onclick="switchTab('security', this)" id="tabBtnSecurity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Keamanan
            </button>
        </div>

        <!-- ========== TAB: PROFILE ========== -->
        <div id="tabProfile" class="tab-content {{ session('open_tab') === 'security' ? 'hidden' : '' }}">
            <div class="profile-grid">

                <!-- LEFT: Profile Card -->
                <div class="profile-left fade-up d1">
                    <div class="profile-card" style="border-radius:20px;">

                        <!-- Cover + Avatar -->
                        <!-- Cover + Display (Simple) -->
                        <div class="profile-cover">
                            <div class="cover-bg"></div>
                            <div class="cover-avatar-wrap">
                                <div class="cover-avatar">
                                    <span class="avatar-initials">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 48px; margin-bottom:20px; text-align:center; padding: 0 20px;">
                            <h2 class="profile-name sora">{{ $user->name }}</h2>
                            <div style="margin-top:4px;">
                                <span class="profile-role-badge">{{ $user->position ?? 'HR Manager' }}</span>
                            </div>
                            <p class="profile-email" style="margin-top:6px;">{{ $user->email }}</p>
                        </div>

                        <!-- Stats -->
                        <div class="profile-stats">
                            <div class="prof-stat">
                                <p class="prof-stat-val">{{ $user->division ?? '—' }}</p>
                                <p class="prof-stat-lbl">Divisi</p>
                            </div>
                            <div class="prof-stat">
                                <p class="prof-stat-val">
                                    {{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('Y') : '—' }}
                                </p>
                                <p class="prof-stat-lbl">Bergabung</p>
                            </div>
                            <div class="prof-stat">
                                <p class="prof-stat-val">HR</p>
                                <p class="prof-stat-lbl">Role</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- RIGHT: Edit Form -->
                <div class="profile-right space-y-6">
                    <div class="panel fade-up d2">
                        <div class="panel-header">
                            <div>
                                <h3 class="panel-title">Informasi Pribadi</h3>
                                <p class="panel-subtitle">Perbarui detail profil akun Anda</p>
                            </div>
                        </div>

                        <div class="p-5 md:p-6">
                            <form action="{{ route('profileHR.update') }}" method="POST" id="profileForm">
                                @csrf
                                @method('POST')

                                <div class="form-grid">
                                    <!-- Nama -->
                                    <div class="form-field col-span-2">
                                        <label class="form-label" for="pName">Nama Lengkap <span class="text-red-400">*</span></label>
                                        <input type="text" id="pName" name="name" class="form-input @error('name') is-error @enderror"
                                            value="{{ old('name', $user->name) }}"
                                            placeholder="Nama lengkap" required>
                                        @error('name')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-field">
                                        <label class="form-label" for="pEmail">Alamat Email <span class="text-red-400">*</span></label>
                                        <input type="email" id="pEmail" name="email" class="form-input @error('email') is-error @enderror"
                                            value="{{ old('email', $user->email) }}"
                                            placeholder="email@attensys.id" required>
                                        @error('email')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Telepon -->
                                    <div class="form-field">
                                        <label class="form-label" for="pPhone">Nomor Telepon</label>
                                        <input type="tel" id="pPhone" name="phone" class="form-input @error('phone') is-error @enderror"
                                            value="{{ old('phone', $user->phone) }}"
                                            placeholder="08xxxxxxxxxx">
                                        @error('phone')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Divisi -->
                                    <div class="form-field">
                                        <label class="form-label" for="pDivision">Divisi / Departemen</label>
                                        <input type="text" id="pDivision" name="division" class="form-input @error('division') is-error @enderror"
                                            value="{{ old('division', $user->division) }}"
                                            placeholder="Divisi Anda">
                                        @error('division')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Jabatan -->
                                    <div class="form-field">
                                        <label class="form-label" for="pPosition">Jabatan</label>
                                        <input type="text" id="pPosition" name="position" class="form-input @error('position') is-error @enderror"
                                            value="{{ old('position', $user->position) }}"
                                            placeholder="Posisi Anda">
                                        @error('position')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Bergabung -->
                                    <div class="form-field">
                                        <label class="form-label" for="pJoinDate">Tanggal Bergabung</label>
                                        <input type="date" id="pJoinDate" name="join_date" class="form-input @error('join_date') is-error @enderror"
                                            value="{{ old('join_date', $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('Y-m-d') : '') }}">
                                        @error('join_date')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Alamat -->
                                    <div class="form-field col-span-2">
                                        <label class="form-label" for="pAddress">Alamat Rumah</label>
                                        <textarea id="pAddress" name="address" class="form-input @error('address') is-error @enderror" rows="2"
                                            placeholder="Alamat lengkap tempat tinggal">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <span class="form-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                                    <a href="{{ route('profileHR') }}" class="btn-ghost">Batal</a>
                                    <button type="submit" class="btn-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end right -->

            </div><!-- end profile-grid -->
        </div><!-- end tabProfile -->

        <!-- ========== TAB: SECURITY ========== -->
        <div id="tabSecurity" class="tab-content {{ session('open_tab') === 'security' ? '' : 'hidden' }}">
            <div class="max-w-xl">
                <div class="panel fade-up d1">
                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Ganti Password</h3>
                            <p class="panel-subtitle">Pastikan password Anda kuat dan unik</p>
                        </div>
                    </div>

                    <div class="p-5 md:p-6">
                        <form action="{{ route('profileHR.password') }}" method="POST" id="passwordForm">
                            @csrf

                            <!-- Password Lama -->
                            <div class="form-field mb-4">
                                <label class="form-label" for="pwdCurrent">Password Saat Ini <span class="text-red-400">*</span></label>
                                <div class="input-pwd-wrap">
                                    <input type="password" id="pwdCurrent" name="current_password"
                                        class="form-input @error('current_password') is-error @enderror"
                                        placeholder="Masukkan password saat ini" autocomplete="current-password">
                                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdCurrent', this)" title="Tampilkan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password Baru -->
                            <div class="form-field mb-4">
                                <label class="form-label" for="pwdNew">Password Baru <span class="text-red-400">*</span></label>
                                <div class="input-pwd-wrap">
                                    <input type="password" id="pwdNew" name="new_password"
                                        class="form-input @error('new_password') is-error @enderror"
                                        placeholder="Minimal 8 karakter" autocomplete="new-password"
                                        oninput="checkPwdStrength(this.value)">
                                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdNew', this)" title="Tampilkan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                                @error('new_password')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror

                                <!-- Strength Meter -->
                                <div id="pwdStrength" style="display:none;" class="mt-2">
                                    <div class="strength-bars">
                                        <div class="sbar" id="sb1"></div>
                                        <div class="sbar" id="sb2"></div>
                                        <div class="sbar" id="sb3"></div>
                                        <div class="sbar" id="sb4"></div>
                                    </div>
                                    <span id="strLabel" class="text-xs font-semibold mt-1 block"></span>
                                </div>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="form-field mb-6">
                                <label class="form-label" for="pwdConfirm">Konfirmasi Password Baru <span class="text-red-400">*</span></label>
                                <div class="input-pwd-wrap">
                                    <input type="password" id="pwdConfirm" name="new_password_confirmation"
                                        class="form-input @error('new_password_confirmation') is-error @enderror"
                                        placeholder="Ulangi password baru" autocomplete="new-password">
                                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdConfirm', this)" title="Tampilkan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                                @error('new_password_confirmation')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tips -->
                            <div class="pwd-tips">
                                <p class="text-xs font-semibold text-slate-600 mb-2">Tips Password Kuat:</p>
                                <ul class="text-xs text-slate-500 space-y-1 list-none pl-0">
                                    <li class="flex items-center gap-1.5"><span class="tip-dot"></span> Minimal 8 karakter</li>
                                    <li class="flex items-center gap-1.5"><span class="tip-dot"></span> Kombinasi huruf besar & kecil</li>
                                    <li class="flex items-center gap-1.5"><span class="tip-dot"></span> Mengandung angka</li>
                                    <li class="flex items-center gap-1.5"><span class="tip-dot"></span> Mengandung karakter spesial (!@#$%)</li>
                                </ul>
                            </div>

                            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                                <a href="{{ route('profileHR') }}" class="btn-ghost">Batal</a>
                                <button type="submit" class="btn-primary btn-danger-gradient">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- end tabSecurity -->

    </div>
</div>

<script src="{{ asset('js/Admin_HR/HRprofile.js') }}"></script>
<script>
/* ---- TAB SWITCHING ---- */
function switchTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.remove('hidden');
    btn.classList.add('active');
}


/* ---- PASSWORD TOGGLE ---- */
function togglePwd(id, btn) {
    const inp = document.getElementById(id);
    if (!inp) return;
    const isText = inp.type === 'text';
    inp.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '#94a3b8' : '#6366f1';
}

/* ---- PASSWORD STRENGTH ---- */
function checkPwdStrength(val) {
    const wrap = document.getElementById('pwdStrength');
    if (!wrap) return;
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';

    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
    const labels = ['Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
    const strLabel = document.getElementById('strLabel');
    if (strLabel) {
        strLabel.textContent = labels[score - 1] || 'Lemah';
        strLabel.style.color = colors[score - 1] || '#ef4444';
    }
    for (let i = 1; i <= 4; i++) {
        const bar = document.getElementById(`sb${i}`);
        if (bar) bar.style.background = i <= score ? colors[score - 1] : '#e2e8f0';
    }
}

/* ---- AUTO HIDE FLASH TOAST ---- */
setTimeout(() => {
    const t1 = document.getElementById('flashToast');
    const t2 = document.getElementById('flashToastErr');
    if (t1) t1.remove();
    if (t2) t2.remove();
}, 5000);
</script>
</body>
</html>
