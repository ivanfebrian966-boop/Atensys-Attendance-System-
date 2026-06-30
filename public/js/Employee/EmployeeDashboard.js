/* ============================================================
   ATTENSYS — Employee Dashboard JS
   ============================================================ */

// ===== Loader =====
window.addEventListener('load', function () {
    const loader = document.getElementById('global-loader');
    if (loader) {
        loader.style.transition = 'opacity 0.5s ease';
        loader.style.opacity = '0';
        setTimeout(() => {
            loader.style.display = 'none';
        }, 500);
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    updateCurrentDate();
    generateQRCode();
    setupEventListeners();
});

// Update current date and time
function updateCurrentDate() {
    const dateElement = document.getElementById('currentDate');
    const clockElement = document.getElementById('realtime-clock');

    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    const now = new Date();

    if (dateElement) {
        const dayName = days[now.getDay()];
        const day = String(now.getDate()).padStart(2, '0');
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();
        dateElement.textContent = `${dayName}, ${day} ${monthName} ${year}`;
    }

    if (clockElement) {
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        clockElement.textContent = `${hours}:${minutes}:${seconds}`;
    }
}

// Set interval for realtime clock
setInterval(updateCurrentDate, 1000);

// Generate QR Code - refreshes every 10 seconds
function generateQRCode() {
    const qrCodeBase = document.getElementById('qrCodeBase');
    const qrCodeData = document.getElementById('qrCodeData');
    const qrCodeBox = document.getElementById('qrCodeBox');

    if (!qrCodeBox || (!qrCodeBase && !qrCodeData)) return;

    const base = qrCodeBase ? qrCodeBase.value : qrCodeData.value;
    const data = qrCodeBase ? `${base}:${Date.now()}` : qrCodeData.value;

    // Show loading state
    qrCodeBox.innerHTML = `
        <div class="qr-loading">
            <div class="qr-loading-spinner"></div>
            <p>Generating QR Code...</p>
        </div>
    `;

    try {
        setTimeout(() => {
            qrCodeBox.innerHTML = '';
            const qr = new QRCode(qrCodeBox, {
                text: data,
                width: 300,
                height: 300,
                colorDark: '#0f172a',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });

            window.currentQRCode = {
                data: data,
                generatedAt: Date.now(),
                element: qrCodeBox
            };
        }, 300);
    } catch (error) {
        console.error('Error generating QR code:', error);
        qrCodeBox.innerHTML = `
            <div class="text-center text-red-500 py-8">
                <p class="text-lg font-semibold mb-2">Error generating QR Code</p>
                <p class="text-sm">Please refresh the page to try again</p>
            </div>
        `;
    }
}

// Setup event listeners
function setupEventListeners() {
    // Sidebar toggle
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Check in button
    const checkInForm = document.querySelector('form[action*="checkin"]');
    if (checkInForm) {
        checkInForm.addEventListener('submit', function (e) {
            showLoadingState();
        });
    }

    // Check out button
    const checkOutForm = document.querySelector('form[action*="checkout"]');
    if (checkOutForm) {
        checkOutForm.addEventListener('submit', function (e) {
            showLoadingState();
        });
    }
}

// Sidebar functions
function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebar) sidebar.classList.add('open');
    if (overlay) overlay.classList.add('open');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
}


// Show loading state for buttons
function showLoadingState() {
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
    buttons.forEach(btn => {
        if (btn.disabled === false) {
            btn.style.opacity = '0.6';
            btn.disabled = true;
        }
    });
}

