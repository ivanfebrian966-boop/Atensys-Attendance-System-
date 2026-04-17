/**
 * Attendance Management System - Main JS File
 * Handles attendance data loading, filtering, rendering and API integration
 */

let _attFull = [];

/* ========== API INTEGRATION ========== */

function loadAttendanceData() {
    const date = document.getElementById('filterDate')?.value || new Date().toISOString().split('T')[0];
    
    fetch(`${ATTENDANCE_DATA_URL}?date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                _attFull = data.data;
                filterAtt();
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateStats() {
    const date = document.getElementById('filterDate')?.value || new Date().toISOString().split('T')[0];
    
    fetch(`${ATTENDANCE_STATS_URL}?date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const s = data.data;
                if (document.getElementById('sTotal')) document.getElementById('sTotal').textContent = s.total || 0;
                if (document.getElementById('sPresent')) document.getElementById('sPresent').textContent = s.present || 0;
                if (document.getElementById('sAbsent')) document.getElementById('sAbsent').textContent = s.absent || 0;
                if (document.getElementById('sLate')) document.getElementById('sLate').textContent = s.late || 0;
                if (document.getElementById('sSick')) document.getElementById('sSick').textContent = s.sick || 0;
                if (document.getElementById('sPerm')) document.getElementById('sPerm').textContent = s.permission || 0;
            }
        })
        .catch(error => console.error('Error:', error));
}

/* ========== FILTER & RENDER ========== */

function filterAtt() {
    if (!_attFull) {
        renderAttendanceTable([]);
        return;
    }

    const search = (document.getElementById('searchAtt')?.value || '').toLowerCase();
    const status = document.getElementById('filterAttStatus')?.value || '';
    const div = document.getElementById('filterAttDiv')?.value || '';
    
    let filtered = _attFull;
    
    if (search) {
        filtered = filtered.filter(r => 
            (r.name && r.name.toLowerCase().includes(search)) ||
            (r.division && r.division.toLowerCase().includes(search))
        );
    }
    if (status) filtered = filtered.filter(r => r.status === status);
    if (div) filtered = filtered.filter(r => r.division === div);
    
    renderAttendanceTable(filtered);
}

function renderAttendanceTable(data) {
    const body = document.getElementById('attBody');
    if (!body) return;
    
    const empty = document.getElementById('attEmpty');
    const info = document.getElementById('attInfo');
    
    if (!data || !data.length) {
        body.innerHTML = '';
        if (empty) empty.classList.remove('hidden');
        if (info) info.textContent = '0 data';
        return;
    }
    
    if (empty) empty.classList.add('hidden');
    if (info) info.textContent = `${data.length} data`;
    
    body.innerHTML = data.map(r => `
        <tr class="table-row">
            <td><span class="font-semibold text-slate-800 text-sm">${r.name || '—'}</span></td>
            <td class="hidden sm:table-cell text-slate-600 text-sm">${r.division || '—'}</td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.date || '—'}</td>
            <td><span class="status-badge ${getStatusClass(r.status)}">${getStatusIcon(r.status)} ${r.status}</span></td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.check_in || '—'}</td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.check_out || '—'}</td>
            <td class="hidden lg:table-cell text-slate-600 text-sm">${r.duration || '—'}</td>
            <td class="text-right text-xs text-slate-500">${r.notes || '—'}</td>
            <td class="text-right">
                <button class="action-btn action-btn-edit" onclick="editAtt(${r.id})" title="Edit">✏️</button>
                <button class="action-btn action-btn-delete" onclick="openDeleteModal(${r.id})" title="Hapus">🗑️</button>
            </td>
        </tr>
    `).join('');
}

/* ========== UTILITY FUNCTIONS ========== */

function getStatusClass(status) {
    const classes = {
        'Present': 'status-present',
        'Absent': 'status-absent',
        'Late': 'status-late',
        'Sick': 'status-sick',
        'Permission': 'status-permission'
    };
    return classes[status] || '';
}

function getStatusIcon(status) {
    const icons = {
        'Present': '✅',
        'Absent': '❌',
        'Late': '⏰',
        'Sick': '🏥',
        'Permission': '📋'
    };
    return icons[status] || '—';
}

