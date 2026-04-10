/* ---- Raw data (shared with attendance demo) ---- */
const RAW = [
    {name:'Andi Rahman',    div:'Engineering',date:'2026-04-07',status:'Present',   ci:'08:02'},
    {name:'Siti Wulandari', div:'HR',         date:'2026-04-07',status:'Present',   ci:'07:55'},
    {name:'Budi Pratama',   div:'Finance',    date:'2026-04-07',status:'Late',      ci:'09:15'},
    {name:'Rini Handayani', div:'Marketing',  date:'2026-04-07',status:'Absent',    ci:'-'},
    {name:'Fajar Nugroho',  div:'IT',         date:'2026-04-07',status:'Sick',      ci:'-'},
    {name:'Dewi Susanti',   div:'Engineering',date:'2026-04-07',status:'Permission',ci:'-'},
    {name:'Hendra Putra',   div:'Operasional',date:'2026-04-07',status:'Present',   ci:'07:48'},
    {name:'Yuni Setiawati', div:'HR',         date:'2026-04-07',status:'Present',   ci:'08:00'},
    {name:'Andi Rahman',    div:'Engineering',date:'2026-04-06',status:'Present',   ci:'08:01'},
    {name:'Siti Wulandari', div:'HR',         date:'2026-04-06',status:'Late',      ci:'09:10'},
    {name:'Budi Pratama',   div:'Finance',    date:'2026-04-06',status:'Present',   ci:'08:05'},
    {name:'Fajar Nugroho',  div:'IT',         date:'2026-04-06',status:'Present',   ci:'07:50'},
    {name:'Hendra Putra',   div:'Operasional',date:'2026-04-06',status:'Present',   ci:'07:45'},
    {name:'Yuni Setiawati', div:'HR',         date:'2026-04-06',status:'Absent',    ci:'-'},
    {name:'Andi Rahman',    div:'Engineering',date:'2026-04-05',status:'Present',   ci:'08:00'},
    {name:'Fajar Nugroho',  div:'IT',         date:'2026-04-05',status:'Late',      ci:'09:05'},
    {name:'Dewi Susanti',   div:'Engineering',date:'2026-04-05',status:'Present',   ci:'07:58'},
    {name:'Rini Handayani', div:'Marketing',  date:'2026-04-05',status:'Sick',      ci:'-'},
];

/* ---- State ---- */
let period = 'week';
let filteredData = [...RAW];

