<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola — ATTENSYS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/shared.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin_HR/HRmanage.css') }}">
    <style>
        .form-error { color: #ef4444; font-size: 0.75rem; margin-top: 2px; display: block; }
    </style>
</head>
<body>

@include('Admin_HR.sidebar')

<!-- ===== MAIN ===== -->
<div class="main-content">

    <!-- TOPBAR -->
    @include('Admin_HR.topbarHR', [
        'pageTitle'    => 'Kelola Data',
        'pageSubtitle' => now()->translatedFormat('l, d F Y'),
    ])

    <!-- CONTENT -->
    <div class="p-4 md:p-6">

        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl flex items-center gap-3 fade-up">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- TAB NAV -->
        <div class="tab-nav fade-up d1">
            <button class="tab-btn active" onclick="switchTab('employees', this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.9-3.53M9 20H4v-2a4 4 0 015.9-3.53M15 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Data Karyawan
                <span class="tab-count" id="countEmployees">{{ count($employees) }}</span>
            </button>
            <button class="tab-btn" onclick="switchTab('divisions', this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2M5 21H3M9 7h6M9 11h6M9 15h2"/>
                </svg>
                Divisi
                <span class="tab-count" id="countDivisions">{{ count($divisions) }}</span>
            </button>
            <button class="tab-btn" onclick="switchTab('attendance', this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Data Absensi
                <span class="tab-count" id="countAttendance">{{ count($attendances) }}</span>
            </button>
        </div>

        <!-- ============ TAB: EMPLOYEES ============ -->
        <div id="tab-employees" class="tab-content fade-up d2">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Data Karyawan</h3>
                        <p class="panel-subtitle">Kelola informasi seluruh {{ count($employees) }} karyawan</p>
                    </div>
                    <div class="header-actions">
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
                                <th>Divisi</th>
                                <th class="hidden md:table-cell">Jabatan</th>
                                <th>Status Akun</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="employeeBody">
                            @forelse($employees as $emp)
                            <tr class="table-row">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar-sm" style="background:var(--gradient)">
                                            @if($emp->avatar)
                                                <img src="{{ asset('storage/'.$emp->avatar) }}" class="w-full h-full object-cover rounded-xl">
                                            @else
                                                {{ strtoupper(substr($emp->name, 0, 2)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800 text-sm sora">{{ $emp->name }}</p>
                                            <p class="text-slate-400" style="font-size:0.72rem">{{ $emp->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-slate-600 text-sm">
                                        {{ $emp->employee->division->division_name ?? '—' }}
                                    </span>
                                </td>
                                <td class="hidden md:table-cell">
                                    <span class="text-slate-600 text-sm">{{ $emp->position ?? '—' }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-aktif">● Aktif</span>
                                </td>
                                <td>
                                    <div class="action-wrap">
                                        <button class="btn-edit" onclick="openEditEmployee({{ json_encode($emp) }}, {{ json_encode($emp->employee ? $emp->employee->division_id : '') }})">
                                            Edit
                                        </button>
                                        <button class="btn-delete" onclick="confirmDelete('employee', '{{ route('HRmanage.employee.delete', $emp->id) }}', '{{ $emp->name }}')">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                        <button class="btn-primary" onclick="openModal('modalAddDivision')">
                            Tambah Divisi
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table" id="divisionTable">
                        <thead>
                            <tr>
                                <th>Nama Divisi</th>
                                <th>ID</th>
                                <th>Jumlah Karyawan</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="divisionBody">
                            @forelse($divisions as $div)
                            <tr class="table-row">
                                <td><span class="font-semibold text-slate-800 text-sm sora">{{ $div->division_name }}</span></td>
                                <td><span class="text-slate-400 text-xs font-mono">#{{ $div->id }}</span></td>
                                <td><span class="font-semibold text-slate-700 text-sm sora">{{ $div->employees_count }}</span> <span class="text-slate-400 text-xs ml-1">orang</span></td>
                                <td>
                                    <div class="action-wrap">
                                        <button class="btn-edit" onclick="openEditDivision({{ json_encode($div) }})">Edit</button>
                                        <button class="btn-delete" onclick="confirmDelete('division', '{{ route('HRmanage.division.delete', $div->id) }}', '{{ $div->division_name }}')">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400">Belum ada divisi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============ TAB: ATTENDANCE ============ -->
        <div id="tab-attendance" class="tab-content hidden fade-up d2">
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Riwayat Absensi</h3>
                        <p class="panel-subtitle">Data absensi terbaru</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="data-table" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceBody">
                            @forelse($attendances as $att)
                            <tr class="table-row">
                                <td><span class="font-semibold text-slate-800 text-sm sora">{{ $att->user->name }}</span></td>
                                <td><span class="text-slate-400 text-xs">{{ $att->created_at->format('d M Y') }}</span></td>
                                <td><span class="status-badge status-{{ strtolower($att->status) }}">● {{ $att->status }}</span></td>
                                <td>
                                    <div class="action-wrap">
                                        <button class="btn-delete" onclick="confirmDelete('attendance', '{{ route('HRmanage.attendance.delete', $att->id) }}', 'Absensi {{ $att->user->name }}')">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400">Tidak ada riwayat absensi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
        <form action="{{ route('HRmanage.employee.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">Tambah Karyawan</h3>
                    <p class="modal-sub">Isi data karyawan baru</p>
                </div>
                <button type="button" class="modal-close" onclick="closeModal('modalAddEmployee')">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-field col-span-2">
                        <label class="form-label">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" class="form-input" placeholder="Nama lengkap karyawan" required>
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" class="form-input" placeholder="email@attensys.id" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Password <span class="text-red-400">*</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Min 8 karakter" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Divisi <span class="text-red-400">*</span></label>
                        <select name="division_id" class="form-select" required>
                            <option value="">Pilih Divisi</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}">{{ $div->division_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="position" class="form-input" placeholder="Jabatan / posisi" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" class="form-input" placeholder="08xxx">
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-input" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddEmployee')">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL: EDIT KARYAWAN
     ======================================================== -->
<div class="modal-overlay" id="modalEditEmployee" onclick="closeModalOutside(event,'modalEditEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <form id="formEditEmployee" method="POST">
            @csrf
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">Edit Karyawan</h3>
                    <p class="modal-sub">Perbarui informasi karyawan</p>
                </div>
                <button type="button" class="modal-close" onclick="closeModal('modalEditEmployee')">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-field col-span-2">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="editName" name="name" class="form-input" required>
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">Email</label>
                        <input type="email" id="editEmail" name="email" class="form-input" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Isi hanya jika ingin ganti">
                    </div>
                    <div class="form-field">
                        <label class="form-label">Divisi</label>
                        <select id="editDivision" name="division_id" class="form-select" required>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}">{{ $div->division_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="form-label">Jabatan</label>
                        <input type="text" id="editPosition" name="position" class="form-input" required>
                    </div>
                    <div class="form-field">
                        <label class="form-label">No. HP</label>
                        <input type="text" id="editPhone" name="phone" class="form-input">
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">Alamat</label>
                        <textarea id="editAddress" name="address" class="form-input" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditEmployee')">Batal</button>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL: TAMBAH DIVISI
     ======================================================== -->
<div class="modal-overlay" id="modalAddDivision" onclick="closeModalOutside(event,'modalAddDivision')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <form action="{{ route('HRmanage.division.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <div><h3 class="modal-title">Tambah Divisi</h3></div>
                <button type="button" class="modal-close" onclick="closeModal('modalAddDivision')">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-field">
                    <label class="form-label">Nama Divisi</label>
                    <input type="text" name="division_name" class="form-input" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddDivision')">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL: EDIT DIVISI
     ======================================================== -->
<div class="modal-overlay" id="modalEditDivision" onclick="closeModalOutside(event,'modalEditDivision')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <form id="formEditDivision" method="POST">
            @csrf
            <div class="modal-header">
                <div><h3 class="modal-title">Edit Divisi</h3></div>
                <button type="button" class="modal-close" onclick="closeModal('modalEditDivision')">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-field">
                    <label class="form-label">Nama Divisi</label>
                    <input type="text" id="editDivName" name="division_name" class="form-input" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditDivision')">Batal</button>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================
     MODAL: KONFIRMASI HAPUS
     ======================================================== -->
<div class="modal-overlay" id="modalDelete" onclick="closeModalOutside(event,'modalDelete')">
    <div class="modal-box modal-sm" onclick="event.stopPropagation()">
        <form id="formDelete" method="POST">
            @csrf
            @method('DELETE')
            <div class="delete-icon-wrap"><div class="delete-icon">🗑</div></div>
            <h3 class="delete-title">Hapus Data?</h3>
            <p class="delete-sub" id="deleteMsg">Data ini akan dihapus secara permanen.</p>
            <div class="modal-footer justify-center">
                <button type="button" class="btn-ghost" onclick="closeModal('modalDelete')">Batal</button>
                <button type="submit" class="btn-danger">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tabName, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(`tab-${tabName}`).classList.remove('hidden');
        btn.classList.add('active');
    }
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }
    function closeModalOutside(e, id) {
        if (e.target.id === id) closeModal(id);
    }

    function openEditEmployee(user, divisionId) {
        const form = document.getElementById('formEditEmployee');
        form.action = `/HRmanage/employee/${user.id}`;
        document.getElementById('editName').value = user.name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editPosition').value = user.position || '';
        document.getElementById('editPhone').value = user.phone || '';
        document.getElementById('editAddress').value = user.address || '';
        document.getElementById('editDivision').value = divisionId;
        openModal('modalEditEmployee');
    }

    function openEditDivision(div) {
        const form = document.getElementById('formEditDivision');
        form.action = `/HRmanage/division/${div.id}`;
        document.getElementById('editDivName').value = div.division_name;
        openModal('modalEditDivision');
    }

    function confirmDelete(type, actionUrl, label) {
        const form = document.getElementById('formDelete');
        form.action = actionUrl;
        document.getElementById('deleteMsg').textContent = `"${label}" akan dihapus permanen.`;
        openModal('modalDelete');
    }
</script>
</body>
</html>
