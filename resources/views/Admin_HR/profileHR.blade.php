<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/profileHR.css') }}">
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
                    <h1 class="page-title">My Profile</h1>
                    <p class="text-xs text-slate-400" id="currentDate">—</p>
                </div>
            </div>
            <div class="topbar-profile">
                <div class="topbar-avatar" id="topbarAvatarText">HR</div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-800 sora" id="topbarName">Admin HR</p>
                    <p class="text-xs text-slate-400">HR Manager</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="p-4 md:p-6">
        <div class="profile-layout">

            <!-- ============ LEFT COLUMN ============ -->
            <div class="profile-left fade-up d1">

                <!-- Profile Card -->
                <div class="profile-card">
                    <!-- Cover -->
                    <div class="profile-cover">
                        <div class="cover-gradient"></div>
                        <!-- Floating blobs -->
                        <div class="cover-blob-1"></div>
                        <div class="cover-blob-2"></div>
                        <!-- Avatar -->
                        <div class="avatar-container">
                            <div class="profile-avatar" id="profileAvatar">
                                <span class="avatar-initials" id="avatarInitials">HR</span>
                                <img id="avatarImg" class="avatar-photo hidden" alt="Profile photo">
                            </div>
                            <label class="avatar-upload-btn" for="avatarFileInput" title="Change photo">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </label>
                            <input type="file" id="avatarFileInput" class="hidden" accept="image/*" onchange="handleAvatarChange(event)">
                        </div>
                    </div>

                    <!-- Identity -->
                    <div class="profile-identity">
                        <div class="identity-name-row">
                            <h2 class="identity-name sora" id="displayName">Admin HR</h2>
                            <span class="identity-badge">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                HR Admin
                            </span>
                        </div>
                        <p class="identity-role" id="displayPosition">HR Manager</p>
                        <p class="identity-email" id="displayEmail">hr@attensys.id</p>
                    </div>

                    <!-- Quick stats -->
                    <div class="profile-stats-row">
                        <div class="pstat">
                            <span class="pstat-val sora">48</span>
                            <span class="pstat-lbl">Employees</span>
                        </div>
                        <div class="pstat-divider"></div>
                        <div class="pstat">
                            <span class="pstat-val sora">7</span>
                            <span class="pstat-lbl">Divisions</span>
                        </div>
                        <div class="pstat-divider"></div>
                        <div class="pstat">
                            <span class="pstat-val sora" id="yearsJoined">2+</span>
                            <span class="pstat-lbl">Years</span>
                        </div>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="info-card fade-up d2">
                    <h4 class="info-card-title sora">Contact Info</h4>
                    <div class="info-row">
                        <div class="info-icon" style="background:#eef2ff;color:#6366f1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Email Address</p>
                            <p class="info-val" id="infoEmail">hr@attensys.id</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon" style="background:#ecfdf5;color:#10b981">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Phone Number</p>
                            <p class="info-val" id="infoPhone">{{ auth()->user()->phone ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon" style="background:#eff6ff;color:#3b82f6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Address</p>
                            <p class="info-val" id="infoAddress">{{ auth()->user()->address ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon" style="background:#faf5ff;color:#8b5cf6">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="info-label">Joined Since</p>
                            <p class="info-val" id="infoJoinDate">{{ auth()->user()->join_date ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="info-card fade-up d3">
                    <h4 class="info-card-title sora">Recent Activity</h4>
                    <div class="activity-timeline" id="activityTimeline">
                        <div class="activity-item">
                            <div class="act-line-wrap">
                                <div class="act-dot" style="background:#10b981"></div>
                                <div class="act-line"></div>
                            </div>
                            <div class="act-content">
                                <p class="act-text">Added new employee — Andi Rahman</p>
                                <p class="act-time">2 hours ago</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="act-line-wrap">
                                <div class="act-dot" style="background:#6366f1"></div>
                                <div class="act-line"></div>
                            </div>
                            <div class="act-content">
                                <p class="act-text">Corrected attendance — Budi Pratama</p>
                                <p class="act-time">5 hours ago</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="act-line-wrap">
                                <div class="act-dot" style="background:#f59e0b"></div>
                                <div class="act-line"></div>
                            </div>
                            <div class="act-content">
                                <p class="act-text">Exported monthly attendance report</p>
                                <p class="act-time">Yesterday, 15:30</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="act-line-wrap">
                                <div class="act-dot" style="background:#06b6d4"></div>
                                <div class="act-line"></div>
                            </div>
                            <div class="act-content">
                                <p class="act-text">Updated Marketing division info</p>
                                <p class="act-time">2 days ago</p>
                            </div>
                        </div>
                        <div class="activity-item last">
                            <div class="act-line-wrap">
                                <div class="act-dot" style="background:#94a3b8"></div>
                            </div>
                            <div class="act-content">
                                <p class="act-text">Account created</p>
                                <p class="act-time">Jan 2024</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- end left -->

            <!-- ============ RIGHT COLUMN ============ -->
            <div class="profile-right">

                <!-- Personal Info Form -->
                <div class="panel fade-up d2">
                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Personal Information</h3>
                            <p class="panel-subtitle">Update your profile details</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="saved-indicator hidden" id="savedIndicator">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-emerald-600 text-xs font-semibold sora">Saved</span>
                            </div>
                            <div class="unsaved-indicator hidden" id="unsavedIndicator">
                                <span class="unsaved-dot"></span>
                                <span class="text-amber-600 text-xs font-semibold sora">Unsaved changes</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 md:p-6">
                        <div class="form-grid-profile">

                            <div class="form-field">
                                <label class="form-label">Full Name <span class="text-red-400">*</span></label>
                                <input type="text" id="fName" class="form-input"
                                    value="{{ Auth::user()->name ?? 'Admin HR' }}"
                                    placeholder="Your full name"
                                    oninput="markDirty()">
                                <span class="form-error" id="efName"></span>
                            </div>

                            <div class="form-field">
                                <label class="form-label">Employee ID</label>
                                <input type="text" id="fEmpId" class="form-input" value="HR-001"
                                    readonly style="background:#f8fafc;color:#94a3b8;cursor:not-allowed">
                            </div>

                            <div class="form-field col-2">
                                <label class="form-label">Email Address <span class="text-red-400">*</span></label>
                                <div class="email-field-wrap">
                                    <input type="email" id="fEmail" class="form-input"
                                        value="{{ Auth::user()->email ?? 'hr@attensys.id' }}"
                                        placeholder="your@email.com"
                                        oninput="markDirty()">
                                    <span class="email-verified-badge">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Verified
                                    </span>
                                </div>
                                <span class="form-error" id="efEmail"></span>
                            </div>

                            <div class="form-field">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" id="fPhone" class="form-input"
                                    value="{{ Auth::user()->phone ?? '' }}"
                                    placeholder="08xxxxxxxxxx"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Department / Division</label>
                                <input type="text" id="fDivision" class="form-input"
                                    value="{{ Auth::user()->division ?? 'Human Resources' }}"
                                    placeholder="Your division"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Position / Job Title</label>
                                <input type="text" id="fPosition" class="form-input"
                                    value="{{ Auth::user()->position ?? 'HR Manager' }}"
                                    placeholder="Your position"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field">
                                <label class="form-label">Date Joined</label>
                                <input type="date" id="fJoinDate" class="form-input"
                                    value="{{ Auth::user()->join_date ?? '2024-01-15' }}"
                                    oninput="markDirty()">
                            </div>

                            <div class="form-field col-2">
                                <label class="form-label">Home Address</label>
                                <textarea id="fAddress" class="form-input" rows="2"
                                    placeholder="Your full address"
                                    oninput="markDirty()">{{ Auth::user()->address ?? '' }}</textarea>
                            </div>

                            <div class="form-field col-2">
                                <label class="form-label">Short Bio</label>
                                <textarea id="fBio" class="form-input" rows="3"
                                    placeholder="Tell your team a little about yourself..."
                                    oninput="markDirty(); countBio()">{{ Auth::user()->bio ?? '' }}</textarea>
                                <div class="bio-count-wrap">
                                    <span class="bio-count" id="bioCount">0 / 160</span>
                                </div>
                            </div>

                        </div>

                        <!-- Form actions -->
                        <div class="form-actions">
                            <button class="btn-ghost" id="btnReset" onclick="resetForm()" disabled>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Discard Changes
                            </button>
                            <button class="btn-primary" id="btnSave" onclick="saveProfile()" disabled>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="panel fade-up d3">
                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Change Password</h3>
                            <p class="panel-subtitle">Keep your account secure with a strong password</p>
                        </div>
                        <div class="security-tag">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Secured
                        </div>
                    </div>

                    <div class="p-5 md:p-6">
                        <div class="form-grid-profile">

                            <div class="form-field col-2">
                                <label class="form-label">Current Password <span class="text-red-400">*</span></label>
                                <div class="pwd-field-wrap">
                                    <input type="password" id="pwdCurrent" class="form-input" placeholder="Your current password">
                                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdCurrent',this)" tabindex="-1">
                                        <svg class="w-4 h-4 eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg class="w-4 h-4 eye-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="form-error" id="epwdCurrent"></span>
                            </div>

                            <div class="form-field">
                                <label class="form-label">New Password <span class="text-red-400">*</span></label>
                                <div class="pwd-field-wrap">
                                    <input type="password" id="pwdNew" class="form-input"
                                        placeholder="Min. 8 characters"
                                        oninput="checkStrength(this.value)">
                                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdNew',this)" tabindex="-1">
                                        <svg class="w-4 h-4 eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg class="w-4 h-4 eye-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Strength meter -->
                                <div class="strength-meter hidden" id="strengthMeter">
                                    <div class="strength-bars">
                                        <div class="str-seg" id="ss1"></div>
                                        <div class="str-seg" id="ss2"></div>
                                        <div class="str-seg" id="ss3"></div>
                                        <div class="str-seg" id="ss4"></div>
                                    </div>
                                    <span class="strength-label" id="strengthLabel">—</span>
                                </div>
                                <!-- Requirements checklist -->
                                <div class="pwd-requirements hidden" id="pwdRequirements">
                                    <div class="req-item" id="reqLen">
                                        <svg class="w-3 h-3 req-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        At least 8 characters
                                    </div>
                                    <div class="req-item" id="reqUpper">
                                        <svg class="w-3 h-3 req-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Uppercase letter (A–Z)
                                    </div>
                                    <div class="req-item" id="reqNum">
                                        <svg class="w-3 h-3 req-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Number (0–9)
                                    </div>
                                    <div class="req-item" id="reqSym">
                                        <svg class="w-3 h-3 req-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Special character (!@#$)
                                    </div>
                                </div>
                                <span class="form-error" id="epwdNew"></span>
                            </div>

                            <div class="form-field">
                                <label class="form-label">Confirm New Password <span class="text-red-400">*</span></label>
                                <div class="pwd-field-wrap">
                                    <input type="password" id="pwdConfirm" class="form-input"
                                        placeholder="Repeat new password"
                                        oninput="checkMatch()">
                                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdConfirm',this)" tabindex="-1">
                                        <svg class="w-4 h-4 eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg class="w-4 h-4 eye-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="match-hint hidden" id="matchHint">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Passwords match
                                </div>
                                <span class="form-error" id="epwdConfirm"></span>
                            </div>

                        </div>

                        <div class="form-actions">
                            <button class="btn-primary" onclick="changePassword()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Account Settings / Danger Zone -->
                <div class="panel fade-up d4">
                    <div class="panel-header">
                        <div>
                            <h3 class="panel-title">Account Settings</h3>
                            <p class="panel-subtitle">Manage your account preferences</p>
                        </div>
                    </div>
                    <div class="p-5 md:p-6 space-y-4">

                        <!-- Notification toggle -->
                        <div class="setting-row">
                            <div class="setting-info">
                                <p class="setting-title sora">Email Notifications</p>
                                <p class="setting-desc">Receive attendance alerts and reminders via email</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="toggleEmail" checked onchange="saveSetting('emailNotif', this.checked)">
                                <span class="toggle-track"></span>
                            </label>
                        </div>

                        <div class="setting-divider"></div>

                        <!-- System notification toggle -->
                        <div class="setting-row">
                            <div class="setting-info">
                                <p class="setting-title sora">In-App Notifications</p>
                                <p class="setting-desc">Show notifications inside the dashboard</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="toggleSystem" checked onchange="saveSetting('systemNotif', this.checked)">
                                <span class="toggle-track"></span>
                            </label>
                        </div>

                        <div class="setting-divider"></div>

                        <!-- Language -->
                        <div class="setting-row">
                            <div class="setting-info">
                                <p class="setting-title sora">Language</p>
                                <p class="setting-desc">Choose your preferred interface language</p>
                            </div>
                            <select class="filter-select text-sm" style="min-width:120px" onchange="saveSetting('lang', this.value)">
                                <option value="en" selected>English</option>
                                <option value="id">Bahasa Indonesia</option>
                            </select>
                        </div>

                        <div class="setting-divider"></div>

                        <!-- Logout all devices -->
                        <div class="setting-row">
                            <div class="setting-info">
                                <p class="setting-title sora text-amber-600">Sign Out All Devices</p>
                                <p class="setting-desc">Log out from all active sessions on other devices</p>
                            </div>
                            <button class="btn-ghost text-amber-600 border-amber-200 hover:bg-amber-50 text-sm" onclick="confirmSignOutAll()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Sign Out
                            </button>
                        </div>

                    </div>
                </div>

            </div><!-- end right -->

        </div><!-- end profile-layout -->
    </div><!-- end content -->
</div><!-- end main-content -->

<!-- Toast -->
<div id="toast" class="toast"><div class="toast-inner"><span id="tIcon">✅</span><span id="tMsg">—</span></div></div>

<script src="{{ asset('js/Admin_HR/shared.js') }}"></script>
<script src="{{ asset('js/Admin_HR/profileHR.js') }}"></script>
</body>
</html>
