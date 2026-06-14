<!-- ===== MODAL: Tambah Karyawan ===== -->
<div class="modal-overlay" id="modalAddEmployee" onclick="closeModalOutside(event,'modalAddEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add Employee Account</h3>
                <p class="text-sm text-slate-400 mt-1">Fill in the data to create a new employee account</p>
            </div>
            <button onclick="closeModal('modalAddEmployee')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>

        <!-- Step Indicator -->
        <div class="flex items-center gap-2 mb-6">
            <div id="addEmpDot1" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background: linear-gradient(135deg,#6366f1,#06b6d4)">1</div>
            <div class="flex-1 h-1 rounded" style="background:#e2e8f0"><div id="addEmpBar1" class="h-1 rounded transition-all duration-300" style="background:linear-gradient(135deg,#6366f1,#06b6d4);width:0%"></div></div>
            <div id="addEmpDot2" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-slate-200 text-slate-400">2</div>
            <div class="flex-1 h-1 rounded" style="background:#e2e8f0"><div id="addEmpBar2" class="h-1 rounded transition-all duration-300" style="background:linear-gradient(135deg,#6366f1,#06b6d4);width:0%"></div></div>
            <div id="addEmpDot3" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-slate-200 text-slate-400">3</div>
        </div>

        <form method="POST" action="{{ route('super_admin.store_employee') }}">
            @csrf
            <!-- Step 1 -->
            <div id="addEmpStep1" class="step-container flex flex-col" style="min-height: 300px;">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-field col-span-2">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-input" placeholder="Employee full name" value="{{ old('name') }}" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Division</label>
                        <select name="division_id" class="form-select" required>
                            <option value="">Select Division</option>
                            @foreach ($divisions as $div)
                                @if(stripos($div->division_name, 'HR') === false)
                                    <option value="{{ $div->division_id }}" {{ old('division_id') == $div->division_id ? 'selected' : '' }}>{{ $div->division_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('division_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Position</label>
                        <input type="text" name="jabatan" class="form-input" placeholder="Position / role" value="{{ old('jabatan') }}" required>
                        @error('jabatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-input" placeholder="e.g.: 0000002" value="{{ old('nip') }}" required>
                        @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-auto pt-5 border-t border-slate-100">
                    <button type="button" class="btn-ghost" onclick="closeModal('modalAddEmployee')">Cancel</button>
                    <button type="button" class="btn-primary" onclick="nextStep('addEmp', 1, 3)">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Step 2 -->
            <div id="addEmpStep2" class="step-container hidden flex flex-col" style="min-height: 300px;">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-field">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="email@attensys.id" value="{{ old('email') }}" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="no_hp" class="form-input" placeholder="08xxxx" value="{{ old('no_hp') }}" required>
                        @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Active</option>
                            <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="alamat" class="form-input" placeholder="Full address" required>{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-auto pt-5 border-t border-slate-100">
                    <button type="button" class="btn-ghost flex items-center" onclick="prevStep('addEmp', 2, 3)">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </button>
                    <button type="button" class="btn-primary" onclick="nextStep('addEmp', 2, 3)">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Step 3 -->
            <div id="addEmpStep3" class="step-container hidden flex flex-col" style="min-height: 300px;">
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-field">
                        <label class="form-label">Temporary Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Password (min 8 characters)" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-auto pt-5 border-t border-slate-100">
                    <button type="button" class="btn-ghost flex items-center" onclick="prevStep('addEmp', 3, 3)">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </button>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Tambah Admin HR ===== -->
<div class="modal-overlay" id="modalAddAdmin" onclick="closeModalOutside(event,'modalAddAdmin')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add Admin HR Account</h3>
                <p class="text-sm text-slate-400 mt-1">Create a new account with HR management access</p>
            </div>
            <button onclick="closeModal('modalAddAdmin')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>

        <!-- Step Indicator -->
        <div class="flex items-center gap-2 mb-6">
            <div id="addAdminDot1" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background: linear-gradient(135deg,#6366f1,#06b6d4)">1</div>
            <div class="flex-1 h-1 rounded" style="background:#e2e8f0"><div id="addAdminBar1" class="h-1 rounded transition-all duration-300" style="background:linear-gradient(135deg,#6366f1,#06b6d4);width:0%"></div></div>
            <div id="addAdminDot2" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-slate-200 text-slate-400">2</div>
            <div class="flex-1 h-1 rounded" style="background:#e2e8f0"><div id="addAdminBar2" class="h-1 rounded transition-all duration-300" style="background:linear-gradient(135deg,#6366f1,#06b6d4);width:0%"></div></div>
            <div id="addAdminDot3" class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold bg-slate-200 text-slate-400">3</div>
        </div>

        <form method="POST" action="{{ route('super_admin.store_hr_admin') }}">
            @csrf
            <!-- Step 1 -->
            <div id="addAdminStep1" class="step-container flex flex-col" style="min-height: 300px;">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-field col-span-2">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-input" placeholder="Admin full name" value="{{ old('name') }}" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Division</label>
                        <select name="division_id" class="form-select bg-slate-100 cursor-not-allowed appearance-none" style="pointer-events: none;" tabindex="-1" required>
                            @foreach ($divisions as $div)
                                @if(stripos($div->division_name, 'HR') !== false)
                                    <option value="{{ $div->division_id }}" selected>{{ $div->division_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('division_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" class="form-input" placeholder="e.g.: HR Manager" value="{{ old('position') }}" required>
                        @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-input" placeholder="e.g.: 0000001" value="{{ old('nip') }}" required>
                        @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-auto pt-5 border-t border-slate-100">
                    <button type="button" class="btn-ghost" onclick="closeModal('modalAddAdmin')">Cancel</button>
                    <button type="button" class="btn-primary" onclick="nextStep('addAdmin', 1, 3)">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Step 2 -->
            <div id="addAdminStep2" class="step-container hidden flex flex-col" style="min-height: 300px;">
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-field">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="email@attensys.id" value="{{ old('email') }}" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-input" placeholder="08xxxx" value="{{ old('phone') }}" required>
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Active</option>
                            <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="form-field col-span-2">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-input" placeholder="Full address" required>{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-auto pt-5 border-t border-slate-100">
                    <button type="button" class="btn-ghost flex items-center" onclick="prevStep('addAdmin', 2, 3)">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </button>
                    <button type="button" class="btn-primary" onclick="nextStep('addAdmin', 2, 3)">
                        Next
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Step 3 -->
            <div id="addAdminStep3" class="step-container hidden flex flex-col" style="min-height: 300px;">
                <div class="grid grid-cols-1 gap-4">
                    <div class="form-field">
                        <label class="form-label">Temporary Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Password (min 8 characters)" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-auto pt-5 border-t border-slate-100">
                    <button type="button" class="btn-ghost flex items-center" onclick="prevStep('addAdmin', 3, 3)">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </button>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Tambah Divisi ===== -->
<div class="modal-overlay" id="modalAddDivision" onclick="closeModalOutside(event,'modalAddDivision')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add New Division</h3>
                <p class="text-sm text-slate-400 mt-1">Add a new division to the system</p>
            </div>
            <button onclick="closeModal('modalAddDivision')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_division') }}">
            @csrf
            <div class="form-field">
                <label class="form-label">Division Name</label>
                <input type="text" name="division_name" class="form-input" placeholder="e.g.: IT Support, Marketing, etc." required>
                @error('division_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddDivision')">Cancel</button>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Division
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Edit Divisi ===== -->
<div class="modal-overlay" id="modalEditDivision" onclick="closeModalOutside(event,'modalEditDivision')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Division</h3>
                <p class="text-sm text-slate-400 mt-1">Update division name</p>
            </div>
            <button onclick="closeModal('modalEditDivision')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditDivision" method="POST">
            @csrf
            <div class="form-field">
                <label class="form-label">Division Name</label>
                <input type="text" name="division_name" id="edit_division_name" class="form-input" required>
                @error('division_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditDivision')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>



<!-- ===== MODAL: Edit Karyawan ===== -->
<div class="modal-overlay" id="modalEditEmployee" onclick="closeModalOutside(event,'modalEditEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Employee Account</h3>
                <p class="text-sm text-slate-400 mt-1">Update employee account information</p>
            </div>
            <button onclick="closeModal('modalEditEmployee')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditEmployee" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field col-span-2">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" id="edit_emp_name" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_emp_email" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" id="edit_emp_nip" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Division</label>
                    <select name="division_id" id="edit_emp_division" class="form-select" required>
                        @foreach ($divisions as $div)
                            @if(stripos($div->division_name, 'HR') === false)
                                <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position</label>
                    <input type="text" name="jabatan" id="edit_emp_jabatan" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="no_hp" id="edit_emp_no_hp" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Gender</label>
                    <select name="gender" id="edit_emp_gender" class="form-select" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="alamat" id="edit_emp_alamat" class="form-input" required></textarea>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_emp_status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Tidak Aktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Change Password (Optional)</label>
                    <input type="password" name="password" class="form-input" placeholder="Leave blank to keep unchanged">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditEmployee')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Edit Admin HR ===== -->
<div class="modal-overlay" id="modalEditAdmin" onclick="closeModalOutside(event,'modalEditAdmin')">
    <div class="modal-box max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Admin HR</h3>
                <p class="text-sm text-slate-400 mt-1">Update HR admin account</p>
            </div>
            <button onclick="closeModal('modalEditAdmin')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditAdmin" method="POST">
            @csrf
            <div class="form-field mb-4">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" id="edit_admin_name" class="form-input" required>
            </div>
            <div class="form-field mb-4">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" id="edit_admin_nip" class="form-input" required>
            </div>
            <div class="form-field mb-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="edit_admin_email" class="form-input" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-field">
                    <label class="form-label">Division</label>
                    <select name="division_id" id="edit_admin_division" class="form-select bg-slate-100 cursor-not-allowed appearance-none" style="pointer-events: none;" tabindex="-1" required>
                        @foreach ($divisions as $div)
                            @if(stripos($div->division_name, 'HR') !== false)
                                <option value="{{ $div->division_id }}" selected>{{ $div->division_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position / Role</label>
                    <input type="text" name="position" id="edit_admin_position" class="form-input" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="edit_admin_phone" class="form-input" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_admin_status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Tidak Aktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Gender</label>
                    <select name="gender" id="edit_admin_gender" class="form-select" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" id="edit_admin_address" class="form-input" required></textarea>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Change Password (Optional)</label>
                    <input type="password" name="password" class="form-input" placeholder="Leave blank to keep unchanged">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditAdmin')">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Scanner Modal -->
<div class="modal-overlay" id="modalAddScanner" onclick="closeModalOutside(event,'modalAddScanner')">
    <div class="modal-box">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add New Scanner</h3>
                <p class="text-sm text-slate-400 mt-1">Add a new scanner device account</p>
            </div>
            <button onclick="closeModal('modalAddScanner')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_scanner') }}">
            @csrf
            <div class="form-field mb-4">
                <label class="form-label">Scanner ID</label>
                <input type="text" name="scanner_id" class="form-input font-mono" placeholder="e.g.: SD-101 (Max 5 chars)" maxlength="5" required>
                @error('scanner_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="form-field mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Min 8 characters" minlength="8" required>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddScanner')">Cancel</button>
                <button type="submit" class="btn-primary">
                    Save Scanner
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Scanner Modal -->
<div class="modal-overlay" id="modalEditScanner" onclick="closeModalOutside(event,'modalEditScanner')">
    <div class="modal-box">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Edit Scanner</h3>
                <p class="text-sm text-slate-400 mt-1">Update scanner device credentials</p>
            </div>
            <button onclick="closeModal('modalEditScanner')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form id="formEditScanner" method="POST">
            @csrf
            <div class="form-field mb-4">
                <label class="form-label">Scanner ID (Cannot be changed)</label>
                <input type="text" id="edit_scanner_id_display" class="form-input font-mono bg-slate-100 cursor-not-allowed" readonly>
            </div>
            <div class="form-field mb-4">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" placeholder="Leave blank to keep unchanged" minlength="8">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalEditScanner')">Cancel</button>
                <button type="submit" class="btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    function nextStep(prefix, current, total) {
        const currentStepEl = document.getElementById(prefix + 'Step' + current);
        const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
        let valid = true;
        for (const input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                valid = false;
                break;
            }
        }
        if (!valid) return;

        currentStepEl.classList.add('hidden');
        document.getElementById(prefix + 'Step' + (current + 1)).classList.remove('hidden');
        updateStepIndicator(prefix, current + 1, total);
    }

    function prevStep(prefix, current, total) {
        document.getElementById(prefix + 'Step' + current).classList.add('hidden');
        document.getElementById(prefix + 'Step' + (current - 1)).classList.remove('hidden');
        updateStepIndicator(prefix, current - 1, total);
    }

    function updateStepIndicator(prefix, activeStep, total) {
        for (let i = 1; i <= total; i++) {
            const dot = document.getElementById(prefix + 'Dot' + i);
            if (!dot) continue;
            if (i < activeStep) {
                dot.style.background = 'linear-gradient(135deg,#6366f1,#06b6d4)';
                dot.style.color = 'white';
                dot.classList.remove('bg-slate-200','text-slate-400');
                dot.innerHTML = '✓';
            } else if (i === activeStep) {
                dot.style.background = 'linear-gradient(135deg,#6366f1,#06b6d4)';
                dot.style.color = 'white';
                dot.classList.remove('bg-slate-200','text-slate-400');
                dot.innerHTML = i;
            } else {
                dot.style.background = '';
                dot.classList.add('bg-slate-200','text-slate-400');
                dot.innerHTML = i;
            }

            if (i < total) {
                const bar = document.getElementById(prefix + 'Bar' + i);
                if (bar) bar.style.width = (i < activeStep) ? '100%' : '0%';
            }
        }
    }
</script>