/* ---- Period ---- */
function setPeriod(p, btn) {
    period = p;
    document.querySelectorAll('.period-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    renderReports();
}

function getDateRange() {
    const from = document.getElementById('dateFrom')?.value;
    const to   = document.getElementById('dateTo')?.value;
    if (from && to) return {from, to};

    const now  = new Date();
    let   f    = new Date(now);

    if (period==='week')    { f.setDate(now.getDate()-6); }
    else if(period==='month'){ f = new Date(now.getFullYear(),now.getMonth(),1); }
    else if(period==='quarter'){ f = new Date(now.getFullYear(),Math.floor(now.getMonth()/3)*3,1); }
    else if(period==='year'){ f = new Date(now.getFullYear(),0,1); }

    return {
        from: f.toISOString().slice(0,10),
        to:   now.toISOString().slice(0,10),
    };
}

/* ---- Main render ---- */
function renderReports() {
    const {from, to} = getDateRange();
    const divFilt    = document.getElementById('reportDiv')?.value || '';

    filteredData = RAW.filter(r => r.date >= from && r.date <= to && (!divFilt || r.div === divFilt));

    document.getElementById('chartSubtitle').textContent =
        `${fmtDate(from)} — ${fmtDate(to)}`;

    renderSummaryCards();
    renderBarChart();
    renderDonut();
    renderDivisionTable();
    renderDetailTable(filteredData);
}

/* ---- Summary ---- */
function renderSummaryCards() {
    const total   = filteredData.length;
    const present = filteredData.filter(r=>r.status==='Present').length;
    const absent  = filteredData.filter(r=>r.status==='Absent').length;
    const late    = filteredData.filter(r=>r.status==='Late').length;
    const other   = filteredData.filter(r=>r.status==='Sick'||r.status==='Permission').length;
    const pct = v => total ? (v/total*100).toFixed(1)+'%' : '—';

    document.getElementById('rTotal').textContent    = total;
    document.getElementById('rPresent').textContent  = present;
    document.getElementById('rAbsent').textContent   = absent;
    document.getElementById('rLate').textContent     = late;
    document.getElementById('rOther').textContent    = other;
    document.getElementById('rPresentPct').textContent = pct(present);
    document.getElementById('rAbsentPct').textContent  = pct(absent);
    document.getElementById('rLatePct').textContent    = pct(late);
    document.getElementById('rOtherPct').textContent   = pct(other);
}

/* ---- Bar chart ---- */
function renderBarChart() {
    const dates  = [...new Set(filteredData.map(r=>r.date))].sort();
    const wrap   = document.getElementById('barChart');
    if(!wrap) return;

    const maxVal = Math.max(...dates.map(d => {
        const day = filteredData.filter(r=>r.date===d);
        return day.length;
    }), 1);

    wrap.innerHTML = dates.slice(-10).map(d => {
        const day  = filteredData.filter(r=>r.date===d);
        const pres = day.filter(r=>r.status==='Present').length;
        const abs  = day.filter(r=>r.status==='Absent').length;
        const late = day.filter(r=>r.status==='Late').length;
        const h    = pct => `${Math.round(pct/maxVal*100)}%`;
        const lbl  = d.slice(5); // MM-DD
        return `
            <div class="bar-group-wrap">
                <div class="bar-group" style="height:100px">
                    <div class="bar-seg" style="background:#10b981;height:${h(pres)};width:33%" title="Hadir: ${pres}"></div>
                    <div class="bar-seg" style="background:#ef4444;height:${h(abs)};width:33%" title="Absen: ${abs}"></div>
                    <div class="bar-seg" style="background:#f59e0b;height:${h(late)};width:33%" title="Telat: ${late}"></div>
                </div>
                <p class="bar-label">${lbl}</p>
            </div>`;
    }).join('');
}

/* ---- Donut ---- */
function renderDonut() {
    const total   = filteredData.length;
    const present = filteredData.filter(r=>r.status==='Present').length;
    const absent  = filteredData.filter(r=>r.status==='Absent').length;
    const late    = filteredData.filter(r=>r.status==='Late').length;
    const sick    = filteredData.filter(r=>r.status==='Sick').length;
    const perm    = filteredData.filter(r=>r.status==='Permission').length;

    const pct     = total ? Math.round(present/total*100) : 0;
    document.getElementById('donutPct').textContent = pct+'%';

    const data = [
        {label:'Hadir',   val:present, color:'#10b981'},
        {label:'Absen',   val:absent,  color:'#ef4444'},
        {label:'Telat',   val:late,    color:'#f59e0b'},
        {label:'Sakit',   val:sick,    color:'#3b82f6'},
        {label:'Izin',    val:perm,    color:'#8b5cf6'},
    ].filter(d=>d.val>0);

    const svg    = document.getElementById('donutSvg');
    const cx=60,cy=60,r=48,stroke=14;
    const circ   = 2*Math.PI*r;
    let   offset = 0;
    let   paths  = '';

    data.forEach(d => {
        const frac = d.val/total;
        const dash = frac*circ;
        paths += `<circle cx="${cx}" cy="${cy}" r="${r}" fill="none" stroke="${d.color}"
            stroke-width="${stroke}" stroke-dasharray="${dash} ${circ-dash}"
            stroke-dashoffset="${-offset}" stroke-linecap="round"/>`;
        offset += dash;
    });

    svg.innerHTML = paths || `<circle cx="60" cy="60" r="48" fill="none" stroke="#e2e8f0" stroke-width="14"/>`;

    document.getElementById('donutLegend').innerHTML = data.map(d => `
        <div class="legend-item">
            <span class="legend-dot" style="background:${d.color}"></span>
            <span class="text-xs text-slate-500">${d.label} (${total?Math.round(d.val/total*100):0}%)</span>
        </div>`).join('');
}

/* ---- Division table ---- */
function renderDivisionTable() {
    const divs = [...new Set(RAW.map(r=>r.div))];
    const body = document.getElementById('divisionReportBody');

    body.innerHTML = divs.map(div => {
        const rows   = filteredData.filter(r=>r.div===div);
        const total  = rows.length;
        if(!total) return '';
        const present= rows.filter(r=>r.status==='Present').length;
        const absent = rows.filter(r=>r.status==='Absent').length;
        const late   = rows.filter(r=>r.status==='Late').length;
        const other  = rows.filter(r=>r.status==='Sick'||r.status==='Permission').length;
        const rate   = Math.round(present/total*100);
        const rCls   = rate>=85?'rate-high':rate>=70?'rate-mid':'rate-low';

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
                    <div class="prog-wrap"><div class="prog-bar" style="background:${rate>=85?'#10b981':rate>=70?'#f59e0b':'#ef4444'};width:${rate}%"></div></div>
                    <span class="rate-badge ${rCls}">${rate}%</span>
                </div>
            </td>
            <td><span class="text-xs ${rate>=85?'text-emerald-600':rate>=70?'text-amber-500':'text-red-500'} font-semibold">${rate>=85?'↑ Baik':rate>=70?'→ Cukup':'↓ Perlu Perhatian'}</span></td>
        </tr>`;
    }).join('');
}

/* ---- Detail table ---- */
let _detailFull = [];
function renderDetailTable(data) {
    const names  = [...new Set(data.map(r=>r.name))];
    _detailFull  = names.map(name => {
        const rows   = data.filter(r=>r.name===name);
        const div    = rows[0]?.div||'—';
        const total  = rows.length;
        const present= rows.filter(r=>r.status==='Present').length;
        const absent = rows.filter(r=>r.status==='Absent').length;
        const late   = rows.filter(r=>r.status==='Late').length;
        const sick   = rows.filter(r=>r.status==='Sick').length;
        const perm   = rows.filter(r=>r.status==='Permission').length;
        const rate   = total ? Math.round(present/total*100) : 0;
        const ciRows = rows.filter(r=>r.ci&&r.ci!=='-').map(r=>r.ci);
        const avgCi  = ciRows.length
            ? (() => {
                const mins = ciRows.map(t=>{ const [h,m]=t.split(':').map(Number); return h*60+m; });
                const avg  = Math.round(mins.reduce((a,b)=>a+b,0)/mins.length);
                return `${String(Math.floor(avg/60)).padStart(2,'0')}:${String(avg%60).padStart(2,'0')}`;
              })()
            : '—';
        return {name,div,total,present,absent,late,sick,perm,rate,avgCi};
    }).sort((a,b)=>b.rate-a.rate);

    filterReport();
}

function filterReport() {
    const s = (document.getElementById('searchReport')?.value||'').toLowerCase();
    const list = _detailFull.filter(r => !s || r.name.toLowerCase().includes(s));
    const body = document.getElementById('reportDetailBody');
    const empty= document.getElementById('reportEmpty');
    const info = document.getElementById('reportInfo');

    if(!list.length){ body.innerHTML=''; empty.classList.remove('hidden'); info.textContent='0 data'; return; }
    empty.classList.add('hidden');
    info.textContent = `${list.length} karyawan`;

    body.innerHTML = list.map(r => {
        const rCls = r.rate>=85?'text-emerald-600':r.rate>=70?'text-amber-500':'text-red-500';
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
                    <div class="prog-wrap"><div class="prog-bar" style="background:${r.rate>=85?'#10b981':r.rate>=70?'#f59e0b':'#ef4444'};width:${r.rate}%"></div></div>
                    <span class="font-bold sora ${rCls}" style="font-size:.78rem">${r.rate}%</span>
                </div>
            </td>
            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">${r.avgCi}</span></td>
        </tr>`;
    }).join('');
}

/* ---- Export ---- */
function exportAllReport() {
    const rows = [['Nama','Divisi','Total','Hadir','Absen','Telat','Sakit','Izin','% Hadir','Avg Check In']];
    _detailFull.forEach(r=>rows.push([r.name,r.div,r.total,r.present,r.absent,r.late,r.sick,r.perm,r.rate+'%',r.avgCi]));
    downloadCSV(`laporan_${new Date().toISOString().slice(0,10)}.csv`,rows);
    showToast('📥','Laporan diekspor ke CSV');
}
function exportDetailCSV() { exportAllReport(); }

document.addEventListener('DOMContentLoaded',()=>{
    const now  = new Date();
    const from = new Date(now); from.setDate(now.getDate()-6);
    document.getElementById('dateFrom').value = from.toISOString().slice(0,10);
    document.getElementById('dateTo').value   = now.toISOString().slice(0,10);
    renderReports();
});
