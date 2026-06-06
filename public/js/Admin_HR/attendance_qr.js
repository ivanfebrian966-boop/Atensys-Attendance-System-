/**
 * ATTENSYS — Attendance QR Scanner JS
 * public/js/Admin_HR/attendance_qr.js
 */

let qrReader;
let isProcessing = false;

/* =========================================================
   INIT QR SCANNER (dengan cek hari libur)
   ========================================================= */

async function initQRScanner() {
    if (!window.Html5Qrcode) {
        console.error('Html5Qrcode library not loaded');
        return;
    }

    // ── Cek apakah hari ini adalah hari libur ──────────────────
    try {
        const today = new Date().toISOString().slice(0, 10);
        const res   = await fetch(`/admin-hr/holidays/check?date=${today}`);
        const data  = await res.json();

        if (data.is_holiday) {
            // Tampilkan status libur, jangan nyalakan kamera
            setQRStatus(`🎉 Holiday: ${data.name}. Scanner disabled.`, 'holiday');
            showHolidayOverlay(data.name);
            return;
        }
    } catch (e) {
        // Jika endpoint gagal, tetap lanjut (fail open untuk UX)
        console.warn('Holiday check failed, proceeding with scanner:', e);
    }
    // ──────────────────────────────────────────────────────────

    qrReader = new Html5Qrcode('qr-reader');

    Html5Qrcode.getCameras()
        .then(cameras => {
            if (cameras && cameras.length) {
                const cameraId = cameras[0].id;

                qrReader.start(
                    cameraId,
                    { fps: 10, aspectRatio: 1.777778 },
                    onScanSuccess,
                    onScanFailure
                );

                console.log('QR Scanner started — camera:', cameraId);
            } else {
                setQRStatus('Camera not found', 'error');
            }
        })
        .catch(err => {
            setQRStatus('Failed to access camera: ' + err, 'error');
        });
}

/* =========================================================
   OVERLAY HARI LIBUR
   ========================================================= */

function showHolidayOverlay(holidayName) {
    const container = document.getElementById('qr-reader');
    if (!container) return;

    container.style.position = 'relative';
    container.innerHTML = `
        <div style="
            min-height:220px;
            display:flex;flex-direction:column;align-items:center;justify-content:center;
            gap:12px;padding:30px 20px;
            background:linear-gradient(135deg,#fee2e2,#fef2f2);
            border-radius:12px;border:2px dashed #fca5a5;
        ">
            <div style="font-size:48px;line-height:1;">🎉</div>
            <div style="text-align:center;">
                <p style="font-size:16px;font-weight:700;color:#b91c1c;margin:0 0 6px;">National Holiday</p>
                <p style="font-size:14px;font-weight:600;color:#ef4444;margin:0 0 8px;">${holidayName}</p>
                <p style="font-size:12px;color:#f87171;margin:0;">The attendance scanning system is closed today.<br>All employees are marked as present.</p>
            </div>
        </div>`;
}

/* =========================================================
   SCAN HANDLERS
   ========================================================= */

function onScanSuccess(decodedText) {
    if (isProcessing) return;

    isProcessing = true;
    setQRStatus('Processing...', 'processing');

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/admin-hr/attendance/process-qr', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ qr_data: decodedText })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const icon    = data.type === 'check_in' ? '✅' : '👋';
            const message = data.type === 'check_in'
                ? `Check In: ${data.employee} (${data.time})`
                : `Check Out: ${data.employee} (${data.duration})`;

            setQRStatus(message, 'success');
            if (typeof showToast === 'function') showToast(icon, message, 3000);

            setTimeout(() => {
                if (typeof loadAttendanceData === 'function') loadAttendanceData();
                if (typeof updateStats        === 'function') updateStats();
            }, 1000);

            qrReader.pause();
            setTimeout(() => {
                isProcessing = false;
                qrReader.resume();
                setQRStatus('Ready to scan', 'ready');
            }, 2000);
        } else {
            setQRStatus(data.message, 'error');
            if (typeof showToast === 'function') showToast('❌', data.message, 3000);
            isProcessing = false;
        }
    })
    .catch(err => {
        console.error('QR scan error:', err);
        setQRStatus('Connection error', 'error');
        if (typeof showToast === 'function') showToast('❌', 'Failed to connect to server', 3000);
        isProcessing = false;
    });
}

function onScanFailure() {
    // silent
}

/* =========================================================
   HELPERS
   ========================================================= */

function setQRStatus(message, status) {
    const el = document.getElementById('qr-result');
    if (!el) return;

    el.innerText = message;

    const colorMap = {
        success:    '#10b981',
        error:      '#ef4444',
        processing: '#f59e0b',
        ready:      '#6366f1',
        holiday:    '#b91c1c',
    };
    el.style.color = colorMap[status] || '#64748b';
}

function stopQRScanner() {
    if (qrReader) {
        qrReader.stop()
            .then(() => console.log('QR Scanner stopped'))
            .catch(err => console.error('Error stopping scanner:', err));
    }
}

/* =========================================================
   LIFECYCLE
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
    initQRScanner();
    setQRStatus('Initializing...', 'processing');
});

window.addEventListener('beforeunload', stopQRScanner);