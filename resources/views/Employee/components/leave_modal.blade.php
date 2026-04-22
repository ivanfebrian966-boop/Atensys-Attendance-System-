<style>
    /* ===== MODAL ===== */
    .modal-hero {
        background: linear-gradient(135deg, #07064bff 0%, #080550ff 50%, #10026dff 100%);
        padding: 26px 28px 22px; position: relative; overflow: hidden;
    }
    .modal-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3Ccircle cx='5' cy='5' r='5'/%3E%3Ccircle cx='55' cy='55' r='5'/%3E%3C/g%3E%3C/svg%3E") repeat;
    }
    .modal-hero-content { position: relative; z-index: 1; display: flex; align-items: flex-start; justify-content: space-between; }
    .modal-icon {
        width: 42px; height: 42px; border-radius: 13px;
        background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
    }
    .modal-icon svg { width: 21px; height: 21px; stroke: white; }
    .modal-title { font-family: 'Sora', sans-serif; font-size: 1.15rem; font-weight: 700; color: white; }
    .modal-subtitle { font-size: 0.79rem; color: rgba(255,255,255,0.68); margin-top: 3px; }
    .modal-close-btn {
        width: 33px; height: 33px;
        background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);
        border-radius: 10px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 15px; transition: background 0.2s; flex-shrink: 0;
    }
    .modal-close-btn:hover { background: rgba(255,255,255,0.25); }

    .modal-body { padding: 22px 28px; }
    .modal-footer {
        padding: 14px 28px 22px; display: flex; gap: 10px;
        justify-content: flex-end; border-top: 1px solid #f1f5f9;
    }

    /* Section label */
    .sec-label {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: #94a3b8; margin-bottom: 9px; margin-top: 16px;
    }
    .sec-label:first-child { margin-top: 0; }

    /* Type Pills */
    .type-pills { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .type-pill {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 14px; border: 2px solid #e2e8f0;
        border-radius: 14px; cursor: pointer; transition: all 0.2s;
        background: #f8fafc;
    }
    .type-pill input[type="radio"] { display: none; }
    .type-pill.active { border-color: #141b98ff; background: #eef2ff; }
    .type-pill:hover:not(.active) { border-color: #c7d2fe; background: #f5f3ff; }
    .pill-icon {
        width: 34px; height: 34px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0;
    }
    .type-pill.active .pill-icon { background: #080547ff; }
    .type-pill:not(.active) .pill-icon { background: #e2e8f0; }
    .pill-label { font-size: 0.83rem; font-weight: 600; color: #334155; }
    .pill-desc  { font-size: 0.7rem; color: #94a3b8; margin-top: 1px; }

    /* Date Row */
    .date-row { display: grid; grid-template-columns: 1fr auto 1fr; gap: 8px; align-items: end; }
    .date-dash { font-size: 1.1rem; color: #cbd5e1; padding-bottom: 10px; text-align: center; }

    /* Field */
    .field { display: flex; flex-direction: column; gap: 5px; }
    .field label { font-size: 0.78rem; font-weight: 600; color: #475569; }

    .form-input, .form-select, .form-textarea {
        width: 100%; padding: 10px 13px;
        border: 1.5px solid #e2e8f0; border-radius: 12px;
        font-size: 0.84rem; font-family: inherit; color: #1e293b;
        background: #f8fafc; transition: all 0.2s; outline: none;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #221ba2ff; background: white;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.08);
    }
    .form-textarea { resize: none; }

    /* Upload Zone */
    .upload-zone {
        border: 2px dashed #c7d2fe; border-radius: 14px;
        padding: 18px 16px; background: #f5f3ff;
        text-align: center; cursor: pointer; transition: all 0.2s;
        position: relative;
    }
    .upload-zone:hover { border-color: #4f46e5; background: #eef2ff; }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }
    .upload-emoji { font-size: 22px; margin-bottom: 5px; }
    .upload-text { font-size: 0.82rem; font-weight: 600; color: #09063fff; }
    .upload-hint { font-size: 0.7rem; color: #94a3b8; margin-top: 3px; }

    .btn-ghost-modal {
        padding: 10px 20px; border: 1.5px solid #e2e8f0;
        border-radius: 12px; background: white; color: #64748b;
        font-size: 0.85rem; font-weight: 600; cursor: pointer;
        transition: all 0.2s; font-family: inherit;
    }
    .btn-ghost-modal:hover { border-color: #c7d2fe; color: #0b066aff; background: #f5f3ff; }
</style>

<!-- ===== LEAVE MODAL ===== -->
<div id="leaveModal"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50"
     onclick="closeLeaveModalOutside(event)">
    <div style="background:white; border-radius:28px; width:100%; max-width:520px; margin:16px; box-shadow:0 32px 80px rgba(0,0,0,0.15), 0 4px 16px rgba(0,0,0,0.07); overflow:hidden;"
         onclick="event.stopPropagation()">

        <!-- Hero Header -->
        <div class="modal-hero">
            <div class="modal-hero-content">
                <div>
                    <div class="modal-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                        </svg>
                    </div>
                    <p class="modal-title">New Leave Request</p>
                </div>
                <button type="button" onclick="closeLeaveModal()" class="modal-close-btn">✕</button>
            </div>
        </div>

        <!-- Body -->
        <div class="modal-body">
            <form id="leaveForm" action="{{ route('employee.attendance.permission') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Type Selector -->
                <p class="sec-label">Request Type</p>
                <div class="type-pills">
                    <label class="type-pill active" id="pill-izin">
                        <input type="radio" name="type" value="Izin" checked onchange="setPill('izin')">
                        <div class="pill-icon">🏖️</div>
                        <div>
                            <div class="pill-label">Permission</div>
                            <div class="pill-desc">Personal leave</div>
                        </div>
                    </label>
                    <label class="type-pill" id="pill-sakit">
                        <input type="radio" name="type" value="Sakit" onchange="setPill('sakit')">
                        <div class="pill-icon">🏥</div>
                        <div>
                            <div class="pill-label">Sick</div>
                            <div class="pill-desc">Medical leave</div>
                        </div>
                    </label>
                </div>

                <!-- Date -->
                <p class="sec-label"></p>
                <div class="date-row">
                    <div class="field">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-input" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="date-dash"> </div>
                    <div class="field">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-input" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Reason -->
                <p class="sec-label"></p>
                <div class="field">
                    <label>Reason / Information</label>
                    <textarea name="information" class="form-textarea" rows="3" required
                              placeholder="Explain your reason for this leave"></textarea>
                </div>

                <!-- File Upload -->
                <p class="sec-label">Attachment</p>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" name="file" accept="application/pdf" required onchange="updateFileName(this)">
                    <div class="upload-emoji">📎</div>
                    <div class="upload-text" id="uploadText">Drag and drop your medical certificate here</div>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mt-4 text-xs text-red-600">
                        @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
                    </div>
                @endif
            </form>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
            <button type="button" class="btn-ghost-modal" onclick="closeLeaveModal()">Cancel</button>
            <button type="submit" form="leaveForm" style="background: linear-gradient(135deg, #1c2388ff, #4560b4ff); color: white; border-radius: 12px; padding: 10px 20px; display: inline-flex; align-items: center; gap: 7px; border: none; cursor: pointer; font-family: 'DM Sans', 'Sora', sans-serif; font-size: 0.85rem; font-weight: 700; box-shadow: 0 4px 14px rgba(79,70,229,0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(79,70,229,0.4)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 14px rgba(79,70,229,0.3)';">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                </svg>
                Submit Request
            </button>
        </div>

    </div>
</div>

<script>
    if (typeof window.openLeaveModal !== 'function') {
        window.openLeaveModal = function() {
            const m = document.getElementById('leaveModal');
            if(m) {
                m.classList.remove('hidden');
                m.classList.add('flex');
            }
        };
        window.closeLeaveModal = function() {
            const m = document.getElementById('leaveModal');
            if(m) {
                m.classList.add('hidden');
                m.classList.remove('flex');
            }
        };
        window.closeLeaveModalOutside = function(e) {
            if (e.target === document.getElementById('leaveModal')) closeLeaveModal();
        };
        window.setPill = function(type) {
            const pillIzin = document.getElementById('pill-izin');
            const pillSakit = document.getElementById('pill-sakit');
            if(pillIzin) pillIzin.classList.toggle('active', type === 'izin');
            if(pillSakit) pillSakit.classList.toggle('active', type === 'sakit');
        };
        window.updateFileName = function(input) {
            const el = document.getElementById('uploadText');
            if (input.files.length && el) {
                el.textContent = '✅ ' + input.files[0].name;
                document.getElementById('uploadZone').style.borderColor = '#4f46e5';
            }
        };

        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() { window.openLeaveModal(); });
        @endif
    }
</script>
