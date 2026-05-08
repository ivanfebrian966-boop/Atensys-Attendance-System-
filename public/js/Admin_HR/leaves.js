let deleteTargetId = null;

// ─── Pagination ──────────────────────────────
let currentLeavePage = 1;
const LEAVE_PAGE_SIZE = 10;
let _allLeaves = [];

function changeLeavePage(page) {
    currentLeavePage = page;
    renderLeaves();
}

function renderLeavePagination(totalItems) {
    const totalPages = Math.ceil(totalItems / LEAVE_PAGE_SIZE) || 1;
    let pag = document.getElementById('leavePagination');
    if (!pag) {
        const info = document.getElementById('leaveInfo');
        pag = document.createElement('div');
        pag.id = 'leavePagination';
        pag.className = 'pagination flex gap-1 items-center justify-end w-full';
        info.parentNode.classList.add('flex', 'justify-between', 'items-center', 'w-full');
        info.parentNode.appendChild(pag);
    }
    
    if (totalPages <= 1) {
        pag.innerHTML = '';
        return;
    }
    
    let html = '';
    html += `<button class="px-3 py-1 border rounded text-xs ${currentLeavePage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeLeavePage(${currentLeavePage - 1})" ${currentLeavePage === 1 ? 'disabled' : ''}>Prev</button>`;
    
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentLeavePage - 1 && i <= currentLeavePage + 1)) {
            html += `<button class="px-3 py-1 border rounded text-xs ${i === currentLeavePage ? 'bg-indigo-50 text-indigo-600 border-indigo-200 font-bold' : 'hover:bg-slate-50'}" onclick="changeLeavePage(${i})">${i}</button>`;
        } else if (i === currentLeavePage - 2 || i === currentLeavePage + 2) {
            html += `<span class="px-2 py-1 text-slate-400">...</span>`;
        }
    }
    
    html += `<button class="px-3 py-1 border rounded text-xs ${currentLeavePage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeLeavePage(${currentLeavePage + 1})" ${currentLeavePage === totalPages ? 'disabled' : ''}>Next</button>`;
    pag.innerHTML = html;
}

// ─── Load Table ──────────────────────────────
async function loadLeaves() {
    const type   = document.getElementById('filterType').value;
    const status = document.getElementById('filterStatus').value;
    const search = document.getElementById('leaveSearch').value;
    const params = new URLSearchParams({ type, status, search });

    const tbody = document.getElementById('leaveBody');
    const empty = document.getElementById('leaveEmpty');
    const info  = document.getElementById('leaveInfo');

    try {
        const res  = await fetch(`${LEAVE_DATA_URL}?${params}`);
        const json = await res.json();
        
        if (!json.success) throw new Error(json.message);
        
        _allLeaves = json.data;
        currentLeavePage = 1; // reset page on new load
        renderLeaves();
    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center py-6 text-red-400 text-sm">Failed to load: ${e.message}</td></tr>`;
        empty.classList.add('hidden');
    }
}

