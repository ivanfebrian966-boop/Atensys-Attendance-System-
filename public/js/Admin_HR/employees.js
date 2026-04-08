/* ---- Data ---- */
let employees = [
    {id:1,name:'Andi Rahman',    nip:'EMP-001',email:'andi@attensys.id',   phone:'081234567890',division:'Engineering',position:'Backend Dev',    joinDate:'2025-01-15',status:'aktif',   address:'Jl. Sudirman No.1, Jakarta',color:COLORS[0]},
    {id:2,name:'Siti Wulandari', nip:'EMP-002',email:'siti@attensys.id',   phone:'081234567891',division:'HR',          position:'HR Specialist',  joinDate:'2024-03-01',status:'aktif',   address:'Jl. Gatot Subroto No.5',color:COLORS[1]},
    {id:3,name:'Budi Pratama',   nip:'EMP-003',email:'budi@attensys.id',   phone:'081234567892',division:'Finance',     position:'Akuntan',        joinDate:'2026-03-10',status:'aktif',   address:'Jl. Thamrin No.10',color:COLORS[4]},
    {id:4,name:'Rini Handayani', nip:'EMP-004',email:'rini@attensys.id',   phone:'081234567893',division:'Marketing',   position:'Marketing Staff',joinDate:'2023-06-20',status:'nonaktif',address:'Jl. Kuningan No.3',color:COLORS[6]},
    {id:5,name:'Fajar Nugroho',  nip:'EMP-005',email:'fajar@attensys.id',  phone:'081234567894',division:'IT',          position:'Frontend Dev',   joinDate:'2024-08-05',status:'aktif',   address:'Jl. HR Rasuna Said',color:COLORS[2]},
    {id:6,name:'Dewi Susanti',   nip:'EMP-006',email:'dewi@attensys.id',   phone:'081234567895',division:'Engineering', position:'QA Engineer',    joinDate:'2024-11-01',status:'aktif',   address:'Jl. Casablanca No.88',color:COLORS[3]},
    {id:7,name:'Hendra Putra',   nip:'EMP-007',email:'hendra@attensys.id', phone:'081234567896',division:'Operasional', position:'Ops Lead',       joinDate:'2023-02-14',status:'aktif',   address:'Jl. MH Thamrin 22',color:COLORS[7]},
    {id:8,name:'Yuni Setiawati', nip:'EMP-008',email:'yuni@attensys.id',   phone:'081234567897',division:'HR',          position:'Recruiter',      joinDate:'2025-05-20',status:'aktif',   address:'Jl. Kebayoran Baru 15',color:COLORS[5]},
];
let nextId   = 9;
let delId    = null;
let empPage  = 1;
const PAGE   = 6;
let viewMode = 'table';

/* ---- View toggle ---- */
function setView(mode) {
    viewMode = mode;
    document.getElementById('viewTable').classList.toggle('hidden', mode !== 'table');
    document.getElementById('viewGrid').classList.toggle('hidden',  mode !== 'grid');
    document.getElementById('btnTable').classList.toggle('active',  mode === 'table');
    document.getElementById('btnGrid').classList.toggle('active',   mode === 'grid');
    renderEmp(filtered(), empPage);
}

/* ---- Filter ---- */
function filtered() {
    const s  = (document.getElementById('searchEmp')?.value  || '').toLowerCase();
    const d  = (document.getElementById('filterDiv')?.value  || '');
    const st = (document.getElementById('filterStat')?.value || '');
    return employees.filter(e =>
        (!s  || e.name.toLowerCase().includes(s) || e.email.toLowerCase().includes(s) || e.nip.toLowerCase().includes(s)) &&
        (!d  || e.division === d) &&
        (!st || e.status   === st)
    );
}
function filterEmp() { empPage=1; renderEmp(filtered(),1); }

/* ---- Render ---- */
function renderEmp(list, page) {
    empPage = page;
    const empty = document.getElementById('empEmpty');
    const info  = document.getElementById('empInfo');

    // Stats
    document.getElementById('statTotal').textContent    = employees.length;
    document.getElementById('statAktif').textContent    = employees.filter(e=>e.status==='aktif').length;
    document.getElementById('statNonaktif').textContent = employees.filter(e=>e.status==='nonaktif').length;
    document.getElementById('statDivisi').textContent   = [...new Set(employees.map(e=>e.division))].length;
    document.getElementById('subtitleEmp').textContent  = `${list.length} karyawan ditemukan`;

    if (!list.length) {
        document.getElementById('empTableBody').innerHTML = '';
        document.getElementById('empGridBody').innerHTML  = '';
        empty.classList.remove('hidden');
        info.textContent = '0 data';
        document.getElementById('empPagination').innerHTML = '';
        return;
    }
    empty.classList.add('hidden');

    const total = list.length;
    const pages = Math.ceil(total / PAGE);
    page = Math.max(1, Math.min(page, pages));
    empPage = page;
    const slice = list.slice((page-1)*PAGE, (page-1)*PAGE+PAGE);

    info.textContent = `Menampilkan ${(page-1)*PAGE+1}–${Math.min(page*PAGE,total)} dari ${total} data`;

    if (viewMode === 'table') renderTableView(slice);
    else                      renderGridView(slice);

    buildPagination('empPagination', total, page, PAGE, 'empPage=arguments[0];renderEmp(filtered(),empPage)');
}

