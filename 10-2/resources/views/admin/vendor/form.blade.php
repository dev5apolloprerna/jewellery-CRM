
<div class="col-lg-6 col-md-6">
    <label for="company_name" class="form-label">Company Name <span style="color:red;">*</span></label>
    <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $vendor->company_name ?? '') }}" maxlength="100" minlength="3" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')" placeholder="Enter Employee Name" required>
    @error('company_name') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="contact_person" class="form-label">Contact Person <span style="color:red;">*</span></label>
    <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $vendor->contact_person ?? '') }}" maxlength="100" minlength="3" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')" placeholder="Enter Employee Name" required>
    @error('contact_person') <small class="text-danger">{{ $message }}</small> @enderror
</div>


<div class="col-lg-6 col-md-6">
    <label for="phone" class="form-label">Mobile No <span style="color:red;">*</span></label>
    <input type="text" class="form-control" name="phone" value="{{ old('phone', $vendor->phone ?? '') }}" minlength="5" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="Enter Phone" required>
    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
</div>
<div class="col-lg-6 col-md-6">
    <label for="email" class="form-label">Email <span style="color:red;"></span></label>
    <input type="email" class="form-control" name="email" value="{{ old('email', $vendor->email ?? '') }}" placeholder="Enter Email"  maxlength="100" >
    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
</div>
<div class="col-lg-6 col-md-6">
    <label for="phone2" class="form-label">Mobile 2 <span style="color:red;"></span></label>
    <input type="text" class="form-control" name="phone2" value="{{ old('phone2', $vendor->phone2 ?? '') }}" placeholder="Enter Secondary Phone"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10" >
    @error('phone2') <small class="text-danger">{{ $message }}</small> @enderror
</div>