function showToast(message, type = 'success', duration = 4000) {
    let title = 'Success!';
    if (type === 'error') title = 'Failed!';
    else if (type === 'warning') title = 'Warning!';
    else if (type === 'info') title = 'Information!';

    if (['❌', '✅', '📥'].includes(message)) {
        message = type;
        if (arguments[0] === '❌') type = 'error';
        else if (arguments[0] === '✅') type = 'success';
        else if (arguments[0] === '📥') type = 'info';
        title = type === 'error' ? 'Failed!' : (type === 'info' ? 'Information!' : 'Success!');
    }

    let container = document.getElementById('attensys-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'attensys-toast-container';
        container.style.cssText = 'position:fixed;top:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:12px;pointer-events:none;';
        document.body.appendChild(container);
    }

    const toastId = 'toast-' + Date.now() + Math.random().toString(36).substr(2, 9);
    const toast = document.createElement('div');
    toast.id = toastId;

    const themes = {
        success: { bg: 'rgba(236, 253, 245, 0.95)', border: '#34d399', text: '#065f46', iconText: '#10b981', iconBg: '#d1fae5', icon: '✓' },
        error: { bg: 'rgba(254, 242, 242, 0.95)', border: '#f87171', text: '#991b1b', iconText: '#ef4444', iconBg: '#fee2e2', icon: '✕' },
        warning: { bg: 'rgba(255, 251, 235, 0.95)', border: '#fbbf24', text: '#92400e', iconText: '#f59e0b', iconBg: '#fef3c7', icon: '!' },
        info: { bg: 'rgba(239, 246, 255, 0.95)', border: '#60a5fa', text: '#1e40af', iconText: '#3b82f6', iconBg: '#dbeafe', icon: 'i' }
    };
    const t = themes[type] || themes.success;

    toast.style.cssText = 'width:380px;background:' + t.bg + ';backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.8);border-left:5px solid ' + t.border + ';border-radius:12px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.1),0 8px 10px -6px rgba(0,0,0,0.05);padding:16px;display:flex;align-items:flex-start;gap:14px;transform:translateX(120%);opacity:0;transition:transform 0.4s cubic-bezier(0.2,0.8,0.2,1),opacity 0.4s ease;pointer-events:auto;position:relative;overflow:hidden;';

    let html = '<div style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:' + t.iconBg + ';display:flex;align-items:center;justify-content:center;color:' + t.iconText + ';font-weight:800;font-size:14px;margin-top:2px;">' + t.icon + '</div>';
    html += '<div style="flex:1;min-width:0;">';
    html += '<h4 style="margin:0;font-size:15px;font-weight:700;color:' + t.text + ';font-family:\'Sora\',sans-serif;">' + title + '</h4>';
    if (message) {
        html += '<p style="margin:4px 0 0 0;font-size:13px;color:#475569;line-height:1.5;font-family:\'DM Sans\',sans-serif;">' + message + '</p>';
    }
    html += '</div>';
    html += '<button onclick="document.getElementById(\'' + toastId + '\').remove()" style="background:transparent;border:none;cursor:pointer;color:#94a3b8;font-size:16px;padding:2px;flex-shrink:0;margin-left:auto;">✕</button>';
    html += '<div style="position:absolute;bottom:0;left:0;height:3px;background:' + t.iconText + ';width:100%;transform-origin:left;animation:toast-progress ' + duration + 'ms linear forwards;"></div>';

    toast.innerHTML = html;

    if (!document.getElementById('attensys-toast-keyframes')) {
        const style = document.createElement('style');
        style.id = 'attensys-toast-keyframes';
        style.textContent = '@keyframes toast-progress { 0% { transform: scaleX(1); } 100% { transform: scaleX(0); } }';
        document.head.appendChild(style);
    }

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    });

    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            const el = document.getElementById(toastId);
            if (el) el.remove();
        }, 400);
    }, duration);
}

// QR Modal functions
function openQRModal() {
    const modal = document.getElementById('qrModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeQRModal() {
    const modal = document.getElementById('qrModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Close modal when clicking outside
document.addEventListener('click', function (event) {
    const modal = document.getElementById('qrModal');
    if (modal && event.target === modal) {
        closeQRModal();
    }
});

// Keyboard shortcut to close modal
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeQRModal();
    }
});

// Interval references
let qrCountdownInterval;
let currentCountdown = 10;

// Refresh QR code and restart countdown
function startQRCountdown() {
    clearInterval(qrCountdownInterval);
    currentCountdown = 10;
    updateCountdownDisplay();

    qrCountdownInterval = setInterval(() => {
        currentCountdown--;
        if (currentCountdown <= 0) {
            generateQRCode();
            currentCountdown = 10;
        }
        updateCountdownDisplay();
    }, 1000);
}

function updateCountdownDisplay() {
    const cdElement = document.getElementById('qr-countdown');
    if (cdElement) {
        cdElement.textContent = currentCountdown;
    }
}

// Start countdown if we have a QR code generated
document.addEventListener('DOMContentLoaded', function () {
    // Start initial countdown logic
    setTimeout(() => {
        const qrCodeBox = document.getElementById('qrCodeBox');
        if (qrCodeBox) {
            startQRCountdown();
        }
    }, 500); // give a little time for initial render
});