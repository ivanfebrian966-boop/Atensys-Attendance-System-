/**
 * ATTENSYS — Dashboard HR JS
 * public/js/Admin_HR/dashboard.js
 *
 * Pure read-only analytics dashboard.
 * No CRUD, no export, no modals.
 */

/* ============================================================
   DEMO DATA
   ============================================================ */
const EMP_COUNT = 120;

const TODAY_DATA = [
    { name:'Andi Rahman',    div:'Engineering', status:'Present',    ci:'08:02', co:'17:10', color:'linear-gradient(135deg,#6366f1,#818cf8)' },
    { name:'Siti Wulandari', div:'HR',          status:'Present',    ci:'07:55', co:'17:00', color:'linear-gradient(135deg,#06b6d4,#0891b2)' },
    { name:'Budi Pratama',   div:'Finance',     status:'Late',       ci:'09:15', co:'17:30', color:'linear-gradient(135deg,#f59e0b,#d97706)' },
    { name:'Rini Handayani', div:'Marketing',   status:'Absent',     ci:'-',     co:'-',     color:'#94a3b8' },
    { name:'Fajar Nugroho',  div:'IT',          status:'Sick',       ci:'-',     co:'-',     color:'linear-gradient(135deg,#3b82f6,#1d4ed8)' },
    { name:'Dewi Susanti',   div:'Engineering', status:'Permission', ci:'-',     co:'-',     color:'linear-gradient(135deg,#8b5cf6,#7c3aed)' },
    { name:'Hendra Putra',   div:'Operations',  status:'Present',    ci:'07:48', co:'17:05', color:'linear-gradient(135deg,#10b981,#059669)' },
    { name:'Yuni Setiawati', div:'HR',          status:'Present',    ci:'08:00', co:'17:00', color:'linear-gradient(135deg,#ec4899,#db2777)' },
    { name:'Rizky Ahmad',    div:'IT',          status:'Present',    ci:'08:10', co:'17:20', color:'linear-gradient(135deg,#f97316,#ea580c)' },
    { name:'Maya Putri',     div:'Finance',     status:'Late',       ci:'09:45', co:'18:00', color:'linear-gradient(135deg,#a855f7,#9333ea)' },
    { name:'Doni Setiawan',  div:'Marketing',   status:'Present',    ci:'07:58', co:'17:02', color:'linear-gradient(135deg,#14b8a6,#0d9488)' },
    { name:'Lisa Amelia',    div:'Operations',  status:'Absent',     ci:'-',     co:'-',     color:'#94a3b8' },
];

const WEEKLY_DATA = [
    { day:'Mon', present:96, absent:8,  late:9,  perm:7  },
    { day:'Tue', present:102,absent:5,  late:7,  perm:6  },
    { day:'Wed', present:88, absent:14, late:11, perm:7  },
    { day:'Thu', present:105,absent:4,  late:6,  perm:5  },
    { day:'Fri', present:98, absent:10, late:8,  perm:4  },
    { day:'Sat', present:72, absent:20, late:15, perm:13 },
    { day:'Sun', present:40, absent:30, late:20, perm:30 },
];

const MONTHLY_RATES = [
    88,91,85,92,87,90,86,93,89,84,91,88,
    92,87,90,85,88,91,86,93,89,87,90,92,
    85,88,91,87,90,88
];

const DIVISIONS = [
    { name:'Engineering', color:'linear-gradient(135deg,#6366f1,#818cf8)', total:22, present:20 },
    { name:'HR',          color:'linear-gradient(135deg,#06b6d4,#0891b2)', total:8,  present:7  },
    { name:'Finance',     color:'linear-gradient(135deg,#10b981,#059669)', total:15, present:12 },
    { name:'Marketing',   color:'linear-gradient(135deg,#f59e0b,#d97706)', total:18, present:15 },
    { name:'IT',          color:'linear-gradient(135deg,#3b82f6,#1d4ed8)', total:20, present:17 },
    { name:'Operations',  color:'linear-gradient(135deg,#8b5cf6,#7c3aed)', total:20, present:17 },
    { name:'Legal',       color:'linear-gradient(135deg,#ec4899,#db2777)', total:17, present:10 },
];

