// ===== Loader =====

    window.addEventListener('load', function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            // Memberikan efek fade out
            loader.style.transition = 'opacity 0.5s ease';
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500); // Sesuaikan dengan durasi transition
        }
    });

// ===== Tab switching =====
function showTab(tab, el) {
    event && event.preventDefault();

    // hide all
    document.querySelectorAll('[id^="tab-"]').forEach(t => t.classList.add('hidden'));
    document.getElementById('tab-' + tab).classList.remove('hidden');

    // update nav active
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    if (el) el.classList.add('active');

    // page title
    const titles = { dashboard: 'Dashboard', employees: 'Employee Accounts', admins: 'Admin HR Accounts', divisions: 'Division Data', profile: 'Profile' };
    document.getElementById('pageTitle').textContent = titles[tab] || 'Dashboard';

    closeSidebar();
}

// ===== Sidebar mobile =====
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}

// ===== Modal =====
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

// ===== Dropdown =====
function toggleDropdown(btn) {
    // close all others
    document.querySelectorAll('.dropdown-menu.open').forEach(d => {
        if (d !== btn.nextElementSibling) d.classList.remove('open');
    });
    btn.nextElementSibling.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.matches('.btn-ghost') || !e.target.textContent.trim().includes('⋮')) {
        document.querySelectorAll('.dropdown-menu.open').forEach(d => d.classList.remove('open'));
    }
});

// ===== Search filter =====
function filterTable(input, tableId) {
    const val = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(val)) {
            row.classList.remove('filtered-out');
        } else {
            row.classList.add('filtered-out');
        }
    });
    if (table.updatePagination) table.updatePagination();
    else {
        rows.forEach(row => row.style.display = row.classList.contains('filtered-out') ? 'none' : '');
    }
}

function filterByStatus(select, tableId) {
    const val = select.value;
    const table = document.getElementById(tableId);
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        if (!val) { 
            row.classList.remove('filtered-out-status');
        } else {
            const status = row.dataset.status || '';
            if (status === val) row.classList.remove('filtered-out-status');
            else row.classList.add('filtered-out-status');
        }
        
        // combine filters
        if (row.classList.contains('filtered-out-status') || row.classList.contains('filtered-out')) {
            row.style.display = 'none';
        } else {
            row.style.display = '';
        }
    });
    if (table.updatePagination) table.updatePagination();
}

// ===== Edit handlers =====
function openEditEmployee(btn) {
    const row = btn.closest('tr');
    const id = row.dataset.id;
    const nip = row.dataset.nip;
    const name = row.dataset.name;
    const email = row.dataset.email;
    const div = row.dataset.division;
    const jabatan = row.dataset.jabatan;
    const no_hp = row.dataset.no_hp;
    const alamat = row.dataset.alamat;
    const status = row.dataset.status;
    const gender = row.dataset.gender;

    const form = document.getElementById('formEditEmployee');
    form.action = `/super-admin/employee/${id}`;
    
    document.getElementById('edit_emp_nip').value = nip;
    document.getElementById('edit_emp_name').value = name;
    document.getElementById('edit_emp_email').value = email;
    document.getElementById('edit_emp_division').value = div;
    document.getElementById('edit_emp_jabatan').value = jabatan;
    document.getElementById('edit_emp_no_hp').value = no_hp;
    document.getElementById('edit_emp_alamat').value = alamat;
    document.getElementById('edit_emp_status').value = status;
    if (document.getElementById('edit_emp_gender')) document.getElementById('edit_emp_gender').value = gender || 'Male';

    openModal('modalEditEmployee');
}

