/**
 * Attendance Management System - Main JS File
 * Handles attendance data loading, filtering, rendering and API integration
 */

let _attFull = [];
let _employees = [];
let currentAttPage = 1;
const ATT_PAGE_SIZE = 10;

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
                if (document.getElementById('sPresent')) document.getElementById('sPresent').textContent = s.present || 0;
                if (document.getElementById('sLate')) document.getElementById('sLate').textContent = s.late || 0;
                if (document.getElementById('sSick')) document.getElementById('sSick').textContent = s.sick || 0;
                if (document.getElementById('sPerm')) document.getElementById('sPerm').textContent = s.permission || 0;
            }
        })
        .catch(error => console.error('Error:', error));
}

function loadEmployees() {
    fetch('/admin-hr/attendance/employees')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                _employees = data.data;
            }
        })
        .catch(error => console.error('Error loading employees:', error));
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
    
    currentAttPage = 1; // Reset to page 1 on filter
    renderAttendanceTable(filtered);
}

function changeAttPage(page) {
    currentAttPage = page;
    // Re-apply filter without resetting page
    const search = (document.getElementById('searchAtt')?.value || '').toLowerCase();
    const status = document.getElementById('filterAttStatus')?.value || '';
    const div = document.getElementById('filterAttDiv')?.value || '';
    
    let filtered = _attFull || [];
    if (search) {
        filtered = filtered.filter(r => 
            (r.name && r.name.toLowerCase().includes(search)) ||
            (r.division && r.division.toLowerCase().includes(search))
        );
    }
    if (status) filtered = filtered.filter(r => r.status === status);
    if (div) filtered = filtered.filter(r => r.division === div);
    
    renderAttendanceTable(filtered, false);
}

function renderAttPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / ATT_PAGE_SIZE) || 1;
    let pag = document.getElementById('attPagination');
    if (!pag) {
        const info = document.getElementById('attInfo');
        if(info && info.parentNode) {
            pag = document.createElement('div');
            pag.id = 'attPagination';
            pag.className = 'pagination flex gap-1 items-center justify-end w-full';
            info.parentNode.classList.add('flex', 'justify-between', 'items-center', 'w-full');
            info.parentNode.appendChild(pag);
        } else {
            return;
        }
    }
    
    if (totalPages <= 1) {
        pag.innerHTML = '';
        return;
    }
    
    let html = '';
    html += `<button class="px-3 py-1 border rounded text-xs ${currentAttPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeAttPage(${currentAttPage - 1})" ${currentAttPage === 1 ? 'disabled' : ''}>Prev</button>`;
    
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentAttPage - 1 && i <= currentAttPage + 1)) {
            html += `<button class="px-3 py-1 border rounded text-xs ${i === currentAttPage ? 'bg-indigo-50 text-indigo-600 border-indigo-200 font-bold' : 'hover:bg-slate-50'}" onclick="changeAttPage(${i})">${i}</button>`;
        } else if (i === currentAttPage - 2 || i === currentAttPage + 2) {
            html += `<span class="px-2 py-1 text-slate-400">...</span>`;
        }
    }
    
    html += `<button class="px-3 py-1 border rounded text-xs ${currentAttPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeAttPage(${currentAttPage + 1})" ${currentAttPage === totalPages ? 'disabled' : ''}>Next</button>`;
    pag.innerHTML = html;
}

