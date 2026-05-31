/**
 * ATTENSYS — Attendance QR Scanner JS
 * public/js/Admin_HR/attendance_qr.js
 */

let qrReader;
let isProcessing = false;

/* =========================================================
   INIT QR SCANNER
   ========================================================= */

function initQRScanner() {
    if (!window.Html5Qrcode) {
        console.error('Html5Qrcode library not loaded');
        return;
    }

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
        ready:      '#6366f1'
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
    setQRStatus('Ready to scan', 'ready');
});

window.addEventListener('beforeunload', stopQRScanner);