/* ========== MODAL FUNCTIONS ========== */

function openAddAttModal() {
    const today = new Date().toISOString().split('T')[0];
    if (document.getElementById('aaName')) document.getElementById('aaName').value = '';
    if (document.getElementById('aaDate')) document.getElementById('aaDate').value = today;
    if (document.getElementById('aaStatus')) document.getElementById('aaStatus').value = 'Present';
    if (document.getElementById('aaCheckIn')) document.getElementById('aaCheckIn').value = '';
    if (document.getElementById('aaCheckOut')) document.getElementById('aaCheckOut').value = '';
    if (document.getElementById('aaNote')) document.getElementById('aaNote').value = '';
    clearAllErrors();
    openModal('modalAddAtt');
}

function saveAtt() {
    const nameEl = document.getElementById('aaName');
    const dateEl = document.getElementById('aaDate');
    if (!nameEl || !dateEl) return;
    
    const name = nameEl.value.trim();
    const date = dateEl.value;
    
    if (!name) {
        setErr('aaName', 'eaaName', 'Nama wajib diisi');
        return;
    }
    if (!date) {
        setErr('aaDate', 'eaaDate', 'Tanggal wajib diisi');
        return;
    }
    
    showToast('✅', 'Data absensi berhasil ditambahkan', 3000);
    closeModal('modalAddAtt');
    loadAttendanceData();
}

function editAtt(id) {
    const r = _attFull.find(a => a.id === id);
    if (!r) return;
    
    if (document.getElementById('eaId')) document.getElementById('eaId').value = id;
    if (document.getElementById('eaName')) document.getElementById('eaName').value = r.name || '';
    if (document.getElementById('eaDate')) document.getElementById('eaDate').value = r.date || '';
    if (document.getElementById('eaStatus')) document.getElementById('eaStatus').value = r.status || 'Present';
    if (document.getElementById('eaCheckIn')) document.getElementById('eaCheckIn').value = r.check_in || '';
    if (document.getElementById('eaCheckOut')) document.getElementById('eaCheckOut').value = r.check_out || '';
    if (document.getElementById('eaNote')) document.getElementById('eaNote').value = r.notes || '';
    
    clearAllErrors();
    openModal('modalEditAtt');
}

function updateAtt() {
    const idEl = document.getElementById('eaId');
    if (!idEl) return;
    
    showToast('✅', 'Data absensi berhasil diperbarui', 3000);
    closeModal('modalEditAtt');
    loadAttendanceData();
}

function openDeleteModal(id) {
    const r = _attFull.find(a => a.id === id);
    if (!r) return;
    const msgEl = document.getElementById('delAttMsg');
    const eaIdEl = document.getElementById('eaId');
    if (msgEl) msgEl.textContent = `Hapus data ${r.name}?`;
    if (eaIdEl) eaIdEl.value = id;
    openModal('modalDelAtt');
}

function execDelAtt() {
    showToast('✅', 'Data absensi berhasil dihapus', 3000);
    closeModal('modalDelAtt');
    loadAttendanceData();
}

function exportAtt() {
    showToast('📥', 'Sedang memproses export...', 2000);
}

/* ========== INITIALIZATION ========== */

document.addEventListener('DOMContentLoaded', () => {
    if (typeof setDate === 'function') setDate();
    loadAttendanceData();
    updateStats();
    
    const filterDate = document.getElementById('filterDate');
    const filterStatus = document.getElementById('filterAttStatus');
    const filterDiv = document.getElementById('filterAttDiv');
    const searchAtt = document.getElementById('searchAtt');
    
    if (filterDate) {
        filterDate.addEventListener('change', () => {
            loadAttendanceData();
            updateStats();
        });
    }
    if (filterStatus) filterStatus.addEventListener('change', filterAtt);
    if (filterDiv) filterDiv.addEventListener('change', filterAtt);
    if (searchAtt) searchAtt.addEventListener('input', filterAtt);
    
    setInterval(() => { updateStats(); }, 15000);
});