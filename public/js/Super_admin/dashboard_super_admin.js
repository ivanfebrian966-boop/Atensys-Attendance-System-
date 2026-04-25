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
    if (confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')) {
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

function showToast(icon, msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastIcon').textContent = icon;
    document.getElementById('toastMsg').textContent = msg;
    toast.style.opacity = '1';
    toast.style.pointerEvents = 'all';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.pointerEvents = 'none';
    }, 3000);
}