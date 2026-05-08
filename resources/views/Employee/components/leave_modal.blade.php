<style>
    /* Leave Modal — matching Super Admin modal-overlay / modal-box style */
    #leaveModal .leave-modal-box {
        background: white;
        border-radius: 24px;
        width: 480px;
        max-width: 95vw;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        overflow: hidden;
        transform: translateY(20px);
        transition: transform 0.25s ease;
    }
    #leaveModal.open .leave-modal-box {
        transform: translateY(0);
    }
    .leave-modal-header {
        padding: 28px 32px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    .leave-modal-header h3 {
        font-family: 'Sora', sans-serif;
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
    }
    .leave-modal-header p {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 4px;
    }
    .leave-modal-header .close-btn {
        padding: 8px;
        border-radius: 12px;
        border: none;
        background: transparent;
        cursor: pointer;
        color: #94a3b8;
        font-size: 16px;
        transition: all 0.2s;
    }
    .leave-modal-header .close-btn:hover {
        background: #f1f5f9;
        color: #64748b;
    }
    .leave-modal-body {
        padding: 24px 32px;
    }
    .leave-modal-footer {
        padding: 16px 32px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        border-top: 1px solid #f1f5f9;
    }

    /* Form field — same as Super Admin .form-field + .form-label + .form-input */
    .leave-form-field {
        margin-bottom: 16px;
    }
    .leave-form-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
        font-family: 'Sora', sans-serif;
    }
    .leave-form-input,
    .leave-form-select,
    .leave-form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.875rem;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: 'DM Sans', sans-serif;
    }
    .leave-form-input:focus,
    .leave-form-select:focus,
    .leave-form-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        background: white;
    }
    .leave-form-textarea {
        resize: none;
    }
    .leave-form-select {
        cursor: pointer;
    }

    /* Type Pills */
    .type-pills { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .type-pill {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 14px; border: 1.5px solid #e2e8f0;
        border-radius: 14px; cursor: pointer; transition: all 0.2s;
        background: #f8fafc;
    }
    .type-pill input[type="radio"] { display: none; }
    .type-pill.active { border-color: #6366f1; background: #eef2ff; }
    .type-pill:hover:not(.active) { border-color: #c7d2fe; background: #f5f3ff; }
    .pill-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 17px; flex-shrink: 0;
    }
    .type-pill.active .pill-icon { background: linear-gradient(135deg, #6366f1, #06b6d4); }
    .type-pill:not(.active) .pill-icon { background: #e2e8f0; }
    .pill-label { font-size: 0.84rem; font-weight: 600; color: #334155; font-family: 'Sora', sans-serif; }
    .pill-desc  { font-size: 0.72rem; color: #94a3b8; margin-top: 1px; font-family: 'DM Sans', sans-serif; }

    /* Upload Zone */
    .upload-zone {
        border: 2px dashed #c7d2fe; border-radius: 14px;
        padding: 20px 16px; background: #f5f3ff;
        text-align: center; cursor: pointer; transition: all 0.2s;
        position: relative;
    }
    .upload-zone:hover { border-color: #6366f1; background: #eef2ff; }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0;
        cursor: pointer; width: 100%; height: 100%;
    }
    .upload-emoji { font-size: 24px; margin-bottom: 6px; }
    .upload-text { font-size: 0.84rem; font-weight: 600; color: #374151; font-family: 'Sora', sans-serif; }
    .upload-hint { font-size: 0.72rem; color: #94a3b8; margin-top: 4px; }

    /* Buttons matching SA style */
    .btn-leave-ghost {
        background: transparent;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
        padding: 9px 20px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-leave-ghost:hover { border-color: #6366f1; color: #6366f1; background: #eef2ff; }

    .btn-leave-primary {
        background: linear-gradient(135deg, #6366f1, #06b6d4);
        color: white;
        border: none;
        padding: 9px 20px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-family: 'Sora', sans-serif;
        transition: opacity 0.2s, transform 0.15s;
        box-shadow: 0 4px 14px rgba(99,102,241,0.25);
    }
    .btn-leave-primary:hover { opacity: 0.9; transform: translateY(-1px); }
</style>

<!-- ===== LEAVE MODAL (Super Admin Style) ===== -->
<div id="leaveModal"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50"
     onclick="closeLeaveModalOutside(event)">
    <div class="leave-modal-box" onclick="event.stopPropagation()">

        <!-- Header (SA style: title + subtitle + close button) -->
        <div class="leave-modal-header">
            <div>
                <h3 id="leaveModalTitle">New Leave Request</h3>
                <p id="leaveModalSub">Fill in the details to submit a leave or sick request</p>
            </div>
            <button type="button" onclick="closeLeaveModal()" class="close-btn">✕</button>
        </div>

        <!-- Body -->
        <div class="leave-modal-body">
            <form id="leaveForm" action="{{ route('employee.attendance.permission') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Type Selector -->
                <div class="leave-form-field">
                    <label class="leave-form-label">Request Type</label>
                    <div class="type-pills">
                        <label class="type-pill active" id="pill-permission">
                            <input type="radio" name="type" value="Leave" checked onchange="setPill('leave')">
                            <div class="pill-icon">🏖️</div>
                            <div>
                                <div class="pill-label">Permission</div>
                                <div class="pill-desc">Personal leave</div>
                            </div>
                        </label>
                        <label class="type-pill" id="pill-sick">
                            <input type="radio" name="type" value="Sick" onchange="setPill('sick')">
                            <div class="pill-icon">🏥</div>
                            <div>
                                <div class="pill-label">Sick</div>
                                <div class="pill-desc">Medical leave</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Date Range (2-col grid like SA) -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="leave-form-field">
                        <label class="leave-form-label">Start Date</label>
                        <input type="date" name="start_date" class="leave-form-input" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="leave-form-field">
                        <label class="leave-form-label">End Date</label>
                        <input type="date" name="completion_date" class="leave-form-input" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Leave Category -->
                <div class="leave-form-field">
                    <label class="leave-form-label">Leave Category</label>
                    <select id="leave_category" name="leave_category" class="leave-form-select" onchange="updateInformation()">
                        <!-- Options populated via JS -->
                    </select>
                </div>

                <!-- Reason (Additional details) -->
                <div class="leave-form-field">
                    <label class="leave-form-label">Additional Details (Optional)</label>
                    <textarea id="leave_detail" class="leave-form-textarea" rows="2"
                              placeholder="Explain further details..." oninput="updateInformation()"></textarea>
                </div>

                <!-- Hidden Input for Controller -->
                <input type="hidden" name="information" id="real_information" required>

                <!-- File Upload -->
                <div class="leave-form-field">
                    <label class="leave-form-label" id="attachment_label">Attachment</label>
                    <div class="upload-zone" id="uploadZone">
                        <input type="file" name="file" id="file_upload" accept="application/pdf" onchange="updateFileName(this)">
                        <div class="upload-emoji">📎</div>
                        <div class="upload-text" id="uploadText">Click or drag to upload your document</div>
                        <div class="upload-hint">PDF files only (max 2MB)</div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mt-2 text-xs text-red-600">
                        @foreach($errors->all() as $e) <p>• {{ $e }}</p> @endforeach
                    </div>
                @endif
            </form>
        </div>

        <!-- Footer (SA style: Cancel + Submit) -->
        <div class="leave-modal-footer">
            <button type="button" class="btn-leave-ghost" onclick="closeLeaveModal()">Cancel</button>
            <button type="submit" form="leaveForm" class="btn-leave-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                </svg>
                <span id="leaveSubmitText">Submit Request</span>
            </button>
        </div>

    </div>
</div>

<script>
    if (typeof window.openLeaveModal !== 'function') {
            window.openLeaveModal = function(isEdit = false, hasFile = false) {
            const m = document.getElementById('leaveModal');
            if(m) {
                isEditing = isEdit;
                hasExistingFile = hasFile;

                if (!isEdit) {
                    // Reset form for NEW request
                    document.getElementById('leaveForm').reset();
                    document.getElementById('leaveForm').action = "{{ route('employee.attendance.permission') }}";
                    document.getElementById('leaveModalTitle').textContent = "New Leave Request";
                    document.getElementById('leaveModalSub').textContent = "Fill in the details to submit a leave or sick request";
                    document.getElementById('leaveSubmitText').textContent = "Submit Request";
                    document.getElementById('uploadText').textContent = "Click or drag to upload your document";
                    setPill('leave');
                    updateInformation();
                }
                m.classList.remove('hidden');
                m.classList.add('flex');
                setTimeout(() => m.classList.add('open'), 10);
            }
        };

        window.editLeave = function(data) {
            const form = document.getElementById('leaveForm');
            form.action = `{{ url('employee/attendance/permission') }}/${data.id}/update`;
            
            document.getElementById('leaveModalTitle').textContent = "Edit Leave Request";
            document.getElementById('leaveModalSub').textContent = "Update your pending leave request details";
            document.getElementById('leaveSubmitText').textContent = "Save Changes";
            document.getElementById('uploadText').textContent = data.hasFile ? "✅ Document already uploaded" : "Click or drag to upload your document";

            // Set type
            const typeVal = data.type.toLowerCase() === 'leave' ? 'leave' : 'sick';
            const radio = document.querySelector(`input[name="type"][value="${data.type}"]`);
            if(radio) radio.checked = true;
            setPill(typeVal);

            // Set dates
            form.querySelector('input[name="start_date"]').value = data.start_raw;
            form.querySelector('input[name="completion_date"]').value = data.end_raw;

            // Set category after a short delay for setPill to finish
            setTimeout(() => {
                const catSelect = document.getElementById('leave_category');
                catSelect.value = data.category;
                
                // Set information/reason
                document.getElementById('leave_detail').value = data.information === '-' ? '' : data.information;
                updateInformation();
            }, 50);

            window.openLeaveModal(true, data.hasFile);
        };

        window.closeLeaveModal = function() {
            const m = document.getElementById('leaveModal');
            if(m) {
                m.classList.remove('open');
                setTimeout(() => {
                    m.classList.add('hidden');
                    m.classList.remove('flex');
                }, 250);
            }
        };
        window.closeLeaveModalOutside = function(e) {
            if (e.target === document.getElementById('leaveModal')) closeLeaveModal();
        };
        const permissionOptions = [
            { text: "Marriage Leave", requireUpload: true },
            { text: "Maternity Leave", requireUpload: true },
            { text: "Annual Leave", requireUpload: false },
            { text: "Bereavement Leave", requireUpload: false },
            { text: "Personal Leave", requireUpload: false },
            { text: "Family Event", requireUpload: false },
            { text: "Others", requireUpload: false }
        ];
        
        const sickOptions = [
            { text: "Sick Leave with Medical Certificate", requireUpload: true },
            { text: "Hospitalization", requireUpload: true },
            { text: "Accident", requireUpload: true },
            { text: "Mild Illness (Flu / Fever)", requireUpload: false },
            { text: "Outpatient Care", requireUpload: false },
            { text: "Medical Checkup", requireUpload: false },
            { text: "Others", requireUpload: false }
        ];

        let isEditing = false;
        let hasExistingFile = false;

        window.setPill = function(type) {
            const pillPerm = document.getElementById('pill-permission');
            const pillSick = document.getElementById('pill-sick');
            if(pillPerm) pillPerm.classList.toggle('active', type === 'permission' || type === 'leave');
            if(pillSick) pillSick.classList.toggle('active', type === 'sick');

            const catSelect = document.getElementById('leave_category');
            if(catSelect) {
                catSelect.innerHTML = '';
                catSelect.name = (type === 'permission' || type === 'leave') ? 'leave_category' : 'sick_category';
                const opts = (type === 'permission' || type === 'leave') ? permissionOptions : sickOptions;
                opts.forEach(o => {
                    const opt = document.createElement('option');
                    opt.value = o.text;
                    opt.textContent = o.text;
                    opt.dataset.requireUpload = o.requireUpload;
                    catSelect.appendChild(opt);
                });
                updateInformation();
            }
        };

        window.updateInformation = function() {
            const det = document.getElementById('leave_detail').value;
            const hiddenInfo = document.getElementById('real_information');
            if(hiddenInfo) {
                hiddenInfo.value = det.trim();
            }
            updateUploadRequirement();
        };

        window.updateUploadRequirement = function() {
            const catSelect = document.getElementById('leave_category');
            if (!catSelect || catSelect.selectedIndex === -1) return;
            
            const selectedOption = catSelect.options[catSelect.selectedIndex];
            const isRequired = selectedOption.dataset.requireUpload === 'true';
            
            const fileInput = document.getElementById('file_upload');
            const labelEl = document.getElementById('attachment_label');
            const uploadZone = document.getElementById('uploadZone');
            
            if (fileInput) {
                // If we are editing and there's already a file, don't force re-upload
                if (isEditing && hasExistingFile) {
                    fileInput.required = false;
                } else {
                    fileInput.required = isRequired;
                }
            }
            
            if (labelEl) {
                if (isRequired) {
                    labelEl.innerHTML = 'Attachment <span style="color:#ef4444; font-weight:bold;">*</span>';
                    if(uploadZone) uploadZone.style.borderColor = fileInput.files.length ? '#6366f1' : '#f87171';
                } else {
                    labelEl.innerHTML = 'Attachment <span style="color:#94a3b8; font-weight:normal;">(Optional)</span>';
                    if(uploadZone) uploadZone.style.borderColor = '#c7d2fe';
                }
            }
        };

        // Initialize default options
        document.addEventListener('DOMContentLoaded', function() {
            const activeRadio = document.querySelector('input[name="type"]:checked');
            if(activeRadio) {
                const val = activeRadio.value.toLowerCase();
                setPill(val === 'leave' ? 'permission' : val);
            } else {
                setPill('permission'); 
            }
            updateInformation();

            const form = document.getElementById('leaveForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const catSelect = document.getElementById('leave_category');
                    const fileInput = document.getElementById('file_upload');
                    const selectedOption = catSelect.options[catSelect.selectedIndex];
                    const isRequired = selectedOption.dataset.requireUpload === 'true';

                    if (isRequired && !fileInput.files.length && (!isEditing || !hasExistingFile)) {
                        e.preventDefault();
                        if(typeof showToast === 'function') {
                            showToast('Please upload a supporting document for this category.', 'error');
                        } else {
                            alert('Please upload a supporting document for this category.');
                        }
                        document.getElementById('uploadZone').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            }
        });

        window.updateFileName = function(input) {
            const el = document.getElementById('uploadText');
            if (input.files.length && el) {
                el.textContent = '✅ ' + input.files[0].name;
                document.getElementById('uploadZone').style.borderColor = '#6366f1';
            } else {
                el.textContent = 'Click or drag to upload your document';
                updateUploadRequirement();
            }
        };

        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() { window.openLeaveModal(); });
        @endif
    }
</script>
