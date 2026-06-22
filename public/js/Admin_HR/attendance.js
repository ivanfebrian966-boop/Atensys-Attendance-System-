/**
 * Attendance Management System - Main JS File
 * public/js/Admin_HR/attendance.js
 */

/**
 * Cek apakah checkout hari ini sudah boleh diisi/diedit.
 * Diperbolehkan jika: tanggal bukan hari ini, ATAU jam sudah >= 17:00
 * @param {string|null} dateStr  - string tanggal 'YYYY-MM-DD', null = hari ini
 * @returns {boolean}
 */
function isTodayCheckoutAllowed(dateStr) {
    const today = new Date().toISOString().split('T')[0];
    const targetDate = dateStr || today;
    if (targetDate !== today) return true; // hari lain: bebas
    const now = new Date();
    return now.getHours() >= 17; // >= 17:00 boleh
}

let _employees = [];
let currentAttPage = 1;
const ATT_PAGE_SIZE = 10;

/* =========================================================
   API INTEGRATION
   ========================================================= */

function loadAttendanceData() {
    const date = document.getElementById('filterDate')?.value
        || new Date().toISOString().split('T')[0];

    fetch(`${ATTENDANCE_DATA_URL}?date=${date}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                _attFull = data.data;
                filterAtt();
            }
        })
        .catch(err => console.error('loadAttendanceData error:', err));
}

function updateStats() {
    const date = document.getElementById('filterDate')?.value
        || new Date().toISOString().split('T')[0];

    fetch(`${ATTENDANCE_STATS_URL}?date=${date}`)
        .then(r => r.json())
        .then(data => {
            if (data.success && data.data) {
                const s = data.data;
                const set = (id, val) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = val || 0;
                };
                set('sTotal',   s.total);
                set('sPresent', s.present);
                set('sAbsent',  s.absent);
                set('sLate',    s.late);
                set('sSick',    s.sick);
                set('sPerm',    s.permission);
            }
        })
        .catch(err => console.error('updateStats error:', err));
}

function loadEmployees() {
    fetch('/admin-hr/attendance/employees')
        .then(r => r.json())
        .then(data => {
            if (data.success) _employees = data.data;
        })
        .catch(err => console.error('loadEmployees error:', err));
}

/* =========================================================
   FILTER & RENDER
   ========================================================= */

function filterAtt(resetPage = true) {
    if (!_attFull) return;

    const search = (document.getElementById('searchAtt')?.value || '').toLowerCase();
    const status = document.getElementById('filterAttStatus')?.value || '';
    const div    = document.getElementById('filterAttDiv')?.value || '';

    let filtered = _attFull;

    if (search) {
        filtered = filtered.filter(r =>
            (r.name     && r.name.toLowerCase().includes(search)) ||
            (r.division && r.division.toLowerCase().includes(search))
        );
    }
    if (status) filtered = filtered.filter(r => r.status   === status);
    if (div)    filtered = filtered.filter(r => r.division === div);

    renderAttendanceTable(filtered, resetPage);
}

function changeAttPage(page) {
    currentAttPage = page;
    filterAtt(false);
}

function renderAttPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / ATT_PAGE_SIZE) || 1;
    let pag = document.getElementById('attPagination');

    if (!pag) {
        const info = document.getElementById('attInfo');
        if (info && info.parentNode) {
            pag = document.createElement('div');
            pag.id        = 'attPagination';
            pag.className = 'pagination flex gap-1 items-center justify-end w-full';
            info.parentNode.classList.add('flex', 'justify-between', 'items-center', 'w-full');
            info.parentNode.appendChild(pag);
        } else {
            return;
        }
    }

    if (totalPages <= 1) { pag.innerHTML = ''; return; }

    const btnBase   = 'px-3 py-1 border rounded text-xs';
    const btnActive = `${btnBase} bg-indigo-50 text-indigo-600 border-indigo-200 font-bold`;
    const btnNormal = `${btnBase} hover:bg-slate-50`;
    const btnDis    = `${btnBase} opacity-50 cursor-not-allowed`;

    let html = `<button class="${currentAttPage === 1 ? btnDis : btnNormal}"
        onclick="changeAttPage(${currentAttPage - 1})"
        ${currentAttPage === 1 ? 'disabled' : ''}>Prev</button>`;

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentAttPage - 1 && i <= currentAttPage + 1)) {
            html += `<button class="${i === currentAttPage ? btnActive : btnNormal}"
                onclick="changeAttPage(${i})">${i}</button>`;
        } else if (i === currentAttPage - 2 || i === currentAttPage + 2) {
            html += `<span class="px-2 py-1 text-slate-400">…</span>`;
        }
    }

    html += `<button class="${currentAttPage === totalPages ? btnDis : btnNormal}"
        onclick="changeAttPage(${currentAttPage + 1})"
        ${currentAttPage === totalPages ? 'disabled' : ''}>Next</button>`;

    pag.innerHTML = html;
}

function renderAttendanceTable(data, resetPage = true) {
    const body = document.getElementById('attBody');
    if (!body) return;

    const empty = document.getElementById('attEmpty');
    const info  = document.getElementById('attInfo');

    if (!data || !data.length) {
        body.innerHTML = '';
        if (empty) empty.classList.remove('hidden');
        if (info)  info.textContent = '0 data';
        renderAttPagination(0);
        return;
    }

    if (empty) empty.classList.add('hidden');
    if (info)  info.textContent = `${data.length} data`;

    if (resetPage) currentAttPage = 1;
    const totalPages = Math.ceil(data.length / ATT_PAGE_SIZE) || 1;
    if (currentAttPage > totalPages) currentAttPage = totalPages;

    const start     = (currentAttPage - 1) * ATT_PAGE_SIZE;
    const paginated = data.slice(start, start + ATT_PAGE_SIZE);

    body.innerHTML = paginated.map(r => `
        <tr class="table-row">
            <td><span class="font-semibold text-slate-800 text-sm">${r.name || '—'}</span></td>
            <td class="hidden sm:table-cell text-slate-600 text-sm">${r.date || '—'}</td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.division || '—'}</td>
            <td><span class="status-badge ${getStatusClass(r.status)}">${getStatusIcon(r.status)} ${r.status}</span></td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.check_in  || '—'}</td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.check_out || '—'}</td>
            <td class="hidden lg:table-cell text-slate-600 text-sm">${r.duration  || '—'}</td>
            <td class="text-right">
                <div class="flex justify-end items-center gap-2">
                    <button class="flex items-center justify-center rounded-lg hover:bg-indigo-100 transition"
                        style="color:#6366f1;background:#eef2ff;border-color:#c7d2fe;padding:6px;width:32px;height:32px;"
                        onclick="editAtt(${r.id})" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                    <button class="flex items-center justify-center rounded-lg hover:bg-red-50 transition"
                        style="padding:6px;width:32px;height:32px;"
                        onclick="openDeleteModal(${r.id})" title="Hapus">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderAttPagination(data.length);
}