function renderTableView(slice) {
    document.getElementById('empTableBody').innerHTML = slice.map(e => `
        <tr class="table-row">
            <td>
                <div class="flex items-center gap-3 cursor-pointer" onclick="openDetail(${e.id})">
                    <div class="avatar-sm" style="background:${e.color}">${initials(e.name)}</div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm sora">${e.name}</p>
                        <p class="text-slate-400" style="font-size:.72rem">${e.email}</p>
                    </div>
                </div>
            </td>
            <td class="hidden sm:table-cell"><span class="text-slate-400 text-xs font-mono">${e.nip}</span></td>
            <td><span class="badge" style="background:${divBg(e.division)};color:${divFg(e.division)}">${e.division}</span></td>
            <td class="hidden md:table-cell"><span class="text-slate-600 text-sm">${e.position||'—'}</span></td>
            <td><span class="badge badge-${e.status}">● ${e.status==='aktif'?'Aktif':'Nonaktif'}</span></td>
            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">${fmtDate(e.joinDate)}</span></td>
            <td class="hidden lg:table-cell"><span class="text-slate-400 text-xs">${e.phone||'—'}</span></td>
            <td>
                <div class="flex items-center gap-1 justify-end">
                    <button class="btn-edit" onclick="openEditEmp(${e.id})">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg> Edit
                    </button>
                    <button class="btn-delete" onclick="confirmDel(${e.id},'${e.name}')">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg> Hapus
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderGridView(slice) {
    document.getElementById('empGridBody').innerHTML = slice.map(e => `
        <div class="emp-card" onclick="openDetail(${e.id})">
            <div class="emp-card-avatar" style="background:${e.color}">${initials(e.name)}</div>
            <p class="emp-card-name">${e.name}</p>
            <p class="emp-card-pos">${e.position||'—'}</p>
            <span class="emp-card-div">${e.division}</span>
            <br>
            <span class="badge badge-${e.status}" style="margin-bottom:10px;display:inline-flex">● ${e.status==='aktif'?'Aktif':'Nonaktif'}</span>
            <div class="emp-card-actions" onclick="event.stopPropagation()">
                <button class="btn-edit" onclick="openEditEmp(${e.id})">Edit</button>
                <button class="btn-delete" onclick="confirmDel(${e.id},'${e.name}')">Hapus</button>
            </div>
        </div>
    `).join('');
}

/* ---- Detail ---- */
function openDetail(id) {
    const e = employees.find(x=>x.id===id); if(!e) return;
    document.getElementById('detailContent').innerHTML = `
        <div class="flex items-center gap-4 mb-5">
            <div class="avatar-lg" style="background:${e.color}">${initials(e.name)}</div>
            <div>
                <h3 class="font-bold text-slate-900 text-lg sora">${e.name}</h3>
                <p class="text-slate-400 text-sm">${e.position||'—'} · ${e.division}</p>
                <span class="badge badge-${e.status} mt-1 inline-flex">● ${e.status==='aktif'?'Aktif':'Nonaktif'}</span>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm">
            ${detRow('NIP', e.nip)}
            ${detRow('Email', e.email)}
            ${detRow('No. HP', e.phone||'—')}
            ${detRow('Divisi', e.division)}
            ${detRow('Jabatan', e.position||'—')}
            ${detRow('Bergabung', fmtDate(e.joinDate))}
            <div class="col-span-2 p-3 bg-slate-50 rounded-xl"><p class="text-xs text-slate-400 mb-1">Alamat</p><p class="font-medium text-slate-700">${e.address||'—'}</p></div>
        </div>
    `;
    document.getElementById('detailEditBtn').onclick = () => { closeModal('modalDetail'); openEditEmp(id); };
    openModal('modalDetail');
}
function detRow(lbl,val) {
    return `<div class="p-3 bg-slate-50 rounded-xl"><p class="text-xs text-slate-400 mb-1">${lbl}</p><p class="font-semibold text-slate-800 sora">${val}</p></div>`;
}

/* ---- Add ---- */
function saveEmp() {
    clearAllErrors();
    const name = document.getElementById('aName').value.trim();
    const email= document.getElementById('aEmail').value.trim();
    const div  = document.getElementById('aDivision').value;
    let ok=true;
    if(!name)  { setErr('aName','eaName','Nama wajib diisi'); ok=false; }
    if(!email) { setErr('aEmail','eaEmail','Email wajib diisi'); ok=false; }
    else if(!/\S+@\S+\.\S+/.test(email)) { setErr('aEmail','eaEmail','Format email tidak valid'); ok=false; }
    if(!div)   { setErr('aDivision','eaDivision','Divisi wajib dipilih'); ok=false; }
    if(!ok) return;
    employees.push({
        id:nextId++, name, email, div,
        nip:`EMP-${String(nextId-1).padStart(3,'0')}`,
        phone:document.getElementById('aPhone').value.trim(),
        division:div,
        position:document.getElementById('aPosition').value.trim(),
        joinDate:document.getElementById('aJoinDate').value || new Date().toISOString().slice(0,10),
        status:document.getElementById('aStatus').value,
        address:document.getElementById('aAddress').value.trim(),
        color:rndColor(),
    });
    ['aName','aEmail','aPhone','aPosition','aJoinDate','aAddress'].forEach(i=>document.getElementById(i).value='');
    document.getElementById('aDivision').value='';
    document.getElementById('aStatus').value='aktif';
    closeModal('modalAdd');
    filterEmp();
    showToast('✅',`Karyawan "${name}" berhasil ditambahkan`);
}

/* ---- Edit ---- */
function openEditEmp(id) {
    const e=employees.find(x=>x.id===id); if(!e) return;
    document.getElementById('eId').value       = e.id;
    document.getElementById('eName').value     = e.name;
    document.getElementById('eNip').value      = e.nip;
    document.getElementById('eEmail').value    = e.email;
    document.getElementById('ePhone').value    = e.phone||'';
    document.getElementById('eDivision').value = e.division;
    document.getElementById('ePosition').value = e.position||'';
    document.getElementById('eJoinDate').value = e.joinDate||'';
    document.getElementById('eStatus').value   = e.status;
    document.getElementById('eAddress').value  = e.address||'';
    clearAllErrors();
    openModal('modalEdit');
}
function updateEmp() {
    clearAllErrors();
    const id   = parseInt(document.getElementById('eId').value);
    const name = document.getElementById('eName').value.trim();
    const email= document.getElementById('eEmail').value.trim();
    const div  = document.getElementById('eDivision').value;
    let ok=true;
    if(!name)  { setErr('eName','eeName','Nama wajib diisi'); ok=false; }
    if(!email) { setErr('eEmail','eeEmail','Email wajib diisi'); ok=false; }
    else if(!/\S+@\S+\.\S+/.test(email)) { setErr('eEmail','eeEmail','Format email tidak valid'); ok=false; }
    if(!div)   { setErr('eDivision','eeDivision','Divisi wajib dipilih'); ok=false; }
    if(!ok) return;
    const idx = employees.findIndex(x=>x.id===id);
    employees[idx] = { ...employees[idx], name, email, division:div,
        phone:document.getElementById('ePhone').value.trim(),
        position:document.getElementById('ePosition').value.trim(),
        joinDate:document.getElementById('eJoinDate').value,
        status:document.getElementById('eStatus').value,
        address:document.getElementById('eAddress').value.trim(),
    };
    closeModal('modalEdit');
    filterEmp();
    showToast('✏️',`Data "${name}" berhasil diperbarui`);
}

/* ---- Delete ---- */
function confirmDel(id,name) {
    delId = id;
    document.getElementById('delMsg').textContent = `"${name}" akan dihapus secara permanen.`;
    openModal('modalDel');
}
function execDelete() {
    if(!delId) return;
    const name = employees.find(x=>x.id===delId)?.name||'';
    employees  = employees.filter(x=>x.id!==delId);
    delId=null;
    closeModal('modalDel');
    filterEmp();
    showToast('🗑',`Karyawan "${name}" berhasil dihapus`);
}

/* ---- Export ---- */
function exportCSV() {
    const list = filtered();
    const rows = [['Nama','NIP','Email','No. HP','Divisi','Jabatan','Tgl Bergabung','Status']];
    list.forEach(e => rows.push([e.name,e.nip,e.email,e.phone||'',e.division,e.position||'',e.joinDate||'',e.status]));
    downloadCSV(`karyawan_${new Date().toISOString().slice(0,10)}.csv`, rows);
    showToast('📥','Data karyawan diekspor ke CSV');
}

/* ---- Color helpers ---- */
const DIV_BG = {Engineering:'#eef2ff',HR:'#ecfeff',Finance:'#ecfdf5',Marketing:'#fffbeb',IT:'#eff6ff',Operasional:'#faf5ff'};
const DIV_FG = {Engineering:'#6366f1',HR:'#06b6d4',Finance:'#10b981',Marketing:'#d97706',IT:'#3b82f6',Operasional:'#8b5cf6'};
function divBg(d){ return DIV_BG[d]||'#f1f5f9'; }
function divFg(d){ return DIV_FG[d]||'#64748b'; }

document.addEventListener('DOMContentLoaded', () => filterEmp());
