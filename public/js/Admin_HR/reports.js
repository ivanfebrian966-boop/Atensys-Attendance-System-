/* ---- State ---- */
let period = 'week';
let RAW = [];
let filteredData = [];
let currentPage = 1;
const PAGE_SIZE = 10;

/* ---- API FETCH ---- */
async function loadReportsData() {
    try {
        const response = await fetch(REPORTS_DATA_URL);
        const data = await response.json();
        RAW = data;
        renderReports();
    } catch (error) {
        console.error('Error loading reports:', error);
    }
}

/* ---- Period ---- */
function setPeriod(p, btn) {
    period = p;
    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Reset date inputs to allow period to take effect
    const dateFromEl = document.getElementById('dateFrom');
    const dateToEl = document.getElementById('dateTo');

    // Temporarily clear inputs so getDateRange uses the period logic
    const oldFrom = dateFromEl.value;
    const oldTo = dateToEl.value;
    dateFromEl.value = '';
    dateToEl.value = '';

    const range = getDateRange();

    // Set the inputs to the new range
    dateFromEl.value = range.from;
    dateToEl.value = range.to;

    renderReports();
}

function toLocalISO(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}

function getDateRange() {
    const from = document.getElementById('dateFrom')?.value;
    const to = document.getElementById('dateTo')?.value;
    if (from && to) return { from, to };

    const now = new Date();
    let f = new Date(now);

    if (period === 'week') { f.setDate(now.getDate() - 6); }
    else if (period === 'month') { f = new Date(now.getFullYear(), now.getMonth(), 1); }
    else if (period === 'quarter') { f = new Date(now.getFullYear(), Math.floor(now.getMonth() / 3) * 3, 1); }
    else if (period === 'year') { f = new Date(now.getFullYear(), 0, 1); }

    return {
        from: toLocalISO(f),
        to: toLocalISO(now),
    };
}

/* ---- Main render ---- */
function renderReports() {
    if (!RAW || RAW.length === 0) {
        updateSummaryCardsEmpty();
        return;
    }

    const { from, to } = getDateRange();
    const divFilt = document.getElementById('reportDiv')?.value || '';

    filteredData = RAW.filter(r => r.date >= from && r.date <= to && (!divFilt || r.div === divFilt));

    const chartSubtitle = document.getElementById('chartSubtitle');
    if (chartSubtitle) {
        chartSubtitle.textContent = `${fmtDate(from)} — ${fmtDate(to)}`;
    }

    renderSummaryCards();
    renderBarChart();
    renderDonut();
    renderDivisionTable();
    renderDetailTable(filteredData);
}

function updateSummaryCardsEmpty() {
    const ids = ['rTotal', 'rPresent', 'rAbsent', 'rLate', 'rSick', 'rPerm', 'rPresentPct', 'rAbsentPct', 'rLatePct', 'rSickPct', 'rPermPct'];
    ids.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = id.includes('Pct') ? '—' : '0';
    });
}

/* ---- Summary ---- */
function renderSummaryCards() {
    const total = filteredData.length;
    const present = filteredData.filter(r => r.status === 'Present').length;
    const absent = filteredData.filter(r => r.status === 'Absent').length;
    const late = filteredData.filter(r => r.status === 'Late').length;
    const sick = filteredData.filter(r => r.status === 'Sick').length;
    const perm = filteredData.filter(r => r.status === 'Permission').length;
    const pct = v => total ? (v / total * 100).toFixed(1) + '%' : '—';

    document.getElementById('rTotal').textContent = total;
    document.getElementById('rPresent').textContent = present;
    document.getElementById('rAbsent').textContent = absent;
    document.getElementById('rLate').textContent = late;
    document.getElementById('rSick').textContent = sick;
    document.getElementById('rPerm').textContent = perm;
    document.getElementById('rPresentPct').textContent = pct(present);
    document.getElementById('rAbsentPct').textContent = pct(absent);
    document.getElementById('rLatePct').textContent = pct(late);
    document.getElementById('rSickPct').textContent = pct(sick);
    document.getElementById('rPermPct').textContent = pct(perm);
}

