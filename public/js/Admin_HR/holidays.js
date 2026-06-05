/* ── Holiday Calendar Script ────────────────────────── */

let calYear = new Date().getFullYear();
let calMonth = new Date().getMonth(); // 0-indexed

const MONTHS_EN = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

function renderCalendar() {
    const grid = document.getElementById('calGrid');
    if (!grid) return;
    grid.innerHTML = '';

    const selectMonth = document.getElementById('selectMonth');
    const selectYear = document.getElementById('selectYear');

    if (selectMonth) selectMonth.value = calMonth;
    if (selectYear) selectYear.value = calYear;

    const firstDay = new Date(calYear, calMonth, 1).getDay(); // 0=Sun
    const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
    const today = new Date().toISOString().slice(0, 10);

    // Empty spaces before first day of the month
    for (let i = 0; i < firstDay; i++) {
        const el = document.createElement('div');
        el.className = 'cal-day empty';
        grid.appendChild(el);
    }

    // Days in the month
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${calYear}-${String(calMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        const isHol = HOLIDAY_DATES.includes(dateStr);
        const isTdy = dateStr === today;

        const dayOfWeek = new Date(calYear, calMonth, d).getDay();
        let weekendClass = '';
        if (dayOfWeek === 0) {
            weekendClass = ' sunday';
        } else if (dayOfWeek === 6) {
            weekendClass = ' saturday';
        }

        const el = document.createElement('div');
        el.className = 'cal-day' + (isHol ? ' holiday' : '') + (isTdy ? ' today' : '') + weekendClass;
        el.textContent = d;
        el.style.cursor = 'pointer';

        if (isHol) {
            const name = getHolidayName(dateStr);
            el.title = name || 'Holiday';
            el.onclick = () => {
                const row = document.querySelector(`[data-hol-date="${dateStr}"]`);
                if (row) {
                    const id = row.id.replace('hol-row-', '');
                    deleteHoliday(id, name);
                }
            };
        } else {
            el.onclick = () => {
                openAddHolidayModalWithDate(dateStr);
            };
        }
        grid.appendChild(el);
    }
}

function getHolidayName(dateStr) {
    const row = document.querySelector(`[data-hol-date="${dateStr}"]`);
    return row ? row.dataset.holName : '';
}

function calPrev() {
    calMonth--;
    if (calMonth < 0) {
        calMonth = 11;
        calYear--;
    }
    renderCalendar();
}

function calNext() {
    calMonth++;
    if (calMonth > 11) {
        calMonth = 0;
        calYear++;
    }
    renderCalendar();
}

function onMonthYearChange() {
    const selectMonth = document.getElementById('selectMonth');
    const selectYear = document.getElementById('selectYear');
    if (selectMonth && selectYear) {
        calMonth = parseInt(selectMonth.value);
        calYear = parseInt(selectYear.value);
        renderCalendar();
    }
}

/* ── Add Holiday ────────────────────────────────────── */
function openAddHolidayModal() {
    const dateInput = document.getElementById('holDate');
    const nameInput = document.getElementById('holName');
    const errDate = document.getElementById('eHolDate');
    const errName = document.getElementById('eHolName');

    if (dateInput) dateInput.value = '';
    if (nameInput) nameInput.value = '';
    if (errDate) errDate.textContent = '';
    if (errName) errName.textContent = '';

    openModal('modalAddHoliday');
}

function openAddHolidayModalWithDate(dateStr) {
    openAddHolidayModal();
    const dateInput = document.getElementById('holDate');
    if (dateInput) {
        dateInput.value = dateStr;
    }
    const displayEl = document.getElementById('selectedDateDisplay');
    if (displayEl) {
        const d = new Date(dateStr + 'T00:00:00');
        displayEl.textContent = d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    }
}

function saveHoliday() {
    const date = document.getElementById('holDate').value.trim();
    const name = document.getElementById('holName').value.trim();
    const errDate = document.getElementById('eHolDate');
    const errName = document.getElementById('eHolName');

    let valid = true;
    if (errDate) errDate.textContent = '';
    if (errName) errName.textContent = '';

    if (!date) {
        if (errDate) errDate.textContent = 'Date is required.';
        valid = false;
    }
    if (!name) {
        if (errName) errName.textContent = 'Holiday name is required.';
        valid = false;
    }
    if (!valid) return;

    fetch(HOLIDAY_STORE_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: JSON.stringify({ date, name })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('🔴', data.message, 3000);
            closeModal('modalAddHoliday');
            addHolidayToList(data.holiday);
            HOLIDAY_DATES.push(data.holiday.date);
            renderCalendar();
            updateTotalCount(1);
        } else {
            if (data.errors?.date) {
                if (errDate) errDate.textContent = data.errors.date[0];
            } else {
                showToast('❌', data.message, 3000);
            }
        }
    })
    .catch(() => showToast('❌', 'Failed to connect to the server', 3000));
}

