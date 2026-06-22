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
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        overflow-x: hidden;
    }
    .leave-modal-body::-webkit-scrollbar {
        width: 6px;
    }
    .leave-modal-body::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .leave-modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .leave-modal-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
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
    
    /* Spinner animation */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @-webkit-keyframes spin {
        from { -webkit-transform: rotate(0deg); }
        to { -webkit-transform: rotate(360deg); }
    }
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
            <form id="leaveForm" action="{{ route('employee.attendance.permission') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" id="userGender" value="{{ Auth::user()->gender ?? 'Male' }}">

                <!-- Type Selector -->
                <div class="leave-form-field">
                    <label class="leave-form-label">Request Type</label>
                    <div class="type-pills">
                        <label class="type-pill active" id="pill-permission">
                            <input type="radio" name="type" value="Leave" checked onchange="setPill('leave')">
                            <div class="pill-icon">🏖️</div>
                            <div>
                                <div class="pill-label">Leave</div>
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

                <!-- Duration Selector -->
                <div class="leave-form-field">
                    <label class="leave-form-label">Duration</label>
                    <div class="duration-pills grid grid-cols-2 gap-3">
                        <label class="type-pill active" id="pill-full">
                            <input type="radio" name="duration_mode" value="full" checked onchange="setLeaveDuration('full')">
                            <div class="pill-icon">📅</div>
                            <div>
                                <div class="pill-label">Full Day</div>
                                <div class="pill-desc">All-day absence</div>
                            </div>
                        </label>
                        <label class="type-pill" id="pill-partial">
                            <input type="radio" name="duration_mode" value="partial" onchange="setLeaveDuration('partial')">
                            <div class="pill-icon">⏰</div>
                            <div>
                                <div class="pill-label">Partial</div>
                                <div class="pill-desc">Specific hours</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Date Range (2-col grid like SA) -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="leave-form-field">
                        <label class="leave-form-label">Start Date</label>
                        <input type="date" name="start_date" class="leave-form-input" required min="{{ date('Y-m-d') }}" onchange="updateEndDate()">
                    </div>
                    <div class="leave-form-field">
                        <label class="leave-form-label">End Date</label>
                        <input type="date" name="completion_date" class="leave-form-input" required min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Time Range (only for Partial mode) -->
                <div id="timeRangeDiv" class="grid grid-cols-2 gap-4 hidden">
                    <div class="leave-form-field">
                        <label class="leave-form-label">Start Time</label>
                        <input type="time" name="start_time" class="leave-form-input" min="08:00" max="17:00">
                    </div>
                    <div class="leave-form-field">
                        <label class="leave-form-label">End Time</label>
                        <input type="time" name="end_time" class="leave-form-input" min="08:00" max="17:00">
                    </div>
                </div>

                <!-- Leave Category -->
                <div class="leave-form-field">
                    <label class="leave-form-label">Leave Category</label>
                    <select id="leave_category" name="leave_category" class="leave-form-select" onchange="updateInformation()">
                        <!-- Options populated via JS -->
                    </select>
                    <!-- Category detail box shown below dropdown -->
                    <div id="category_detail_box" class="mt-2 p-3 bg-indigo-50 border border-indigo-100 rounded-xl flex items-start gap-3 hidden">
                        <div class="text-indigo-500 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p id="cat_detail_text" class="text-xs text-slate-600 leading-relaxed font-medium font-dm-sans"></p>
                        </div>
                    </div>
                </div>

                <!-- Reason (Additional details) -->
                <div class="leave-form-field">
                    <label class="leave-form-label">Additional Details (Optional)</label>
                    <textarea name="information" id="leave_detail" class="leave-form-textarea" rows="2"
                              placeholder="Explain further details..." oninput="updateInformation()"></textarea>
                </div>

                <!-- Hidden Input for Controller (information field is set dynamically) -->

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
            <button type="button" class="btn-leave-ghost" onclick="closeLeaveModal()" id="cancelLeaveBtn">Cancel</button>
            <button type="button" class="btn-leave-primary" id="submitLeaveBtn" onclick="submitLeaveForm()">
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
                    
                    const form = document.getElementById('leaveForm');
                    const today = new Date().toISOString().split('T')[0];
                    const startInput = form.querySelector('input[name="start_date"]');
                    const endInput = form.querySelector('input[name="completion_date"]');
                    if (startInput) startInput.value = today;
                    if (endInput) endInput.value = today;
                    
                    updateInformation();
                }
                m.classList.remove('hidden');
                m.classList.add('flex');
                setTimeout(() => m.classList.add('open'), 10);
            }
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
        const userGender = document.getElementById('userGender')?.value || 'Male';
        
        const permissionOptions = [
            { text: "Marriage Leave", requireUpload: true, min: 1, max: 3 },
            ...(userGender === 'Female' ? [{ text: "Maternity Leave", requireUpload: true, min: 1, max: 90 }] : []),
            { text: "Annual Leave", requireUpload: false, min: 1, max: 12 },
            { text: "Bereavement Leave", requireUpload: false, min: 1, max: 3 },
            { text: "Personal Leave", requireUpload: false, min: 1, max: 1 },
            { text: "Family Event", requireUpload: false, min: 1, max: 2 },
            { text: "Hajj Leave", requireUpload: true, min: 1, max: 40 },
            { text: "Umrah Leave", requireUpload: true, min: 1, max: 15 },
            { text: "Official Duty Leave", requireUpload: true, min: 1, max: 'Unlimited' }
        ];
        
        const sickOptions = [
            { text: "Sick Leave with Medical Certificate", requireUpload: true, min: 1, max: 14 },
            { text: "Hospitalization", requireUpload: true, min: 1, max: 'Unlimited' },
            { text: "Accident", requireUpload: true, min: 1, max: 'Unlimited' },
            { text: "Mild Illness (Flu / Fever)", requireUpload: false, min: 1, max: 2 },
            { text: "Outpatient Care", requireUpload: false, min: 1, max: 1 },
            { text: "Medical Checkup", requireUpload: false, min: 1, max: 1 }
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
                    opt.dataset.maxDays = o.max;
                    catSelect.appendChild(opt);
                });
                updateInformation();
            }
        };

        window.updateInformation = function(isFromEdit = false) {
            const det = document.getElementById('leave_detail').value;
            // The information field will be set automatically from textarea
            updateUploadRequirement();
            if (!isFromEdit && !isEditing) {
                updateEndDate();
            }
        };

        window.updateEndDate = function() {
            const form = document.getElementById('leaveForm');
            if(!form) return;
            const startInput = form.querySelector('input[name="start_date"]');
            const endInput = form.querySelector('input[name="completion_date"]');
            if(!startInput || !endInput || !startInput.value) return;

            const catSelect = document.getElementById('leave_category');
            if(!catSelect || catSelect.selectedIndex === -1) return;

            const selectedOption = catSelect.options[catSelect.selectedIndex];
            const maxDaysStr = selectedOption?.dataset.maxDays;

            if (maxDaysStr && maxDaysStr !== 'Unlimited') {
                const maxDays = parseInt(maxDaysStr, 10);
                
                const startDate = new Date(startInput.value);
                if(!isNaN(startDate.getTime())) {
                    const endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + (maxDays - 1));
                    
                    const yyyy = endDate.getFullYear();
                    const mm = String(endDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(endDate.getDate()).padStart(2, '0');
                    
                    endInput.value = `${yyyy}-${mm}-${dd}`;
                }
            }
        };

        window.setLeaveDuration = function(mode) {
            const timeDiv = document.getElementById('timeRangeDiv');
            if (!timeDiv) return;

            // Toggle active styling for duration cards
            const pillFull = document.getElementById('pill-full');
            const pillPartial = document.getElementById('pill-partial');
            if (pillFull) pillFull.classList.toggle('active', mode === 'full');
            if (pillPartial) pillPartial.classList.toggle('active', mode === 'partial');

            if (mode === 'partial') {
                timeDiv.classList.remove('hidden');
            } else {
                timeDiv.classList.add('hidden');
                // clear time inputs
                const inputs = timeDiv.querySelectorAll('input');
                inputs.forEach(i => i.value = '');
            }
        };
        // Initialize based on default selection
        document.addEventListener('DOMContentLoaded', function() {
            const defaultMode = document.querySelector('input[name="duration_mode"]:checked')?.value || 'full';
            setLeaveDuration(defaultMode);
        });

        window.updateUploadRequirement = function() {
            const catSelect = document.getElementById('leave_category');
            if (!catSelect || catSelect.selectedIndex === -1) return;
            
            const selectedOption = catSelect.options[catSelect.selectedIndex];
            const isRequired = selectedOption.dataset.requireUpload === 'true';
            
            // Show details below dropdown
            const detailBox = document.getElementById('category_detail_box');
            const detailText = document.getElementById('cat_detail_text');
            if (detailBox && detailText) {
                const maxDays = selectedOption.dataset.maxDays;
                const reqText = isRequired ? "Requires a supporting document (PDF)." : "No document required.";
                const maxText = maxDays === 'Unlimited' ? "No maximum day limit." : `Maximum of <b>${maxDays} days</b>.`;
                detailText.innerHTML = `${maxText} ${reqText}`;
                detailBox.classList.remove('hidden');
            }

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
            updateUploadRequirement();

            const form = document.getElementById('leaveForm');
            if (form) {
                // Set default dates to today
                const today = new Date().toISOString().split('T')[0];
                const startInput = form.querySelector('input[name="start_date"]');
                const endInput = form.querySelector('input[name="completion_date"]');
                if (startInput && !startInput.value) startInput.value = today;
                if (endInput && !endInput.value) endInput.value = today;
                if (!isEditing) updateEndDate();
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

        window.submitLeaveForm = function() {
            const form = document.getElementById('leaveForm');
            if (!form) {
                showNotification('Form not found', 'error');
                return;
            }

            // Validate required fields
            const startDate = form.querySelector('input[name="start_date"]').value;
            const endDate = form.querySelector('input[name="completion_date"]').value;
            const type = form.querySelector('input[name="type"]:checked')?.value;
            const category = document.getElementById('leave_category')?.value;

            if (!startDate) {
                showNotification('Please select a start date', 'error');
                return;
            }
            if (!endDate) {
                showNotification('Please select an end date', 'error');
                return;
            }
            if (!type) {
                showNotification('Please select request type', 'error');
                return;
            }
            if (!category || category === '') {
                showNotification('Please select a leave category', 'error');
                return;
            }

            // Check if file upload is required
            const catSelect = document.getElementById('leave_category');
            const fileInput = document.getElementById('file_upload');
            const selectedOption = catSelect.options[catSelect.selectedIndex];
            const isRequired = selectedOption?.dataset.requireUpload === 'true';

            if (isRequired && !fileInput.files.length && (!isEditing || !hasExistingFile)) {
                showNotification('Please upload a supporting document for this category', 'error');
                document.getElementById('uploadZone').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Max days validation
            const maxDaysStr = selectedOption?.dataset.maxDays;
            if (maxDaysStr && maxDaysStr !== 'Unlimited') {
                const maxDays = parseInt(maxDaysStr, 10);
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                // Check if end date is before start date
                if (end < start) {
                    showNotification('End date cannot be before start date', 'error');
                    return;
                }
                
                // Calculate difference in days (inclusive)
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                if (diffDays > maxDays) {
                    showNotification(`Maximum duration for this category is ${maxDays} days`, 'error');
                    return;
                }
            }

            // Disable button and show loading state
            const submitBtn = document.getElementById('submitLeaveBtn');
            const cancelBtn = document.getElementById('cancelLeaveBtn');
            submitBtn.disabled = true;
            cancelBtn.disabled = true;
            submitBtn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"></circle><path stroke="currentColor" stroke-width="2" d="M12 2a10 10 0 010 20"></path></svg><span>Submitting...</span>';

            // Use FormData to properly handle file uploads
            const formData = new FormData(form);
            
            console.log('Submitting form with data:', {
                type: formData.get('type'),
                start_date: formData.get('start_date'),
                completion_date: formData.get('completion_date'),
                leave_category: formData.get('leave_category'),
                sick_category: formData.get('sick_category'),
                information: formData.get('information'),
                has_file: fileInput.files.length > 0
            });

            // Submit using fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.status === 302 || response.status === 301) {
                    // Redirect - submission successful
                    return response.text().then(() => {
                        showNotification('Leave request submitted successfully! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    });
                } else if (response.ok) {
                    return response.json().then(data => {
                        showNotification(data.message || 'Leave request submitted successfully!', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }).catch(() => {
                        // If response is not JSON, assume success
                        showNotification('Leave request submitted successfully!', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    });
                } else {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        // Try to parse as JSON for error details
                        try {
                            const data = JSON.parse(text);
                            let errMsg = data.message || 'Submission failed';
                            if (data.errors) {
                                const firstKey = Object.keys(data.errors)[0];
                                if (firstKey && data.errors[firstKey].length) {
                                    errMsg = data.errors[firstKey][0];
                                }
                            }
                            throw new Error(errMsg);
                        } catch (e) {
                            throw new Error(e.message || 'Submission failed. Please try again.');
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showNotification('Error: ' + error.message, 'error');
                submitBtn.disabled = false;
                cancelBtn.disabled = false;
                submitBtn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg><span id="leaveSubmitText">Submit Request</span>';
            });
        };

        window.showNotification = function(message, type = 'info') {
            console.log(`[Leave Modal] ${type.toUpperCase()}: ${message}`);
            
            // Try multiple notification systems
            if (typeof showToast === 'function') {
                if (type === 'success') {
                    showToast(message, 'success');
                } else if (type === 'error') {
                    showToast(message, 'error');
                } else {
                    showToast(message, 'info');
                }
            } else if (window.Toastify) {
                const bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: bgColor,
                }).showToast();
            } else {
                // Fallback to alert
                alert(`[${type.toUpperCase()}] ${message}`);
            }
        };

        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() { window.openLeaveModal(); });
        @endif

        // Handle success/error messages from session
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showNotification("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                showNotification("{{ session('error') }}", 'error');
            @endif
            
            // Reset submit button after page load
            setTimeout(() => {
                const submitBtn = document.getElementById('submitLeaveBtn');
                const cancelBtn = document.getElementById('cancelLeaveBtn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg><span id="leaveSubmitText">Submit Request</span>';
                }
                if (cancelBtn) {
                    cancelBtn.disabled = false;
                }
            }, 1000);
        });
    }
</script>
