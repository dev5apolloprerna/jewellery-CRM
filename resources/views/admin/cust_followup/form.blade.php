
<input type="hidden" name="cust_id" value="{{$id}}">
<div class="col-lg-6 col-md-6"><div class="col-lg-6 col-md-6">
    <label for="emp_name" class="form-label">Employee Name <span style="color:red;">*</span></label>
    <select class="form-control" name="emp_id" id="emp_id" required>
        <option value="">Select Employee</option>
        @foreach ($employees as $emp)
            <option value="{{ $emp->emp_id }}" {{ old('emp_id', $followup->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
                {{ $emp->emp_name }}
            </option>
        @endforeach
    </select>
    @error('emp_id') <small class="text-danger">{{ $message }}</small> @enderror

</div>
 <label for="branch_id" class="form-label">Branch <span style="color:red;">*</span></label>
    <select class="form-control" name="branch_id" id="branch_id" required>
        <option value="">Select Branch</option>
        @foreach ($branches as $branch)
            <option value="{{ $branch->branch_id }}" {{ old('branch_id', $followup->branch_id ?? '') == $branch->branch_id ? 'selected' : '' }}>
                {{ $branch->branch_name }}
            </option>
        @endforeach
    </select>
    @error('branch_id') <small class="text-danger">{{ $message }}</small> @enderror

</div>



<div class="col-lg-6 col-md-6">
    <label for="remark" class="form-label">Remark <span style="color:red;">*</span></label>
    <textarea class="form-control" name="remark" placeholder="Enter Remark" required>{{ old('remark', $followup->remark ?? '') }}</textarea>
    @error('remark') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-lg-6 col-md-6">
    <label for="next_date" class="form-label">Next Date <span style="color:red;">*</span></label>
    <input type="date" class="form-control" name="next_date" placeholder="Enter Next Date" value="{{ old('next_date', $followup->next_date ?? '') }}"  maxlength="100" required>
    @error('next_date') <small class="text-danger">{{ $message }}</small> @enderror
</div>
