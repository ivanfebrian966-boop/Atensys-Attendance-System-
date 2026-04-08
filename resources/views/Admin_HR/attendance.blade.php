<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/attendance.css') }}">
    <!-- QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>

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
                    <h1 class="page-title">Attendance</h1>
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

    <div class="p-4 md:p-6">

        <!-- QR CODE SCANNER -->
        <div class="panel fade-up d1 mb-6">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Scan QR Code Kehadiran</h3>
                    <p class="panel-subtitle">Arahkan kamera ke QR code untuk absen</p>
                </div>
            </div>
            <div class="modal-body pt-4 pb-4">
                <div id="qr-reader" style="width:320px;max-width:100%;margin:auto;"></div>
                <div id="qr-result" class="mt-4 text-center text-emerald-600 font-semibold"></div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="stat-card indigo fade-up d1">
                <div class="stat-icon" style="background:#eef2ff">👥</div>
                <p class="stat-value text-slate-900" id="sTotal">0</p>
                <p class="stat-label">Total</p>
            </div>
            <div class="stat-card green fade-up d2">
                <div class="stat-icon" style="background:#ecfdf5">✅</div>
                <p class="stat-value text-emerald-600" id="sPresent">0</p>
                <p class="stat-label">Hadir</p>
            </div>
            <div class="stat-card red fade-up d3">
                <div class="stat-icon" style="background:#fef2f2">❌</div>
                <p class="stat-value text-red-500" id="sAbsent">0</p>
                <p class="stat-label">Absen</p>
            </div>
            <div class="stat-card amber fade-up d4">
                <div class="stat-icon" style="background:#fffbeb">⏰</div>
                <p class="stat-value text-amber-500" id="sLate">0</p>
                <p class="stat-label">Terlambat</p>
            </div>
            <div class="stat-card blue fade-up d5">
                <div class="stat-icon" style="background:#eff6ff">🏥</div>
                <p class="stat-value text-blue-500" id="sSick">0</p>
                <p class="stat-label">Sakit</p>
            </div>
            <div class="stat-card purple fade-up d6">
                <div class="stat-icon" style="background:#faf5ff">📋</div>
                <p class="stat-value text-purple-500" id="sPerm">0</p>
                <p class="stat-label">Izin</p>
            </div>
        </div>

        <!-- ATTENDANCE TABLE -->
        <div class="panel fade-up d2">
            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Data Absensi</h3>
                    <p class="panel-subtitle">Rekap kehadiran karyawan harian</p>
                </div>
                <div class="header-actions">
                    <div class="search-wrap">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchAtt" class="search-input" placeholder="Cari nama..." oninput="filterAtt()">
                    </div>
                    <input type="date" id="filterDate" class="filter-select" onchange="filterAtt()" style="padding-left:12px">
                    <select id="filterAttStatus" class="filter-select" onchange="filterAtt()">
                        <option value="">Semua Status</option>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                    <select id="filterAttDiv" class="filter-select" onchange="filterAtt()">
                        <option value="">Semua Divisi</option>
                        <option>Engineering</option><option>HR</option><option>Finance</option>
                        <option>Marketing</option><option>IT</option><option>Operasional</option>
                    </select>
                    <button class="btn-secondary" onclick="exportAtt()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                    <button class="btn-primary" onclick="openModal('modalAddAtt')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th class="hidden sm:table-cell">Tanggal</th>
                            <th class="hidden md:table-cell">Divisi</th>
                            <th>Status</th>
                            <th class="hidden md:table-cell">Check In</th>
                            <th class="hidden md:table-cell">Check Out</th>
                            <th class="hidden lg:table-cell">Durasi</th>
                            <th class="hidden lg:table-cell">Keterangan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="attBody"></tbody>
                </table>
            </div>

            <div id="attEmpty" class="empty-state hidden">
                <div class="empty-icon">📋</div>
                <p class="empty-title">Tidak ada data absensi</p>
                <p class="empty-sub">Ubah filter tanggal atau tambah data manual</p>
            </div>

            <div class="table-footer">
                <p class="table-info" id="attInfo">— data</p>
                <div class="pagination" id="attPagination"></div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL ADD ATT -->
