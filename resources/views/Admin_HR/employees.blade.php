<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/employees.css') }}">
</head>
<body>

@include('Admin_HR.sidebar')

<div class="main-content">
    <!-- TOPBAR -->
    @include('Admin_HR.topbarHR', [
        'pageTitle'    => 'Employees',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
    ])

    <div class="p-4 md:p-6">

        <!-- STAT CARDS -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 md:gap-4 mb-6">
            <div class="stat-card indigo fade-up d1">
                <div class="stat-icon" style="background:#eef2ff">👥</div>
                <p class="stat-value text-slate-900" id="statTotal">0</p>
                <p class="stat-label">Total Karyawan</p>
                <p class="stat-trend text-emerald-600">↑ +3 bulan ini</p>
            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon" style="background:#ecfdf5">✅</div>
                <p class="stat-value text-emerald-600" id="statAktif">0</p>
                <p class="stat-label">Aktif</p>
            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon" style="background:#fef2f2">🚫</div>
                <p class="stat-value text-red-500" id="statNonaktif">0</p>
                <p class="stat-label">Nonaktif</p>
            </div>
            <div class="stat-card cyan fade-up d4">
                <div class="stat-icon" style="background:#ecfeff">🏢</div>
                <p class="stat-value text-cyan-500" id="statDivisi">0</p>
                <p class="stat-label">Divisi</p>
            </div>
        </div>

        <!-- EMPLOYEE TABLE -->
        <div class="panel fade-up d2">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Daftar Karyawan</h3>
                    <p class="panel-subtitle" id="subtitleEmp">Semua karyawan terdaftar</p>
                </div>
                <div class="header-actions">
                    <div class="search-wrap">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchEmp" class="search-input" placeholder="Cari karyawan..." oninput="filterEmp()">
                    </div>
                    <select id="filterDiv" class="filter-select" onchange="filterEmp()">
                        <option value="">Semua Divisi</option>
                        <option>Engineering</option><option>HR</option><option>Finance</option>
                        <option>Marketing</option><option>IT</option><option>Operasional</option>
                    </select>
                    <select id="filterStat" class="filter-select" onchange="filterEmp()">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                    <button class="btn-secondary" onclick="exportCSV()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                    <button class="btn-primary" onclick="openModal('modalAdd')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>

            <!-- VIEW TOGGLE -->
            <div class="px-5 py-3 border-b border-slate-50 flex items-center gap-3">
                <button id="btnTable" class="view-btn active" onclick="setView('table')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18"/>
                    </svg>
                    Tabel
                </button>
                <button id="btnGrid" class="view-btn" onclick="setView('grid')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Kartu
                </button>
            </div>

            <!-- TABLE VIEW -->
            <div id="viewTable" class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th class="hidden sm:table-cell">NIP</th>
                            <th>Divisi</th>
                            <th class="hidden md:table-cell">Jabatan</th>
                            <th>Status</th>
                            <th class="hidden lg:table-cell">Bergabung</th>
                            <th class="hidden lg:table-cell">No. HP</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="empTableBody"></tbody>
                </table>
            </div>

            <!-- GRID VIEW -->
            <div id="viewGrid" class="hidden p-4">
                <div id="empGridBody" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"></div>
            </div>

            <!-- EMPTY -->
            <div id="empEmpty" class="empty-state hidden">
                <div class="empty-icon">🔍</div>
                <p class="empty-title">Tidak ada karyawan ditemukan</p>
                <p class="empty-sub">Coba ubah filter atau tambah karyawan baru</p>
            </div>

            <div class="table-footer">
                <p class="table-info" id="empInfo">— data</p>
                <div class="pagination" id="empPagination"></div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL ADD -->
