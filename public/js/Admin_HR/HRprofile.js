let isDirty  = false;
let origData = {};

/* ---- Init ---- */
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

/* ---- Dirty flag ---- */
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

/* ---- Save profile ---- */
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

    // Sidebar name
    const sbarName = document.querySelector('.sidebar-user .text-white');
    if(sbarName) sbarName.textContent = name;

    origData = captureData();
    isDirty  = false;
    disableSave(true);

    const ind = document.getElementById('saveIndicator');
    if(ind){ ind.classList.remove('hidden'); setTimeout(()=>ind.classList.add('hidden'),3000); }

    showToast('✅','Profil berhasil disimpan');
}

/* ---- Reset ---- */
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

/* ---- Avatar preview ---- */
function previewAvatar(event) {
    const file = event.target.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        const av = document.getElementById('avatarDisplay');
        if(!av) return;
        // remove text, add img
        av.innerHTML = `<img src="${e.target.result}" alt="avatar">`;
        showToast('📷','Foto profil diperbarui (belum tersimpan)');
    };
    reader.readAsDataURL(file);
}

/* ---- Password ---- */
function togglePwd(id, btn) {
    const inp = document.getElementById(id); if(!inp) return;
    const isText = inp.type === 'text';
    inp.type = isText ? 'password' : 'text';
    // toggle eye icon color
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

    // Demo: just clear & show success
    ['pwdCurrent','pwdNew','pwdConfirm'].forEach(id => { document.getElementById(id).value=''; });
    document.getElementById('pwdStrength').style.display='none';
    showToast('🔐','Password berhasil diubah');
}
