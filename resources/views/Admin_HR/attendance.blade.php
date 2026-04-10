<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!-- TOPBAR -->
    @include('Admin_HR.topbarHR', [
        'pageTitle'    => 'Attendance',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
    ])

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
                    <input type="date" id="filterDate" class="filter-select" value="{{ date('Y-m-d') }}" onchange="loadAttendanceData()" style="padding-left:12px">
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

<!-- QR Code Scanner Script -->
<script>
    let qrReader;
    let isProcessing = false;
    
    // Initialize QR Scanner
    function initQRScanner() {
        if (!window.Html5Qrcode) {
            console.error('Html5Qrcode library not loaded');
            return;
        }
        
        qrReader = new Html5Qrcode("qr-reader");
        
        
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                const cameraId = cameras[0].id;
                
                qrReader.start(
                    cameraId,
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    onScanSuccess,
                    onScanFailure
                );
                
                console.log('QR Scanner started with camera: ' + cameraId);
            } else {
                setQRStatus('❌ Kamera tidak ditemukan', 'error');
            }
        }).catch(err => {
            setQRStatus('❌ Gagal akses kamera: ' + err, 'error');
        });
    }
    
    /**
     * Handle successful QR code scan
     */
    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return; // Prevent multiple simultaneous scans
        
        isProcessing = true;
        setQRStatus('⏳ Memproses...', 'processing');
        
        // Send to server
        fetch('{{ route("attendance.process-qr") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ qr_data: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const icon = data.type === 'check_in' ? '✅' : '👋';
                const message = data.type === 'check_in' 
                    ? `Check In: ${data.employee} (${data.time})` 
                    : `Check Out: ${data.employee} (${data.duration})`;
                
                setQRStatus(`${icon} ${message}`, 'success');
                showToast(icon, message, 3000);
                
                // Reload attendance data
                setTimeout(() => {
                    loadAttendanceData();
                    updateStats();
                }, 1000);
                
                // Pause scanner briefly to prevent duplicate scans
                qrReader.pause();
                setTimeout(() => {
                    isProcessing = false;
                    qrReader.resume();
                    setQRStatus('🟢 Siap scan', 'ready');
                }, 2000);
            } else {
                setQRStatus(`❌ ${data.message}`, 'error');
                showToast('❌', data.message, 3000);
                isProcessing = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            setQRStatus('❌ Error koneksi', 'error');
            showToast('❌', 'Gagal menghubungi server', 3000);
            isProcessing = false;
        });
    }
    
    /**
     * Handle QR code scan failure (silent - too many errors)
     */
    function onScanFailure(error) {
        // Silently ignore errors to avoid spam
    }
    
    /**
     * Set QR scan status message
     */
    function setQRStatus(message, status) {
        const resultEl = document.getElementById('qr-result');
        if (!resultEl) return;
        
        resultEl.innerText = message;
        
        // Color based on status
        if (status === 'success') {
            resultEl.style.color = '#10b981';
        } else if (status === 'error') {
            resultEl.style.color = '#ef4444';
        } else if (status === 'processing') {
            resultEl.style.color = '#f59e0b';
        } else if (status === 'ready') {
            resultEl.style.color = '#6366f1';
        }
    }
    
    /**
     * Stop QR scanner
     */
    function stopQRScanner() {
        if (qrReader) {
            qrReader.stop().then(() => {
                console.log('QR Scanner stopped');
            }).catch(err => {
                console.error('Error stopping scanner:', err);
            });
        }
    }
    
    // Initialize QR Scanner on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initQRScanner === 'function') {
            initQRScanner();
            setQRStatus('🟢 Siap scan', 'ready');
        }
    });
    
    // Stop scanner on page unload
    window.addEventListener('beforeunload', stopQRScanner);
</script>

</html>