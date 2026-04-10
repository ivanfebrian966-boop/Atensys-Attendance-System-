<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/HRmanage.css') }}">
</head>
<body>

@include('Admin_HR.sidebar')

<!-- ===== MAIN ===== -->
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
                    <h1 class="page-title">Kelola Data</h1>
                    <p class="text-xs text-slate-400" id="currentDate">—</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="topbar-icon-btn relative">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="notif-dot"></span>
                </button>
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

        <!-- TAB NAV -->
        <div class="tab-nav fade-up d1">
            <button class="tab-btn active" onclick="switchTab('employees', this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Data Karyawan
                <span class="tab-count" id="countEmployees">0</span>
            </button>
            <button class="tab-btn" onclick="switchTab('divisions', this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3M9 7h6M9 11h6M9 15h2"/>
                </svg>
                Divisi
                <span class="tab-count" id="countDivisions">0</span>
            </button>
            <button class="tab-btn" onclick="switchTab('attendance', this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Data Absensi
                <span class="tab-count" id="countAttendance">0</span>
            </button>
        </div>

        <!-- ============ TAB: EMPLOYEES ============ -->
        <div id="tab-employees" class="tab-content fade-up d2">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Data Karyawan</h3>
                        <p class="panel-subtitle">Kelola informasi seluruh karyawan</p>
                    </div>
                    <div class="header-actions">
                        <div class="search-wrap">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchEmployee" placeholder="Cari karyawan..."
                                class="search-input" oninput="filterEmployees()">
                        </div>
                        <select class="filter-select" id="filterDivision" onchange="filterEmployees()">
                            <option value="">Semua Divisi</option>
                            <option>Engineering</option>
                            <option>HR</option>
                            <option>Finance</option>
                            <option>Marketing</option>
                            <option>IT</option>
                            <option>Operasional</option>
                        </select>
                        <select class="filter-select" id="filterStatus" onchange="filterEmployees()">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        <button class="btn-primary" onclick="openModal('modalAddEmployee')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table" id="employeeTable">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th class="hidden sm:table-cell">NIP</th>
                                <th>Divisi</th>
                                <th class="hidden md:table-cell">Jabatan</th>
                                <th>Status</th>
                                <th class="hidden lg:table-cell">Bergabung</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="employeeBody">
                            <!-- Diisi JS -->
                        </tbody>
                    </table>
                </div>

                <div id="emptyEmployee" class="empty-state hidden">
                    <div class="text-4xl mb-3">🔍</div>
                    <p class="empty-title">Tidak ada data ditemukan</p>
                    <p class="empty-sub">Coba ubah filter atau tambah karyawan baru</p>
                </div>

                <div class="table-footer">
                    <p class="table-info" id="infoEmployee">— data</p>
                    <div class="pagination" id="paginationEmployee"></div>
                </div>
            </div>
        </div>

        <!-- ============ TAB: DIVISIONS ============ -->
        <div id="tab-divisions" class="tab-content hidden fade-up d2">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Manajemen Divisi</h3>
                        <p class="panel-subtitle">Kelola divisi dan departemen perusahaan</p>
                    </div>
                    <div class="header-actions">
                        <div class="search-wrap">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchDivision" placeholder="Cari divisi..."
                                class="search-input" oninput="filterDivisions()">
                        </div>
                        <button class="btn-primary" onclick="openModal('modalAddDivision')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Divisi
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table" id="divisionTable">
                        <thead>
                            <tr>
                                <th>Nama Divisi</th>
                                <th class="hidden sm:table-cell">Kode</th>
                                <th class="hidden md:table-cell">Kepala Divisi</th>
                                <th>Jumlah Karyawan</th>
                                <th class="hidden lg:table-cell">Deskripsi</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="divisionBody">
                            <!-- Diisi JS -->
                        </tbody>
                    </table>
                </div>

                <div id="emptyDivision" class="empty-state hidden">
                    <div class="text-4xl mb-3">🏢</div>
                    <p class="empty-title">Belum ada divisi</p>
                    <p class="empty-sub">Tambah divisi pertama untuk memulai</p>
                </div>

                <div class="table-footer">
                    <p class="table-info" id="infoDivision">— data</p>
                </div>
            </div>
        </div>

        <!-- ============ TAB: ATTENDANCE ============ -->
        <div id="tab-attendance" class="tab-content hidden fade-up d2">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Data Absensi</h3>
                        <p class="panel-subtitle">Kelola dan koreksi data absensi karyawan</p>
                    </div>
                    <div class="header-actions">
                        <div class="search-wrap">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="searchAttendance" placeholder="Cari nama..."
                                class="search-input" oninput="filterAttendance()">
                        </div>
                        <input type="date" id="filterDate" class="filter-select" onchange="filterAttendance()"
                            style="padding-left:12px">
                        <select class="filter-select" id="filterAttStatus" onchange="filterAttendance()">
                            <option value="">Semua Status</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                            <option value="Sick">Sick</option>
                            <option value="Permission">Permission</option>
                        </select>
                        <button class="btn-secondary" onclick="exportAttendance()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th class="hidden sm:table-cell">Tanggal</th>
                                <th>Status</th>
                                <th class="hidden md:table-cell">Check In</th>
                                <th class="hidden md:table-cell">Check Out</th>
                                <th class="hidden lg:table-cell">Keterangan</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceBody">
                            <!-- Diisi JS -->
                        </tbody>
                    </table>
                </div>

                <div id="emptyAttendance" class="empty-state hidden">
                    <div class="text-4xl mb-3">📋</div>
                    <p class="empty-title">Tidak ada data absensi</p>
                    <p class="empty-sub">Coba ubah filter tanggal atau status</p>
                </div>

                <div class="table-footer">
                    <p class="table-info" id="infoAttendance">— data</p>
                    <div class="pagination" id="paginationAttendance"></div>
                </div>
            </div>
        </div>

    </div><!-- end content -->