function openEditAdmin(btn) {
    const row = btn.closest('tr');
    const id = row.dataset.id;
    const name = row.dataset.name;
    const email = row.dataset.email;
    const nip = row.dataset.nip;
    const phone = row.dataset.phone;
    const address = row.dataset.address;
    const status = row.dataset.status;
    const division = row.dataset.division;
    const position = row.dataset.position;
    const gender = row.dataset.gender;

    const form = document.getElementById('formEditAdmin');
    form.action = `/super-admin/hr-admin/${id}`;
    
    document.getElementById('edit_admin_name').value = name;
    document.getElementById('edit_admin_email').value = email;
    document.getElementById('edit_admin_nip').value = nip || '';
    document.getElementById('edit_admin_phone').value = phone || '';
    document.getElementById('edit_admin_address').value = address || '';
    document.getElementById('edit_admin_status').value = status || 'Aktif';
    document.getElementById('edit_admin_division').value = division || '';
    document.getElementById('edit_admin_position').value = position || '';
    if (document.getElementById('edit_admin_gender')) document.getElementById('edit_admin_gender').value = gender || 'Male';

    openModal('modalEditAdmin');
}

function openEditDivision(id, name) {
    const form = document.getElementById('formEditDivision');
    form.action = `/super-admin/division/${id}`;
    document.getElementById('edit_division_name').value = name;
    openModal('modalEditDivision');
}


// ===== Actions =====
function confirmDelete(btn, type) {
    closeAllDropdowns();
    if (confirm('Are you sure you want to delete this ' + type + '? This action cannot be undone.')) {
        const row = btn.closest('tr');
        const id = row.dataset.id;
        const form = document.getElementById('formDelete');
        
        if (type === 'employee') {
            form.action = `/super-admin/employee/${id}`;
        } else if (type === 'admin') {
            form.action = `/super-admin/hr-admin/${id}`;
        }
        
        form.submit();
    }
}
function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu.open').forEach(d => d.classList.remove('open'));
}

// ===== Toast =====
// ===== Realtime Date =====
function updateRealtimeDate() {
    const el = document.getElementById('realtime-date');
    if (!el) return;

    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    
    const now = new Date();
    const dayName = days[now.getDay()];
    const day = String(now.getDate()).padStart(2, '0');
    const monthName = months[now.getMonth()];
    const year = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    el.textContent = `${dayName}, ${day} ${monthName} ${year} | ${hours}:${minutes}:${seconds}`;
}

document.addEventListener('DOMContentLoaded', () => {
    updateRealtimeDate();
    setInterval(updateRealtimeDate, 1000);

    // Initialize Pagination for > 10 items
    initPagination('employee-table', 10);
    initPagination('division-table', 10);
});