<div class="modal-overlay" id="modalAddAtt" onclick="closeModalOutside(event,'modalAddAtt')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div><h3 class="modal-title">Tambah Data Absensi</h3><p class="modal-sub">Input manual kehadiran karyawan</p></div>
            <button class="modal-close" onclick="closeModal('modalAddAtt')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-field col-2">
                    <label class="form-label">Nama Karyawan *</label>
                    <input type="text" id="aaName" class="form-input" placeholder="Nama karyawan">
                    <span class="form-error" id="eaaName"></span>
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal *</label>
                    <input type="date" id="aaDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status *</label>
                    <select id="aaStatus" class="form-select">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Check In</label>
                    <input type="time" id="aaCheckIn" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Check Out</label>
                    <input type="time" id="aaCheckOut" class="form-input">
                </div>
                <div class="form-field col-2">
                    <label class="form-label">Keterangan</label>
                    <textarea id="aaNote" class="form-input" rows="2" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalAddAtt')">Batal</button>
            <button class="btn-primary" onclick="saveAtt()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- MODAL EDIT ATT -->
<div class="modal-overlay" id="modalEditAtt" onclick="closeModalOutside(event,'modalEditAtt')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <div><h3 class="modal-title">Koreksi Absensi</h3><p class="modal-sub">Edit data kehadiran</p></div>
            <button class="modal-close" onclick="closeModal('modalEditAtt')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="eaId">
            <div class="form-grid">
                <div class="form-field col-2">
                    <label class="form-label">Nama Karyawan</label>
                    <input type="text" id="eaName" class="form-input" readonly style="background:#f8fafc;color:#64748b;cursor:not-allowed">
                </div>
                <div class="form-field">
                    <label class="form-label">Tanggal</label>
                    <input type="date" id="eaDate" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Status *</label>
                    <select id="eaStatus" class="form-select">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Late">Late</option>
                        <option value="Sick">Sick</option>
                        <option value="Permission">Permission</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Check In</label>
                    <input type="time" id="eaCheckIn" class="form-input">
                </div>
                <div class="form-field">
                    <label class="form-label">Check Out</label>
                    <input type="time" id="eaCheckOut" class="form-input">
                </div>
                <div class="form-field col-2">
                    <label class="form-label">Keterangan</label>
                    <textarea id="eaNote" class="form-input" rows="2"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-ghost" onclick="closeModal('modalEditAtt')">Batal</button>
            <button class="btn-primary" onclick="updateAtt()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Koreksi
            </button>
        </div>
    </div>
</div>

<!-- MODAL DELETE -->
<div class="modal-overlay" id="modalDelAtt" onclick="closeModalOutside(event,'modalDelAtt')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <div class="del-icon-wrap"><div class="del-icon">🗑</div></div>
        <h3 class="del-title">Hapus Data Absensi?</h3>
        <p class="del-sub" id="delAttMsg">Data akan dihapus permanen.</p>
        <div class="modal-footer" style="justify-content:center">
            <button class="btn-ghost" onclick="closeModal('modalDelAtt')">Batal</button>
            <button class="btn-danger" onclick="execDelAtt()">Ya, Hapus</button>
        </div>
    </div>
</div>

<div id="toast" class="toast"><div class="toast-inner"><span id="tIcon">✅</span><span id="tMsg">Berhasil!</span></div></div>

<script src="{{ asset('js/Admin_HR/shared.js') }}"></script>
<script src="{{ asset('js/Admin_HR/attendance.js') }}"></script>
</body>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('qr-result').innerText = 'QR Terdeteksi: ' + decodedText;
        // TODO: Kirim data ke server untuk proses absen
    }
    function onScanFailure(error) {
        // Optionally handle scan errors
    }
    if (window.Html5Qrcode) {
        const qrReader = new Html5Qrcode("qr-reader");
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                qrReader.start(
                    cameras[0].id,
                    {
                        fps: 10,
                        qrbox: 250
                    },
                    onScanSuccess,
                    onScanFailure
                );
            } else {
                document.getElementById('qr-result').innerText = 'Kamera tidak ditemukan.';
            }
        }).catch(err => {
            document.getElementById('qr-result').innerText = 'Gagal mengakses kamera: ' + err;
        });
    }
</script>
</html>
