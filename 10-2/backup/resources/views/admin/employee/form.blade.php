
<div class="col-lg-6 col-md-6">
        <label for="branch_id" class="form-label">Branch  <span style="color:red;">*</span></label>
    <select class="form-control" name="branch_id" id="branch_id" required>
        <option value="">Select Branch</option>
        @foreach ($branches as $branch)
            <option value="{{ $branch->branch_id }}" {{ old('branch_id', $employee->branch_id ?? '') == $branch->branch_id ? 'selected' : '' }}>
                {{ $branch->branch_name }}
            </option>
        @endforeach
    </select>
    @error('branch_id') <small class="text-danger">{{ $message }}</small> @enderror

</div>

<div class="col-lg-6 col-md-6">
    <label for="emp_name" class="form-label">Employee Name <span style="color:red;">*</span></label>
    <input type="text" class="form-control" name="emp_name" value="{{ old('emp_name', $employee->emp_name ?? '') }}" maxlength="100" minlength="3" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')" placeholder="Enter Employee Name" required>
    @error('emp_name') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="emp_phone" class="form-label">Phone <span style="color:red;">*</span></label>
    <input type="text" class="form-control" name="emp_phone" value="{{ old('emp_phone', $employee->emp_phone ?? '') }}" minlength="5" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="Enter Phone" required>
    @error('emp_phone') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="emp_email" class="form-label">Email <span style="color:red;">*</span></label>
    <input type="email" class="form-control" name="emp_email" value="{{ old('emp_email', $employee->emp_email ?? '') }}" placeholder="Enter Email"  maxlength="100" required>
    @error('emp_email') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="emp_phone2" class="form-label">Secondary Phone <span style="color:red;"></span></label>
    <input type="text" class="form-control" name="emp_phone2" value="{{ old('emp_phone2', $employee->emp_phone2 ?? '') }}" placeholder="Enter Secondary Phone"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10" >
    @error('emp_phone2') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="emp_dob" class="form-label">Date of Birth <span style="color:red;">*</span></label>
    <input type="date" class="form-control" name="emp_dob" value="{{ old('emp_dob', isset($employee->emp_dob) ? \Carbon\Carbon::parse($employee->emp_dob)->format('Y-m-d') : '') }}" placeholder="Enter Date of Birth" required>
    @error('emp_dob') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="accesOutside" class="form-label">Access Outside <span style="color:red;">*</span></label>
    <select class="form-control" name="accesOutside" required>
        <option value="">-- Select --</option>
        <option value="Yes" {{ old('accesOutside', $employee->accesOutside ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
        <option value="No" {{ old('accesOutside', $employee->accesOutside ?? '') == 'No' ? 'selected' : '' }}>No</option>
    </select>
    @error('accesOutside') <small class="text-danger">{{ $message }}</small> @enderror
</div>

@if(!isset($employee))
<div class="col-lg-6 col-md-6">
    <label for="password" class="form-label">Password <span style="color:red;">*</span></label>
    <input type="password" class="form-control" name="password" value="{{ old('password', $employee->password ?? '') }}" placeholder="Enter Password"  maxlength="20" >
    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
</div>
@endif