    <input type="hidden" value="{{$branch_id}}" name="branch_id">
    <div class="col-lg-6 col-md-6">
        <label class="form-label">Customer Name <span style="color:red;">*</span></label>
        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name ?? '') }}" maxlength="100" minlength="3" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')" placeholder="Enter Customer Name">
        @error('customer_name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-6 col-md-6">
        <label class="form-label">Phone <span style="color:red;">*</span></label>
        <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $customer->customer_phone ?? '') }}" minlength="5" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="Enter Phone">
        @error('customer_phone') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-6 col-md-6">
        <label class="form-label">Customer Type <span style="color:red;">*</span></label>
        <select name="customer_type" class="form-control">
            <option value="">Select</option>
            <option value="New Walk In" {{ old('customer_type', $customer->customer_type ?? '') == 'New Walk In' ? 'selected' : '' }}>New Walk In</option>
            <option value="Regular" {{ old('customer_type', $customer->customer_type ?? '') == 'Regular' ? 'selected' : '' }}>Regular</option>
            <option value="Gold" {{ old('customer_type', $customer->customer_type ?? '') == 'Gold' ? 'selected' : '' }}>Gold</option>
            <option value="Premium" {{ old('customer_type', $customer->customer_type ?? '') == 'Premium' ? 'selected' : '' }}>Premium</option>
            <option value="No credit" {{ old('customer_type', $customer->customer_type ?? '') == 'No credit' ? 'selected' : '' }}>No credit</option>
        </select>
        @error('customer_type') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-6 col-md-6">
        <label class="form-label">Refer By <span style="color:red;"></span></label>
        <input type="text" name="refer_by" class="form-control" value="{{ old('refer_by', $customer->refer_by ?? '') }}" oninput="this.value = this.value.replace(/[^A-Za-z]/g, '')" placeholder="Enter Refer By">
    </div>

