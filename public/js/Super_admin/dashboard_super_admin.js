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
    const titles = { dashboard: 'Dashboard', employees: 'Akun Karyawan', admins: 'Akun Admin HR' };
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
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(val) ? '' : 'none';
    });
}

function filterByStatus(select, tableId) {
    const val = select.value;
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        if (!val) { row.style.display = ''; return; }
        const status = row.dataset.status || '';
        row.style.display = status === val ? '' : 'none';
    });
}

// ===== Edit handlers =====
function openEditEmployee(btn) {
    const row = btn.closest('tr');
    const name = row.querySelector('.font-semibold').textContent;
    document.getElementById('editName').value = name;
    openModal('modalEditEmployee');
}
function openEditAdmin(btn) {
    openModal('modalEditEmployee');
}

// ===== Actions =====
function resetPassword() {
    closeAllDropdowns();
    showToast('🔑', 'Link reset password telah dikirim ke email!');
}
function toggleStatus(btn) {
    closeAllDropdowns();
    showToast('🔄', 'Status akun berhasil diperbarui!');
}
function confirmDelete(btn) {
    closeAllDropdowns();
    if (confirm('Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.')) {
        const row = btn.closest('tr');
        row.style.transition = 'opacity 0.3s, transform 0.3s';
        row.style.opacity = '0';
        row.style.transform = 'translateX(20px)';
        setTimeout(() => row.remove(), 300);
        showToast('🗑', 'Akun berhasil dihapus!');
    }
}
function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu.open').forEach(d => d.classList.remove('open'));
}

// ===== Toast =====
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