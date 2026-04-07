/**
 * ATTENSYS — HR Manage JavaScript
 * File: public/js/Admin_HR/HRmanage.js
 *
 * Handles:
 *  - Tab switching
 *  - CRUD: Karyawan, Divisi, Absensi (in-memory demo data)
 *  - Search & filter per tab
 *  - Pagination
 *  - Modal open/close with validation
 *  - Toast notifications
 *  - Delete confirmation
 */

/* ============================================================
   STATE
   ============================================================ */
const COLORS = [
    'linear-gradient(135deg,#6366f1,#818cf8)',
    'linear-gradient(135deg,#06b6d4,#0891b2)',
    'linear-gradient(135deg,#10b981,#059669)',
    'linear-gradient(135deg,#f59e0b,#d97706)',
    'linear-gradient(135deg,#8b5cf6,#7c3aed)',
    'linear-gradient(135deg,#ec4899,#db2777)',
    'linear-gradient(135deg,#ef4444,#dc2626)',
    'linear-gradient(135deg,#3b82f6,#1d4ed8)',
];

function randomColor() {
    return COLORS[Math.floor(Math.random() * COLORS.length)];
}

/* ---- Demo data ---- */
let employees = [
    { id:1, name:'Andi Rahman',    nip:'EMP-001', email:'andi@attensys.id',   division:'Engineering', position:'Backend Dev',    joinDate:'2025-01-15', status:'aktif',    color:COLORS[0] },
    { id:2, name:'Siti Wulandari', nip:'EMP-002', email:'siti@attensys.id',   division:'HR',          position:'HR Specialist',  joinDate:'2024-03-01', status:'aktif',    color:COLORS[1] },
    { id:3, name:'Budi Pratama',   nip:'EMP-003', email:'budi@attensys.id',   division:'Finance',     position:'Akuntan',        joinDate:'2026-03-10', status:'aktif',    color:COLORS[4] },
    { id:4, name:'Rini Handayani', nip:'EMP-004', email:'rini@attensys.id',   division:'Marketing',   position:'Marketing Staff',joinDate:'2023-06-20', status:'nonaktif', color:COLORS[6] },
    { id:5, name:'Fajar Nugroho',  nip:'EMP-005', email:'fajar@attensys.id',  division:'IT',          position:'Frontend Dev',   joinDate:'2024-08-05', status:'aktif',    color:COLORS[2] },
    { id:6, name:'Dewi Susanti',   nip:'EMP-006', email:'dewi@attensys.id',   division:'Engineering', position:'QA Engineer',    joinDate:'2024-11-01', status:'aktif',    color:COLORS[3] },
    { id:7, name:'Hendra Putra',   nip:'EMP-007', email:'hendra@attensys.id', division:'Operasional', position:'Ops Lead',       joinDate:'2023-02-14', status:'aktif',    color:COLORS[7] },
    { id:8, name:'Yuni Setiawati', nip:'EMP-008', email:'yuni@attensys.id',   division:'HR',          position:'Recruiter',      joinDate:'2025-05-20', status:'aktif',    color:COLORS[5] },
];

let divisions = [
    { id:1, name:'Engineering', code:'DIV-ENG', head:'Andi Rahman',    count:3, desc:'Pengembangan & pemeliharaan sistem' },
    { id:2, name:'HR',          code:'DIV-HR',  head:'Siti Wulandari', count:2, desc:'Sumber daya manusia & rekrutmen' },
    { id:3, name:'Finance',     code:'DIV-FIN', head:'Budi Pratama',   count:2, desc:'Keuangan & akuntansi perusahaan' },
    { id:4, name:'Marketing',   code:'DIV-MKT', head:'Rini Handayani', count:3, desc:'Pemasaran & brand awareness' },
    { id:5, name:'IT',          code:'DIV-IT',  head:'Fajar Nugroho',  count:4, desc:'Infrastruktur & keamanan sistem' },
    { id:6, name:'Operasional', code:'DIV-OPS', head:'Hendra Putra',   count:5, desc:'Operasional harian perusahaan' },
];