</div><!-- end main -->

<!-- ========================================================
     MODAL: TAMBAH KARYAWAN
     ======================================================== -->
<div class="modal-overlay" id="modalAddEmployee" onclick="closeModalOutside(event,'modalAddEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Tambah Karyawan</h3>
                <p class="modal-sub">Isi data karyawan baru</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalAddEmployee')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" id="addName" class="form-input" placeholder="Nama lengkap karyawan">
                    <span class="form-error" id="errAddName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" id="addNip" class="form-input" placeholder="EMP-XXX (otomatis jika kosong)">
                </div>
                <div class="form-field">
                    <label class="form-label">Email <span class="text-red-400">*</span></label>
                    <input type="email" id="addEmail" class="form-input" placeholder="email@attensys.id">
                    <span class="form-error" id="errAddEmail"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Divisi <span class="text-red-400">*</span></label>
                    <select id="addDivision" class="form-select">
                        <option value="">Pilih Divisi</option>
                        <option>Engineering</option>
                        <option>HR</option>
                        <option>Finance</option>
                        <option>Marketing</option>
                        <option>IT</option>
                        <option>Operasional</option>
                    </select>
                    <span class="form-error" id="errAddDivision"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Jabatan</label>
                    <input type="text" id="addPosition" class="form-input" placeholder="Jabatan / posisi">
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" id="addJoinDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select id="addStatus" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalAddEmployee')">Batal</button>
            <button class="btn-primary" onclick="saveEmployee()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL: EDIT KARYAWAN
     ======================================================== -->
<div class="modal-overlay" id="modalEditEmployee" onclick="closeModalOutside(event,'modalEditEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Edit Karyawan</h3>
                <p class="modal-sub">Perbarui informasi karyawan</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEditEmployee')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editId">
            <div class="form-grid">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" id="editName" class="form-input" placeholder="Nama lengkap karyawan">
                    <span class="form-error" id="errEditName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" id="editNip" class="form-input" readonly
                        style="background:#f8fafc;cursor:not-allowed;color:#94a3b8">
                </div>
                <div class="form-field">
                    <label class="form-label">Email <span class="text-red-400">*</span></label>
                    <input type="email" id="editEmail" class="form-input" placeholder="email@attensys.id">
                    <span class="form-error" id="errEditEmail"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Divisi <span class="text-red-400">*</span></label>
                    <select id="editDivision" class="form-select">
                        <option value="">Pilih Divisi</option>
                        <option>Engineering</option>
                        <option>HR</option>
                        <option>Finance</option>
                        <option>Marketing</option>
                        <option>IT</option>
                        <option>Operasional</option>
                    </select>
                    <span class="form-error" id="errEditDivision"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Jabatan</label>
                    <input type="text" id="editPosition" class="form-input" placeholder="Jabatan / posisi">
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" id="editJoinDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select id="editStatus" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalEditEmployee')">Batal</button>
            <button class="btn-primary" onclick="updateEmployee()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL: TAMBAH DIVISI
     ======================================================== -->
