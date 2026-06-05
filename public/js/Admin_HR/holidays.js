/* ══════════════════════════════════════════════════════
   Holiday Calendar Script
   - Support multiple holiday names per date
   - Add & Edit modal combined
   - Click holiday day to open Edit modal
   - Click empty day to open Add modal
   ══════════════════════════════════════════════════════ */

let calYear  = new Date().getFullYear();
let calMonth = new Date().getMonth(); // 0-indexed

const MONTHS_ID = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

/* ──────────────────────────────────────────────────────
   CALENDAR RENDER
   ────────────────────────────────────────────────────── */
function renderCalendar() {
    const grid = document.getElementById('calGrid');
    if (!grid) return;
    grid.innerHTML = '';

    const selectMonth = document.getElementById('selectMonth');
    const selectYear  = document.getElementById('selectYear');
    if (selectMonth) selectMonth.value = calMonth;
    if (selectYear)  selectYear.value  = calYear;

    const firstDay    = new Date(calYear, calMonth, 1).getDay(); // 0=Min
    const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
    const today       = new Date().toISOString().slice(0, 10);

    // Empty spaces before first day
    for (let i = 0; i < firstDay; i++) {
        const el = document.createElement('div');
        el.className = 'cal-day empty';
        grid.appendChild(el);
    }

    // Days
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr  = `${calYear}-${String(calMonth + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const names    = HOLIDAY_MAP[dateStr] || [];
        const isHol    = names.length > 0;
        const isTdy    = dateStr === today;
        const dayOfWeek = new Date(calYear, calMonth, d).getDay();
        const isSun    = dayOfWeek === 0;
        const isSat    = dayOfWeek === 6;

        const el = document.createElement('div');
        el.className = 'cal-day'
            + (isHol ? ' holiday' : '')
            + (isTdy  ? ' today'   : '')
            + (isSun  ? ' sunday'  : '')
            + (isSat  ? ' saturday': '');
        el.dataset.date = dateStr;

        // Day number
        const numSpan = document.createElement('span');
        numSpan.className = 'cal-num';
        numSpan.textContent = d;
        el.appendChild(numSpan);

        // Holiday indicator dots (max 2 shown)
        if (isHol) {
            const dotsWrap = document.createElement('div');
            dotsWrap.className = 'cal-hol-dots';
            const count = Math.min(names.length, 3);
            for (let n = 0; n < count; n++) {
                const dot = document.createElement('span');
                dot.className = 'cal-dot';
                dotsWrap.appendChild(dot);
            }
            el.appendChild(dotsWrap);

            // Tooltip: show all names
            el.title = names.join('\n');

            el.onclick = () => openEditHolidayByDate(dateStr);
        } else {
            el.onclick = () => openAddHolidayModalWithDate(dateStr);
        }

        grid.appendChild(el);
    }
}

function calPrev() {
    calMonth--;
    if (calMonth < 0) { calMonth = 11; calYear--; }
    renderCalendar();
}
function calNext() {
    calMonth++;
    if (calMonth > 11) { calMonth = 0; calYear++; }
    renderCalendar();
}
function onMonthYearChange() {
    const sm = document.getElementById('selectMonth');
    const sy = document.getElementById('selectYear');
    if (sm && sy) {
        calMonth = parseInt(sm.value);
        calYear  = parseInt(sy.value);
        renderCalendar();
    }
}

/* ──────────────────────────────────────────────────────
   NAME FIELDS BUILDER
   ────────────────────────────────────────────────────── */
function buildNameFields(names = ['']) {
    const wrap = document.getElementById('nameFieldsWrap');
    if (!wrap) return;
    wrap.innerHTML = '';
    names.forEach((nm, idx) => addNameField(nm));
}

function addNameField(value = '') {
    const wrap  = document.getElementById('nameFieldsWrap');
    if (!wrap) return;
    const idx   = wrap.children.length;
    const row   = document.createElement('div');
    row.className = 'name-field-row';
    row.innerHTML = `
        <span class="name-field-badge">${idx + 1}</span>
        <input type="text"
               class="form-input hol-name-input"
               placeholder="Nama hari libur ke-${idx + 1}"
               value="${escHtml(value)}"
               style="font-size:13px;flex:1;">
        ${idx > 0
            ? `<button type="button" onclick="removeNameField(this)" class="btn-remove-name" title="Hapus">✕</button>`
            : `<span style="width:28px;"></span>`
        }`;
    wrap.appendChild(row);
    // Re-number badges after adding
    refreshBadges();
}

function removeNameField(btn) {
    const row = btn.closest('.name-field-row');
    if (row) row.remove();
    refreshBadges();
}

function refreshBadges() {
    document.querySelectorAll('.name-field-row').forEach((row, idx) => {
        const badge = row.querySelector('.name-field-badge');
        const input = row.querySelector('.hol-name-input');
        if (badge) badge.textContent = idx + 1;
        if (input) input.placeholder = `Nama hari libur ke-${idx + 1}`;
        // show/hide remove button
        const removeBtn = row.querySelector('.btn-remove-name');
        if (idx === 0) {
            if (removeBtn) removeBtn.style.display = 'none';
        } else {
            if (removeBtn) removeBtn.style.display = 'flex';
        }
    });
}

function getNameValues() {
    return Array.from(document.querySelectorAll('.hol-name-input'))
        .map(i => i.value.trim())
        .filter(v => v !== '');
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;')
        .replace(/"/g,'&quot;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;');
}

/* ──────────────────────────────────────────────────────
   MODAL OPEN / CLOSE
   ────────────────────────────────────────────────────── */
function resetHolModal() {
    document.getElementById('holDate').value   = '';
    document.getElementById('holEditId').value = '';
    document.getElementById('eHolNames').textContent = '';
    buildNameFields(['']);
}

function setDateDisplay(dateStr) {
    const d   = new Date(dateStr + 'T00:00:00');
    const fmt = d.toLocaleDateString('id-ID', {
        weekday:'long', year:'numeric', month:'long', day:'numeric'
    });
    document.getElementById('selectedDateDisplay').textContent = fmt;
}

/** Open modal to ADD a holiday on the given date */
function openAddHolidayModalWithDate(dateStr) {
    resetHolModal();
    document.getElementById('holDate').value = dateStr;
    document.getElementById('modalHolTitle').textContent  = 'Tambah Hari Libur';
    document.getElementById('btnSaveHolTxt').textContent  = 'Simpan';
    document.getElementById('modalHolIcon').textContent   = '🔴';
    setDateDisplay(dateStr);
    openModal('modalHoliday');
}

/** Open edit modal via the list row button */
function openEditHoliday(id) {
    const row = document.getElementById(`hol-row-${id}`);
    if (!row) return;
    const dateStr = row.dataset.holDate;
    const names   = JSON.parse(row.dataset.holNames || '[]');

    resetHolModal();
    document.getElementById('holDate').value   = dateStr;
    document.getElementById('holEditId').value = id;
    document.getElementById('modalHolTitle').textContent  = 'Edit Hari Libur';
    document.getElementById('btnSaveHolTxt').textContent  = 'Perbarui';
    document.getElementById('modalHolIcon').textContent   = '✏️';
    setDateDisplay(dateStr);
    buildNameFields(names.length ? names : ['']);
    openModal('modalHoliday');
}

/** Open edit modal by clicking a holiday date on calendar */
function openEditHolidayByDate(dateStr) {
    const row = document.querySelector(`[data-hol-date="${dateStr}"]`);
    if (!row) return;
    const id = row.id.replace('hol-row-', '');
    openEditHoliday(id);
}

/* ──────────────────────────────────────────────────────
   SAVE (Add or Edit)
   ────────────────────────────────────────────────────── */
function saveHoliday() {
    const date   = document.getElementById('holDate').value.trim();
    const editId = document.getElementById('holEditId').value.trim();
    const names  = getNameValues();
    const errEl  = document.getElementById('eHolNames');

    errEl.textContent = '';

    if (!date) {
        showToast('❌', 'Tanggal tidak ditemukan.', 3000);
        return;
    }
    if (names.length === 0) {
        errEl.textContent = 'Minimal 1 nama hari libur harus diisi.';
        return;
    }

    const isEdit = editId !== '';
    const url    = isEdit
        ? `${HOLIDAY_UPDATE_URL}/${editId}`
        : HOLIDAY_STORE_URL;
    const method = isEdit ? 'PUT' : 'POST';

    fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify({ date, names }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✅', data.message, 3500);
            closeModal('modalHoliday');

            if (isEdit) {
                updateHolidayInList(data.holiday);
                HOLIDAY_MAP[data.holiday.date] = data.holiday.names;
            } else {
                addHolidayToList(data.holiday);
                HOLIDAY_MAP[data.holiday.date] = data.holiday.names;
                updateTotalCount(1);
            }
            renderCalendar();
        } else {
            if (data.errors?.names) {
                errEl.textContent = data.errors.names[0];
            } else if (data.errors?.date) {
                showToast('❌', data.errors.date[0], 3500);
            } else {
                showToast('❌', data.message, 3500);
            }
        }
    })
    .catch(() => showToast('❌', 'Gagal terhubung ke server.', 3000));
}

/* ──────────────────────────────────────────────────────
   LIST MANIPULATION
   ────────────────────────────────────────────────────── */
function addHolidayToList(hol) {
    const emptyEl = document.getElementById('emptyHolidays');
    if (emptyEl) emptyEl.remove();

    const d   = new Date(hol.date + 'T00:00:00');
    const day = String(d.getDate()).padStart(2, '0');
    const mon = d.toLocaleString('id-ID', { month: 'short' }) + ' ' + d.getFullYear();

    const container = document.getElementById('holidayListContainer');
    const row       = document.createElement('div');
    row.className          = 'hol-row';
    row.id                 = `hol-row-${hol.id}`;
    row.dataset.holDate    = hol.date;
    row.dataset.holNames   = JSON.stringify(hol.names);
    row.innerHTML = buildRowInner(hol, day, mon);

    if (container) container.insertBefore(row, container.firstChild);
}

function updateHolidayInList(hol) {
    const row = document.getElementById(`hol-row-${hol.id}`);
    if (!row) return;
    row.dataset.holNames = JSON.stringify(hol.names);

    const d   = new Date(hol.date + 'T00:00:00');
    const day = String(d.getDate()).padStart(2, '0');
    const mon = d.toLocaleString('id-ID', { month: 'short' }) + ' ' + d.getFullYear();
    row.innerHTML = buildRowInner(hol, day, mon);
}

function buildRowInner(hol, day, mon) {
    const namesHtml = hol.names.map((nm, idx) =>
        `<p class="hol-name ${idx > 0 ? 'hol-name-secondary' : ''}">${idx > 0 ? '& ' : ''}${escHtml(nm)}</p>`
    ).join('');
    const labelEsc = escHtml(hol.label || hol.names.join(' & '));

    return `
        <div class="hol-date-badge">
            <span class="day-num">${day}</span>
            <span class="month-txt">${mon}</span>
        </div>
        <div class="hol-info">${namesHtml}</div>
        <div class="hol-actions">
            <button onclick="openEditHoliday(${hol.id})" class="btn-icon-edit" title="Edit">✏️</button>
            <button onclick="deleteHoliday(${hol.id}, '${labelEsc.replace(/'/g,"\\'")}')" class="btn-icon-del" title="Hapus">🗑</button>
        </div>`;
}

function updateTotalCount(delta) {
    const el = document.getElementById('totalHolidays');
    if (el) el.textContent = Math.max(0, parseInt(el.textContent || '0') + delta);
}

/* ──────────────────────────────────────────────────────
   DELETE
   ────────────────────────────────────────────────────── */
let deleteTargetId = null;

function deleteHoliday(id, label) {
    deleteTargetId = id;
    const msgEl = document.getElementById('delHolMsg');
    if (msgEl) {
        msgEl.textContent = `Hapus "${label}"? Absensi Holiday yang di-generate otomatis juga akan dihapus.`;
    }
    openModal('modalDelHoliday');
}

function execDelHoliday() {
    if (!deleteTargetId) return;

    fetch(`${HOLIDAY_DESTROY_URL}/${deleteTargetId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('✅', data.message, 3000);
            closeModal('modalDelHoliday');

            const row = document.getElementById(`hol-row-${deleteTargetId}`);
            if (row) {
                const dateStr = row.dataset?.holDate;
                row.remove();
                if (dateStr) delete HOLIDAY_MAP[dateStr];
                renderCalendar();
            }
            updateTotalCount(-1);

            // Show empty state if no rows left
            const container = document.getElementById('holidayListContainer');
            if (container && document.querySelectorAll('.hol-row').length === 0) {
                container.innerHTML = `
                    <div id="emptyHolidays" class="text-center py-10">
                        <div style="font-size:40px;margin-bottom:8px;">🏖️</div>
                        <p class="text-sm font-medium text-slate-500">Belum ada hari libur terdaftar</p>
                        <p class="text-xs text-slate-400 mt-1">Klik tanggal pada kalender untuk menambah</p>
                    </div>`;
            }
        } else {
            showToast('❌', data.message, 3000);
        }
    })
    .catch(() => showToast('❌', 'Gagal terhubung ke server.', 3000));
}

/* ──────────────────────────────────────────────────────
   INIT
   ────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    // Populate Month Dropdown
    const selectMonth = document.getElementById('selectMonth');
    if (selectMonth) {
        selectMonth.innerHTML = MONTHS_ID.map((m, idx) =>
            `<option value="${idx}">${m}</option>`
        ).join('');
    }

    // Populate Year Dropdown (2020 – 2035)
    const selectYear = document.getElementById('selectYear');
    if (selectYear) {
        let html = '';
        for (let y = 2020; y <= 2035; y++) html += `<option value="${y}">${y}</option>`;
        selectYear.innerHTML = html;
    }

    renderCalendar();
});