function addHolidayToList(hol) {
    const emptyEl = document.getElementById('emptyHolidays');
    if (emptyEl) emptyEl.remove();

    const d = new Date(hol.date + 'T00:00:00');
    const day = String(d.getDate()).padStart(2, '0');
    const mon = d.toLocaleString('en-US', { month: 'short' }) + ' ' + d.getFullYear();

    const container = document.getElementById('holidayListContainer');
    const row = document.createElement('div');
    row.className = 'hol-row';
    row.id = `hol-row-${hol.id}`;
    row.dataset.holDate = hol.date;
    row.dataset.holName = hol.name;
    row.innerHTML = `
        <div class="hol-date-badge">
            <span class="day-num">${day}</span>
            <span class="month-txt">${mon}</span>
        </div>
        <div class="hol-info">
            <p class="hol-name">${hol.name}</p>
        </div>
        <button onclick="deleteHoliday(${hol.id}, '${hol.name.replace(/'/g, "\\'")}')"
            style="flex-shrink:0;width:30px;height:30px;border-radius:8px;border:1px solid #fecaca;background:#fff5f5;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;color:#ef4444;"
            title="Delete">🗑</button>`;
    
    if (container) {
        container.insertBefore(row, container.firstChild);
    }
}

/* ── Delete Holiday ─────────────────────────────────── */
let deleteTargetId = null;

function deleteHoliday(id, name) {
    deleteTargetId = id;
    const msgEl = document.getElementById('delHolMsg');
    if (msgEl) {
        msgEl.textContent = `Delete "${name}"? Auto-generated Holiday attendance records will also be deleted.`;
    }
    openModal('modalDelHoliday');
}

function execDelHoliday() {
    if (!deleteTargetId) return;
    fetch(`${HOLIDAY_DESTROY_URL}/${deleteTargetId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
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
                if (dateStr) {
                    const idx = HOLIDAY_DATES.indexOf(dateStr);
                    if (idx > -1) HOLIDAY_DATES.splice(idx, 1);
                }
                renderCalendar();
            }
            updateTotalCount(-1);
            const container = document.getElementById('holidayListContainer');
            if (container && document.querySelectorAll('.hol-row').length === 0) {
                container.innerHTML = `
                    <div id="emptyHolidays" class="text-center py-10">
                        <div style="font-size:40px;margin-bottom:8px;">🏖️</div>
                        <p class="text-sm font-medium text-slate-500">No holidays registered yet</p>
                        <p class="text-xs text-slate-400 mt-1">Click a calendar date to add a holiday</p>
                    </div>`;
            }
        } else {
            showToast('❌', data.message, 3000);
        }
    })
    .catch(() => showToast('❌', 'Failed to connect to the server', 3000));
}

function updateTotalCount(delta) {
    const el = document.getElementById('totalHolidays');
    if (el) {
        el.textContent = parseInt(el.textContent || '0') + delta;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Populate Month Dropdown
    const selectMonth = document.getElementById('selectMonth');
    if (selectMonth) {
        selectMonth.innerHTML = MONTHS_EN.map((m, idx) => `
            <option value="${idx}">${m}</option>
        `).join('');
    }

    // Populate Year Dropdown (from 2020 to 2035)
    const selectYear = document.getElementById('selectYear');
    if (selectYear) {
        let yearsHtml = '';
        for (let y = 2020; y <= 2035; y++) {
            yearsHtml += `<option value="${y}">${y}</option>`;
        }
        selectYear.innerHTML = yearsHtml;
    }

    // Inject dataset.holName values to rows loaded from server
    document.querySelectorAll('.hol-row').forEach(row => {
        const btn = row.querySelector('button[onclick]');
        if (!btn) return;
        const match = btn.getAttribute('onclick').match(/deleteHoliday\((\d+),\s*'(.+?)'\)/);
        if (!match) return;
        row.dataset.holName = match[2];
    });

    renderCalendar();
});