function renderLeaves() {
    const tbody = document.getElementById('leaveBody');
    const empty = document.getElementById('leaveEmpty');
    const info  = document.getElementById('leaveInfo');

    if (!_allLeaves || !_allLeaves.length) {
        tbody.innerHTML = '';
        empty.classList.remove('hidden');
        info.textContent = '0 records';
        renderLeavePagination(0);
        return;
    }

    empty.classList.add('hidden');
    info.textContent = `${_allLeaves.length} record(s)`;
    
    const totalPages = Math.ceil(_allLeaves.length / LEAVE_PAGE_SIZE) || 1;
    if (currentLeavePage > totalPages) currentLeavePage = totalPages;
    const start = (currentLeavePage - 1) * LEAVE_PAGE_SIZE;
    const paginated = _allLeaves.slice(start, start + LEAVE_PAGE_SIZE);

    tbody.innerHTML = paginated.map(row => `
            <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                             style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                            ${row.name.substring(0,2).toUpperCase()}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm" style="font-family:'Sora',sans-serif">${row.name}</p>
                            <p class="text-xs text-slate-400">${row.division}</p>
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4">
                    <span class="status-badge ${row.type === 'Sick' ? 'type-sick' : 'type-permission'}">
                        ${row.type === 'Sick' ? '🏥 Sick' : '🏖️ Permission'}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <p class="text-xs font-bold text-slate-700 mb-1">${row.leave_category || row.sick_category || '-'}</p>
                    ${row.information && row.information !== '-' 
                        ? `<p class="info-cell text-[10px] text-slate-500" title="${row.information}">${row.information}</p>` 
                        : ''}
                </td>
                <td class="py-3 px-4 text-sm text-slate-600 whitespace-nowrap">
                    ${row.start_date} — ${row.end_date}
                </td>
                <td class="py-3 px-4">
                    <span class="days-badge">${row.days}d</span>
                </td>
                <td class="py-3 px-4">
                    ${row.file
                        ? `<a href="${row.file}" target="_blank" class="file-link">
                               <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                               PDF
                           </a>`
                        : `<span class="text-slate-300 text-sm">—</span>`
                    }
                </td>
                <td class="py-3 px-4 text-xs text-slate-400 whitespace-nowrap">${row.submitted}</td>
                <td class="py-3 px-4">${statusBadge(row.status)}</td>
                <td class="py-3 px-4">
                    ${row.status === 'Rejected' && row.reject_reason 
                        ? `<p class="text-[10px] text-red-400 font-semibold italic" title="${row.reject_reason}">${row.reject_reason}</p>` 
                        : '<span class="text-slate-300">—</span>'}
                </td>
                <td class="py-3 px-4">
                    <div class="flex justify-end items-center gap-2 flex-wrap">
                        <button class="btn-approve flex items-center justify-center rounded-lg hover:bg-indigo-100 transition" style="color:#6366f1; background:#eef2ff; border-color:#c7d2fe; padding: 6px; width:32px; height:32px;" onclick='openManageModal(${JSON.stringify(row).replace(/'/g, "&#39;")})' title="Manage">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button class="btn-delete flex items-center justify-center rounded-lg hover:bg-red-50 transition" style="padding: 6px; width:32px; height:32px;" onclick="openDeleteModal(${row.id})" title="Delete">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
        
        renderLeavePagination(_allLeaves.length);
}

function statusBadge(status) {
    const map = { Pending:'badge-pending', Approved:'badge-approved', Rejected:'badge-rejected' };
    const icon = { Pending:'⏳', Approved:'✅', Rejected:'❌' };
    const label = status === 'Approved' ? 'Approved' : status;
    return `<span class="px-3 py-1 rounded-full text-xs font-bold ${map[status]||'bg-slate-100 text-slate-600'}">${icon[status]??''} ${label}</span>`;
}

// ─── Quick Approve / Reject ──────────────────
let approveTargetId = null;

function doApprove(id) {
    approveTargetId = id;
    document.getElementById('approveConfirmModal').style.display = 'flex';
}

function closeApproveModal() {
    document.getElementById('approveConfirmModal').style.display = 'none';
}

async function execApprove() {
    if (!approveTargetId) return;
    const btn = document.getElementById('confirmApproveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Approving...';

    try {
        const res  = await fetch(LEAVE_APPROVE(approveTargetId), { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) {
            closeApproveModal();
            setTimeout(() => window.location.reload(), 800);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'Yes, Approve';
        }
    } catch(e) { 
        showToast('Network error: ' + e.message, 'error');
        btn.disabled = false;
        btn.innerHTML = 'Yes, Approve';
    }
}

// Rejection with Modal
function doReject(id) {
    document.getElementById('rejectId').value = id;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

async function submitRejectForm(e) {
    e.preventDefault();
    const id = document.getElementById('rejectId').value;
    const reason = document.getElementById('rejectReason').value;
    const btn = document.getElementById('rejectSubmitBtn');

    btn.disabled = true;
    btn.textContent = 'Processing...';

    try {
        const res = await fetch(LEAVE_REJECT(id), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ reject_reason: reason })
        });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) {
            closeRejectModal();
            setTimeout(() => window.location.reload(), 800);
        }
    } catch(e) {
        showToast('Network error: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Confirm Reject';
    }
}

// ─── Manage ──────────────────────────────────
function openManageModal(row) {
    document.getElementById('manageId').value = row.id;
    document.getElementById('manageEmployeeName').textContent = `${row.name} - ${row.type}`;
    document.getElementById('manageStart').value = row.start_raw;
    document.getElementById('manageEnd').value = row.end_raw;
    document.getElementById('manageStatus').value = row.status;
    document.getElementById('manageRejectReason').value = row.reject_reason || '';
    
    toggleManageRejectReason();
    document.getElementById('manageModal').style.display = 'flex';
}
function toggleManageRejectReason() {
    const status = document.getElementById('manageStatus').value;
    const group = document.getElementById('manageRejectReasonGroup');
    group.style.display = status === 'Rejected' ? 'block' : 'none';
}
function closeManageModal() {
    document.getElementById('manageModal').style.display = 'none';
}

async function submitManageForm(e) {
    e.preventDefault();
    const btn = document.getElementById('manageSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    const id = document.getElementById('manageId').value;
    const start_date = document.getElementById('manageStart').value;
    const completion_date = document.getElementById('manageEnd').value;
    const status = document.getElementById('manageStatus').value;
    const reject_reason = status === 'Rejected' ? document.getElementById('manageRejectReason').value : null;

    try {
        const url = `${LEAVE_BASE_URL}/${id}`;
        const res = await fetch(url, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ start_date, completion_date, status, reject_reason })
        });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) {
            closeManageModal();
            setTimeout(() => window.location.reload(), 800); // Reload to update Pending table
        }
    } catch(err) {
        showToast('Network error: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Changes';
    }
}

// ─── Delete ──────────────────────────────────
function openDeleteModal(id) { deleteTargetId = id; document.getElementById('deleteConfirmModal').classList.add('open'); }
function closeDeleteModal()   { document.getElementById('deleteConfirmModal').classList.remove('open'); deleteTargetId = null; }
async function execDelete() {
    if (!deleteTargetId) return;
    const id = deleteTargetId;
    closeDeleteModal();
    try {
        const res  = await fetch(LEAVE_DELETE(id), { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Content-Type':'application/json'} });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) setTimeout(() => window.location.reload(), 800);
    } catch(e) { showToast('Network error: ' + e.message, 'error'); }
}

document.addEventListener('DOMContentLoaded', loadLeaves);