let attendances = [
    { id:1,  name:'Andi Rahman',    date:'2026-04-07', status:'Present',    checkIn:'08:02', checkOut:'17:10', note:'Tepat waktu' },
    { id:2,  name:'Siti Wulandari', date:'2026-04-07', status:'Present',    checkIn:'07:55', checkOut:'17:00', note:'Tepat waktu' },
    { id:3,  name:'Budi Pratama',   date:'2026-04-07', status:'Late',       checkIn:'09:15', checkOut:'17:30', note:'Terlambat 75 mnt' },
    { id:4,  name:'Rini Handayani', date:'2026-04-07', status:'Absent',     checkIn:'-',     checkOut:'-',     note:'Tidak hadir' },
    { id:5,  name:'Fajar Nugroho',  date:'2026-04-07', status:'Sick',       checkIn:'-',     checkOut:'-',     note:'Sakit + surat dokter' },
    { id:6,  name:'Dewi Susanti',   date:'2026-04-07', status:'Permission', checkIn:'-',     checkOut:'-',     note:'Izin keperluan keluarga' },
    { id:7,  name:'Hendra Putra',   date:'2026-04-07', status:'Present',    checkIn:'07:48', checkOut:'17:05', note:'Tepat waktu' },
    { id:8,  name:'Yuni Setiawati', date:'2026-04-07', status:'Present',    checkIn:'08:00', checkOut:'17:00', note:'Tepat waktu' },
    { id:9,  name:'Andi Rahman',    date:'2026-04-06', status:'Present',    checkIn:'08:01', checkOut:'17:08', note:'' },
    { id:10, name:'Siti Wulandari', date:'2026-04-06', status:'Late',       checkIn:'09:10', checkOut:'17:20', note:'Terlambat' },
];

let nextEmpId  = 9;
let nextDivId  = 7;
let nextAttId  = 11;

/* Pagination state */
const PAGE_SIZE = 6;
let empPage = 1;
let attPage = 1;

/* Delete state */
let deleteTarget = null; // { type, id }

/* ============================================================
   SIDEBAR
   ============================================================ */
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
    document.body.style.overflow = '';
}
window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) closeSidebar();
});

/* ============================================================
   DATE
   ============================================================ */
function setDate() {
    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'];
    const n = new Date();
    const el = document.getElementById('currentDate');
    if (el) el.textContent = `${days[n.getDay()]}, ${n.getDate()} ${months[n.getMonth()]} ${n.getFullYear()}`;
}

/* ============================================================
   TAB SWITCHING
   ============================================================ */
function switchTab(tabName, btn) {
    // Deactivate all
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

    document.getElementById(`tab-${tabName}`).classList.remove('hidden');
    btn.classList.add('active');
}

/* ============================================================
   MODAL
   ============================================================ */
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
    clearErrors();
}
function closeModalOutside(e, id) {
    if (e.target.id === id) closeModal(id);
}

/* ============================================================
   TOAST
   ============================================================ */
let toastTimer = null;
function showToast(icon, msg, duration = 3000) {
    const t = document.getElementById('toast');
    document.getElementById('toastIcon').textContent = icon;
    document.getElementById('toastMsg').textContent  = msg;
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), duration);
}

/* ============================================================
   VALIDATION HELPERS
   ============================================================ */
function clearErrors() {
    document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.form-input.is-error').forEach(el => el.classList.remove('is-error'));
}
function setError(fieldId, errId, msg) {
    const field = document.getElementById(fieldId);
    const err   = document.getElementById(errId);
    if (field) field.classList.add('is-error');
    if (err)   err.textContent = msg;
}
function clearError(fieldId, errId) {
    const field = document.getElementById(fieldId);
    const err   = document.getElementById(errId);
    if (field) field.classList.remove('is-error');
    if (err)   err.textContent = '';
}

