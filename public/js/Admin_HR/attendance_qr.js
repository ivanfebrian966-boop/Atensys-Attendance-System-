/**
 * ATTENSYS — Attendance QR Scanner JS
 * public/js/Admin_HR/attendance_qr.js
 */

let qrReader;
let isProcessing = false;

// Initialize QR Scanner
function initQRScanner() {
    if (!window.Html5Qrcode) {
        console.error('Html5Qrcode library not loaded');
        return;
    }
    
    qrReader = new Html5Qrcode("qr-reader");
    
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            const cameraId = cameras[0].id;
            
            qrReader.start(
                cameraId,
                {
                    fps: 10,
                    aspectRatio: 1.777778
                },
                onScanSuccess,
                onScanFailure
            );
            
            console.log('QR Scanner started with camera: ' + cameraId);
        } else {
            setQRStatus('❌ Camera not found', 'error');
        }
    }).catch(err => {
        setQRStatus('❌ Failed to access camera: ' + err, 'error');
    });
}

/**
 * Handle successful QR code scan
 */
function onScanSuccess(decodedText, decodedResult) {
    if (isProcessing) return; // Prevent multiple simultaneous scans
    
    isProcessing = true;
    setQRStatus('⏳ Processing...', 'processing');
    
    // CSRF Token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Send to server
    fetch('/attendance/process-qr', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ qr_data: decodedText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = data.type === 'check_in' ? '✅' : '👋';
            const message = data.type === 'check_in' 
                ? `Check In: ${data.employee} (${data.time})` 
                : `Check Out: ${data.employee} (${data.duration})`;
            
            setQRStatus(`${icon} ${message}`, 'success');
            if (typeof showToast === 'function') showToast(icon, message, 3000);
            
            // Reload attendance data if functions exist
            setTimeout(() => {
                if (typeof loadAttendanceData === 'function') loadAttendanceData();
                if (typeof updateStats === 'function') updateStats();
            }, 1000);
            
            // Pause scanner briefly to prevent duplicate scans
            qrReader.pause();
            setTimeout(() => {
                isProcessing = false;
                qrReader.resume();
                setQRStatus('🟢 Ready to scan', 'ready');
            }, 2000);
        } else {
            setQRStatus(`❌ ${data.message}`, 'error');
            if (typeof showToast === 'function') showToast('❌', data.message, 3000);
            isProcessing = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        setQRStatus('❌ Connection error', 'error');
        if (typeof showToast === 'function') showToast('❌', 'Failed to connect to server', 3000);
        isProcessing = false;
    });
}

/**
 * Handle QR code scan failure (silent - too many errors)
 */
function onScanFailure(error) {
    // Silently ignore errors to avoid spam
}

/**
 * Set QR scan status message
 */
function setQRStatus(message, status) {
    const resultEl = document.getElementById('qr-result');
    if (!resultEl) return;
    
    resultEl.innerText = message;
    
    // Color based on status
    if (status === 'success') {
        resultEl.style.color = '#10b981';
    } else if (status === 'error') {
        resultEl.style.color = '#ef4444';
    } else if (status === 'processing') {
        resultEl.style.color = '#f59e0b';
    } else if (status === 'ready') {
        resultEl.style.color = '#6366f1';
    }
}

/**
 * Stop QR scanner
 */
function stopQRScanner() {
    if (qrReader) {
        qrReader.stop().then(() => {
            console.log('QR Scanner stopped');
        }).catch(err => {
            console.error('Error stopping scanner:', err);
        });
    }
}

// Initialize QR Scanner on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    if (typeof initQRScanner === 'function') {
        initQRScanner();
        setQRStatus('🟢 Ready to scan', 'ready');
    }
});

// Stop scanner on page unload
window.addEventListener('beforeunload', stopQRScanner);
