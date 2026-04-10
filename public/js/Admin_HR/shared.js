/**
 * ATTENSYS — Shared JS
 * public/js/Admin_HR/shared.js
 */

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
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('show');
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

/* ---- Toast ---- */
let _tt = null;

function showToast(icon, msg, ms = 3200) {
    const t = document.getElementById('toast');
    if (!t) return;
    document.getElementById('tIcon').textContent = icon;
    document.getElementById('tMsg').textContent = msg;
    t.classList.add('show');
    clearTimeout(_tt);
    _tt = setTimeout(() => t.classList.remove('show'), ms);
}

/* ---- Date ---- */
function setDate() {
    const d = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const m = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const n = new Date();
    const el = document.getElementById('currentDate');
    if (el) el.textContent = `${d[n.getDay()]}, ${n.getDate()} ${m[n.getMonth()]} ${n.getFullYear()}`;
}

// Auto-set date on page load
document.addEventListener('DOMContentLoaded', setDate);

/* ---- Helpers ---- */
function initials(name) {
    const p = name.trim().split(' ');
    return p.length >= 2 ? (p[0][0]+p[1][0]).toUpperCase() : name.slice(0,2).toUpperCase();
}
function fmtDate(iso) {
    if(!iso||iso==='-') return '—';
    const m=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const [y,mo,d]=iso.split('-');
    return `${parseInt(d)} ${m[parseInt(mo)-1]} ${y}`;
}
function calcDuration(ci, co) {
    if(!ci||ci==='-'||!co||co==='-') return '—';
    try {
        const [h1,m1]=ci.split(':').map(Number);
        const [h2,m2]=co.split(':').map(Number);
        const mins = (h2*60+m2)-(h1*60+m1);
        if(mins<=0) return '—';
        return `${Math.floor(mins/60)}j ${mins%60}m`;
    } catch { return '—'; }
}

/* ---- Pagination ---- */
function buildPagination(wrapId, total, current, pageSize, onChange) {
    const wrap = document.getElementById(wrapId); if(!wrap) return;
    const pages = Math.ceil(total/pageSize);
    if(pages <= 1) { wrap.innerHTML=''; return; }
    let h = `<button class="page-btn" ${current===1?'disabled':''} onclick="(${onChange})(${current-1})">‹</button>`;
    for(let i=1;i<=pages;i++){
        if(pages>5 && i>2 && i<pages-1 && Math.abs(i-current)>1){
            if(i===3||i===pages-2) h+=`<span style="color:#94a3b8;padding:0 2px">…</span>`;
            continue;
        }
        h+=`<button class="page-btn ${i===current?'active':''}" onclick="(${onChange})(${i})">${i}</button>`;
    }
    h+=`<button class="page-btn" ${current===pages?'disabled':''} onclick="(${onChange})(${current+1})">›</button>`;
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
function rndColor() { return COLORS[Math.floor(Math.random()*COLORS.length)]; }

const DIV_COLORS = {
    Engineering: COLORS[0], HR: COLORS[1], Finance: COLORS[2],
    Marketing: COLORS[3], IT: COLORS[7], Operasional: COLORS[4],
};
function divColor(d) { return DIV_COLORS[d] || rndColor(); }

/* ---- CSV export ---- */
function downloadCSV(filename, rows) {
    const csv  = rows.map(r => r.map(c => `"${String(c).replace(/"/g,'""')}"`).join(',')).join('\n');
    const blob = new Blob(['\ufeff'+csv], {type:'text/csv;charset=utf-8'});
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a'); a.href=url; a.download=filename; a.click();
    URL.revokeObjectURL(url);
}

document.addEventListener('DOMContentLoaded', setDate);