/* =========================================================
   UTILITY
   ========================================================= */

function getStatusClass(status) {
    const map = {
        'Present':    'status-present',
        'Absent':     'status-absent',
        'Late':       'status-late',
        'Sick':       'status-sick',
        'Leave':      'status-leave'
    };
    return map[status] || '';
}

function getStatusIcon(status) {
    const map = {
        'Present':    '✅',
        'Absent':     '❌',
        'Late':       '⏰',
        'Sick':       '🏥',
        'Leave':      '🏖️'
    };
    return map[status] || '—';
}

/* =========================================================
   EMPLOYEE SEARCH DROPDOWN
   ========================================================= */

function filterEmployeeDropdown(inputId, hiddenId, dropdownId) {
    const input    = document.getElementById(inputId);
    const dropdown = document.getElementById(dropdownId);
    const hidden   = document.getElementById(hiddenId);
    if (!input || !dropdown) return;

    const val = input.value.toLowerCase();
    hidden.value = '';

    if (val.length < 1) { dropdown.style.display = 'none'; return; }

    const filtered = _employees.filter(e =>
        e.name.toLowerCase().includes(val) || e.nip.includes(val)
    );

    dropdown.innerHTML = filtered.length === 0
        ? '<li class="p-3 text-slate-400 text-xs text-center">Employee not found</li>'
        : filtered.map(e => `
            <li onclick="selectEmployee('${e.name}','${e.nip}','${e.position || ''}','${inputId}','${hiddenId}','${dropdownId}')">
                <p class="font-bold text-sm">${e.name}</p>
                <p class="text-xs text-slate-400">${e.nip} • ${e.position || ''}</p>
            </li>
          `).join('');

    dropdown.style.display = 'block';
}

