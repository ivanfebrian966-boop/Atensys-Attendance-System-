<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/profileHR.css') }}">
</head>
<body>

@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

@include('Admin_HR.sidebar')

<div class="main-content">
    <div class="topbar">
        <div class="px-4 md:px-6 py-4 flex items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3">
                <button class="topbar-hamburger" onclick="openSidebar()">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="page-title">Profile</h1>
                    <p class="text-xs text-slate-400" id="currentDate">—</p>
                </div>
            </div>
            <div class="topbar-profile">
                <div class="topbar-avatar">HR</div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800 sora">Admin HR</p>
                    <p class="text-xs text-slate-400">HR Manager</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6">
        <div class="profile-grid">

            <!-- LEFT: Profile card -->
            <div class="fade-up d1">
                <!-- Cover -->
                <div class="profile-cover">
                    <div class="cover-bg"></div>
                    <div class="cover-avatar-wrap">
                        <div class="cover-avatar" id="avatarDisplay">HR</div>
                        <button class="avatar-edit-btn" onclick="document.getElementById('avatarInput').click()" title="Ganti foto">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                        <input type="file" id="avatarInput" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                    </div>
                </div>

                <!-- Info -->
                <div class="profile-card">
                    <div class="profile-name-wrap">
                        <h2 class="profile-name sora" id="dispName">{{ optional($user)->name ?? 'Admin HR' }}</h2>
                        <span class="profile-role-badge">HR Manager</span>
                    </div>
                    <p class="profile-email">{{ optional($user)->email ?? 'hr@attensys.id' }}</p>

                    <!-- Stats row -->
                    <div class="profile-stats">
                        <div class="prof-stat">
                            <p class="prof-stat-val sora" id="statEmpHandled">48</p>
                            <p class="prof-stat-lbl">Karyawan</p>
                        </div>
                        <div class="prof-stat">
                            <p class="prof-stat-val sora">7</p>
                            <p class="prof-stat-lbl">Divisi</p>
                        </div>
                        <div class="prof-stat">
                            <p class="prof-stat-val sora">2+</p>
                            <p class="prof-stat-lbl">Thn Bergabung</p>
                        </div>
                    </div>

                    <!-- Activity list -->
                    <div class="activity-section">
                        <p class="activity-title sora">Aktivitas Terbaru</p>
                        <div class="activity-list" id="activityList">
                            <div class="activity-item">
                                <span class="act-dot" style="background:#10b981"></span>
                                <div>
                                    <p class="act-text">Menambah karyawan baru</p>
                                    <p class="act-time">2 jam lalu</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <span class="act-dot" style="background:#6366f1"></span>
                                <div>
                                    <p class="act-text">Koreksi absensi — Budi Pratama</p>
                                    <p class="act-time">5 jam lalu</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <span class="act-dot" style="background:#f59e0b"></span>
                                <div>
                                    <p class="act-text">Export laporan bulanan</p>
                                    <p class="act-time">Kemarin, 15:30</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <span class="act-dot" style="background:#06b6d4"></span>
                                <div>
                                    <p class="act-text">Update divisi Marketing</p>
                                    <p class="act-time">2 hari lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Edit form -->
            <div class="fade-up d2">

                <!-- Personal Info -->
                <div class="panel mb-4">
                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Informasi Pribadi</h3>
                            <p class="panel-subtitle">Perbarui data profil Anda</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="save-indicator hidden" id="saveIndicator">
                                <span class="text-emerald-600 text-xs font-semibold sora">✅ Tersimpan</span>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body pt-4 pb-4">
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" id="pName" class="form-input"
                                    value="{{ optional($user)->name ?? 'Admin HR' }}"
                                    oninput="markDirty()">
                                <span class="form-error" id="epName"></span>
                            </div>
                            <div class="form-field">
                                <label class="form-label">ID Karyawan</label>
                                <input type="text" id="pEmpId" class="form-input" value="HR-001"
                                    readonly style="background:#f8fafc;color:#94a3b8;cursor:not-allowed">
                            </div>
                            <div class="form-field col-2">
                                <label class="form-label">Email *</label>
                                <input type="email" id="pEmail" class="form-input"
                                    value="{{ optional($user)->email ?? 'hr@attensys.id' }}"
                                    oninput="markDirty()">
                                <span class="form-error" id="epEmail"></span>
                            </div>
                            <div class="form-field">
                                <label class="form-label">No. HP</label>
                                <input type="text" id="pPhone" class="form-input"
                                    value="{{ optional($user)->phone ?? '' }}"
                                    placeholder="08xxxxxxxxxx" oninput="markDirty()">
                            </div>
                            <div class="form-field">
                                <label class="form-label">Jabatan</label>
                                <input type="text" id="pPosition" class="form-input"
                                    value="{{ optional($user)->position ?? 'HR Manager' }}"
                                    oninput="markDirty()">
                            </div>
                            <div class="form-field">
                                <label class="form-label">Divisi</label>
                                <input type="text" id="pDivision" class="form-input"
                                    value="{{ optional($user)->division ?? 'HR' }}"
                                    oninput="markDirty()">
                            </div>
                            <div class="form-field">
                                <label class="form-label">Tanggal Bergabung</label>
                                <input type="date" id="pJoinDate" class="form-input"
                                    value="{{ optional($user)->join_date ?? '2024-01-01' }}"
                                    oninput="markDirty()">
                            </div>
                            <div class="form-field col-2">
                                <label class="form-label">Alamat</label>
                                <textarea id="pAddress" class="form-input" rows="2"
                                    placeholder="Alamat lengkap" oninput="markDirty()">{{ optional($user)->address ?? '' }}</textarea>
                            </div>
                            <div class="form-field col-2">
                                <label class="form-label">Bio Singkat</label>
                                <textarea id="pBio" class="form-input" rows="3"
                                    placeholder="Ceritakan sedikit tentang Anda..." oninput="markDirty()">{{ optional($user)->bio ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-5 pt-4 border-t border-slate-100">
                            <button class="btn-ghost" onclick="resetProfile()" id="resetBtn" disabled>Reset</button>
                            <button class="btn-primary" onclick="saveProfile()" id="saveProfileBtn" disabled>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Ubah Password</h3>
                            <p class="panel-subtitle">Buat password baru yang kuat</p>
                        </div>
                        <div class="security-badge">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Aman
                        </div>
                    </div>
                    <div class="modal-body pt-4 pb-4">
                        <div class="form-grid">
                            <div class="form-field col-2">
                                <label class="form-label">Password Saat Ini *</label>
                                <div class="pwd-wrap">
                                    <input type="password" id="pwdCurrent" class="form-input" placeholder="Password lama">
                                    <button class="pwd-toggle" onclick="togglePwd('pwdCurrent',this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="form-error" id="epPwdCurrent"></span>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Password Baru *</label>
                                <div class="pwd-wrap">
                                    <input type="password" id="pwdNew" class="form-input" placeholder="Min. 8 karakter" oninput="checkPwdStrength(this.value)">
                                    <button class="pwd-toggle" onclick="togglePwd('pwdNew',this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Password strength -->
                                <div class="pwd-strength" id="pwdStrength" style="display:none">
                                    <div class="strength-bars">
                                        <div class="str-bar" id="sb1"></div>
                                        <div class="str-bar" id="sb2"></div>
                                        <div class="str-bar" id="sb3"></div>
                                        <div class="str-bar" id="sb4"></div>
                                    </div>
                                    <span class="strength-label" id="strLabel">—</span>
                                </div>
                                <span class="form-error" id="epPwdNew"></span>
                            </div>
                            <div class="form-field">
                                <label class="form-label">Konfirmasi Password *</label>
                                <div class="pwd-wrap">
                                    <input type="password" id="pwdConfirm" class="form-input" placeholder="Ulangi password baru">
                                    <button class="pwd-toggle" onclick="togglePwd('pwdConfirm',this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="form-error" id="epPwdConfirm"></span>
                            </div>
                        </div>
                        <div class="flex justify-end mt-5 pt-4 border-t border-slate-100">
                            <button class="btn-primary" onclick="changePassword()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Ubah Password
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"><div class="toast-inner"><span id="tIcon">✅</span><span id="tMsg">Berhasil!</span></div></div>

<script src="{{ asset('js/Admin_HR/shared.js') }}"></script>
<script src="{{ asset('js/Admin_HR/profile.js') }}"></script>
</body>
</html>
