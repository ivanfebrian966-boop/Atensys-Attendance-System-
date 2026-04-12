/* SIDEBAR */
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

// Close sidebar on desktop resize
window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) {
        closeSidebar();
        document.body.style.overflow = '';
    }
});

/* DATE */
function updateRealtimeDate() {
    const el = document.getElementById('realtime-date');
    if (!el) return;

    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'];
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

/* TABLE FILTER */
function filterTable() {
    const search   = (document.getElementById('searchInput')?.value || '').toLowerCase();
    const status   = document.getElementById('statusFilter')?.value || '';
    const division = document.getElementById('divisionFilter')?.value || '';
    const rows     = document.querySelectorAll('#attendanceTable tbody .table-row');

    let visible = 0;

    rows.forEach(row => {
        const text     = row.textContent.toLowerCase();
        const rowStat  = (row.dataset.status   || '').trim();
        const rowDiv   = (row.dataset.division || '').trim();

        const matchSearch   = !search   || text.includes(search);
        const matchStatus   = !status   || rowStat === status;
        const matchDivision = !division || rowDiv  === division;

        if (matchSearch && matchStatus && matchDivision) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update info text
    const infoEl = document.getElementById('tableInfo');
    if (infoEl) {
        infoEl.textContent = visible > 0
            ? `Menampilkan ${visible} data`
            : 'Tidak ada data yang cocok';
    }

    // Show / hide empty state
    const emptyEl = document.getElementById('emptyState');
    if (emptyEl) {
        emptyEl.classList.toggle('hidden', visible > 0);
    }
}

/* EXPORT (demo) */
function exportTable() {
    showToast('📥', 'Mengekspor data ke CSV...');

    // Collect visible rows
    const rows   = document.querySelectorAll('#attendanceTable tbody .table-row');
    const lines  = ['Karyawan,NIP,Divisi,Status,Check In,Check Out'];

    rows.forEach(row => {
        if (row.style.display === 'none') return;
        const cells = row.querySelectorAll('td');
        const name  = cells[0]?.querySelector('.font-semibold')?.textContent.trim() || '';
        const nip   = cells[1]?.textContent.trim()   || '';
        const div   = cells[2]?.textContent.trim()   || '';
        const stat  = cells[3]?.textContent.trim()   || '';
        const ci    = cells[4]?.textContent.trim()   || '';
        const co    = cells[5]?.textContent.trim()   || '';
        lines.push(`"${name}","${nip}","${div}","${stat}","${ci}","${co}"`);
    });

    const csv  = lines.join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `absensi_${new Date().toISOString().slice(0,10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
}

/* PAGINATION (demo — simple display only) */
let currentPage = 1;

function changePage(dir) {
    const maxPage = 3; // demo value
    currentPage = Math.max(1, Math.min(maxPage, currentPage + dir));

    document.querySelectorAll('.page-btn:not(:first-child):not(:last-child)')
        .forEach((btn, i) => {
            btn.classList.toggle('active', i + 1 === currentPage);
        });
}

/* TOAST NOTIFICATION */
let toastTimer = null;

function showToast(icon, msg, duration = 3000) {
    const toast = document.getElementById('toast');
    if (!toast) return;

    document.getElementById('toastIcon').textContent = icon;
    document.getElementById('toastMsg').textContent  = msg;
    toast.classList.add('show');

    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), duration);
}

/* CHART BAR TOOLTIPS (hover) */
function initChartTooltips() {
    const bars   = document.querySelectorAll('.chart-bar');
    const values = ['75%','90%','65%','88%','70%','87%'];

    bars.forEach((bar, i) => {
        bar.setAttribute('title', `Kehadiran: ${values[i] || ''}`);
    });
}

/* ANIMATE STATUS BARS on load */
function animateStatusBars() {
    const bars = document.querySelectorAll('.status-bar');
    bars.forEach(bar => {
        const target = bar.style.width;
        bar.style.width = '0%';
        requestAnimationFrame(() => {
            setTimeout(() => { bar.style.width = target; }, 100);
        });
    });
}

/* INIT */
document.addEventListener('DOMContentLoaded', () => {
    updateRealtimeDate();
    setInterval(updateRealtimeDate, 1000);
    initChartTooltips();
    animateStatusBars();
});