function selectEmployee(name, nip, position, inputId, hiddenId, dropdownId) {
    // Hide text input, show employee preview card
    const input    = document.getElementById(inputId);
    const hidden   = document.getElementById(hiddenId);
    const dropdown = document.getElementById(dropdownId);
    const preview  = document.getElementById('aaEmpPreview');

    hidden.value = nip;
    input.value  = name;
    input.style.display = 'none';
    dropdown.style.display = 'none';

    if (preview) {
        const initials = name.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase();
        document.getElementById('aaEmpAvatar').textContent      = initials;
        document.getElementById('aaEmpNameDisplay').textContent = name;
        document.getElementById('aaEmpSubDisplay').textContent  = `${position} · NIP ${nip}`;
        preview.style.display = 'flex';
    }
}

function clearEmployeeSelection() {
    const input   = document.getElementById('aaName');
    const hidden  = document.getElementById('aaEmpId');
    const preview = document.getElementById('aaEmpPreview');

    if (input)   { input.value = ''; input.style.display = ''; }
    if (hidden)  hidden.value  = '';
    if (preview) preview.style.display = 'none';
}

/* =========================================================
   TIME TYPE TOGGLE  (Check In / Check Out)
   ========================================================= */

/**
 * @param {'check_in'|'check_out'} type
 */
function setTimeType(type) {
    const btnIn   = document.getElementById('tglBtnIn');
    const btnOut  = document.getElementById('tglBtnOut');
    const inField = document.getElementById('aaCheckIn');
    const outField= document.getElementById('aaCheckOut');
    const label   = document.getElementById('aaTimeLabelText');
    const icon    = document.getElementById('aaTimeIconWrap');
    const hiddenType = document.getElementById('aaTimeType');

    const activeBtn   = 'flex:1;height:38px;border-radius:8px;border:1.5px solid #c7d2fe;background:#eef2ff;color:#4f46e5;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .15s;';
    const inactiveBtn = 'flex:1;height:38px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#94a3b8;font-size:13px;font-weight:500;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .15s;';

    if (type === 'check_in') {
        if (btnIn)  btnIn.style.cssText  = activeBtn;
        if (btnOut) btnOut.style.cssText = inactiveBtn;
        if (inField)  inField.style.display  = '';
        if (outField) { outField.style.display = 'none'; outField.value = ''; }
        if (label) label.textContent = 'CHECK IN TIME';
        if (icon)  icon.style.background = '#eef2ff';
        if (hiddenType) hiddenType.value = 'check_in';
    } else {
        if (btnOut) btnOut.style.cssText = activeBtn;
        if (btnIn)  btnIn.style.cssText  = inactiveBtn;
        if (outField) outField.style.display = '';
        if (inField)  { inField.style.display = 'none'; inField.value = ''; }
        if (label) label.textContent = 'CHECK OUT TIME';
        if (icon)  icon.style.background = '#fef9c3';
        if (hiddenType) hiddenType.value = 'check_out';
    }
}

