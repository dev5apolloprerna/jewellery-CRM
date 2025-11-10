    <input type="hidden" value="{{$branch_id}}" name="branch_id">
    <input type="hidden" value="{{ old('customer_id', $customer->customer_id ?? '') }}" id="customer_id">
       <div class="col-lg-6 col-md-6">
        <label class="form-label">Customer Name <span style="color:red;">*</span></label>
        <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name ?? '') }}" maxlength="100" minlength="3" oninput="this.value = this.value.replace(/[^a-zA-Z ]/g, '')" placeholder="Enter Customer Name">
        @error('customer_name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-6 col-md-6">
        <label class="form-label">Phone <span style="color:red;">*</span></label>
        <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="{{ old('customer_phone', $customer->customer_phone ?? '') }}" minlength="5" maxlength="10" onblur="validatedata();" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="Enter Phone">
        @error('customer_phone') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-6 col-md-6">
        <label class="form-label">Email <span style="color:red;"></span></label>
        <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $customer->customer_email ?? '') }}"  maxlength="100" placeholder="Enter Email">
        @error('customer_email') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

   
    <!-- <div class="col-lg-6 col-md-6">
        <label class="form-label">State <span style="color:red;">*</span></label>
        <select name="state_id" class="form-control">
            <option value="">Select State</option>
            @foreach($state as $s)
                <option value="{{ $s->stateId }}" {{ old('state_id', $customer->state_id ?? '') == $s->stateId ? 'selected' : '' }}>{{ $s->stateName }}</option>
            @endforeach
        </select>
        @error('state_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div> -->


    <div class="col-lg-6 col-md-6">
        <label class="form-label">City <span style="color:red;">*</span></label>
        <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city ?? '') }}" minlength="5" maxlength="100" placeholder="Enter City">
        @error('city') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-6 col-md-6">
        <label class="form-label">Address <span style="color:red;">*</span></label>
        <textarea  name="address" class="form-control" value="{{ old('address', $customer->address ?? '') }}"  placeholder="Enter Address" required></textarea>
        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
    </div>


    <div class="col-lg-6 col-md-6">
        <label class="form-label">Refer By <span style="color:red;"></span></label>
        <input type="text" name="refer_by" class="form-control" value="{{ old('refer_by', $customer->refer_by ?? '') }}" oninput="this.value = this.value.replace(/[^A-Za-z]/g, '')" placeholder="Enter Refer By">
    </div>
 <div class="col-lg-6 col-md-6">
        <label class="form-label">Customer Cast <span style="color:red;">*</span></label>
        <select name="cast_id" class="form-control">
            <option value="">Select</option>
            @foreach($cast as $cat)
                <option value="{{ $cat->cast_id }}" {{ old('cast_id', $customer->cast_id ?? '') == $cat->cast ? 'selected' : '' }}>{{ $cat->cast }}</option>
            @endforeach
        </select>
        @error('cast_id') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="col-lg-6 col-md-6">
        <label class="form-label">Customer Type <span style="color:red;">*</span></label>
        <select name="customer_type" class="form-control">
            <option value="">Select</option>
            @foreach($custCatgory as $cat)
                <option value="{{ $cat->cust_cat_id }}" {{ old('customer_type', $customer->customer_type ?? '') == $cat->cust_cat_id ? 'selected' : '' }}>{{ $cat->cust_cat_name }}</option>
            @endforeach
        </select>
        @error('customer_type') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    
    