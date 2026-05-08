/* ============================================================
   ATTENSYS — Employee Dashboard JS
   ============================================================ */

// ===== Loader =====
        window.addEventListener('load', function() {
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
document.addEventListener('DOMContentLoaded', function() {
    updateCurrentDate();
    generateQRCode();
    setupEventListeners();
});

// Update current date and time
function updateCurrentDate() {
    const dateElement = document.getElementById('currentDate');
    const clockElement = document.getElementById('realtime-clock');
    
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
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
        checkInForm.addEventListener('submit', function(e) {
            showLoadingState();
        });
    }
    
    // Check out button
    const checkOutForm = document.querySelector('form[action*="checkout"]');
    if (checkOutForm) {
        checkOutForm.addEventListener('submit', function(e) {
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

// Toast notification using SweetAlert2
function showToast(message, type = 'success', duration = 3000) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: duration,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
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
document.addEventListener('click', function(event) {
    const modal = document.getElementById('qrModal');
    if (modal && event.target === modal) {
        closeQRModal();
    }
});

// Keyboard shortcut to close modal
document.addEventListener('keydown', function(event) {
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
document.addEventListener('DOMContentLoaded', function() {
    // Start initial countdown logic
    setTimeout(() => {
        const qrCodeBox = document.getElementById('qrCodeBox');
        if (qrCodeBox) {
            startQRCountdown();
        }
    }, 500); // give a little time for initial render
});