const TOP_PERFORMERS = [
    { name:'Andi Rahman',   div:'Engineering', pct:100, days:22, color:'linear-gradient(135deg,#6366f1,#818cf8)' },
    { name:'Hendra Putra',  div:'Operations',  pct:100, days:22, color:'linear-gradient(135deg,#10b981,#059669)' },
    { name:'Yuni Setiawati',div:'HR',          pct:95,  days:21, color:'linear-gradient(135deg,#ec4899,#db2777)' },
    { name:'Rizky Ahmad',   div:'IT',          pct:95,  days:21, color:'linear-gradient(135deg,#f97316,#ea580c)' },
    { name:'Doni Setiawan', div:'Marketing',   pct:91,  days:20, color:'linear-gradient(135deg,#14b8a6,#0d9488)' },
];

const NOTIFICATIONS = [
    { title:'3 employees checked in late today', time:'10 min ago',  color:'#f59e0b', read:false },
    { title:'2 new sick leave submissions',       time:'35 min ago',  color:'#3b82f6', read:false },
    { title:'Rini Handayani is absent today',     time:'1 hour ago',  color:'#ef4444', read:false },
    { title:'Monthly report is ready to view',    time:'Yesterday',   color:'#10b981', read:true  },
    { title:'Attendance rate dropped below 85%',  time:'2 days ago',  color:'#8b5cf6', read:true  },
];

/* ============================================================
   UTILITIES
   ============================================================ */
function ini(name) {
    const p = name.trim().split(' ');
    return p.length >= 2 ? (p[0][0]+p[1][0]).toUpperCase() : name.slice(0,2).toUpperCase();
}
function calcDur(ci, co) {
    if (!ci || ci==='-' || !co || co==='-') return '—';
    try {
        const [h1,m1] = ci.split(':').map(Number);
        const [h2,m2] = co.split(':').map(Number);
        const mins = (h2*60+m2)-(h1*60+m1);
        if (mins <= 0) return '—';
        return `${Math.floor(mins/60)}h ${mins%60}m`;
    } catch { return '—'; }
}

/* ============================================================
   LIVE CLOCK
   ============================================================ */
function tickClock() {
    const el = document.getElementById('liveClock'); if (!el) return;
    const n  = new Date();
    el.textContent = n.toLocaleTimeString('id-ID', { hour12:false });
}

/* ============================================================
   GREETING
   ============================================================ */
function setGreeting() {
    const h  = new Date().getHours();
    const el = document.getElementById('greeting'); if (!el) return;
    if      (h < 12) el.textContent = 'Good morning,';
    else if (h < 17) el.textContent = 'Good afternoon,';
    else             el.textContent = 'Good evening,';
}

/* ============================================================
   TODAY STATS → STAT CARDS + WELCOME PILLS + BARS
   ============================================================ */
function renderStatCards() {
    const total  = EMP_COUNT;
    const present= TODAY_DATA.filter(d=>d.status==='Present').length;
    const absent = TODAY_DATA.filter(d=>d.status==='Absent').length;
    const late   = TODAY_DATA.filter(d=>d.status==='Late').length;
    const sick   = TODAY_DATA.filter(d=>d.status==='Sick').length;
    const perm   = TODAY_DATA.filter(d=>d.status==='Permission').length;

    // Cards (from blade variables or JS fallback)
    document.getElementById('cTotal')?.textContent !== undefined &&
        (document.getElementById('cPresent').textContent = present);
    document.getElementById('cAbsent').textContent     = absent;
    document.getElementById('cLate').textContent       = late;
    document.getElementById('cSick').textContent       = sick;
    document.getElementById('cPermission').textContent = perm;

    // Progress bars
    const setBars = (id, pct) => {
        const el = document.getElementById(id);
        if (el) setTimeout(() => el.style.width = pct+'%', 200);
    };
    setBars('barPresent',    Math.round(present/total*100));
    setBars('barAbsent',     Math.round(absent /total*100));
    setBars('barLate',       Math.round(late   /total*100));
    setBars('barSick',       Math.round(sick   /total*100));
    setBars('barPermission', Math.round(perm   /total*100));

    // Welcome pills
    const attRate  = Math.round(present/total*100);
    const ontimeN  = TODAY_DATA.filter(d=>d.status==='Present' && d.ci<=('08:05')).length;
    const ontimeR  = Math.round(ontimeN/present*100);
    const arEl = document.getElementById('attendanceRateToday');
    const otEl = document.getElementById('onTimeRate');
    if (arEl) arEl.textContent = attRate+'%';
    if (otEl) otEl.textContent = (isNaN(ontimeR)?0:ontimeR)+'%';
}

