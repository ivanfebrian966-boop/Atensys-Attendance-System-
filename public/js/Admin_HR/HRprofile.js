/* ============================================================
   SIDEBAR & GLOBALS (From HRmanage / Shared)
   ============================================================ */
function openSidebar() {
    document.getElementById('sidebar')?.classList.add('open');
    document.getElementById('sidebarOverlay')?.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('sidebar')?.classList.remove('open');
    document.getElementById('sidebarOverlay')?.classList.remove('open');
    document.body.style.overflow = '';
}
window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) closeSidebar();
});

let toastTimer = null;
function showToast(icon, msg, duration = 3000) {
    const t = document.getElementById('toast');
    if (!t) return;
    document.getElementById('toastIcon').textContent = icon;
    document.getElementById('toastMsg').textContent  = msg;
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), duration);
}

function setErr(fieldId, errId, msg) {
    const field = document.getElementById(fieldId);
    const err   = document.getElementById(errId);
    if (field) field.classList.add('is-error');
    if (err)   err.textContent = msg;
}
function clearAllErrors() {
    document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    document.querySelectorAll('.form-input.is-error').forEach(el => el.classList.remove('is-error'));
}

/* ============================================================
   PROFILE SPECIFIC
   ============================================================ */
let isDirty  = false;
let origData = {};

document.addEventListener('DOMContentLoaded', () => {
    origData = captureData();
    disableSave(true);
});

function captureData() {
    return {
        name:     document.getElementById('pName')?.value     || '',
        email:    document.getElementById('pEmail')?.value    || '',
        phone:    document.getElementById('pPhone')?.value    || '',
        position: document.getElementById('pPosition')?.value || '',
        division: document.getElementById('pDivision')?.value || '',
        joinDate: document.getElementById('pJoinDate')?.value || '',
        address:  document.getElementById('pAddress')?.value  || '',
        bio:      document.getElementById('pBio')?.value      || '',
    };
}

function markDirty() {
    isDirty = true;
    disableSave(false);
    document.getElementById('saveIndicator')?.classList.add('hidden');
}

function disableSave(d) {
    const btn  = document.getElementById('saveProfileBtn');
    const rbtn = document.getElementById('resetBtn');
    if(btn)  btn.disabled  = d;
    if(rbtn) rbtn.disabled = d;
    if(btn){
        btn.style.opacity  = d ? '0.5' : '1';
        btn.style.cursor   = d ? 'not-allowed' : 'pointer';
    }
    if(rbtn){
        rbtn.style.opacity = d ? '0.5' : '1';
        rbtn.style.cursor  = d ? 'not-allowed' : 'pointer';
    }
}

function saveProfile() {
    clearAllErrors();
    const name  = document.getElementById('pName').value.trim();
    const email = document.getElementById('pEmail').value.trim();
    let ok = true;
    if(!name)  { setErr('pName','epName','Nama wajib diisi'); ok=false; }
    if(!email) { setErr('pEmail','epEmail','Email wajib diisi'); ok=false; }
    else if(!/\S+@\S+\.\S+/.test(email)){ setErr('pEmail','epEmail','Format email tidak valid'); ok=false; }
    if(!ok) return;

    // Update display name
    const dispEl = document.getElementById('dispName');
    if(dispEl) dispEl.textContent = name;

    const sbarName = document.querySelector('.sidebar-user .text-white');
    if(sbarName) sbarName.textContent = name;

    origData = captureData();
    isDirty  = false;
    disableSave(true);

    const ind = document.getElementById('saveIndicator');
    if(ind){ ind.classList.remove('hidden'); setTimeout(()=>ind.classList.add('hidden'),3000); }

    showToast('✅','Profil berhasil disimpan');
}

function resetProfile() {
    if(!isDirty) return;
    Object.entries(origData).forEach(([k,v]) => {
        const m = {name:'pName',email:'pEmail',phone:'pPhone',position:'pPosition',
                   division:'pDivision',joinDate:'pJoinDate',address:'pAddress',bio:'pBio'};
        const el = document.getElementById(m[k]||k);
        if(el) el.value = v;
    });
    isDirty = false;
    disableSave(true);
    clearAllErrors();
    showToast('↩️','Perubahan dibatalkan');
}

function previewAvatar(event) {
    const file = event.target.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        const av = document.getElementById('avatarDisplay');
        if(!av) return;
        av.innerHTML = `<img src="${e.target.result}" alt="avatar">`;
        showToast('📷','Foto profil diperbarui (belum tersimpan)');
    };
    reader.readAsDataURL(file);
}

function togglePwd(id, btn) {
    const inp = document.getElementById(id); if(!inp) return;
    const isText = inp.type === 'text';
    inp.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '#94a3b8' : '#6366f1';
}

function checkPwdStrength(val) {
    const wrap = document.getElementById('pwdStrength');
    if(!wrap) return;
    if(!val) { wrap.style.display='none'; return; }
    wrap.style.display = 'flex';

    let score = 0;
    if(val.length >= 8)                    score++;
    if(/[A-Z]/.test(val))                  score++;
    if(/[0-9]/.test(val))                  score++;
    if(/[^A-Za-z0-9]/.test(val))           score++;

    const colors   = ['#ef4444','#f59e0b','#3b82f6','#10b981'];
    const labels   = ['Lemah','Cukup','Kuat','Sangat Kuat'];
    const strLabel = document.getElementById('strLabel');
    if(strLabel){ strLabel.textContent = labels[score-1]||'Lemah'; strLabel.style.color = colors[score-1]||'#ef4444'; }

    for(let i=1;i<=4;i++){
        const bar = document.getElementById(`sb${i}`);
        if(bar) bar.style.background = i<=score ? colors[score-1] : '#e2e8f0';
    }
}

function changePassword() {
    clearAllErrors();
    const cur  = document.getElementById('pwdCurrent').value;
    const nw   = document.getElementById('pwdNew').value;
    const conf = document.getElementById('pwdConfirm').value;
    let ok = true;

    if(!cur)          { setErr('pwdCurrent','epPwdCurrent','Password saat ini wajib diisi'); ok=false; }
    if(!nw)           { setErr('pwdNew','epPwdNew','Password baru wajib diisi'); ok=false; }
    else if(nw.length<8){ setErr('pwdNew','epPwdNew','Minimal 8 karakter'); ok=false; }
    if(!conf)         { setErr('pwdConfirm','epPwdConfirm','Konfirmasi password wajib diisi'); ok=false; }
    else if(nw!==conf){ setErr('pwdConfirm','epPwdConfirm','Password tidak cocok'); ok=false; }
    if(!ok) return;

    ['pwdCurrent','pwdNew','pwdConfirm'].forEach(id => { document.getElementById(id).value=''; });
    const pwStr = document.getElementById('pwdStrength');
    if(pwStr) pwStr.style.display='none';
    showToast('🔐','Password berhasil diubah');
}