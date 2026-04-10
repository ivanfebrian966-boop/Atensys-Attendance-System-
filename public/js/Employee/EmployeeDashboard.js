/* ============================================================
   ATTENSYS — Employee Dashboard JS
   ============================================================ */

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCurrentDate();
    generateQRCode();
    setupEventListeners();
    updateNavigationItems();
});

// Update current date and time
function updateCurrentDate() {
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        dateElement.textContent = today.toLocaleDateString('id-ID', options);
    }
}

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

// Update active navigation item
function updateNavigationItems() {
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.includes(href.replace('/', ''))) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
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

// Toast notification
function showToast(message, type = 'success', duration = 3000) {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');
    const toastIcon = document.getElementById('toastIcon');
    
    if (!toast) return;
    
    toastMsg.textContent = message;
    toastIcon.textContent = type === 'success' ? '✅' : '❌';
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
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

// Refresh QR code every 10 seconds
setInterval(function() {
    generateQRCode();
}, 10000);