/* ============================================================
   EMPLOYEE CRUD
   ============================================================ */

/* --- Render --- */
function renderEmployees(list, page) {
    const body    = document.getElementById('employeeBody');
    const empty   = document.getElementById('emptyEmployee');
    const infoEl  = document.getElementById('infoEmployee');
    const pagWrap = document.getElementById('paginationEmployee');

    if (!list.length) {
        body.innerHTML = '';
        empty.classList.remove('hidden');
        infoEl.textContent = '0 data';
        pagWrap.innerHTML  = '';
        return;
    }
    empty.classList.add('hidden');

    const total = list.length;
    const pages = Math.ceil(total / PAGE_SIZE);
    page = Math.max(1, Math.min(page, pages));
    empPage = page;

    const start = (page - 1) * PAGE_SIZE;
    const slice = list.slice(start, start + PAGE_SIZE);

    infoEl.textContent = `Menampilkan ${start+1}–${Math.min(start+PAGE_SIZE, total)} dari ${total} data`;

    body.innerHTML = slice.map(e => `
        <tr class="table-row">
            <td>
                <div class="flex items-center gap-3">
                    <div class="avatar-sm" style="background:${e.color}">${initials(e.name)}</div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">${e.name}</p>
                        <p class="text-slate-400" style="font-size:0.72rem">${e.email}</p>
                    </div>
                </div>
            </td>
            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">${e.nip}</span></td>
            <td><span class="text-slate-600 text-sm">${e.division}</span></td>
            <td class="hidden md:table-cell"><span class="text-slate-600 text-sm">${e.position || '—'}</span></td>
            <td><span class="status-badge status-${e.status}">● ${e.status === 'aktif' ? 'Aktif' : 'Nonaktif'}</span></td>
            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">${formatDate(e.joinDate)}</span></td>
            <td>
                <div class="action-wrap">
                    <button class="btn-edit" onclick="openEditEmployee(${e.id})">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                    <button class="btn-delete" onclick="confirmDelete('employee', ${e.id}, '${e.name}')">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination(pagWrap, pages, page, 'employee');
    updateBadge('countEmployees', employees.length);
}

/* --- Filter --- */
function getFilteredEmployees() {
    const search  = (document.getElementById('searchEmployee')?.value  || '').toLowerCase();
    const divFilt = (document.getElementById('filterDivision')?.value  || '');
    const statFilt= (document.getElementById('filterStatus')?.value    || '');

    return employees.filter(e => {
        const matchS = !search   || e.name.toLowerCase().includes(search) || e.email.toLowerCase().includes(search) || e.nip.toLowerCase().includes(search);
        const matchD = !divFilt  || e.division === divFilt;
        const matchSt= !statFilt || e.status   === statFilt;
        return matchS && matchD && matchSt;
    });
}
function filterEmployees() {
    empPage = 1;
    renderEmployees(getFilteredEmployees(), 1);
}

/* --- Add --- */
function saveEmployee() {
    clearErrors();
    const name     = document.getElementById('addName').value.trim();
    const email    = document.getElementById('addEmail').value.trim();
    const division = document.getElementById('addDivision').value;

    let valid = true;
    if (!name)     { setError('addName','errAddName','Nama wajib diisi'); valid = false; }
    if (!email)    { setError('addEmail','errAddEmail','Email wajib diisi'); valid = false; }
    else if (!/\S+@\S+\.\S+/.test(email)) { setError('addEmail','errAddEmail','Format email tidak valid'); valid = false; }
    if (!division) { setError('addDivision','errAddDivision','Divisi wajib dipilih'); valid = false; }
    if (!valid) return;

    const nip = document.getElementById('addNip').value.trim() || `EMP-${String(nextEmpId).padStart(3,'0')}`;

    employees.push({
        id: nextEmpId++,
        name, nip, email, division,
        position: document.getElementById('addPosition').value.trim(),
        joinDate:  document.getElementById('addJoinDate').value || new Date().toISOString().slice(0,10),
        status:    document.getElementById('addStatus').value,
        color:     randomColor(),
    });

    // Reset form
    ['addName','addNip','addEmail','addPosition','addJoinDate'].forEach(id => { document.getElementById(id).value = ''; });
    document.getElementById('addDivision').value = '';
    document.getElementById('addStatus').value   = 'aktif';

    closeModal('modalAddEmployee');
    filterEmployees();
    showToast('✅', `Karyawan "${name}" berhasil ditambahkan`);
}

/* --- Edit open --- */
function openEditEmployee(id) {
    const e = employees.find(x => x.id === id);
    if (!e) return;

    document.getElementById('editId').value       = e.id;
    document.getElementById('editName').value     = e.name;
    document.getElementById('editNip').value      = e.nip;
    document.getElementById('editEmail').value    = e.email;
    document.getElementById('editDivision').value = e.division;
    document.getElementById('editPosition').value = e.position || '';
    document.getElementById('editJoinDate').value = e.joinDate || '';
    document.getElementById('editStatus').value   = e.status;

    clearErrors();
    openModal('modalEditEmployee');
}

/* --- Edit save --- */
function updateEmployee() {
    clearErrors();
    const id       = parseInt(document.getElementById('editId').value);
    const name     = document.getElementById('editName').value.trim();
    const email    = document.getElementById('editEmail').value.trim();
    const division = document.getElementById('editDivision').value;

    let valid = true;
    if (!name)     { setError('editName','errEditName','Nama wajib diisi'); valid = false; }
    if (!email)    { setError('editEmail','errEditEmail','Email wajib diisi'); valid = false; }
    else if (!/\S+@\S+\.\S+/.test(email)) { setError('editEmail','errEditEmail','Format email tidak valid'); valid = false; }
    if (!division) { setError('editDivision','errEditDivision','Divisi wajib dipilih'); valid = false; }
    if (!valid) return;

    const idx = employees.findIndex(x => x.id === id);
    if (idx === -1) return;

    employees[idx] = {
        ...employees[idx],
        name, email, division,
        position: document.getElementById('editPosition').value.trim(),
        joinDate:  document.getElementById('editJoinDate').value,
        status:    document.getElementById('editStatus').value,
    };

    closeModal('modalEditEmployee');
    filterEmployees();
    showToast('✏️', `Data "${name}" berhasil diperbarui`);
}

/* ============================================================
   DIVISION CRUD
   ============================================================ */

/* --- Render --- */
function renderDivisions(list) {
    const body   = document.getElementById('divisionBody');
    const empty  = document.getElementById('emptyDivision');
    const infoEl = document.getElementById('infoDivision');

    if (!list.length) {
        body.innerHTML = '';
        empty.classList.remove('hidden');
        infoEl.textContent = '0 data';
        return;
    }
    empty.classList.add('hidden');
    infoEl.textContent = `${list.length} divisi`;

    const divColors = {
        Engineering:'linear-gradient(135deg,#6366f1,#818cf8)',
        HR:         'linear-gradient(135deg,#06b6d4,#0891b2)',
        Finance:    'linear-gradient(135deg,#10b981,#059669)',
        Marketing:  'linear-gradient(135deg,#f59e0b,#d97706)',
        IT:         'linear-gradient(135deg,#3b82f6,#1d4ed8)',
        Operasional:'linear-gradient(135deg,#8b5cf6,#7c3aed)',
    };

    body.innerHTML = list.map(d => `
        <tr class="table-row">
            <td>
                <div class="flex items-center gap-3">
                    <div class="avatar-sm" style="background:${divColors[d.name] || randomColor()}">${initials(d.name)}</div>
                    <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">${d.name}</span>
                </div>
            </td>
            <td class="hidden sm:table-cell">
                <span class="text-slate-400 text-xs font-mono">${d.code || '—'}</span>
            </td>
            <td class="hidden md:table-cell">
                <span class="text-slate-600 text-sm">${d.head || '—'}</span>
            </td>
            <td>
                <span class="font-semibold text-slate-700 text-sm" style="font-family:'Sora',sans-serif">${d.count}</span>
                <span class="text-slate-400 text-xs ml-1">orang</span>
            </td>
            <td class="hidden lg:table-cell">
                <span class="text-slate-400 text-xs">${d.desc || '—'}</span>
            </td>
            <td>
                <div class="action-wrap">
                    <button class="btn-edit" onclick="openEditDivision(${d.id})">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                    <button class="btn-delete" onclick="confirmDelete('division', ${d.id}, '${d.name}')">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    updateBadge('countDivisions', divisions.length);
}

/* --- Filter --- */
function filterDivisions() {
    const search = (document.getElementById('searchDivision')?.value || '').toLowerCase();
    const list   = divisions.filter(d =>
        !search || d.name.toLowerCase().includes(search) || (d.head || '').toLowerCase().includes(search)
    );
    renderDivisions(list);
}

/* --- Add --- */
function saveDivision() {
    clearErrors();
    const name = document.getElementById('addDivName').value.trim();
    if (!name) { setError('addDivName','errAddDivName','Nama divisi wajib diisi'); return; }

    divisions.push({
        id:    nextDivId++,
        name,
        code:  document.getElementById('addDivCode').value.trim() || `DIV-${String(nextDivId-1).padStart(3,'0')}`,
        head:  document.getElementById('addDivHead').value.trim(),
        count: 0,
        desc:  document.getElementById('addDivDesc').value.trim(),
    });

    ['addDivName','addDivCode','addDivHead','addDivDesc'].forEach(id => { document.getElementById(id).value = ''; });

    closeModal('modalAddDivision');
    filterDivisions();
    showToast('🏢', `Divisi "${name}" berhasil ditambahkan`);
}

/* --- Edit open --- */
function openEditDivision(id) {
    const d = divisions.find(x => x.id === id);
    if (!d) return;

    document.getElementById('editDivId').value   = d.id;
    document.getElementById('editDivName').value = d.name;
    document.getElementById('editDivCode').value = d.code || '';
    document.getElementById('editDivHead').value = d.head || '';
    document.getElementById('editDivDesc').value = d.desc || '';

    clearErrors();
    openModal('modalEditDivision');
}

/* --- Edit save --- */
function updateDivision() {
    clearErrors();
    const id   = parseInt(document.getElementById('editDivId').value);
    const name = document.getElementById('editDivName').value.trim();
    if (!name) { setError('editDivName','errEditDivName','Nama divisi wajib diisi'); return; }

    const idx = divisions.findIndex(x => x.id === id);
    if (idx === -1) return;

    divisions[idx] = {
        ...divisions[idx],
        name,
        code: document.getElementById('editDivCode').value.trim(),
        head: document.getElementById('editDivHead').value.trim(),
        desc: document.getElementById('editDivDesc').value.trim(),
    };

    closeModal('modalEditDivision');
    filterDivisions();
    showToast('✏️', `Divisi "${name}" berhasil diperbarui`);
}

/* ============================================================
   ATTENDANCE CRUD
   ============================================================ */

/* --- Render --- */
function renderAttendance(list, page) {
    const body    = document.getElementById('attendanceBody');
    const empty   = document.getElementById('emptyAttendance');
    const infoEl  = document.getElementById('infoAttendance');
    const pagWrap = document.getElementById('paginationAttendance');

    if (!list.length) {
        body.innerHTML = '';
        empty.classList.remove('hidden');
        infoEl.textContent = '0 data';
        pagWrap.innerHTML  = '';
        return;
    }
    empty.classList.add('hidden');

    const total = list.length;
    const pages = Math.ceil(total / PAGE_SIZE);
    page = Math.max(1, Math.min(page, pages));
    attPage = page;

    const start = (page - 1) * PAGE_SIZE;
    const slice = list.slice(start, start + PAGE_SIZE);

    infoEl.textContent = `Menampilkan ${start+1}–${Math.min(start+PAGE_SIZE, total)} dari ${total} data`;

    body.innerHTML = slice.map(a => {
        const emp = employees.find(e => e.name === a.name);
        const col = emp ? emp.color : randomColor();
        return `
            <tr class="table-row">
                <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar-sm" style="background:${col}">${initials(a.name)}</div>
                        <span class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">${a.name}</span>
                    </div>
                </td>
                <td class="hidden sm:table-cell">
                    <span class="text-slate-400 text-xs">${formatDate(a.date)}</span>
                </td>
                <td>
                    <span class="status-badge status-${a.status.toLowerCase()}">● ${a.status}</span>
                </td>
                <td class="hidden md:table-cell">
                    <span class="text-slate-500 text-sm">${a.checkIn || '—'}</span>
                </td>
                <td class="hidden md:table-cell">
                    <span class="text-slate-500 text-sm">${a.checkOut || '—'}</span>
                </td>
                <td class="hidden lg:table-cell">
                    <span class="text-slate-400 text-xs">${a.note || '—'}</span>
                </td>
                <td>
                    <div class="action-wrap">
                        <button class="btn-edit" onclick="openEditAttendance(${a.id})">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Koreksi
                        </button>
                        <button class="btn-delete" onclick="confirmDelete('attendance', ${a.id}, '${a.name} - ${formatDate(a.date)}')">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    renderPagination(pagWrap, pages, page, 'attendance');
    updateBadge('countAttendance', attendances.length);
}

/* --- Filter --- */
function getFilteredAttendance() {
    const search = (document.getElementById('searchAttendance')?.value  || '').toLowerCase();
    const date   = (document.getElementById('filterDate')?.value        || '');
    const status = (document.getElementById('filterAttStatus')?.value   || '');

    return attendances.filter(a => {
        const matchS  = !search || a.name.toLowerCase().includes(search);
        const matchD  = !date   || a.date === date;
        const matchSt = !status || a.status === status;
        return matchS && matchD && matchSt;
    });
}
function filterAttendance() {
    attPage = 1;
    renderAttendance(getFilteredAttendance(), 1);
}

/* --- Edit open --- */
function openEditAttendance(id) {
    const a = attendances.find(x => x.id === id);
    if (!a) return;

    document.getElementById('editAttId').value       = a.id;
    document.getElementById('editAttName').value     = a.name;
    document.getElementById('editAttDate').value     = a.date;
    document.getElementById('editAttStatus').value   = a.status;
    document.getElementById('editAttCheckIn').value  = (a.checkIn  !== '-' ? a.checkIn  : '');
    document.getElementById('editAttCheckOut').value = (a.checkOut !== '-' ? a.checkOut : '');
    document.getElementById('editAttNote').value     = a.note || '';

    openModal('modalEditAttendance');
}

/* --- Edit save --- */
function updateAttendance() {
    const id = parseInt(document.getElementById('editAttId').value);
    const idx = attendances.findIndex(x => x.id === id);
    if (idx === -1) return;

    const status   = document.getElementById('editAttStatus').value;
    const checkIn  = document.getElementById('editAttCheckIn').value  || '-';
    const checkOut = document.getElementById('editAttCheckOut').value || '-';

    attendances[idx] = {
        ...attendances[idx],
        date:     document.getElementById('editAttDate').value,
        status,
        checkIn,
        checkOut,
        note: document.getElementById('editAttNote').value.trim(),
    };

    closeModal('modalEditAttendance');
    renderAttendance(getFilteredAttendance(), attPage);
    showToast('✏️', 'Data absensi berhasil dikoreksi');
}

/* --- Export --- */
function exportAttendance() {
    const list  = getFilteredAttendance();
    const lines = ['Nama,Tanggal,Status,Check In,Check Out,Keterangan'];
    list.forEach(a => {
        lines.push(`"${a.name}","${a.date}","${a.status}","${a.checkIn}","${a.checkOut}","${a.note}"`);
    });
    const csv  = lines.join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `absensi_${new Date().toISOString().slice(0,10)}.csv`;
    link.click();
    URL.revokeObjectURL(url);
    showToast('📥', 'Data absensi diekspor ke CSV');
}

/* ============================================================
   DELETE
   ============================================================ */
function confirmDelete(type, id, label) {
    deleteTarget = { type, id };
    document.getElementById('deleteMsg').textContent =
        `"${label}" akan dihapus secara permanen dan tidak dapat dikembalikan.`;
    openModal('modalDelete');
}

function executeDelete() {
    if (!deleteTarget) return;
    const { type, id } = deleteTarget;

    if (type === 'employee') {
        const name = employees.find(x => x.id === id)?.name || '';
        employees  = employees.filter(x => x.id !== id);
        closeModal('modalDelete');
        filterEmployees();
        showToast('🗑', `Karyawan "${name}" berhasil dihapus`);
    }
    else if (type === 'division') {
        const name = divisions.find(x => x.id === id)?.name || '';
        divisions  = divisions.filter(x => x.id !== id);
        closeModal('modalDelete');
        filterDivisions();
        showToast('🗑', `Divisi "${name}" berhasil dihapus`);
    }
    else if (type === 'attendance') {
        attendances = attendances.filter(x => x.id !== id);
        closeModal('modalDelete');
        renderAttendance(getFilteredAttendance(), attPage);
        showToast('🗑', 'Data absensi berhasil dihapus');
    }

    deleteTarget = null;
}

/* ============================================================
   PAGINATION
   ============================================================ */
function renderPagination(wrap, totalPages, current, type) {
    if (totalPages <= 1) { wrap.innerHTML = ''; return; }

    let html = `<button class="page-btn" onclick="goPage('${type}',${current-1})" ${current===1?'disabled':''}>‹</button>`;

    for (let i = 1; i <= totalPages; i++) {
        if (totalPages > 5 && i > 2 && i < totalPages - 1 && Math.abs(i - current) > 1) {
            if (i === 3 || i === totalPages - 2) html += `<span style="color:#94a3b8;padding:0 4px">…</span>`;
            continue;
        }
        html += `<button class="page-btn ${i===current?'active':''}" onclick="goPage('${type}',${i})">${i}</button>`;
    }

    html += `<button class="page-btn" onclick="goPage('${type}',${current+1})" ${current===totalPages?'disabled':''}>›</button>`;
    wrap.innerHTML = html;
}

function goPage(type, page) {
    if (type === 'employee')   renderEmployees(getFilteredEmployees(),  page);
    if (type === 'attendance') renderAttendance(getFilteredAttendance(), page);
}

/* ============================================================
   HELPERS
   ============================================================ */
function initials(name) {
    const parts = name.trim().split(' ');
    return parts.length >= 2
        ? (parts[0][0] + parts[1][0]).toUpperCase()
        : name.slice(0,2).toUpperCase();
}

function formatDate(iso) {
    if (!iso) return '—';
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const [y, m, d] = iso.split('-');
    return `${parseInt(d)} ${months[parseInt(m)-1]} ${y}`;
}

function updateBadge(id, count) {
    const el = document.getElementById(id);
    if (el) el.textContent = count;
}

/* ============================================================
   INIT
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    setDate();
    filterEmployees();
    filterDivisions();
    renderAttendance(getFilteredAttendance(), 1);

    // Set today as default date filter for attendance
    const today = new Date().toISOString().slice(0,10);
    const dateInput = document.getElementById('filterDate');
    if (dateInput) dateInput.value = today;
    filterAttendance();
});