/* ---- Bar chart ---- */
function renderBarChart() {
    const dates = [...new Set(filteredData.map(r => r.date))].sort();
    const wrap = document.getElementById('barChart');
    if (!wrap) return;

    const maxVal = Math.max(...dates.map(d => {
        const day = filteredData.filter(r => r.date === d);
        return day.length;
    }), 1);

    wrap.innerHTML = dates.slice(-10).map(d => {
        const day = filteredData.filter(r => r.date === d);
        const pres = day.filter(r => r.status === 'Present').length;
        const abs = day.filter(r => r.status === 'Absent').length;
        const late = day.filter(r => r.status === 'Late').length;
        const h = pct => `${Math.round(pct / maxVal * 100)}%`;
        const lbl = d.slice(5); // MM-DD
        return `
            <div class="bar-group-wrap">
                <div class="bar-group" style="height:100px">
                    <div class="bar-seg" style="background:#10b981;height:${h(pres)};width:33%" title="Present: ${pres}"></div>
                    <div class="bar-seg" style="background:#ef4444;height:${h(abs)};width:33%" title="Absent: ${abs}"></div>
                    <div class="bar-seg" style="background:#f59e0b;height:${h(late)};width:33%" title="Late: ${late}"></div>
                </div>
                <p class="bar-label">${lbl}</p>
            </div>`;
    }).join('');
}

/* ---- Donut ---- */
function renderDonut() {
    const total = filteredData.length;
    const present = filteredData.filter(r => r.status === 'Present').length;
    const absent = filteredData.filter(r => r.status === 'Absent').length;
    const late = filteredData.filter(r => r.status === 'Late').length;
    const sick = filteredData.filter(r => r.status === 'Sick').length;
    const perm = filteredData.filter(r => r.status === 'Permission').length;

    const pct = total ? Math.round(present / total * 100) : 0;
    document.getElementById('donutPct').textContent = pct + '%';

    const data = [
        { label: 'Present', val: present, color: '#10b981' },
        { label: 'Absent', val: absent, color: '#ef4444' },
        { label: 'Late', val: late, color: '#f59e0b' },
        { label: 'Sick', val: sick, color: '#3b82f6' },
        { label: 'Permission', val: perm, color: '#8b5cf6' },
    ].filter(d => d.val > 0);

    const svg = document.getElementById('donutSvg');
    const cx = 60, cy = 60, r = 48, stroke = 14;
    const circ = 2 * Math.PI * r;
    let offset = 0;
    let paths = '';

    data.forEach(d => {
        const frac = d.val / total;
        const dash = frac * circ;
        paths += `<circle cx="${cx}" cy="${cy}" r="${r}" fill="none" stroke="${d.color}"
            stroke-width="${stroke}" stroke-dasharray="${dash} ${circ - dash}"
            stroke-dashoffset="${-offset}" stroke-linecap="round"/>`;
        offset += dash;
    });

    svg.innerHTML = paths || `<circle cx="60" cy="60" r="48" fill="none" stroke="#e2e8f0" stroke-width="14"/>`;

    document.getElementById('donutLegend').innerHTML = data.map(d => `
        <div class="legend-item">
            <span class="legend-dot" style="background:${d.color}"></span>
            <span class="text-xs text-slate-500">${d.label} (${total ? Math.round(d.val / total * 100) : 0}%)</span>
        </div>`).join('');
}

/* ---- Division table ---- */
function renderDivisionTable() {
    const divs = [...new Set(RAW.map(r => r.div))];
    const body = document.getElementById('divisionReportBody');
    if (!body) return;

    body.innerHTML = divs.map(div => {
        const rows = filteredData.filter(r => r.div === div);
        const total = rows.length;
        if (!total) return '';
        const present = rows.filter(r => r.status === 'Present').length;
        const absent = rows.filter(r => r.status === 'Absent').length;
        const late = rows.filter(r => r.status === 'Late').length;
        const other = rows.filter(r => r.status === 'Sick' || r.status === 'Permission').length;
        const rate = Math.round(present / total * 100);
        const rCls = rate >= 85 ? 'rate-high' : rate >= 70 ? 'rate-mid' : 'rate-low';

        return `<tr class="table-row">
            <td>
                <div class="flex items-center gap-3">
                    <div class="avatar-sm" style="background:${divColor(div)}">${initials(div)}</div>
                    <span class="font-semibold text-slate-800 text-sm sora">${div}</span>
                </div>
            </td>
            <td><span class="font-semibold text-emerald-600 sora">${present}</span></td>
            <td><span class="font-semibold text-red-500 sora">${absent}</span></td>
            <td><span class="font-semibold text-amber-500 sora">${late}</span></td>
            <td><span class="font-semibold text-purple-500 sora">${other}</span></td>
            <td>
                <div class="flex items-center gap-2">
                    <div class="prog-wrap"><div class="prog-bar" style="background:${rate >= 85 ? '#10b981' : rate >= 70 ? '#f59e0b' : '#ef4444'};width:${rate}%"></div></div>
                    <span class="rate-badge ${rCls}">${rate}%</span>
                </div>
            </td>
            <td><span class="text-xs ${rate >= 85 ? 'text-emerald-600' : rate >= 70 ? 'text-amber-500' : 'text-red-500'} font-semibold">${rate >= 85 ? '↑ Baik' : rate >= 70 ? '→ Cukup' : '↓ Perlu Perhatian'}</span></td>
        </tr>`;
    }).join('');
}