<div class="modal-overlay" id="modalAdd" onclick="closeModalOutside(event,'modalAdd')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div><h3 class="modal-title">Tambah Karyawan</h3><p class="modal-sub">Isi data karyawan baru</p></div>
            <button class="modal-close" onclick="closeModal('modalAdd')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-field col-2">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" id="aName" class="form-input" placeholder="Nama lengkap">
                    <span class="form-error" id="eaName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Email *</label>
                    <input type="email" id="aEmail" class="form-input" placeholder="email@attensys.id">
                    <span class="form-error" id="eaEmail"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">No. HP</label>
                    <input type="text" id="aPhone" class="form-input" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-field">
                    <label class="form-label">Divisi *</label>
                    <select id="aDivision" class="form-select">
                        <option value="">Pilih Divisi</option>
                        <option>Engineering</option><option>HR</option><option>Finance</option>
                        <option>Marketing</option><option>IT</option><option>Operasional</option>
                    </select>
                    <span class="form-error" id="eaDivision"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Jabatan</label>
                    <input type="text" id="aPosition" class="form-input" placeholder="Jabatan">
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" id="aJoinDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select id="aStatus" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="form-field col-2">
                    <label class="form-label">Alamat</label>
                    <textarea id="aAddress" class="form-input" rows="2" placeholder="Alamat lengkap"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalAdd')">Batal</button>
            <button class="btn-primary" onclick="saveEmp()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal-overlay" id="modalEdit" onclick="closeModalOutside(event,'modalEdit')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div><h3 class="modal-title">Edit Karyawan</h3><p class="modal-sub">Perbarui data karyawan</p></div>
            <button class="modal-close" onclick="closeModal('modalEdit')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="eId">
            <div class="form-grid">
                <div class="form-field col-2">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" id="eName" class="form-input">
                    <span class="form-error" id="eeName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" id="eNip" class="form-input" readonly style="background:#f8fafc;color:#94a3b8;cursor:not-allowed">
                </div>
                <div class="form-field">
                    <label class="form-label">Email *</label>
                    <input type="email" id="eEmail" class="form-input">
                    <span class="form-error" id="eeEmail"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">No. HP</label>
                    <input type="text" id="ePhone" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Divisi *</label>
                    <select id="eDivision" class="form-select">
                        <option value="">Pilih Divisi</option>
                        <option>Engineering</option><option>HR</option><option>Finance</option>
                        <option>Marketing</option><option>IT</option><option>Operasional</option>
                    </select>
                    <span class="form-error" id="eeDivision"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Jabatan</label>
                    <input type="text" id="ePosition" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal Bergabung</label>
                    <input type="date" id="eJoinDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select id="eStatus" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="form-field col-2">
                    <label class="form-label">Alamat</label>
                    <textarea id="eAddress" class="form-input" rows="2"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalEdit')">Batal</button>
            <button class="btn-primary" onclick="updateEmp()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal-overlay" id="modalDetail" onclick="closeModalOutside(event,'modalDetail')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div><h3 class="modal-title">Detail Karyawan</h3><p class="modal-sub">Informasi lengkap</p></div>
            <button class="modal-close" onclick="closeModal('modalDetail')">✕</button>
        </div>
        <div class="modal-body" id="detailContent"></div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalDetail')">Tutup</button>
            <button class="btn-primary" id="detailEditBtn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </button>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal-overlay" id="modalDel" onclick="closeModalOutside(event,'modalDel')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="del-icon-wrap"><div class="del-icon">🗑</div></div>
        <h3 class="del-title">Hapus Karyawan?</h3>
        <p class="del-sub" id="delMsg">Data akan dihapus permanen.</p>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn-ghost" onclick="closeModal('modalDel')">Batal</button>
            <button class="btn-danger" onclick="execDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

<!-- TOAST -->
<div id="toast" class="toast"><div class="toast-inner"><span id="tIcon">✅</span><span id="tMsg">Berhasil!</span></div></div>

<script src="{{ asset('js/Admin_HR/shared.js') }}"></script>
<script src="{{ asset('js/Admin_HR/employees.js') }}"></script>
</body>
</html>