function initPagination(tableId, limit) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    
    if (allRows.length <= limit) return;
    
    let currentPage = 1;
    
    const paginationWrapper = document.createElement('div');
    paginationWrapper.className = 'flex items-center justify-end gap-1 px-6 py-3';
    paginationWrapper.id = tableId + '-pagination';
    table.parentElement.appendChild(paginationWrapper);

    function update() {
        const activeRows = allRows.filter(r => 
            !r.classList.contains('filtered-out') && !r.classList.contains('filtered-out-status')
        );
        const totalPages = Math.ceil(activeRows.length / limit) || 1;
        
        if (currentPage > totalPages) currentPage = totalPages;
        
        allRows.forEach(r => r.style.display = 'none');
        
        const start = (currentPage - 1) * limit;
        const end = start + limit;
        
        activeRows.slice(start, end).forEach(r => r.style.display = '');
        
        renderLinks(totalPages);
    }
    
    function renderLinks(totalPages) {
        paginationWrapper.innerHTML = '';
        if (totalPages <= 1) return;
        
        const prev = document.createElement('button');
        prev.innerHTML = '«';
        prev.className = `px-2 py-1 rounded text-sm ${currentPage === 1 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-600 hover:bg-slate-100'}`;
        prev.onclick = () => { if(currentPage > 1) { currentPage--; update(); } };
        paginationWrapper.appendChild(prev);
        
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded text-sm ${i === currentPage ? 'bg-indigo-500 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100'}`;
            btn.onclick = () => { currentPage = i; update(); };
            paginationWrapper.appendChild(btn);
        }
        
        const next = document.createElement('button');
        next.innerHTML = '»';
        next.className = `px-2 py-1 rounded text-sm ${currentPage === totalPages ? 'text-slate-300 cursor-not-allowed' : 'text-slate-600 hover:bg-slate-100'}`;
        next.onclick = () => { if(currentPage < totalPages) { currentPage++; update(); } };
        paginationWrapper.appendChild(next);
    }
    
    table.updatePagination = () => {
        currentPage = 1;
        update();
    };
    
    update();
}

function showToast(message, type = 'success', duration = 4000) {
    let title = 'Success!';
    if (type === 'error') title = 'Failed!';
    else if (type === 'warning') title = 'Warning!';
    else if (type === 'info') title = 'Information!';

    if (['❌', '✅', '📥'].includes(message)) {
        message = type;
        if (arguments[0] === '❌') type = 'error';
        else if (arguments[0] === '✅') type = 'success';
        else if (arguments[0] === '📥') type = 'info';
        title = type === 'error' ? 'Failed!' : (type === 'info' ? 'Information!' : 'Success!');
    }

    let container = document.getElementById('attensys-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'attensys-toast-container';
        container.style.cssText = 'position:fixed;top:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:12px;pointer-events:none;';
        document.body.appendChild(container);
    }

    const toastId = 'toast-' + Date.now() + Math.random().toString(36).substr(2, 9);
    const toast = document.createElement('div');
    toast.id = toastId;

    const themes = {
        success: { bg: 'rgba(236, 253, 245, 0.95)', border: '#34d399', text: '#065f46', iconText: '#10b981', iconBg: '#d1fae5', icon: '✓' },
        error: { bg: 'rgba(254, 242, 242, 0.95)', border: '#f87171', text: '#991b1b', iconText: '#ef4444', iconBg: '#fee2e2', icon: '✕' },
        warning: { bg: 'rgba(255, 251, 235, 0.95)', border: '#fbbf24', text: '#92400e', iconText: '#f59e0b', iconBg: '#fef3c7', icon: '!' },
        info: { bg: 'rgba(239, 246, 255, 0.95)', border: '#60a5fa', text: '#1e40af', iconText: '#3b82f6', iconBg: '#dbeafe', icon: 'i' }
    };
    const t = themes[type] || themes.success;

    toast.style.cssText = 'width:380px;background:' + t.bg + ';backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.8);border-left:5px solid ' + t.border + ';border-radius:12px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.1),0 8px 10px -6px rgba(0,0,0,0.05);padding:16px;display:flex;align-items:flex-start;gap:14px;transform:translateX(120%);opacity:0;transition:transform 0.4s cubic-bezier(0.2,0.8,0.2,1),opacity 0.4s ease;pointer-events:auto;position:relative;overflow:hidden;';

    let html = '<div style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:' + t.iconBg + ';display:flex;align-items:center;justify-content:center;color:' + t.iconText + ';font-weight:800;font-size:14px;margin-top:2px;">' + t.icon + '</div>';
    html += '<div style="flex:1;min-width:0;">';
    html += '<h4 style="margin:0;font-size:15px;font-weight:700;color:' + t.text + ';font-family:\'Sora\',sans-serif;">' + title + '</h4>';
    if (message) {
        html += '<p style="margin:4px 0 0 0;font-size:13px;color:#475569;line-height:1.5;font-family:\'DM Sans\',sans-serif;">' + message + '</p>';
    }
    html += '</div>';
    html += '<button onclick="document.getElementById(\'' + toastId + '\').remove()" style="background:transparent;border:none;cursor:pointer;color:#94a3b8;font-size:16px;padding:2px;flex-shrink:0;margin-left:auto;">✕</button>';
    html += '<div style="position:absolute;bottom:0;left:0;height:3px;background:' + t.iconText + ';width:100%;transform-origin:left;animation:toast-progress ' + duration + 'ms linear forwards;"></div>';

    toast.innerHTML = html;

    if (!document.getElementById('attensys-toast-keyframes')) {
        const style = document.createElement('style');
        style.id = 'attensys-toast-keyframes';
        style.textContent = '@keyframes toast-progress { 0% { transform: scaleX(1); } 100% { transform: scaleX(0); } }';
        document.head.appendChild(style);
    }

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    });

    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            const el = document.getElementById(toastId);
            if (el) el.remove();
        }, 400);
    }, duration);
}