/* =========================================================
   MODAL — ADD
   ========================================================= */

function openAddAttModal() {
    const today = new Date().toISOString().split('T')[0];

    // Reset employee
    clearEmployeeSelection();

    document.getElementById('aaDate').value   = today;
    document.getElementById('aaCheckIn').value  = '';
    document.getElementById('aaCheckOut').value = '';

    // Reset toggle to Check In
    setTimeType('check_in');

    // Cek apakah checkout hari ini sudah boleh diisi
    applyCheckoutRestriction();

    clearAllErrors();
    openModal('modalAddAtt');
}

/**
 * Aktifkan / nonaktifkan tombol Check Out pada modal Add
 * berdasarkan tanggal yang dipilih dan jam sekarang.
 */
function applyCheckoutRestriction() {
    const dateVal = document.getElementById('aaDate')?.value || '';
    const allowed = isTodayCheckoutAllowed(dateVal);
    const btnOut  = document.getElementById('tglBtnOut');
    const banner  = document.getElementById('aaCheckoutBanner');

    if (btnOut) {
        btnOut.disabled = !allowed;
        btnOut.style.opacity = allowed ? '1' : '0.4';
        btnOut.style.cursor  = allowed ? 'pointer' : 'not-allowed';
        btnOut.title = allowed ? '' : 'Checkout hari ini hanya bisa diisi setelah jam 17:00';
    }
    if (banner) {
        banner.style.display = allowed ? 'none' : 'flex';
    }
    // Jika tombol Check Out sedang aktif & sekarang tidak diizinkan → paksa ke Check In
    if (!allowed) {
        const currentType = document.getElementById('aaTimeType')?.value;
        if (currentType === 'check_out') setTimeType('check_in');
    }
}

function saveAtt() {
    const nip      = document.getElementById('aaEmpId')?.value;
    const date     = document.getElementById('aaDate')?.value;
    const timeType = document.getElementById('aaTimeType')?.value || 'check_in';

    const check_in  = timeType === 'check_in'  ? (document.getElementById('aaCheckIn')?.value  || '') : '';
    const check_out = timeType === 'check_out' ? (document.getElementById('aaCheckOut')?.value || '') : '';

    // Validation
    if (!nip) {
        setErr('aaName', 'eaaName', 'Please select an employee from the dropdown');
        return;
    }
    if (!date) {
        setErr('aaDate', 'eaaDate', 'Date is required');
        return;
    }
    if (timeType === 'check_in' && !check_in) {
        setErr('aaCheckIn', 'eaaCheckIn', 'Check In time is required');
        return;
    }
    if (timeType === 'check_out' && !check_out) {
        setErr('aaCheckOut', 'eaaCheckOut', 'Check Out time is required');
        return;
    }

    fetch(ATTENDANCE_STORE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ nip, date, check_in, check_out, time_type: timeType })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success', 3000);
            closeModal('modalAddAtt');
            loadAttendanceData();
            updateStats();
        } else {
            showToast(data.message || 'Failed to save data', 'error', 3000);
        }
    })
    .catch(() => showToast('System error occurred', 'error', 3000));
}

/* =========================================================
   MODAL — EDIT
   ========================================================= */

function editAtt(id) {
    const r = _attFull.find(a => a.id === id);
    if (!r) return;

    document.getElementById('eaId').value       = id;
    document.getElementById('eaName').value     = r.name    || '';
    document.getElementById('eaDate').value     = r.date    || '';
    document.getElementById('eaCheckIn').value  = r.check_in  || '';
    document.getElementById('eaCheckOut').value = r.check_out !== '—' ? (r.check_out || '') : '';

    // Blokir field check_out jika hari ini & sebelum jam 17:00
    const allowed = isTodayCheckoutAllowed(r.date);
    const coInput  = document.getElementById('eaCheckOut');
    const coBanner = document.getElementById('eaCheckoutBanner');
    if (coInput) {
        coInput.disabled = !allowed;
        coInput.style.opacity = allowed ? '1' : '0.45';
        coInput.style.cursor  = allowed ? '' : 'not-allowed';
        coInput.title = allowed ? '' : 'Checkout hari ini hanya bisa diedit setelah jam 17:00';
    }
    if (coBanner) {
        coBanner.style.display = allowed ? 'none' : 'flex';
    }

    clearAllErrors();
    openModal('modalEditAtt');
}

