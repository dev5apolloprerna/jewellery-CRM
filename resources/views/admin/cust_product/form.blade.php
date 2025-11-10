
<input type="hidden" name="cust_id" value="{{$id}}">
<div class="col-lg-6 col-md-6">
 <label for="product_id" class="form-label">Product <span style="color:red;">*</span></label>
    <select class="form-control" name="product_id" id="product_id" required>
        <option value="">Select Product</option>
        @foreach ($products as $product)
            <option value="{{ $product->product_id }}" {{ old('product_id', $custProduct->product_id ?? '') == $product->product_id ? 'selected' : '' }}>
                {{ $product->product_name }}
            </option>
        @endforeach
    </select>
    @error('product_id') <small class="text-danger">{{ $message }}</small> @enderror

</div>

<div class="col-lg-6 col-md-6">
    <label for="emp_name" class="form-label">Employee Name <span style="color:red;">*</span></label>
    <select class="form-control" name="emp_id" id="emp_id" required>
        <option value="">Select Employee</option>
        @foreach ($employees as $emp)
            <option value="{{ $emp->emp_id }}" {{ old('emp_id', $custProduct->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
                {{ $emp->emp_name }}
            </option>
        @endforeach
    </select>
    @error('emp_id') <small class="text-danger">{{ $message }}</small> @enderror

</div>

<div class="col-lg-6 col-md-6">
    <label for="quantity" class="form-label">Quantity <span style="color:red;">*</span></label>
    <input type="number" class="form-control" name="quantity" value="{{ old('quantity', $custProduct->quantity ?? '') }}" placeholder="Enter Quantity" maxlength="10" required>
    @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="visit_date" class="form-label">Visit Date <span style="color:red;">*</span></label>
    <input type="date" class="form-control" name="visit_date" value="{{ old('visit_date', $custProduct->visit_date ?? '') }}"  placeholder="Enter Visit date" maxlength="100" required>
    @error('visit_date') <small class="text-danger">{{ $message }}</small> @enderror
</div>
