<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance QR Scanner — ATTENSYS</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: {
                            50: '#f0f3ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            800: '#1b224c',
                            900: '#0c1e49',
                        }
                    },
                    fontFamily: {
                        sora: ['Sora', 'sans-serif'],
                        dmsans: ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- HTML5-QRCODE Library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #f8fafc;
        }
        .sora {
            font-family: 'Sora', sans-serif;
        }

        /* Panel style matching Admin HR attendance UI */
        .panel {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: box-shadow 0.25s ease;
        }
        .panel:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        .panel-header {
            padding: 18px 20px 14px;
            border-bottom: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .panel-title {
            font-size: .925rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .panel-subtitle {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: 3px;
        }
        .modal-body {
            padding: 16px 20px 22px;
        }
        .viewport-wrap {
            width: 100%;
        }
        #qr-reader {
            width: 100% !important;
            max-width: 640px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            min-height: 320px;
            background: #000;
        }
        
        @keyframes scanLaser {
            0%   { top: 5%; opacity: 1; }
            50%  { top: 95%; opacity: 1; }
            100% { top: 5%; opacity: 1; }
        }

        #qr-reader video {
            width: 100% !important;
            object-fit: cover !important;
            transform: scaleX(-1);
        }
        #qr-reader > img { display: none !important; }
        #qr-reader__scan_region > img { display: none !important; }
        #qr-reader__dashboard { display: none !important; }

        /* Holiday overlay styling */
        .holiday-overlay {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            padding: 40px 24px;
            background: #0f172a;
            text-align: center;
            gap: 16px;
        }

        /* Pulsing Dot animation */
        @keyframes dotPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            50% { box-shadow: 0 0 0 6px rgba(99, 102, 241, 0); }
        }
        .pulse-active {
            animation: dotPulse 1.8s infinite;
        }

        /* Mobile friendly adjustments */
        @media (max-width: 768px) {
            body {
                background-color: #f8fafc;
            }
            main {
                padding-left: 1rem;
                padding-right: 1rem;
                padding-top: 1.25rem;
                padding-bottom: 1.25rem;
            }
            .viewport-wrap {
                border-radius: 12px;
            }
            #qr-reader video {
                min-height: 240px !important;
            }
            .bg-white.rounded-3xl {
                border: none;
                box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
            }
            .shadow-sm {
                box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- TOPBAR (Redesigned for dashboard tone & vibes) -->
    <header class="bg-white border-b border-slate-100 sticky top-0 z-30 h-16 md:h-[72px] flex items-center shadow-sm">
        <div class="w-full max-w-7xl mx-auto px-4 md:px-6 flex items-center justify-between">
            
            <!-- Left: Brand / Section Title + Realtime Date -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/LOGO.PNG') }}" alt="ATTENSYS Logo" class="h-10 w-auto object-contain" style="filter: invert(1);">
                <div>
                    <h1 class="text-base md:text-lg font-bold text-slate-800 font-sora leading-tight tracking-tight">Attendance Scanner</h1>
                    <p class="text-[10px] md:text-xs text-slate-400 mt-0.5" id="realtime-date">Loading date & time...</p>
                </div>
            </div>

            <!-- Right: Profile Block (Matches user screenshot) -->
            <div class="flex items-center gap-3 md:gap-4">
                
                <!-- Divider -->
                <div class="h-8 w-px bg-slate-200"></div>

                <!-- Profile Section -->
                <div class="flex items-center gap-2.5">
                    <!-- Avatar circle with cyan/blue gradient -->
                    <div class="w-9 h-9 md:w-10 h-10 rounded-full bg-gradient-to-tr from-cyan-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md select-none">
                        SD
                    </div>
                    
                    <!-- Scanner name -->
                    <div class="hidden sm:block text-left">
                        <p class="text-xs md:text-sm font-bold text-slate-800 leading-tight font-sora">Scanner Device</p>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-6 md:py-8 min-h-[calc(100vh-72px)]">
        <div class="w-full max-w-3xl space-y-4">
            
            <!-- Camera Select Dropdown (Hidden unless multiple cameras detected) -->
            <div id="cameraSelectWrap" class="hidden transform transition-all duration-300">
                <div class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-3">
                    <span class="text-sm font-bold text-slate-700 font-sora">Camera:</span>
                    <select id="cameraSelect" class="flex-1 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-indigo-500 cursor-pointer font-medium">
                        <option>Detecting cameras...</option>
                    </select>
                </div>
            </div>

            <!-- Scanner Card (Centered Camera Frame) -->
            <div class="panel w-full">
                
                <!-- Scanner Card Header -->
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">QR Attendance Scanner</h3>
                        <p class="panel-subtitle">Position employee QR code within the camera frame</p>
                    </div>
                    
                    <!-- Status Badge -->
                    <div id="scanStatusWrap" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold font-sora transition-colors duration-300 bg-indigo-50 text-indigo-700 border border-indigo-100">
                        <span class="w-2 h-2 rounded-full bg-indigo-600 pulse-active"></span>
                        <span id="scanStatusText">Initializing...</span>
                    </div>
                </div>

                <!-- Viewport & Scan Count Info -->
                <div class="modal-body flex flex-col items-center">
                    <!-- Camera Viewport Frame -->
                    <div class="viewport-wrap state-scanning w-full" id="viewportWrap">
                        <div id="qr-reader"></div>
                    </div>

                    <!-- Total Scan indicator (Centered under camera preview) -->
                    <div class="mt-5 flex items-center justify-center gap-2.5 px-5 py-2.5 bg-indigo-50/75 rounded-2xl border border-indigo-100/60 w-full max-w-xs transition-all duration-300 shadow-sm">
                        <span class="text-indigo-600 text-xs font-bold font-sora uppercase tracking-wider">Total Scans:</span>
                        <span id="scanCount" class="text-indigo-700 text-xl font-black font-sora">0</span>
                        <span class="text-slate-400 text-[10px] font-bold ml-1">this session</span>
                    </div>
                </div>

                <!-- Console Controls Footer -->
                <div class="px-5 py-4 border-t border-slate-50 bg-slate-50/55 flex flex-col sm:flex-row items-center justify-between gap-4">
                    
                    <!-- Quick Hint -->
                    <p class="text-xs text-slate-400 flex items-center gap-1.5 justify-center sm:justify-start">
                        <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Camera focus is adjusted automatically
                    </p>

                    <!-- Controls -->
                    <div class="flex items-center gap-3 justify-center sm:justify-end w-full sm:w-auto">
                        
                        <!-- Sound Toggle -->
                        <button id="soundToggle" 
                                class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition duration-200 flex items-center justify-center shadow-sm relative group"
                                title="Mute/Unmute Scan Sounds">
                            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-emerald-500" id="soundStatusIndicator"></span>
                            <svg id="soundOnIcon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 18.75V5.25L7.75 9.5H4.5v5h3.25L12 18.75z"/>
                            </svg>
                            <svg id="soundOffIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75L19.5 12m0 0l2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25M12 18.75V5.25L7.75 9.5H4.5v5h3.25L12 18.75z"/>
                            </svg>
                        </button>

                        <!-- Logout Button (Instead of Pause Camera) -->
                        <a href="{{ route('scanner.logout') }}" 
                           class="px-5 py-2.5 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 hover:bg-rose-100 hover:text-rose-700 text-xs font-bold font-sora shadow-sm transition duration-200 flex items-center gap-1.5"
                           title="Logout Scanner Device">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER HINT -->
    <footer class="py-4 text-center border-t border-slate-100 bg-white">
        <p class="text-[10px] text-slate-400 font-medium font-sora">ATTENSYS Attendance System</p>
    </footer>

    <script>
        // ── Realtime Clock & Calendars ──────────────────────────────────
        (function () {
            const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            function pad(n) { return String(n).padStart(2, '0'); }
            
            function tick() {
                const clockEl = document.getElementById('realtime-date');
                if (!clockEl) return;
                const now  = new Date();
                const day  = days[now.getDay()];
                const date = pad(now.getDate());
                const mon  = months[now.getMonth()];
                const year = now.getFullYear();
                const time = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                
                clockEl.textContent = `${day}, ${date} ${mon} ${year}  |  ${time}`;
            }
            tick();
            setInterval(tick, 1000);
        })();

        // ── Audio Feedback Synthesizer (Zero External Dependencies) ─────
        let isSoundEnabled = true;
        
        function playScanSound(type) {
            if (!isSoundEnabled) return;
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                if (type === 'success') {
                    // Soft, futuristic double-beep chime
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.setValueAtTime(880, ctx.currentTime); // A5
                    gain.gain.setValueAtTime(0.08, ctx.currentTime);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.08);

                    setTimeout(() => {
                        const osc2 = ctx.createOscillator();
                        const gain2 = ctx.createGain();
                        osc2.connect(gain2);
                        gain2.connect(ctx.destination);
                        osc2.frequency.setValueAtTime(1320, ctx.currentTime); // E6
                        gain2.gain.setValueAtTime(0.08, ctx.currentTime);
                        osc2.start();
                        osc2.stop(ctx.currentTime + 0.12);
                    }, 100);

                } else if (type === 'error') {
                    // Buzz alarm warning tone
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(160, ctx.currentTime);
                    gain.gain.setValueAtTime(0.12, ctx.currentTime);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.25);
                }
            } catch (e) {
                console.warn('Audio synthesis failed:', e);
            }
        }

        // Toggle sound status handler
        const soundToggleBtn = document.getElementById('soundToggle');
        const soundIndicator = document.getElementById('soundStatusIndicator');
        const soundOnIcon = document.getElementById('soundOnIcon');
        const soundOffIcon = document.getElementById('soundOffIcon');
        
        soundToggleBtn.addEventListener('click', () => {
            isSoundEnabled = !isSoundEnabled;
            if (isSoundEnabled) {
                soundIndicator.classList.remove('bg-rose-500');
                soundIndicator.classList.add('bg-emerald-500');
                soundOnIcon.classList.remove('hidden');
                soundOffIcon.classList.add('hidden');
                showToast('Scan sounds enabled', 'info', 1500);
            } else {
                soundIndicator.classList.remove('bg-emerald-500');
                soundIndicator.classList.add('bg-rose-500');
                soundOnIcon.classList.add('hidden');
                soundOffIcon.classList.remove('hidden');
                showToast('Scan sounds disabled', 'info', 1500);
            }
        });

        // ── Camera Scanner State & Logic ────────────────────────────────
        let html5QrCode;
        let isProcessing = false;
        let sessionScans = 0;

        const viewportWrap      = document.getElementById('viewportWrap');
        const statusWrap        = document.getElementById('scanStatusWrap');
        const statusText        = document.getElementById('scanStatusText');
        const scanCountEl       = document.getElementById('scanCount');

        function setStatus(text, type = 'ready') {
            statusText.textContent = text;
            
            // Remove previous styling classes
            viewportWrap.className = 'viewport-wrap w-full';
            statusWrap.className   = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold font-sora transition-colors duration-300';
            
            // Apply contextual classes
            if (type === 'ready') {
                viewportWrap.classList.add('state-scanning');
                statusWrap.classList.add('bg-indigo-50', 'text-indigo-700', 'border', 'border-indigo-100');
                statusWrap.querySelector('span').className = 'w-2 h-2 rounded-full bg-indigo-600 pulse-active';
            } else if (type === 'success') {
                viewportWrap.classList.add('state-success');
                statusWrap.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-100');
                statusWrap.querySelector('span').className = 'w-2 h-2 rounded-full bg-emerald-600';
            } else if (type === 'error') {
                viewportWrap.classList.add('state-error');
                statusWrap.classList.add('bg-rose-50', 'text-rose-700', 'border', 'border-rose-100');
                statusWrap.querySelector('span').className = 'w-2 h-2 rounded-full bg-rose-600';
            } else if (type === 'holiday') {
                viewportWrap.classList.add('state-error');
                statusWrap.classList.add('bg-amber-50', 'text-amber-700', 'border', 'border-amber-100');
                statusWrap.querySelector('span').className = 'w-2 h-2 rounded-full bg-amber-500';
            }
        }

        // Swal Toast mixin
        function showToast(msg, type = 'success', ms = 3000) {
            const iconMap = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
            Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: ms,
                timerProgressBar: true,
            }).fire({ icon: iconMap[type] || 'info', title: msg });
        }

        // Holiday Check
        async function checkHolidayAndInit() {
            try {
                const today = new Date().toLocaleDateString('en-CA');
                const res   = await fetch(`/scanner/holidays/check?date=${today}`);
                const data  = await res.json();
                if (data.is_holiday) {
                    setStatus(`🎉 Holiday: ${data.name}`, 'holiday');
                    showHolidayOverlay(data.name);
                    return;
                }
            } catch (e) {
                console.warn('Holiday check skipped or failed, continuing...');
            }
            initScanner();
        }

        function showHolidayOverlay(name) {
            const el = document.getElementById('qr-reader');
            if (!el) return;
            const laser = document.querySelector('.scan-laser');
            if (laser) laser.style.display = 'none';
            el.innerHTML = `
                <div class="holiday-overlay">
                    <div class="text-5xl">🎉</div>
                    <p class="text-sm font-extrabold text-amber-400 font-sora leading-snug">${name}</p>
                    <p class="text-[11px] text-slate-400 max-w-[280px] mx-auto leading-relaxed">
                        Camera disabled today. A holiday has been detected on the system calendar.
                    </p>
                </div>`;
            // pauseResumeToggle is removed
        }

        // Initialize Camera
        function initScanner() {
            if (!window.Html5Qrcode) {
                setStatus('Library error', 'error');
                return;
            }
            html5QrCode = new Html5Qrcode("qr-reader");

            Html5Qrcode.getCameras().then(cams => {
                if (!cams || cams.length === 0) {
                    setStatus('No camera found', 'error');
                    return;
                }

                // If multiple cameras detected, populate selector
                if (cams.length > 1) {
                    const sel  = document.getElementById('cameraSelect');
                    const wrap = document.getElementById('cameraSelectWrap');
                    sel.innerHTML = '';
                    cams.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.text  = c.label || `Lens ${sel.options.length + 1}`;
                        sel.appendChild(opt);
                    });
                    wrap.classList.remove('hidden');
                    sel.addEventListener('change', () => startCam(sel.value));
                }

                startCam(cams[0].id);
            }).catch(() => setStatus('Camera permission denied', 'error'));
        }

        function startCam(camId) {
            if (html5QrCode.isScanning) {
                html5QrCode.stop().then(() => doStart(camId)).catch(console.error);
            } else {
                doStart(camId);
            }
        }

        function doStart(camId) {
            html5QrCode.start(
                camId,
                {
                    fps: 15,
                    qrbox: (w, h) => {
                        const s = Math.min(w, h) * 0.75;
                        return { width: s, height: s };
                    },
                    aspectRatio: 1.777778, // Widescreen 16:9 ratio
                },
                onScanSuccess,
                () => {} // Silent catch scan loop
            ).then(() => {
                setStatus('Ready to Scan', 'ready');
            }).catch(err => {
                console.error(err);
                setStatus('Camera start failed', 'error');
            });
        }

        // pauseResumeToggle click handler removed

        // ── Scan Result Processor ────────────────────────────────────────
        function onScanSuccess(decodedText) {
            if (isProcessing) return;
            isProcessing = true;
            setStatus('Processing...', 'ready');

            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            fetch("{{ route('scanner.process-qr') }}", {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body:    JSON.stringify({ qr_data: decodedText }),
            })
            .then(async r => {
                const contentType = r.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    // Server returned HTML (e.g., redirect to login page or PHP fatal error)
                    const text = await r.text();
                    console.error('[ATTENSYS] Non-JSON response from server:', r.status, text.substring(0, 200));
                    throw new Error('Server returned non-JSON response (status ' + r.status + '). Check server logs.');
                }
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    playScanSound('success');
                    
                    const isCheckIn = data.type === 'check_in';
                    const msg = isCheckIn
                        ? `✅ Check In — ${data.employee} (${data.time})`
                        : `✅ Check Out — ${data.employee} (${data.time})`;

                    setStatus(isCheckIn ? 'Check In Successful' : 'Check Out Successful', 'success');
                    showToast(msg, 'success', 4000);

                    // Update session counter
                    sessionScans++;
                    if (scanCountEl) scanCountEl.textContent = sessionScans;

                    // Pause camera for 3 seconds to avoid duplicate scans
                    html5QrCode.pause();
                    setTimeout(() => {
                        html5QrCode.resume();
                        isProcessing = false;
                        setStatus('Ready to Scan', 'ready');
                    }, 3000);

                } else {
                    playScanSound('error');
                    setStatus(data.message || 'Scan rejected', 'error');
                    showToast(data.message || 'Scan rejected by server', 'error', 4000);
                    
                    setTimeout(() => {
                        isProcessing = false;
                        setStatus('Ready to Scan', 'ready');
                    }, 3500);
                }
            })
            .catch(err => {
                console.error('[ATTENSYS] Scanner fetch error:', err);
                playScanSound('error');
                const errMsg = err && err.message && !err.message.includes('JSON') 
                    ? err.message 
                    : 'Failed to connect to server. Please check your network connection.';
                setStatus('Connection Failed', 'error');
                showToast(errMsg, 'error', 4000);
                
                setTimeout(() => {
                    isProcessing = false;
                    setStatus('Ready to Scan', 'ready');
                }, 3500);
            });
        }

        // ── DOM Listeners & Lifecycles ──────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            setStatus('Initializing...', 'ready');
            checkHolidayAndInit();
        });

        window.addEventListener('beforeunload', () => {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().catch(() => {});
            }
        });
    </script>
</body>
</html>