<div class="modal-overlay" id="modalAddDivision" onclick="closeModalOutside(event,'modalAddDivision')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Tambah Divisi</h3>
                <p class="modal-sub">Buat divisi atau departemen baru</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalAddDivision')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Divisi <span class="text-red-400">*</span></label>
                    <input type="text" id="addDivName" class="form-input" placeholder="Nama divisi">
                    <span class="form-error" id="errAddDivName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Kode Divisi</label>
                    <input type="text" id="addDivCode" class="form-input" placeholder="DIV-XXX">
                </div>
                <div class="form-field">
                    <label class="form-label">Kepala Divisi</label>
                    <input type="text" id="addDivHead" class="form-input" placeholder="Nama kepala divisi">
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea id="addDivDesc" class="form-input" rows="3"
                        placeholder="Deskripsi singkat divisi..." style="resize:vertical"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalAddDivision')">Batal</button>
            <button class="btn-primary" onclick="saveDivision()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL: EDIT DIVISI
     ======================================================== -->
<div class="modal-overlay" id="modalEditDivision" onclick="closeModalOutside(event,'modalEditDivision')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Edit Divisi</h3>
                <p class="modal-sub">Perbarui informasi divisi</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEditDivision')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editDivId">
            <div class="form-grid">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Divisi <span class="text-red-400">*</span></label>
                    <input type="text" id="editDivName" class="form-input" placeholder="Nama divisi">
                    <span class="form-error" id="errEditDivName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Kode Divisi</label>
                    <input type="text" id="editDivCode" class="form-input" placeholder="DIV-XXX">
                </div>
                <div class="form-field">
                    <label class="form-label">Kepala Divisi</label>
                    <input type="text" id="editDivHead" class="form-input" placeholder="Nama kepala divisi">
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea id="editDivDesc" class="form-input" rows="3"
                        style="resize:vertical"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalEditDivision')">Batal</button>
            <button class="btn-primary" onclick="updateDivision()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL: EDIT ABSENSI
     ======================================================== -->
<div class="modal-overlay" id="modalEditAttendance" onclick="closeModalOutside(event,'modalEditAttendance')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Koreksi Absensi</h3>
                <p class="modal-sub">Edit data kehadiran karyawan</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEditAttendance')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="editAttId">
            <div class="form-grid">
                <div class="form-field col-span-2">
                    <label class="form-label">Nama Karyawan</label>
                    <input type="text" id="editAttName" class="form-input" readonly
                        style="background:#f8fafc;cursor:not-allowed;color:#94a3b8">
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal</label>
                    <input type="date" id="editAttDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status <span class="text-red-400">*</span></label>
                    <select id="editAttStatus" class="form-select">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Check In</label>
                    <input type="time" id="editAttCheckIn" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Check Out</label>
                    <input type="time" id="editAttCheckOut" class="form-input">
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Keterangan</label>
                    <textarea id="editAttNote" class="form-input" rows="2"
                        placeholder="Keterangan tambahan..." style="resize:vertical"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalEditAttendance')">Batal</button>
            <button class="btn-primary" onclick="updateAttendance()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Koreksi
            </button>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL: KONFIRMASI HAPUS
     ======================================================== -->
<div class="modal-overlay" id="modalDelete" onclick="closeModalOutside(event,'modalDelete')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="delete-icon-wrap">
            <div class="delete-icon">🗑</div>
        </div>
        <h3 class="delete-title">Hapus Data?</h3>
        <p class="delete-sub" id="deleteMsg">Data ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
        <div class="modal-footer justify-center">
            <button class="btn-ghost" onclick="closeModal('modalDelete')">Batal</button>
            <button class="btn-danger" id="deleteConfirmBtn" onclick="executeDelete()">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<!-- ===== TOAST ===== -->
<div id="toast" class="toast">
    <div class="toast-inner">
        <span id="toastIcon">✅</span>
        <span id="toastMsg">Berhasil!</span>
    </div>
</div>

<script src="{{ asset('js/Admin_HR/HRmanage.js') }}"></script>
</body>
</html>
