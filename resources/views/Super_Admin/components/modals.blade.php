<!-- ===== MODAL: Tambah Karyawan ===== -->
<div class="modal-overlay" id="modalAddEmployee" onclick="closeModalOutside(event,'modalAddEmployee')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add Employee Account</h3>
                <p class="text-sm text-slate-400 mt-1">Fill in the data to create a new employee account</p>
            </div>
            <button onclick="closeModal('modalAddEmployee')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_employee') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field col-span-2">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Employee full name" value="{{ old('name') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@attensys.id" value="{{ old('email') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" placeholder="e.g.: 19900101xxxx" value="{{ old('nip') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Division</label>
                    <select name="division_id" class="form-select" required>
                        <option value="">Select Division</option>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position</label>
                    <input type="text" name="jabatan" class="form-input" placeholder="Position / role" value="{{ old('jabatan') }}" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="no_hp" class="form-input" placeholder="08xxxx" value="{{ old('no_hp') }}" required>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="alamat" class="form-input" placeholder="Full address" required>{{ old('alamat') }}</textarea>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Temporary Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Password (min 8 characters)" required>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddEmployee')">Cancel</button>
                <button type="submit" class="btn-primary">Save Account</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Tambah Admin HR ===== -->
<div class="modal-overlay" id="modalAddAdmin" onclick="closeModalOutside(event,'modalAddAdmin')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900" style="font-family:'Sora',sans-serif">Add Admin HR Account</h3>
                <p class="text-sm text-slate-400 mt-1">Create a new account with HR management access</p>
            </div>
            <button onclick="closeModal('modalAddAdmin')" class="p-2 rounded-xl hover:bg-slate-100 transition text-slate-400">✕</button>
        </div>
        <form method="POST" action="{{ route('super_admin.store_hr_admin') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="form-field">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input" placeholder="Admin full name" required>
                </div>
                <div class="form-field">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip" class="form-input" placeholder="NIP Admin" required>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="email@attensys.id" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Division</label>
                    <select name="division_id" class="form-select" required>
                        <option value="">Select Division</option>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label class="form-label">Position</label>
                    <input type="text" name="position" class="form-input" placeholder="e.g.: HR Manager" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input" placeholder="08xxxx" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
                    </select>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-input" placeholder="Full address" required></textarea>
                </div>
                <div class="form-field col-span-2">
                    <label class="form-label">Temporary Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Password (min 8 characters)" required>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="button" class="btn-ghost" onclick="closeModal('modalAddAdmin')">Cancel</button>
                <button type="submit" class="btn-primary">Save Account</button>
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
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
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
                <div class="form-field col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="alamat" id="edit_emp_alamat" class="form-input" required></textarea>
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_emp_status" class="form-select" required>
                        <option value="Aktif">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
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
                    <select name="division_id" id="edit_admin_division" class="form-select" required>
                        @foreach ($divisions as $div)
                        <option value="{{ $div->division_id }}">{{ $div->division_name }}</option>
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
                        <option value="Pending">Pending</option>
                        <option value="Nonaktif">Inactive</option>
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