/* ---- Detail table ---- */
let _detailFull = [];
function renderDetailTable(data) {
    const names = [...new Set(data.map(r => r.name))];
    _detailFull = names.map(name => {
        const rows = data.filter(r => r.name === name);
        const div = rows[0]?.div || '—';
        const total = rows.length;
        const present = rows.filter(r => r.status === 'Present').length;
        const absent = rows.filter(r => r.status === 'Absent').length;
        const late = rows.filter(r => r.status === 'Late').length;
        const sick = rows.filter(r => r.status === 'Sick').length;
        const perm = rows.filter(r => r.status === 'Permission').length;
        const rate = total ? Math.round(present / total * 100) : 0;
        const ciRows = rows.filter(r => r.ci && r.ci !== '-').map(r => r.ci);
        const avgCi = ciRows.length
            ? (() => {
                const mins = ciRows.map(t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; });
                const avg = Math.round(mins.reduce((a, b) => a + b, 0) / mins.length);
                return `${String(Math.floor(avg / 60)).padStart(2, '0')}:${String(avg % 60).padStart(2, '0')}`;
            })()
            : '—';
        return { name, div, total, present, absent, late, sick, perm, rate, avgCi };
    }).sort((a, b) => b.rate - a.rate);

    filterReport();
}

function filterReport() {
    const s = (document.getElementById('searchReport')?.value || '').toLowerCase();
    const list = _detailFull.filter(r => !s || r.name.toLowerCase().includes(s));
    const body = document.getElementById('reportDetailBody');
    const empty = document.getElementById('reportEmpty');
    const info = document.getElementById('reportInfo');

    if (!list.length) {
        if (body) body.innerHTML = '';
        if (empty) empty.classList.remove('hidden');
        if (info) info.textContent = '0 data';
        renderReportPagination(0);
        return;
    }
    if (empty) empty.classList.add('hidden');
    if (info) info.textContent = `${list.length} employees`;

    const totalPages = Math.ceil(list.length / PAGE_SIZE) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    const paginatedList = list.slice((currentPage - 1) * PAGE_SIZE, currentPage * PAGE_SIZE);

    if (body) {
        body.innerHTML = paginatedList.map(r => {
            const rCls = r.rate >= 85 ? 'text-emerald-600' : r.rate >= 70 ? 'text-amber-500' : 'text-red-500';
            return `<tr class="table-row">
                <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar-sm" style="background:${divColor(r.div)}">${initials(r.name)}</div>
                        <span class="font-semibold text-slate-800 text-sm sora">${r.name}</span>
                    </div>
                </td>
                <td class="hidden sm:table-cell"><span class="text-slate-500 text-sm">${r.div}</span></td>
                <td><span class="font-semibold text-emerald-600 sora">${r.present}</span></td>
                <td class="hidden sm:table-cell"><span class="font-semibold text-red-500 sora">${r.absent}</span></td>
                <td class="hidden md:table-cell"><span class="font-semibold text-amber-500 sora">${r.late}</span></td>
                <td class="hidden md:table-cell"><span class="font-semibold text-blue-500 sora">${r.sick}</span></td>
                <td class="hidden md:table-cell"><span class="font-semibold text-purple-500 sora">${r.perm}</span></td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="prog-wrap"><div class="prog-bar" style="background:${r.rate >= 85 ? '#10b981' : r.rate >= 70 ? '#f59e0b' : '#ef4444'};width:${r.rate}%"></div></div>
                        <span class="font-bold sora ${rCls}" style="font-size:.78rem">${r.rate}%</span>
                    </div>
                </td>
                <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">${r.avgCi}</span></td>
            </tr>`;
        }).join('');
    }
    renderReportPagination(totalPages);
}

function renderReportPagination(totalPages) {
    const pag = document.getElementById('reportPagination');
    if (!pag) return;
    if (totalPages <= 1) {
        pag.innerHTML = '';
        return;
    }

    let html = '';
    html += `<button class="px-3 py-1 border rounded text-xs ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeReportPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>Prev</button>`;

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            html += `<button class="px-3 py-1 border rounded text-xs ${i === currentPage ? 'bg-indigo-50 text-indigo-600 border-indigo-200 font-bold' : 'hover:bg-slate-50'}" onclick="changeReportPage(${i})">${i}</button>`;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            html += `<span class="px-2 py-1 text-slate-400">...</span>`;
        }
    }

    html += `<button class="px-3 py-1 border rounded text-xs ${currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50'}" onclick="changeReportPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>Next</button>`;
    pag.innerHTML = html;
}

