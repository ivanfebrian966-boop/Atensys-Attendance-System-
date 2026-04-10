<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/HRprofile.css') }}">
</head>
<body>

@include('Admin_HR.sidebar')

<div class="main-content">
    <!-- TOPBAR -->
    <div class="topbar">
        <div class="px-4 md:px-6 py-4 flex items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-3">
                <button class="topbar-hamburger" onclick="openSidebar()">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="page-title">Profil Pengguna</h1>
                    <p class="text-xs text-slate-400">Pengaturan akun Admin HR</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="topbar-profile">
                    <div class="topbar-avatar">HR</div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-slate-800 sora">Admin HR</p>
                        <p class="text-xs text-slate-400">HR Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="p-4 md:p-6">
        <div class="profile-grid">

            <!-- ============ LEFT COLUMN ============ -->
            <div class="profile-left fade-up d1">
                <!-- Profile Card -->
                <div class="profile-card" style="padding-top: 10px; text-align: center; border-radius: 20px;">
                    <div class="profile-cover">
                        <div class="cover-bg"></div>
                        <div class="cover-avatar-wrap">
                            <div class="cover-avatar" id="avatarDisplay">
                                <span class="avatar-initials" id="avatarInitials">HR</span>
                            </div>
                            <label class="avatar-edit-btn" for="avatarFileInput" title="Change photo">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </label>
                            <input type="file" id="avatarFileInput" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                        </div>
                    </div>

                    <div style="margin-top: 40px; margin-bottom:20px;">
                        <h2 class="profile-name sora" id="dispName">{{ Auth::user()->name ?? 'Admin HR' }}</h2>
                        <div style="margin-top:4px;">
                            <span class="profile-role-badge">HR Manager</span>
                        </div>
                        <p class="profile-email" style="margin-top:6px;">{{ Auth::user()->email ?? 'hr@attensys.id' }}</p>
                    </div>
                </div>
            </div><!-- end left -->

            <!-- ============ RIGHT COLUMN ============ -->
            <div class="profile-right space-y-6">

                <!-- Personal Info Form -->
                <div class="panel fade-up d2">
                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Informasi Pribadi</h3>
                            <p class="panel-subtitle">Perbarui detail profil akun Anda</p>
                        </div>
                        <div class="header-actions">
                            <div class="hidden" id="saveIndicator">
                                <span class="badge badge-aktif px-2 py-1">Disimpan</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 md:p-6">
                        <div class="form-grid">
                            <div class="form-field col-span-2">
                                <label class="form-label">Nama Lengkap <span class="text-red-400">*</span></label>
                                <input type="text" id="pName" class="form-input"
                                    value="{{ Auth::user()->name ?? 'Admin HR' }}"
                                    placeholder="Nama lengkap"
                                    oninput="markDirty()">
                                <span class="form-error" id="epName"></span>
                            </div>

                            <div class="form-field">
                                <label class="form-label">Alamat Email <span class="text-red-400">*</span></label>
                                <input type="email" id="pEmail" class="form-input"
                                    value="{{ Auth::user()->email ?? 'hr@attensys.id' }}"
                                    placeholder="email@attensys.id"
                                    oninput="markDirty()">
                                <span class="form-error" id="epEmail"></span>
                            </div>

                            <div class="form-field">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" id="pPhone" class="form-input"
                                    value="{{ Auth::user()->phone ?? '' }}"
                                    placeholder="08xxxxxxxxxx"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Divisi / Departemen</label>
                                <input type="text" id="pDivision" class="form-input"
                                    value="{{ Auth::user()->division ?? 'Human Resources' }}"
                                    placeholder="Divisi Anda"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Jabatan</label>
                                <input type="text" id="pPosition" class="form-input"
                                    value="{{ Auth::user()->position ?? 'HR Manager' }}"
                                    placeholder="Posisi Anda"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Tanggal Bergabung</label>
                                <input type="date" id="pJoinDate" class="form-input"
                                    value="{{ Auth::user()->join_date ?? '2024-01-15' }}"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field col-span-2">
                                <label class="form-label">Alamat Rumah</label>
                                <textarea id="pAddress" class="form-input" rows="2"
                                    placeholder="Alamat lengkap tempat tinggal"
                                    oninput="markDirty()">{{ Auth::user()->address ?? '' }}</textarea>
                            </div>

                            <div class="form-field col-span-2">
                                <label class="form-label">Bio Singkat</label>
                                <textarea id="pBio" class="form-input" rows="3"
                                    placeholder="Ceritakan sedikit tentang Anda..."
                                    oninput="markDirty()">{{ Auth::user()->bio ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                            <button class="btn-ghost" id="resetBtn" onclick="resetProfile()" disabled>Discard</button>
                            <button class="btn-primary" id="saveProfileBtn" onclick="saveProfile()" disabled>Save Changes</button>
                        </div>
                    </div>
                </div>
            </div><!-- end right column -->
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="toast" class="toast">
    <div class="toast-inner">
        <span id="toastIcon">✅</span>
        <span id="toastMsg">Berhasil!</span>
    </div>
</div>

<script src="{{ asset('js/Admin_HR/HRprofile.js') }}"></script>
</body>
</html>
