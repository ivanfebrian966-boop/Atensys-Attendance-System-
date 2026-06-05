@extends('Admin_HR.layouts.main')

@section('title', 'Attendance Scanner — ATTENSYS')

@section('styles')
<style>
    /* Full height centering for scanner page */
    .scanner-container {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    
    .scanner-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        border-radius: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        max-width: 600px;
    }
    
    /* Scanner viewport frame */
    .scanner-viewport-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: #0f172a;
        box-shadow: inset 0 0 40px rgba(0, 0, 0, 0.6);
        border: 4px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    
    .scanner-viewport-wrapper.scanning {
        border-color: #6366f1;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.25), inset 0 0 40px rgba(0, 0, 0, 0.6);
    }
    
    .scanner-viewport-wrapper.success {
        border-color: #10b981;
        box-shadow: 0 0 35px rgba(16, 185, 129, 0.4), inset 0 0 40px rgba(0, 0, 0, 0.6);
    }
    
    .scanner-viewport-wrapper.error {
        border-color: #ef4444;
        box-shadow: 0 0 35px rgba(239, 68, 68, 0.4), inset 0 0 40px rgba(0, 0, 0, 0.6);
    }

    #qr-reader {
        width: 100%;
        aspect-ratio: 4/3;
        margin: 0 auto;
        overflow: hidden;
    }

    #qr-reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transform: scaleX(-1);
    }

    /* Laser scan line animation */
    .scanner-laser {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to bottom, rgba(99, 102, 241, 0), #6366f1 50%, rgba(99, 102, 241, 0));
        animation: scan 2.5s linear infinite;
        z-index: 10;
        pointer-events: none;
        box-shadow: 0 0 12px #6366f1;
    }

    .scanner-viewport-wrapper.success .scanner-laser {
        background: linear-gradient(to bottom, rgba(16, 185, 129, 0), #10b981 50%, rgba(16, 185, 129, 0));
        box-shadow: 0 0 12px #10b981;
        animation-play-state: paused;
    }

    .scanner-viewport-wrapper.error .scanner-laser {
        background: linear-gradient(to bottom, rgba(239, 68, 68, 0), #ef4444 50%, rgba(239, 68, 68, 0));
        box-shadow: 0 0 12px #ef4444;
        animation-play-state: paused;
    }

    @keyframes scan {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }

    /* Corner brackets for aiming */
    .scanner-bracket {
        position: absolute;
        width: 24px;
        height: 24px;
        border-color: #6366f1;
        border-style: solid;
        z-index: 12;
        pointer-events: none;
        transition: border-color 0.3s ease;
    }

    .scanner-viewport-wrapper.success .scanner-bracket { border-color: #10b981; }
    .scanner-viewport-wrapper.error .scanner-bracket { border-color: #ef4444; }

    .bracket-tl { top: 20px; left: 20px; border-width: 4px 0 0 4px; border-top-left-radius: 8px; }
    .bracket-tr { top: 20px; right: 20px; border-width: 4px 4px 0 0; border-top-right-radius: 8px; }
    .bracket-bl { bottom: 20px; left: 20px; border-width: 0 0 4px 4px; border-bottom-left-radius: 8px; }
    .bracket-br { bottom: 20px; right: 20px; border-width: 0 4px 4px 0; border-bottom-right-radius: 8px; }

    /* Custom Topbar */
    .scanner-topbar {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        position: sticky;
        top: 0;
        z-index: 50;
    }
</style>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@endsection

@section('main_structure')
<!-- Custom Scanner Topbar -->
<header class="scanner-topbar shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow-md shadow-indigo-600/30">
                📷
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-800 sora leading-tight">ATTENSYS — Scanner Device</h1>
                <p class="text-xs text-slate-400" id="realtime-date">Initializing clock...</p>
            </div>
        </div>
        <div>
            <a href="{{ route('logout') }}" class="btn-ghost text-red-600 hover:text-red-700 hover:bg-red-50 border-red-200 hover:border-red-300 font-semibold px-4 py-2 rounded-xl transition duration-150 flex items-center gap-2 text-sm" style="color: #ef4444; border-color: #fecaca;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </a>
        </div>
    </div>
</header>

<main class="scanner-container">
    <div class="scanner-card p-6 md:p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800 sora mb-2">Scan Attendance QR Code</h2>
            <p class="text-sm text-slate-500">Align the employee's QR Code inside the scanner area</p>
        </div>

        {{-- Camera Dropdown --}}
        <div class="mb-6" id="cameraSelectContainer" style="display: none;">
            <label for="cameraSelect" class="form-label text-center mb-2">Select Scanner Camera</label>
            <select id="cameraSelect" class="form-select text-center font-medium max-w-md mx-auto block">
                <!-- Camera options populated dynamically -->
            </select>
        </div>

        <div class="scanner-viewport-wrapper scanning mb-6" id="viewportWrapper">
            <!-- Aiming Brackets -->
            <div class="scanner-bracket bracket-tl"></div>
            <div class="scanner-bracket bracket-tr"></div>
            <div class="scanner-bracket bracket-bl"></div>
            <div class="scanner-bracket bracket-br"></div>
            
            <!-- Laser Scan Line -->
            <div class="scanner-laser" id="laserLine"></div>
            
            <!-- The QR Reader Target -->
            <div id="qr-reader"></div>
        </div>

        <div class="text-center">
            <div id="scanStatusBadge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-600 font-semibold text-sm transition-all duration-300">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
                <span id="scanStatusText">Starting scanner...</span>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    let html5QrCode;
    let isProcessing = false;
    const viewportWrapper = document.getElementById('viewportWrapper');
    const scanStatusText = document.getElementById('scanStatusText');
    const scanStatusBadge = document.getElementById('scanStatusBadge');
    const cameraSelect = document.getElementById('cameraSelect');
    const cameraSelectContainer = document.getElementById('cameraSelectContainer');

    function setStatus(text, type = 'scanning') {
        scanStatusText.textContent = text;
        
        // Remove existing styles
        viewportWrapper.className = 'scanner-viewport-wrapper mb-6';
        scanStatusBadge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full font-semibold text-sm transition-all duration-300';
        
        const dot = scanStatusBadge.querySelector('span');
        
        if (type === 'scanning') {
            viewportWrapper.classList.add('scanning');
            scanStatusBadge.classList.add('bg-indigo-50', 'text-indigo-600');
            dot.className = 'w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse';
        } else if (type === 'success') {
            viewportWrapper.classList.add('success');
            scanStatusBadge.classList.add('bg-emerald-50', 'text-emerald-600');
            dot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500';
        } else if (type === 'error') {
            viewportWrapper.classList.add('error');
            scanStatusBadge.classList.add('bg-rose-50', 'text-rose-600');
            dot.className = 'w-2.5 h-2.5 rounded-full bg-rose-500';
        } else if (type === 'holiday') {
            viewportWrapper.classList.add('error');
            scanStatusBadge.classList.add('bg-amber-50', 'text-amber-600');
            dot.className = 'w-2.5 h-2.5 rounded-full bg-amber-500';
        }
    }

    async function checkHolidayAndInit() {
        try {
            const today = new Date().toISOString().slice(0, 10);
            const res = await fetch(`/admin-hr/holidays/check?date=${today}`);
            const data = await res.json();

            if (data.is_holiday) {
                setStatus(`🎉 Holiday: ${data.name}. Scanner Disabled.`, 'holiday');
                showHolidayOverlay(data.name);
                return;
            }
        } catch (e) {
            console.warn('Holiday check failed, proceeding with scanner:', e);
        }

        initScanner();
    }

    function showHolidayOverlay(holidayName) {
        const readerEl = document.getElementById('qr-reader');
        if (!readerEl) return;
        
        // Hide laser
        document.getElementById('laserLine').style.display = 'none';
        
        readerEl.innerHTML = `
            <div class="flex flex-col items-center justify-center text-center p-8 h-full bg-slate-900/95" style="min-height: 250px;">
                <span class="text-5xl mb-4">🎉</span>
                <h3 class="text-lg font-bold text-white mb-1">National Holiday</h3>
                <p class="text-rose-400 font-semibold text-sm mb-3">${holidayName}</p>
                <p class="text-xs text-slate-400 max-w-xs leading-relaxed">
                    Attendance system is currently closed today. All employees are auto-marked as Holiday.
                </p>
            </div>
        `;
    }

    function initScanner() {
        if (!window.Html5Qrcode) {
            setStatus('Failed to load scan library', 'error');
            return;
        }

        html5QrCode = new Html5Qrcode("qr-reader");

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length > 0) {
                // If more than 1 camera, show selector
                if (devices.length > 1) {
                    cameraSelectContainer.style.display = 'block';
                    cameraSelect.innerHTML = '';
                    devices.forEach(device => {
                        const opt = document.createElement('option');
                        opt.value = device.id;
                        opt.text = device.label || `Camera ${cameraSelect.options.length + 1}`;
                        cameraSelect.appendChild(opt);
                    });

                    cameraSelect.addEventListener('change', () => {
                        startCamera(cameraSelect.value);
                    });
                }
                
                // Start with first camera
                startCamera(devices[0].id);
            } else {
                setStatus('No cameras found on device', 'error');
            }
        }).catch(err => {
            console.error('Camera retrieval failed', err);
            setStatus('Camera permission denied or unavailable', 'error');
        });
    }

    function startCamera(cameraId) {
        if (html5QrCode.isScanning) {
            html5QrCode.stop().then(() => {
                doStart(cameraId);
            }).catch(err => console.error('Error stopping scanner:', err));
        } else {
            doStart(cameraId);
        }
    }

    function doStart(cameraId) {
        html5QrCode.start(
            cameraId,
            {
                fps: 10,
                qrbox: (width, height) => {
                    const size = Math.min(width, height) * 0.7;
                    return { width: size, height: size };
                },
                aspectRatio: 1.333333
            },
            onScanSuccess,
            onScanFailure
        ).then(() => {
            setStatus('Ready to scan', 'scanning');
        }).catch(err => {
            console.error('Scanner start error:', err);
            setStatus('Failed to initialize camera viewport', 'error');
        });
    }

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;

        setStatus('Processing scan...', 'scanning');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch("{{ route('scanner.process-qr') }}", {
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
                const message = data.type === 'check_in'
                    ? `Check In Successful: ${data.employee} (${data.time})`
                    : `Check Out Successful: ${data.employee} (${data.time})`;

                setStatus(message, 'success');
                showToast(message, 'success');

                // Pause camera temporarily for visual confirmation
                html5QrCode.pause();
                
                setTimeout(() => {
                    html5QrCode.resume();
                    isProcessing = false;
                    setStatus('Ready to scan', 'scanning');
                }, 3000);
            } else {
                setStatus(data.message, 'error');
                showToast(data.message, 'error');
                
                setTimeout(() => {
                    isProcessing = false;
                    setStatus('Ready to scan', 'scanning');
                }, 3000);
            }
        })
        .catch(err => {
            console.error('QR post failed:', err);
            setStatus('Network connection error', 'error');
            showToast('Failed to connect to server', 'error');
            
            setTimeout(() => {
                isProcessing = false;
                setStatus('Ready to scan', 'scanning');
            }, 3000);
        });
    }

    function onScanFailure(error) {
        // silent
    }

    document.addEventListener('DOMContentLoaded', () => {
        checkHolidayAndInit();
    });

    window.addEventListener('beforeunload', () => {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().catch(err => console.error('Error stopping scanner:', err));
        }
    });
</script>
@endsection