function changeReportPage(page) {
    currentPage = page;
    filterReport();
}

/* ---- Export ---- */
function exportAllReport() {
    const rows = [['Nama', 'Divisi', 'Total', 'Hadir', 'Absen', 'Telat', 'Sakit', 'Izin', '% Hadir', 'Avg Check In']];
    _detailFull.forEach(r => rows.push([r.name, r.div, r.total, r.present, r.absent, r.late, r.sick, r.perm, r.rate + '%', r.avgCi]));
    downloadCSV(`laporan_${new Date().toISOString().slice(0, 10)}.csv`, rows);
    showToast('📥', 'Laporan diekspor ke CSV');
}
function exportDetailCSV() { exportAllReport(); }

document.addEventListener('DOMContentLoaded', () => {
    const now = new Date();
    const from = new Date(now); from.setDate(now.getDate() - 6);
    const dateFromEl = document.getElementById('dateFrom');
    const dateToEl = document.getElementById('dateTo');
    if (dateFromEl) dateFromEl.value = toLocalISO(from);
    if (dateToEl) dateToEl.value = toLocalISO(now);

    loadReportsData();

    if (dateFromEl) dateFromEl.addEventListener('change', () => {
        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
        renderReports();
    });
    if (dateToEl) dateToEl.addEventListener('change', () => {
        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
        renderReports();
    });
});

/**
 * Toggle between card and table view for report details
 */
function switchReportView(view) {
    const tableView = document.getElementById('reportTableView');
    const cardView = document.getElementById('reportCardView');
    const btnTable = document.getElementById('btnReportTableView');
    const btnCard = document.getElementById('btnReportCardView');

    if (view === 'table') {
        if (tableView) tableView.classList.remove('hidden');
        if (cardView) cardView.classList.add('hidden');
        if (btnTable) btnTable.classList.add('active');
        if (btnCard) btnCard.classList.remove('active');
    } else {
        if (tableView) tableView.classList.add('hidden');
        if (cardView) cardView.classList.remove('hidden');
        if (btnTable) btnTable.classList.remove('active');
        if (btnCard) btnCard.classList.add('active');
        renderReportCards();
    }
}

/**
 * Render cards from the global _detailFull data (already populated by renderDetailTable)
 */
function renderReportCards() {
    const cardContainer = document.getElementById('reportCardContainer');
    if (!cardContainer || !_detailFull) return;

    cardContainer.innerHTML = _detailFull.map(r => {
        const percentage = r.rate + '%';
        const initialsStr = initials(r.name);
        const nameSub = r.name;

        return `
            <div class="report-detail-card">
                <div class="report-card-header">
                    <div class="report-card-avatar" style="background:${divColor(r.div)}">${initialsStr}</div>
                    <div class="flex-1">
                        <p class="report-card-name">${nameSub}</p>
                        <p class="report-card-division">${r.div}</p>
                    </div>
                </div>
                <div class="report-card-body">
                    <div class="report-stat-item">
                        <span class="report-stat-label">Present</span>
                        <span class="report-stat-value" style="color:#10b981">${r.present}</span>
                    </div>
                    <div class="report-stat-item">
                        <span class="report-stat-label">Absent</span>
                        <span class="report-stat-value" style="color:#ef4444">${r.absent}</span>
                    </div>
                    <div class="report-stat-item">
                        <span class="report-stat-label">Late</span>
                        <span class="report-stat-value" style="color:#f59e0b">${r.late}</span>
                    </div>
                    <div class="report-stat-item">
                        <span class="report-stat-label">Sick</span>
                        <span class="report-stat-value" style="color:#3b82f6">${r.sick}</span>
                    </div>
                    <div class="report-stat-item">
                        <span class="report-stat-label">Permission</span>
                        <span class="report-stat-value" style="color:#8b5cf6">${r.perm}</span>
                    </div>
                </div>
                <div class="report-card-footer">
                    <div class="report-percentage-bar">
                        <div class="report-percentage-fill" style="width:${parseInt(percentage)}%"></div>
                    </div>
                    <p class="report-percentage-text">${percentage} Present</p>
                </div>
            </div>
        `;
    }).join('');
}

function initials(name) {
    if (!name) return '—';
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
}

function divColor(div) {
    const colors = {
        'Engineering': '#0ea5e9',
        'HR': '#ec4899',
        'Finance': '#10b981',
        'Marketing': '#f59e0b',
        'IT': '#6366f1',
        'Operasional': '#64748b'
    };
    return colors[div] || '#94a3b8';
}

function fmtDate(d) {
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
}

function downloadCSV(filename, rows) {
    const content = rows.map(e => e.join(',')).join("\n");
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