function renderAttendanceTable(data, resetPage = true) {
    const body = document.getElementById('attBody');
    if (!body) return;
    
    const empty = document.getElementById('attEmpty');
    const info = document.getElementById('attInfo');
    
    if (!data || !data.length) {
        body.innerHTML = '';
        if (empty) empty.classList.remove('hidden');
        if (info) info.textContent = '0 data';
        renderAttPagination(0);
        return;
    }
    
    if (empty) empty.classList.add('hidden');
    if (info) info.textContent = `${data.length} data`;
    
    if (resetPage) currentAttPage = 1;
    const totalPages = Math.ceil(data.length / ATT_PAGE_SIZE) || 1;
    if (currentAttPage > totalPages) currentAttPage = totalPages;
    const start = (currentAttPage - 1) * ATT_PAGE_SIZE;
    const paginated = data.slice(start, start + ATT_PAGE_SIZE);
    
    body.innerHTML = paginated.map(r => `
        <tr class="table-row">
            <td><span class="font-semibold text-slate-800 text-sm">${r.name || '—'}</span></td>
            <td class="hidden sm:table-cell text-slate-600 text-sm">${r.division || '—'}</td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.date || '—'}</td>
            <td><span class="status-badge ${getStatusClass(r.status)}">${getStatusIcon(r.status)} ${r.status}</span></td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.check_in || '—'}</td>
            <td class="hidden md:table-cell text-slate-600 text-sm">${r.check_out || '—'}</td>
            <td class="hidden lg:table-cell text-slate-600 text-sm">${r.duration || '—'}</td>
            <td class="text-slate-600 text-sm">${r.information || '—'}</td>
            <td class="text-right">
                <button class="action-btn action-btn-edit" onclick="editAtt(${r.id})" title="Edit">✏️</button>
                <button class="action-btn action-btn-delete" onclick="openDeleteModal(${r.id})" title="Hapus">🗑️</button>
            </td>
        </tr>
    `).join('');
    
    renderAttPagination(data.length);
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

function filterEmployeeDropdown(inputId, hiddenId, dropdownId) {
    const input = document.getElementById(inputId);
    const dropdown = document.getElementById(dropdownId);
    const hidden = document.getElementById(hiddenId);
    if (!input || !dropdown) return;

    const val = input.value.toLowerCase();
    hidden.value = ''; // Reset ID if user types manually

    if (val.length < 1) {
        dropdown.style.display = 'none';
        return;
    }

    const filtered = _employees.filter(e => e.name.toLowerCase().includes(val) || e.nip.includes(val));
    
    if (filtered.length === 0) {
        dropdown.innerHTML = '<li class="p-3 text-slate-400 text-xs text-center">Employee not found</li>';
    } else {
        dropdown.innerHTML = filtered.map(e => `
            <li onclick="selectEmployee('${e.name}', '${e.nip}', '${inputId}', '${hiddenId}', '${dropdownId}')">
                <p class="font-bold text-sm">${e.name}</p>
                <p class="text-xs text-slate-400">${e.nip} • ${e.position}</p>
            </li>
        `).join('');
    }
    dropdown.style.display = 'block';
}

function selectEmployee(name, nip, inputId, hiddenId, dropdownId) {
    document.getElementById(inputId).value = name;
    document.getElementById(hiddenId).value = nip;
    document.getElementById(dropdownId).style.display = 'none';
}

/* ========== MODAL FUNCTIONS ========== */

function openAddAttModal() {
    const today = new Date().toISOString().split('T')[0];
    if (document.getElementById('aaName')) document.getElementById('aaName').value = '';
    if (document.getElementById('aaDate')) document.getElementById('aaDate').value = today;
    if (document.getElementById('aaStatus')) document.getElementById('aaStatus').value = 'Present';
    if (document.getElementById('aaCheckIn')) document.getElementById('aaCheckIn').value = '';
    if (document.getElementById('aaCheckOut')) document.getElementById('aaCheckOut').value = '';
    if (document.getElementById('aaInfo')) document.getElementById('aaInfo').value = '';
    clearAllErrors();
    openModal('modalAddAtt');
}

function saveAtt() {
    const nip = document.getElementById('aaEmpId')?.value;
    const date = document.getElementById('aaDate')?.value;
    const status = document.getElementById('aaStatus')?.value;
    const check_in = document.getElementById('aaCheckIn')?.value;
    const check_out = document.getElementById('aaCheckOut')?.value;
    const information = document.getElementById('aaInfo')?.value;
    
    if (!nip) {
        setErr('aaName', 'eaaName', 'Pilih karyawan dari dropdown');
        return;
    }
    if (!date) {
        setErr('aaDate', 'eaaDate', 'Tanggal wajib diisi');
        return;
    }
    
    const formData = {
        nip, date, status, check_in, check_out
    };

    fetch(ATTENDANCE_STORE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅', data.message, 3000);
            closeModal('modalAddAtt');
            loadAttendanceData();
            updateStats();
        } else {
            showToast('❌', data.message || 'Gagal menyimpan data', 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌', 'Terjadi kesalahan sistem', 3000);
    });
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
    if (document.getElementById('eaInformation')) document.getElementById('eaInformation').value = r.information === '—' ? '' : (r.information || '');
    
    toggleInfoWrap('ea', r.status);
    
    clearAllErrors();
    openModal('modalEditAtt');
}

function updateAtt() {
    const id = document.getElementById('eaId')?.value;
    const date = document.getElementById('eaDate')?.value;
    const status = document.getElementById('eaStatus')?.value;
    const check_in = document.getElementById('eaCheckIn')?.value;
    const check_out = document.getElementById('eaCheckOut')?.value;
    const information = document.getElementById('eaInformation')?.value;
    
    if (!id || !date) return;
    
    const formData = {
        date, status, check_in, check_out, information
    };

    fetch(`${ATTENDANCE_UPDATE_URL}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅', data.message, 3000);
            closeModal('modalEditAtt');
            loadAttendanceData();
            updateStats();
        } else {
            showToast('❌', data.message || 'Gagal memperbarui data', 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌', 'Terjadi kesalahan sistem', 3000);
    });
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
    const id = document.getElementById('eaId')?.value;
    if (!id) return;

    fetch(`${ATTENDANCE_DELETE_URL}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅', data.message, 3000);
            closeModal('modalDelAtt');
            loadAttendanceData();
            updateStats();
        } else {
            showToast('❌', data.message || 'Gagal menghapus data', 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌', 'Terjadi kesalahan sistem', 3000);
    });
}

function exportAtt() {
    showToast('📥', 'Sedang memproses export...', 2000);
}

/* ========== INITIALIZATION ========== */

document.addEventListener('DOMContentLoaded', () => {
    if (typeof setDate === 'function') setDate();
    loadAttendanceData();
    updateStats();
    loadEmployees();
    
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
    
    // Close dropdowns on outside click
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.emp-dropdown') && !e.target.closest('.form-input')) {
            document.querySelectorAll('.emp-dropdown').forEach(d => d.style.display = 'none');
        }
    });

    setInterval(() => { updateStats(); }, 15000);

    const eaStatus = document.getElementById('eaStatus');
    if (eaStatus) {
        eaStatus.addEventListener('change', (e) => toggleInfoWrap('ea', e.target.value));
    }
});

function toggleInfoWrap(prefix, status) {
    const wrap = document.getElementById(`${prefix}InfoWrap`);
    if (wrap) {
        if (status === 'Sick' || status === 'Permission') {
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
        }
    }
}


// Add listeners for status change
document.addEventListener('DOMContentLoaded', () => {
    const aaStatus = document.getElementById('aaStatus');
    const eaStatus = document.getElementById('eaStatus');
    
    if (aaStatus) aaStatus.addEventListener('change', (e) => toggleInfoWrap('aa', e.target.value));
    if (eaStatus) eaStatus.addEventListener('change', (e) => toggleInfoWrap('ea', e.target.value));
});