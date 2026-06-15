/**
 * ATTENSYS — Shared JS
 * public/js/Admin_HR/shared.js
 */

// ===== Loader =====
function hideGlobalLoader() {
    const loader = document.getElementById('global-loader');
    if (loader && loader.style.display !== 'none') {
        loader.style.transition = 'opacity 0.5s ease';
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
        }, 500);
    }
}

window.addEventListener('load', hideGlobalLoader);

// Fallback if load event doesn't fire for some reason
setTimeout(hideGlobalLoader, 3000);

/* ---- Sidebar ---- */
function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if (sidebar) sidebar.classList.add('open');
    if (overlay) overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = '';
}

window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) closeSidebar();
});

// Close sidebar when clicking overlay
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
});

/* ---- Modal ---- */
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }
    clearAllErrors();
}

function closeModalOutside(e, id) {
    if (e.target.id === id) closeModal(id);
}

/* ---- Errors ---- */
function clearAllErrors() {
    document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.form-input.err').forEach(el => el.classList.remove('err'));
}

function setErr(fid, eid, msg) {
    const field = document.getElementById(fid);
    const errEl = document.getElementById(eid);
    if (field) field.classList.add('err');
    if (errEl) errEl.textContent = msg;
}

/* ---- Custom Beautiful Toast Notification ---- */
function showToast(title, descOrType = 'success', typeOrMs = null, msFallback = 4000) {
    let desc = '';
    let type = 'success';
    let ms = 4000;

    if (['success', 'error', 'warning', 'info'].includes(descOrType)) {
        type = descOrType;
        ms = typeof typeOrMs === 'number' ? typeOrMs : 4000;
        desc = title;
        if (type === 'success') title = 'Berhasil!';
        else if (type === 'error') title = 'Gagal!';
        else if (type === 'warning') title = 'Peringatan!';
        else if (type === 'info') title = 'Informasi!';
    } else {
        desc = descOrType;
        type = typeof typeOrMs === 'string' ? typeOrMs : 'success';
        ms = typeof msFallback === 'number' ? msFallback : 4000;
    }

    if (!title && desc) {
        title = type === 'success' ? 'Berhasil!' : 'Gagal!';
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
    if (desc) {
        html += '<p style="margin:4px 0 0 0;font-size:13px;color:#475569;line-height:1.5;font-family:\'DM Sans\',sans-serif;">' + desc + '</p>';
    }
    html += '</div>';
    html += '<button onclick="document.getElementById(\'' + toastId + '\').remove()" style="background:transparent;border:none;cursor:pointer;color:#94a3b8;font-size:16px;padding:2px;flex-shrink:0;margin-left:auto;">✕</button>';
    html += '<div style="position:absolute;bottom:0;left:0;height:3px;background:' + t.iconText + ';width:100%;transform-origin:left;animation:toast-progress ' + ms + 'ms linear forwards;"></div>';

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
    }, ms);
}

/* ---- Realtime Date + Clock ---- */
function updateRealtimeDate() {
    const el = document.getElementById('realtime-date');
    if (!el) return;

    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    const now = new Date();

    const dayName = days[now.getDay()];
    const day = String(now.getDate()).padStart(2, '0');
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    el.textContent = `${dayName}, ${day} ${month} ${year}  |  ${hours}:${minutes}:${seconds}`;
}

// Also keep legacy #currentDate support
function setDate() {
    const el = document.getElementById('currentDate');
    if (el) {
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const months = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];
        const now = new Date();
        el.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    }
}

/* ---- Helpers ---- */
function initials(name) {
    const p = name.trim().split(' ');
    return p.length >= 2 ? (p[0][0] + p[1][0]).toUpperCase() : name.slice(0, 2).toUpperCase();
}
function fmtDate(iso) {
    if (!iso || iso === '-') return '—';
    const m = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const [y, mo, d] = iso.split('-');
    return `${parseInt(d)} ${m[parseInt(mo) - 1]} ${y}`;
}
function calcDuration(ci, co) {
    if (!ci || ci === '-' || !co || co === '-') return '—';
    try {
        const [h1, m1] = ci.split(':').map(Number);
        const [h2, m2] = co.split(':').map(Number);
        const mins = (h2 * 60 + m2) - (h1 * 60 + m1);
        if (mins <= 0) return '—';
        return `${Math.floor(mins / 60)}h ${mins % 60}m`;
    } catch { return '—'; }
}

/* ---- Pagination ---- */
function buildPagination(wrapId, total, current, pageSize, onChange) {
    const wrap = document.getElementById(wrapId); if (!wrap) return;
    const pages = Math.ceil(total / pageSize);
    if (pages <= 1) { wrap.innerHTML = ''; return; }
    let h = `<button class="page-btn" ${current === 1 ? 'disabled' : ''} onclick="(${onChange})(${current - 1})">‹</button>`;
    for (let i = 1; i <= pages; i++) {
        if (pages > 5 && i > 2 && i < pages - 1 && Math.abs(i - current) > 1) {
            if (i === 3 || i === pages - 2) h += `<span style="color:#94a3b8;padding:0 2px">…</span>`;
            continue;
        }
        h += `<button class="page-btn ${i === current ? 'active' : ''}" onclick="(${onChange})(${i})">${i}</button>`;
    }
    h += `<button class="page-btn" ${current === pages ? 'disabled' : ''} onclick="(${onChange})(${current + 1})">›</button>`;
    wrap.innerHTML = h;
}

/* ---- Colors ---- */
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
function rndColor() { return COLORS[Math.floor(Math.random() * COLORS.length)]; }

const DIV_COLORS = {
    Engineering: COLORS[0], HR: COLORS[1], Finance: COLORS[2],
    Marketing: COLORS[3], IT: COLORS[7], Operasional: COLORS[4],
};
function divColor(d) { return DIV_COLORS[d] || rndColor(); }

/* ---- CSV export ---- */
function downloadCSV(filename, rows) {
    const csv = rows.map(r => r.map(c => `"${String(c).replace(/"/g, '""')}"`).join(',')).join('\n');
    const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); a.href = url; a.download = filename; a.click();
    URL.revokeObjectURL(url);
}


// Sidebar toggle visibility for mobile
function handleSidebarToggleBtn() {
    const btn = document.getElementById('sidebarToggleBtn');
    if (!btn) return;
    if (window.innerWidth <= 1024) {
        btn.style.display = 'block';
    } else {
        btn.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    handleSidebarToggleBtn();
    setDate();
    updateRealtimeDate();
    setInterval(updateRealtimeDate, 1000);
});

window.addEventListener('resize', () => {
    handleSidebarToggleBtn();
    if (window.innerWidth > 1024) closeSidebar();
});