/* ============================================================
   DONUT CHART
   ============================================================ */
function renderDonut() {
    const total  = EMP_COUNT;
    const present= TODAY_DATA.filter(d=>d.status==='Present').length;
    const absent = TODAY_DATA.filter(d=>d.status==='Absent').length;
    const late   = TODAY_DATA.filter(d=>d.status==='Late').length;
    const sick   = TODAY_DATA.filter(d=>d.status==='Sick').length;
    const perm   = TODAY_DATA.filter(d=>d.status==='Permission').length;

    const pct = Math.round(present/total*100);
    const el  = document.getElementById('donutPct');
    if (el) el.textContent = pct+'%';

    const data = [
        { label:'Present',    val:present, color:'#10b981' },
        { label:'Absent',     val:absent,  color:'#ef4444' },
        { label:'Late',       val:late,    color:'#f59e0b' },
        { label:'Sick',       val:sick,    color:'#3b82f6' },
        { label:'Permission', val:perm,    color:'#8b5cf6' },
    ].filter(d=>d.val>0);

    const svg  = document.getElementById('donutSvg');
    if (!svg) return;
    const cx=60, cy=60, r=48, sw=14;
    const circ = 2*Math.PI*r;
    let offset = 0, paths = '';

    data.forEach(d => {
        const dash = d.val/total*circ;
        paths += `<circle cx="${cx}" cy="${cy}" r="${r}" fill="none" stroke="${d.color}"
            stroke-width="${sw}" stroke-dasharray="${dash} ${circ-dash}"
            stroke-dashoffset="${-offset}" stroke-linecap="butt"/>`;
        offset += dash;
    });
    svg.innerHTML = paths || `<circle cx="60" cy="60" r="48" fill="none" stroke="#e2e8f0" stroke-width="14"/>`;

    const legend = document.getElementById('donutLegend'); if (!legend) return;
    legend.innerHTML = data.map(d => `
        <div class="dleg-item">
            <span class="dleg-dot" style="background:${d.color}"></span>
            <span class="dleg-lbl">${d.label}</span>
            <span class="dleg-val">${d.val}</span>
        </div>`).join('');

    // today date label
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const now = new Date();
    const el2 = document.getElementById('todayDateLabel');
    if (el2) el2.textContent = `${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
}

/* ============================================================
   WEEKLY CHART
   ============================================================ */
function renderWeeklyChart() {
    const chart  = document.getElementById('weeklyChart');
    const labels = document.getElementById('weeklyLabels');
    const sumEl  = document.getElementById('weeklySummary');
    if (!chart) return;

    const today = new Date().getDay(); // 0=Sun
    const dayMap = [6,0,1,2,3,4,5];   // Mon=0 … Sun=6
    const todayIdx = dayMap[today];

    const maxVal = Math.max(...WEEKLY_DATA.map(d=>d.present+d.absent+d.late+d.perm), 1);

    chart.innerHTML = WEEKLY_DATA.map((d, i) => {
        const total = d.present+d.absent+d.late+d.perm;
        const hP = (d.present/maxVal*90);
        const hA = (d.absent /maxVal*90);
        const hL = (d.late   /maxVal*90);
        return `
            <div class="wc-day-wrap">
                <div class="wc-bars">
                    <div class="wc-bar" style="height:${hP}px;background:#10b981">
                        <span class="wc-bar-tooltip">Present: ${d.present}</span>
                    </div>
                    <div class="wc-bar" style="height:${hA}px;background:#ef4444">
                        <span class="wc-bar-tooltip">Absent: ${d.absent}</span>
                    </div>
                    <div class="wc-bar" style="height:${hL}px;background:#f59e0b">
                        <span class="wc-bar-tooltip">Late: ${d.late}</span>
                    </div>
                </div>
            </div>`;
    }).join('');

    labels.innerHTML = WEEKLY_DATA.map((d, i) =>
        `<div class="wc-label ${i===todayIdx?'today':''}">${d.day}${i===todayIdx?' ●':''}</div>`
    ).join('');

    // Weekly summary
    const totPresent = WEEKLY_DATA.reduce((s,d)=>s+d.present,0);
    const totAbsent  = WEEKLY_DATA.reduce((s,d)=>s+d.absent ,0);
    const totLate    = WEEKLY_DATA.reduce((s,d)=>s+d.late   ,0);
    sumEl.innerHTML = `
        <div class="ws-item"><span class="ws-dot" style="background:#10b981"></span>Avg Present: <strong>${Math.round(totPresent/7)}/day</strong></div>
        <div class="ws-item"><span class="ws-dot" style="background:#ef4444"></span>Avg Absent: <strong>${Math.round(totAbsent/7)}/day</strong></div>
        <div class="ws-item"><span class="ws-dot" style="background:#f59e0b"></span>Avg Late: <strong>${Math.round(totLate/7)}/day</strong></div>
    `;

    // Week range
    const now  = new Date();
    const mon  = new Date(now); mon.setDate(now.getDate() - ((now.getDay()+6)%7));
    const sun  = new Date(mon); sun.setDate(mon.getDate()+6);
    const fmt  = d => `${d.getDate()} ${['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][d.getMonth()]}`;
    const rEl  = document.getElementById('weekRange');
    if (rEl) rEl.textContent = `${fmt(mon)} – ${fmt(sun)} ${now.getFullYear()}`;
}

/* ============================================================
   DIVISION BREAKDOWN
   ============================================================ */
function renderDivisions() {
    const el = document.getElementById('divisionBreakdown'); if (!el) return;
    el.innerHTML = DIVISIONS.map(d => {
        const pct   = Math.round(d.present/d.total*100);
        const color = pct>=90?'#10b981':pct>=75?'#f59e0b':'#ef4444';
        return `
        <div class="div-row">
            <div class="div-avatar" style="background:${d.color}">${ini(d.name)}</div>
            <div class="div-info">
                <p class="div-name">${d.name}</p>
                <div class="div-prog-wrap">
                    <div class="div-prog-bar" style="background:${color}" id="dp${d.name.replace(/\s/,'')}"></div>
                </div>
            </div>
            <div class="div-right">
                <p class="div-pct" style="color:${color}">${pct}%</p>
                <p class="div-sub">${d.present}/${d.total}</p>
            </div>
        </div>`;
    }).join('');

    // Animate bars
    DIVISIONS.forEach(d => {
        const bar = document.getElementById(`dp${d.name.replace(/\s/,'')}`);
        const pct = Math.round(d.present/d.total*100);
        if (bar) setTimeout(() => bar.style.width = pct+'%', 300);
    });
}

/* ============================================================
   LATE ARRIVALS
   ============================================================ */
function renderLateList() {
    const late  = TODAY_DATA.filter(d=>d.status==='Late');
    const badge = document.getElementById('lateBadge');
    const list  = document.getElementById('lateList');
    if (badge) badge.textContent = late.length;
    if (!list) return;

    if (!late.length) {
        list.innerHTML = `<p class="late-empty">🎉 No late arrivals today!</p>`;
        return;
    }
    list.innerHTML = late.map(d => {
        const mins = (() => {
            if (!d.ci || d.ci==='-') return 0;
            const [h,m] = d.ci.split(':').map(Number);
            return Math.max(0, (h*60+m) - (8*60));
        })();
        return `
        <div class="late-item">
            <div class="late-avatar" style="background:${d.color}">${ini(d.name)}</div>
            <div>
                <p class="late-name">${d.name}</p>
                <p class="late-div">${d.div}</p>
            </div>
            <span class="late-time">+${mins} min</span>
        </div>`;
    }).join('');
}

/* ============================================================
   ABSENT & LEAVE PANELS
   ============================================================ */
function renderAbsentLeave() {
    const absent  = TODAY_DATA.filter(d=>d.status==='Absent');
    const onLeave = TODAY_DATA.filter(d=>d.status==='Sick'||d.status==='Permission');

    const aBadge = document.getElementById('absentBadge');
    const lBadge = document.getElementById('leaveBadge');
    if (aBadge) aBadge.textContent = absent.length;
    if (lBadge) lBadge.textContent = onLeave.length;

    const renderList = (items, elId) => {
        const el = document.getElementById(elId); if (!el) return;
        if (!items.length) { el.innerHTML = `<p class="al-empty">All clear!</p>`; return; }
        el.innerHTML = items.map(d => `
            <div class="al-item">
                <div class="al-avatar" style="background:${d.color}">${ini(d.name)}</div>
                <div>
                    <p class="al-name">${d.name}</p>
                    <p class="al-div">${d.div}</p>
                </div>
                <span class="al-reason ${d.status.toLowerCase()}">${d.status}</span>
            </div>`).join('');
    };
    renderList(absent,  'absentList');
    renderList(onLeave, 'leaveList');
}

/* ============================================================
   ATTENDANCE LOG TABLE (read-only)
   ============================================================ */
let logData = [...TODAY_DATA];

function renderLog(data) {
    const body  = document.getElementById('logBody');
    const empty = document.getElementById('logEmpty');
    const info  = document.getElementById('logInfo');
    if (!body) return;

    if (!data.length) {
        body.innerHTML = '';
        empty?.classList.remove('hidden');
        if (info) info.textContent = '0 records';
        return;
    }
    empty?.classList.add('hidden');
    if (info) info.textContent = `${data.length} record${data.length!==1?'s':''}`;

    body.innerHTML = data.map(d => {
        const dur = calcDur(d.ci, d.co);
        return `
        <tr class="table-row">
            <td>
                <div class="flex items-center gap-3">
                    <div class="avatar-sm" style="background:${d.color}">${ini(d.name)}</div>
                    <span class="font-semibold text-slate-800 text-sm sora">${d.name}</span>
                </div>
            </td>
            <td class="hidden sm:table-cell"><span class="text-slate-500 text-sm">${d.div}</span></td>
            <td><span class="badge badge-${d.status.toLowerCase()}">● ${d.status}</span></td>
            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">${d.ci!=='-'?d.ci:'—'}</span></td>
            <td class="hidden md:table-cell"><span class="text-slate-500 text-sm">${d.co!=='-'?d.co:'—'}</span></td>
            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">${dur}</span></td>
        </tr>`;
    }).join('');
}

function filterLog() {
    const s  = (document.getElementById('logSearch')?.value     ||'').toLowerCase();
    const st = (document.getElementById('logStatusFilter')?.value||'');
    const filtered = logData.filter(d =>
        (!s  || d.name.toLowerCase().includes(s)) &&
        (!st || d.status === st)
    );
    renderLog(filtered);
}

/* ============================================================
   30-DAY SPARKLINE
   ============================================================ */
function renderSparkline() {
    const wrap = document.getElementById('sparklineWrap');
    const stat = document.getElementById('sparklineStats');
    if (!wrap) return;

    const max = Math.max(...MONTHLY_RATES);
    const min = Math.min(...MONTHLY_RATES);
    const avg = Math.round(MONTHLY_RATES.reduce((a,b)=>a+b,0)/MONTHLY_RATES.length);

    const months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const now = new Date();

    wrap.innerHTML = MONTHLY_RATES.map((v, i) => {
        const h     = Math.round(v/100*70);
        const color = v>=90?'#10b981':v>=80?'#6366f1':v>=70?'#f59e0b':'#ef4444';
        const day   = i+1;
        return `
            <div class="spk-bar" style="height:${h}px;background:${color}">
                <span class="spk-tooltip">${months[now.getMonth()]} ${day}: ${v}%</span>
            </div>`;
    }).join('');

    if (stat) stat.innerHTML = `
        <div class="spk-stat"><span class="spk-stat-val sora" style="color:#10b981">${max}%</span><p class="spk-stat-lbl">Peak day</p></div>
        <div class="spk-stat"><span class="spk-stat-val sora" style="color:#6366f1">${avg}%</span><p class="spk-stat-lbl">Monthly avg</p></div>
        <div class="spk-stat"><span class="spk-stat-val sora" style="color:#ef4444">${min}%</span><p class="spk-stat-lbl">Lowest day</p></div>
        <div class="spk-stat"><span class="spk-stat-val sora">${MONTHLY_RATES.filter(v=>v>=85).length}</span><p class="spk-stat-lbl">Days above 85%</p></div>
    `;
}

/* ============================================================
   TOP PERFORMERS
   ============================================================ */
function renderTopPerformers() {
    const el = document.getElementById('topList'); if (!el) return;
    const rankColors = ['#f59e0b','#94a3b8','#cd7c54','#6366f1','#06b6d4'];
    el.innerHTML = TOP_PERFORMERS.map((p, i) => `
        <div class="top-item">
            <div class="top-rank" style="background:${rankColors[i]||'#6366f1'}">${i+1}</div>
            <div class="top-avatar" style="background:${p.color}">${ini(p.name)}</div>
            <div>
                <p class="top-name">${p.name}</p>
                <p class="top-div">${p.div}</p>
            </div>
            <div class="top-score">
                <p class="top-pct">${p.pct}%</p>
                <span class="top-days">${p.days} days present</span>
            </div>
        </div>`).join('');
}

/* ============================================================
   NOTIFICATIONS
   ============================================================ */
let notifOpen = false;

function toggleNotifPanel() {
    notifOpen = !notifOpen;
    const panel = document.getElementById('notifPanel');
    panel?.classList.toggle('hidden', !notifOpen);
    renderNotifications();
}

function renderNotifications() {
    const list = document.getElementById('notifList'); if (!list) return;
    if (!NOTIFICATIONS.length) {
        list.innerHTML = `<p class="notif-empty">No notifications</p>`;
        return;
    }
    list.innerHTML = NOTIFICATIONS.map((n, i) => `
        <div class="notif-item ${n.read?'':'unread'}" onclick="readNotif(${i})">
            <span class="notif-dot-sm" style="background:${n.color}"></span>
            <div>
                <p class="notif-title">${n.title}</p>
                <p class="notif-time">${n.time}</p>
            </div>
        </div>`).join('');

    const dot   = document.getElementById('notifDot');
    const unread= NOTIFICATIONS.filter(n=>!n.read).length;
    if (dot) dot.style.display = unread>0?'block':'none';
}

function readNotif(idx) {
    NOTIFICATIONS[idx].read = true;
    renderNotifications();
}

function markAllRead() {
    NOTIFICATIONS.forEach(n=>n.read=true);
    renderNotifications();
}

// Close notif panel when clicking outside
document.addEventListener('click', e => {
    if (notifOpen &&
        !e.target.closest('.topbar-icon-btn') &&
        !e.target.closest('.notif-panel')) {
        notifOpen = false;
        document.getElementById('notifPanel')?.classList.add('hidden');
    }
});

/* ============================================================
   TOPBAR — position the notif panel relative to topbar
   ============================================================ */
function positionNotifPanel() {
    const panel = document.getElementById('notifPanel');
    if (!panel) return;
    // Already absolutely positioned via CSS relative to .topbar
}

/* ============================================================
   INIT
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    // Date & greeting
    setDate?.();
    setGreeting();

    // Tick clock every second
    tickClock();
    setInterval(tickClock, 1000);

    // Render all dashboard sections
    renderStatCards();
    renderDonut();
    renderWeeklyChart();
    renderDivisions();
    renderLateList();
    renderAbsentLeave();
    renderLog(logData);
    renderSparkline();
    renderTopPerformers();
    renderNotifications();

    // Simulated "live" refresh: update log every 30s with slight random variation
    setInterval(() => {
        // randomly shift one "Present" employee's checkout time
        const presents = logData.filter(d=>d.status==='Present');
        if (presents.length) {
            const idx = Math.floor(Math.random()*presents.length);
            // just re-render to simulate live feel
            filterLog();
        }
    }, 30000);
});