function updateAtt() {
    const id        = document.getElementById('eaId')?.value;
    const date      = document.getElementById('eaDate')?.value;
    const check_in  = document.getElementById('eaCheckIn')?.value;
    const check_out = document.getElementById('eaCheckOut')?.value;

    if (!id || !date) return;

    // Sanitize: value "—" from table is sent as null to avoid Carbon error
    const sanitizeTime = v => (v && v !== '—' && v.trim() !== '') ? v : null;

    fetch(`${ATTENDANCE_UPDATE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            date,
            check_in:  sanitizeTime(check_in),
            check_out: sanitizeTime(check_out)
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success', 3000);
            closeModal('modalEditAtt');
            loadAttendanceData();
            updateStats();
        } else {
            showToast(data.message || 'Failed to update data', 'error', 3000);
        }
    })
    .catch(() => showToast('System error occurred', 'error', 3000));
}

/* =========================================================
   MODAL — DELETE
   ========================================================= */

function openDeleteModal(id) {
    const r = _attFull.find(a => a.id === id);
    if (!r) return;

    const msgEl  = document.getElementById('delAttMsg');
    const eaIdEl = document.getElementById('eaId');
    if (msgEl)  msgEl.textContent  = `Delete attendance data for ${r.name}?`;
    if (eaIdEl) eaIdEl.value       = id;

    openModal('modalDelAtt');
}

function execDelAtt() {
    const id = document.getElementById('eaId')?.value;
    if (!id) return;

    fetch(`${ATTENDANCE_DELETE_URL}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success', 3000);
            closeModal('modalDelAtt');
            loadAttendanceData();
            updateStats();
        } else {
            showToast(data.message || 'Failed to delete data', 'error', 3000);
        }
    })
    .catch(() => showToast('System error occurred', 'error', 3000));
}

/* =========================================================
   EXPORT
   ========================================================= */

function exportAtt() {
    if (!_attFull || _attFull.length === 0) {
        showToast('No attendance data available to export', 'error', 3000);
        return;
    }
    showToast('Processing export...', 'info', 2000);
}

/* =========================================================
   INITIALIZATION
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
    if (typeof setDate === 'function') setDate();

    loadAttendanceData();
    updateStats();
    loadEmployees();

    const filterDate   = document.getElementById('filterDate');
    const filterStatus = document.getElementById('filterAttStatus');
    const filterDiv    = document.getElementById('filterAttDiv');
    const searchAtt    = document.getElementById('searchAtt');

    if (filterDate) {
        filterDate.addEventListener('change', () => {
            loadAttendanceData();
            updateStats();
        });
    }
    if (filterStatus) filterStatus.addEventListener('change', filterAtt);
    if (filterDiv)    filterDiv.addEventListener('change', filterAtt);
    if (searchAtt)    searchAtt.addEventListener('input', filterAtt);

    // Saat tanggal di modal Add berubah, terapkan pembatasan checkout
    const aaDateInput = document.getElementById('aaDate');
    if (aaDateInput) {
        aaDateInput.addEventListener('change', applyCheckoutRestriction);
    }

    // Close employee dropdown when clicking outside
    document.addEventListener('click', e => {
        if (!e.target.closest('.emp-dropdown') && !e.target.closest('#aaName')) {
            document.querySelectorAll('.emp-dropdown').forEach(d => d.style.display = 'none');
        }
    });

    // Auto-refresh stats every 15 seconds
    setInterval(updateStats, 15000);
});