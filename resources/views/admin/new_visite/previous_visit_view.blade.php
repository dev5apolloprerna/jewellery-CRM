@extends('layouts.app')
@section('title', 'Customer Previous Visite')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Messages --}}
            @include('common.alert')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="d-flex justify-content-between card-header">
                            <h5 class="card-title text-uppercase fw-bold text-black mb-0">Customer Previous Visite</h5>
                            <div class="page-title-right">
                                @if($feedback->followup_status == 1)
                                <a href="{{ route('newVisite.create',$id) }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Add New Visit
                                </a>
                                @endif
                                <a href="{{ route('customer.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="card-body">

                            {{-- Client Data List --}}
                            <div class="border p-3 mb-4">
                                <h6 class="text-uppercase fw-bold mb-3">Client Data List</h6>
                                <div class="row mb-2">
                                    <div class="col-md-3"><strong>Name:</strong> {{ $Customer->customer_name }}</div>
                                    <div class="col-md-3"><strong>Mobile No:</strong> {{ $Customer->customer_phone }}</div>
                                    <div class="col-md-3"><strong>Phone:</strong> {{ $Customer->customer_phone ?? '0' }}</div>
                                    <div class="col-md-3"><strong>Email:</strong> {{ $Customer->customer_email ?? '-' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3"><strong>Cast:</strong> {{ $Customer->cast->cast ?? '-' }}</div>
                                    <div class="col-md-3"><strong>Branch:</strong> {{ $Customer->branch->branch_name ?? '-' }}</div>
                                    <div class="col-md-3"><strong>City:</strong> {{ $Customer->city }}</div>
                                    <div class="col-md-3"><strong>Address:</strong> {{ $Customer->address ?? '-' }}</div>
                                </div>
                            </div>

                            
                            <!-- {{-- Product List --}} -->
    <hr>
                            <h5 class="card-title text-uppercase fw-bold text-black mb-2">Add customer view product</h5>

                            <form id="regForm" method="POST" action="" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" class="form-control" name="visit_id" id="visit_id" value="{{ $feedback->visit_id ?? '' }}" readonly>
                                        <input type="hidden" class="form-control" name="cust_id" id="cust_id" value="{{ $Customer->customer_id }}" readonly>


                                        <div class="row gy-4">
                                            <div class="col-lg-3 col-md-6">
                                                <div>
                                                    <span style="color:red;">*</span>Category
                                                    <select class="form-control" name="category_id" id="category_id">
                                                        <option value="">Select Category</option>
                                                        @foreach($Category as $cat)
                                                        <option value="{{$cat->category_id}}">{{ $cat->category_name }}</option>
                                                        @endforeach 
                                                    </select>
                                                    <span class="text-danger error-text" id="error-category_id"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div>
                                                    <span style="color:red;">*</span>Product

                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                            type="button" id="productDropBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Select product(s)
                                                        </button>

                                                        <div class="dropdown-menu w-100 p-2" aria-labelledby="productDropBtn"
                                                             style="max-height:250px; overflow:auto;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="productSelectAll">
                                                                <label class="form-check-label" for="productSelectAll">Select All</label>
                                                            </div>
                                                            <div class="dropdown-divider"></div>
                                                            <div id="productCheckList">
                                                                <small class="text-muted">Select category first.</small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <span class="text-danger error-text" id="error-product_id"></span>
                                                </div>
                                            </div>

                                            {{-- ✅ Fix: visit_date used in ajax (your JS references #visit_date) --}}
                                            <input type="hidden" id="visit_date" value="{{ $feedback->visit_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}">

                                            <!-- <div class="col-lg-3 col-md-6">
                                                <div>
                                                    <span style="color:red;">*</span>Product
                                                    <select class="form-control" name="product_id" id="product_id">
                                                        <option value="">Select Product</option>
                                                        @foreach($Products as $cat)
                                                        <option value="{{$cat->product_id}}">{{ $cat->product_name }}</option>
                                                        @endforeach 
                                                    </select>
                                                    <span class="text-danger error-text" id="error-product_id"></span>
                                                </div>
                                            </div> -->
                                           <div class="col-lg-3 col-md-3">
                                                Employee Name <span style="color:red;">*</span>
                                                <select class="form-control" name="emp_id" id="emp_id" >
                                                    <option value="">Select Employee</option>
                                                    @foreach ($employees as $emp)
                                                        <option value="{{ $emp->emp_id }}" {{ old('emp_id', $custProduct->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
                                                            {{ $emp->emp_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                    <span class="text-danger error-text" id="error-emp_id"></span>

                                            </div>
                                            <input type="hidden" value="view" id="productstatus" name="status">

                                            <div class="col-lg-1 col-md-6 mt-5"><div>
                                            <button class="btn btn-primary btn-user float-right mb-3 mx-2" type="button" id="addProductBtn">Add</button>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <h5 class="card-title text-uppercase fw-bold text-black mb-2">Product List</h5>
                                                    <table class="table table-bordered" >
                                                        <thead>
                                                            <tr>
                                                                <th>Sr. No</th>
                                                                <th>Product Category</th>
                                                                <th>Product Name</th>
                                                                <!--<th>Visit Date</th>-->
                                                                <!-- <th>Product Amount</th> -->
                                                                <th>Status</th>
                                                                <th>Attended By</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="productTableBody">
                                                        </tbody>
                                                    </table>
                                            </div>
                                            <div>
                                                <div class="mt-4">
                                            <h5 class="card-title text-uppercase fw-bold text-black mb-2">Purchased Product List</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Product Category</th>
                                                        <th>Product Name</th>
                                                        <th>Status</th>
                                                        <th>Attended By</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="purchasedProductTableBody"></tbody>
                                            </table>
                                        </div>

                                            </div>
                                    </form>


                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

    <hr>
                            <div class="border p-3 mb-4">
                                <h5 class="card-title text-uppercase fw-bold text-black mb-2">Next Followup</h5>
                                    <!-- folloowup form start -->
                                    <form action="{{ route('custFollowup.store') }}" method="POST">
                                            @csrf
                                            <div class="row gy-4">
                                            <input type="hidden" class="form-control" name="visit_id" value="{{ $feedback->visit_id ?? '' }}" readonly>
                                            <input type="hidden" class="form-control" name="cust_id" value="{{ $Customer->customer_id }}" readonly>
                                            <input type="hidden" class="form-control" name="branch_id" value="{{ $Customer->branch_id }}" readonly>
                                            <input type="hidden" class="form-control" name="visit_id" value="{{ $feedback->visit_id ?? $newVisiteNo }}" readonly>
                                            <div class="col-lg-3 col-md-6">
                                                <label>Status</label><br>
                                                    <div class="btn-group" role="group" aria-label="Status">
                                                        <input type="hidden" name="followup_status" id="followup_status" value="{{ $feedback->followup_status ?? '0' }}">
                                                        
                                                        <button type="button" id="btnOpen" class="btn {{ ($feedback->followup_status ?? '') == '0' ? 'btn-success' : 'btn-outline-success' }}">
                                                            Open
                                                        </button>
                                                        
                                                        <button type="button" id="btnClose" class="btn 
                                                        {{ ($feedback->followup_status ?? '') == '1' ? 'btn-danger' : 'btn-outline-danger' }}">
                                                            Close
                                                        </button>
                                                    </div>                                                    
                                                     </button>
                                                     @error('followup_status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                        <div class="col-lg-3 col-md-6">
                                                <label>Close Reason</label>

                                                <select name="close_reason_id" class="form-control" id="close_reason" {{ optional($feedback)->followup_status != '1' ? 'disabled' : '' }}>
                                                    <option value="">Select Reason</option>
                                                    @foreach($closereason as $cs)
                                                    <option value="{{ $cs->close_reason_id }}" {{ optional($feedback)->close_reason_id == $cs->close_reason_id ? 'selected' : '' }}>{{$cs->close_reason}}</option>
                                                    @endforeach
                                                </select>
                                                    @error('close_reason')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>


                                        <div class="col-lg-3 col-md-6">
                                                <label>Remark</label>
                                                <input type="text" name="remark" class="form-control" value="{{ $feedback->remark ?? '' }}">
                                                @error('remark')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                        <div class="col-lg-3 col-md-6">
                                                <label>Visit Date</label>
                                                <input type="date" name="visit_date" class="form-control" value="{{ old('visit_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                                @error('visit_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                           
                                            <div class="col-lg-3 col-md-6" >
                                                <label>Follow-up Date</label>
                                                <input type="date" name="next_followup_date" id="next_followup_date" class="form-control" value="{{ $feedback->next_followup_date ?? '' }}" {{ optional($feedback)->status == '1' ? 'disabled' : '' }}>

                                                @error('next_followup_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                            <div class="col-lg-3 col-md-6">
                                                <label for="emp_name" class="form-label">Employee Name <span style="color:red;">*</span></label>
                                                <select class="form-control" name="emp_id" id="emp_id" required>
                                                    <option value="">Select Employee</option>
                                                    @foreach ($employees as $emp)
                                                        <option value="{{ $emp->emp_id }}" {{ old('emp_id', $feedback->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
                                                            {{ $emp->emp_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('emp_id') <small class="text-danger">{{ $message }}</small> @enderror

                                            </div>
                                           <div class="col-lg-3 col-md-6 mt-5">
                                                
                                                <button type="submit" class="btn btn-success">Save</button>
                                                <a href="{{ route('customer.index') }}" class="btn btn-danger">Back</a>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    {{-- Followup History --}}
                            <h6 class="text-uppercase fw-bold mt-4 mb-2 text-black">Followup History</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Visite Date</th>
                                        <th>Followup Date</th>
                                        <th>Employee Name</th>
                                        <th>Status</th>
                                        <th>Closer Reason</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(sizeof($Followups) != 0)
                                    @foreach($Followups as $key => $followup)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($followup->visit_date)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($followup->next_followup_date)->format('d-m-Y') }}</td>
                                            <td>{{ $followup->followup_status == 0 ? 'Open' : ($followup->followup_status == 1 ? 'Close' : '') }}</td>
                                            <td>{{ $followup->employee->emp_name }}</td>
                                            <td>{{ $followup->closereason->close_reason ?? '-' }}</td>
                                            <td>{{ $followup->remark }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="3" class="text-center">No Data Found</td>
                                </tr>

                                @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade flip" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Edit Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>

            <form method="POST" action="{{ route('custProduct.changeStatus') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" name="product_id" id="statusproduct_id" value="">

                <div class="modal-body">
                    <div class="mb-3">
                        <label><span style="color:red;">*</span> Status</label>
                        <select class="form-control" name="status" id="Editreview_status">
                            <option value="">Select Status</option>
                        @foreach ($orderStatus as $status)
                            <option value="{{ $status->order_status_id  }}" {{ old('delivery_status', $detail->delivery_status ?? '') == $status->order_status_id  ? 'selected' : '' }}>
                                {{ $status->status }}
                            </option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Order Product Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="orderForm">
        @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="orderModalLabel">Order Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
          <input type="hidden" name="product_id" id="orderProductId">
         <input type="hidden" name="cust_pro_id" value="" id="ordercust_pro_id" class="form-control" readonly> 

          <!-- Add other input fields here if needed -->
          <div class="row">
                <div class="card-header d-flex align-items-center">
                    <input type="hidden" id="orderbranch_id" value="">

                    <div class="col-md-3 mb-3">
                        <strong>Branch Name:</strong>
                        <span id="orderbranch_name"></span>
                    </div>

                    <div class="col-md-3 mb-3">
                        <strong>Product Name:</strong>
                        <span id="orderProduct"></span>
                    </div>
                </div>

            <div class="col-lg-4 col-md-6 mt-3">
                 <label for="karat" class="form-label">Karat <span style="color:red;">*</span></label>
                    <select name="karat" id="karat" class="form-control" >
                        <option value="">Select Karat</option>
                        @foreach ($purity as $prt)
                            <option value="{{ $prt->purity_id  }}" {{ old('karat', $detail->karat ?? '') == $prt->purity_id ? 'selected' : '' }}>
                                {{ $prt->purity_value }}
                            </option>
                        @endforeach
                    </select>
                @error('karat') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
                 <label for="color_id" class="form-label">Color <span style="color:red;"></span></label>
                    <select class="form-control" name="color_id" id="color_id" >
                        <option value="">Select Color</option>
                        @foreach ($color as $c)
                            <option value="{{ $c->color_id }}" {{ old('color_id', $detail->color_id ?? '') == $c->color_id ? 'selected' : '' }}>
                                {{ $c->color_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('color_id') <small class="text-danger">{{ $message }}</small> @enderror

            </div>

            <div class="col-lg-4 col-md-6 mt-3">
                <label for="weight" class="form-label">Weight <span style="color:red;">*</span></label>
                <input type="text" step="0.01" name="weight" class="form-control" value="{{ old('weight', $detail->weight ?? '') }}" maxlength="50" placeholder="Enter Weight"  required>
                @error('weight') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
                 <label for="size" class="form-label">Size <span style="color:red;"></span></label>
                <input type="text" name="size" class="form-control"  value="{{ old('size', $detail->size ?? '') }}" maxlength="50" placeholder="Enter Size" >
                @error('size') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
               <label for="refer_tag_number" class="form-label">Reference Tag Number <span style="color:red;"></span></label>
                 <input type="text" name="refer_tag_number" id="orderrefno" class="form-control"  value="{{ old('refer_tag_number', $detail->refer_tag_number ?? '') }}" placeholder="Enter Reference Tag Number"  maxlength="50" >
                @error('refer_tag_number') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-lg-4 col-md-6 mt-3">
                Reference Image <span style="color:red;">*</span>
                <input type="file" class="form-control" name="refer_image" id="refer_image" accept="image/*">
                <small class="text-muted">Upload jpg/png/webp</small>

                {{-- preview (optional) --}}
                <div class="mt-2">
                    <img id="refer_image_preview" src="" style="display:none; width:80px; height:80px; object-fit:cover;" />
                </div>
            </div>
            <!-- <div class="col-lg-4 col-md-6 mt-3">
               <label for="refer_image_url" class="form-label">Reference Image URL<span style="color:red;"></span></label>
                 <input type="text" name="refer_image_url" class="form-control"  value="{{ old('refer_image_url', $detail->refer_image_url ?? '') }}" placeholder="Enter Reference Tag Number"  maxlength="50" >
                @error('refer_image_url') <small class="text-danger">{{ $message }}</small> @enderror
            </div> -->

            <div class="col-lg-4 col-md-6 mt-3">
              <label for="amount" class="form-label">Amount <span style="color:red;">*</span></label>
                <input type="number" step="0.01" name="amount" class="form-control"  value="{{ old('amount', $detail->amount ?? '') }}" maxlength="50" placeholder="Enter Amount" required>
                @error('amount') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
            <div class="col-lg-4 col-md-6 mt-3">
              <label for="rate_type" class="form-label">Rate Type <span style="color:red;"></span></label>
               <select class="form-control" name="rate_type" id="rate_type">
                    <option value="">Select Rate Type</option>
                    <option value="Mk rate" {{ old('rate_type', $detail->rate_type ?? '') == 'Mk rate' ? 'selected' : '' }}>Mk Rate</option>
                    <option value="Z rate" {{ old('rate_type', $detail->rate_type ?? '') == 'Z rate' ? 'selected' : '' }}>Z Rate</option>
                </select>
                @error('rate_type') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
             <div class="col-lg-4 col-md-6 mt-3">
              <label for="rate_fix_open" class="form-label">Rate Fix/Open <span style="color:red;"></span></label>
                <input type="text" step="0.01" name="rate_fix_open" class="form-control"  value="{{ old('rate_fix_open', $detail->rate_fix_open ?? '') }}" maxlength="50" placeholder="Enter Rate Fix/Open">
                @error('rate_fix_open') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
            <div class="col-lg-4 col-md-6 mt-3">
                <label>Remark <span style="color:red;"></span></label>
                <textarea name="remark" class="form-control" maxlength="255" placeholder="Enter Remark">{{ $order->remark ?? '' }}</textarea><br>
                @error('remark') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-lg-4 col-md-6 mt-3">
              <label for="given_to" class="form-label">Order Given To <span style="color:red;"></span></label>
                <select class="form-control" name="given_to" id="given_to" >
                    <option value="">Select Vendor</option>
                    @foreach ($vendor as $emp)
                        <option value="{{ $emp->vendor_id }}" {{ old('given_to', $order->given_to ?? '') == $emp->vendor_id ? 'selected' : '' }}>
                            {{ $emp->contact_person }}
                        </option>
                    @endforeach
                </select>
                @error('given_to') 
                <small class="text-danger">{{ $message }}</small> @enderror
                                                        </div>
            <div class="col-lg-4 col-md-6 mt-3">
              <label for="delivery_status" class="form-label">Delivery Status <span style="color:red;"> *</span></label>
                <select class="form-control" name="delivery_status" id="delivery_status">
                    <option value="">Select Delivery Status</option>
                    @foreach ($orderStatus as $status)
                        <option value="{{ $status->order_status_id  }}" {{ old('delivery_status', $detail->delivery_status ?? '') == $status->order_status_id  ? 'selected' : '' }}>
                            {{ $status->status }}
                        </option>
                    @endforeach
                </select>
                @error('delivery_status') <small class="text-danger">{{ $message }}</small> @enderror

            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label for="delivery_date" class="form-label">Delivery Date <span style="color:red;">*</span></label>
                <input type="date" name="delivery_date" class="form-control" placeholder="Enter Given To" value="{{ old('delivery_date', $detail->delivery_date ?? '') }}" maxlength="50" >
                @error('delivery_date') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
            <div class="col-lg-6 col-md-6 mt-3" id="notPurchasedReasonWrap" style="display:none;">
                Reason (Not Purchased) <span style="color:red;">*</span>
                <textarea class="form-control" name="not_purchased_reason" id="not_purchased_reason" rows="2"
                          placeholder="Enter reason..."></textarea>
            </div>
      
    </div>
        <div class="modal-footer mt-3">
          <button type="submit" class="btn btn-primary">Confirm Order</button>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection
@section('scripts')
<script>


function formatDate(dateStr) {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

/*$(document).on('click', '.deleteProduct', function (event) {
    event.preventDefault(); // prevent page refresh

    let id = $(this).data('id');
    if (confirm('Are you sure you want to delete this product?')) {
        $.ajax({
            url: `../customer-product/delete/${id}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    $(`#row-${id}`).remove();
                } else {
                    alert('Failed to delete the product.');
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText); // helpful for debugging
                alert('An error occurred while deleting the product.');
            }
        });
    }
});
*/
$(document).on('click', '.editStatus', function () {
    let id = $(this).data('id');
    $('#statusproduct_id').val(id);
});

$(document).on('click', '.orderProduct', function () {
    // Get data attributes
    let custProId = $(this).data('id');
    let productId = $(this).data('product');
    let branchId = $(this).data('branch');
    let productName = $(this).data('name');
    let branchName = $(this).data('branchname');
    let refNo = $(this).data('refno');

    // Set hidden fields and static spans
    $('#ordercust_pro_id').val(custProId);
    $('#orderProductId').val(productId);
    $('#orderbranch_id').val(branchId);
    $('#orderProduct').text(productName);
    $('#orderbranch_name').text(branchName);
    $('#orderrefno').val(refNo);

    // Clear all form fields first (optional for reset)
    $('#orderForm')[0].reset();

    // Make AJAX request to get existing data (if any)
    $.ajax({
        url: '/get-order-details/' + custProId, // You will need to create this route
        type: 'GET',
        success: function (response) {
            if(response.success == false)
            {
                    $('#orderProductId').val(productId);
                    $('#orderbranch_id').val(branchId);
                    $('#orderProduct').text(productName);
                    $('#orderbranch_name').text(branchName);
                    $('#orderrefno').val(refNo);
            }
            else if (response.success) 
            {
                let data = response.data;
                // Populate form fields
                $('select[name="karat"]').val(data.karat);
                $('select[name="color_id"]').val(data.color_id);
                $('input[name="weight"]').val(data.weight);
                $('input[name="size"]').val(data.size);
                $('input[name="refer_tag_number"]').val(data.refer_tag_number);
                $('input[name="refer_image_url"]').val(data.refer_image_url);
                $('input[name="amount"]').val(data.amount);
                $('input[name="rate_fix_open"]').val(data.rate_fix_open);
                $('textarea[name="remark"]').val(data.remark);
                $('select[name="rate_type"]').val(data.rate_type);
                $('select[name="given_to"]').val(data.given_to);
                $('select[name="delivery_status"]').val(data.delivery_status);
                $('input[name="delivery_date"]').val(data.delivery_date);
            }
            
        }
    });
});


// When the form is submitted
$('#orderForm').on('submit', function (e) {
    e.preventDefault();

    toggleNotPurchasedReason(); // ensure correct validation state

    if (!confirm('Are you sure you want to order this product?')) return;

    let formData = new FormData(this); // ✅ supports file upload

    $.ajax({
        url: '{{ route("custOrder.orderProduct") }}',
        method: 'POST',
        data: formData,
        processData: false,  // ✅ required for FormData
        contentType: false,  // ✅ required for FormData
        success: function (response) {
            if (response.success) {
                alert(response.message);
                $('#orderModal').modal('hide');
                location.reload();
                // or loadProductList();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('An unexpected error occurred.');
        }
    });
});


   /* $(document).on('click', '.orderForm', function () {
        let productId = $(this).data('id');
    
        if (confirm('Are you sure you want to order this product?')) {
            $.ajax({
                url: '{{ route("custOrder.orderProduct") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId
                },
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        loadProductList(); 
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    alert('An unexpected error occurred.');
                }
            });
        }
    });*/



$(document).on('change', 'select[name="delivery_status"]', function () {
    toggleNotPurchasedReason();
});

 function toggleNotPurchasedReason() {
    let $status = $('select[name="delivery_status"]'); // change name if yours is different
    let val = ($status.val() || '').toString().toLowerCase();
    let text = ($status.find('option:selected').text() || '').toLowerCase();

    let isNotPurchased =
        val === 'not_purchased' || val === '0' || text.includes('not purchased');

    if (isNotPurchased) {
        $('#notPurchasedReasonWrap').show();
        $('#not_purchased_reason').prop('required', true);
    } else {
        $('#notPurchasedReasonWrap').hide();
        $('#not_purchased_reason').prop('required', false).val('');
    }
}
</script>
<script>
    const statusField = document.getElementById('followup_status');
    const btnOpen = document.getElementById('btnOpen');
    const btnClose = document.getElementById('btnClose');
    const closeReason = document.getElementById('close_reason');
    const fDate = document.getElementById('next_followup_date');

    btnOpen.addEventListener('click', function () {
        statusField.value = '0';
        btnOpen.classList.add('btn-success');
        btnOpen.classList.remove('btn-outline-success');
        btnClose.classList.add('btn-outline-danger');
        btnClose.classList.remove('btn-danger');
        closeReason.disabled = true; // disable Close Reason
        fDate.disabled = false; // disable Close Reason
    });

    btnClose.addEventListener('click', function () {
        statusField.value = '1';
        btnClose.classList.add('btn-danger');
        btnClose.classList.remove('btn-outline-danger');
        btnOpen.classList.add('btn-outline-success');
        btnOpen.classList.remove('btn-success');
        closeReason.disabled = false; // enable Close Reason
        fDate.disabled = true; // enable Close Reason
    });

    // On page load, ensure correct state of Close Reason
    if (statusField.value === '1') {
        closeReason.disabled = false;
        fDate.disabled = true;
    } else {
        closeReason.disabled = true;
        fDate.disabled = false;
    }
</script>

<script>
    // ✅ Category-wise products from controller
    const productsByCategory = @json($productsByCategory);

    function updateProductDropText() {
        let count = $('.product_cb:checked').length;
        $('#productDropBtn').text(count ? (count + ' selected') : 'Select product(s)');
    }

    function renderProductsForCategory(categoryId) {
        $('#error-product_id').text('');

        if (!categoryId) {
            $('#productCheckList').html('<small class="text-muted">Select category first.</small>');
            $('#productSelectAll').prop('checked', false);
            updateProductDropText();
            return;
        }

        const list = productsByCategory[categoryId] || [];
        if (!list.length) {
            $('#productCheckList').html('<small class="text-muted">No products found.</small>');
            $('#productSelectAll').prop('checked', false);
            updateProductDropText();
            return;
        }

        let html = '';
        list.forEach(p => {
            html += `
                <div class="form-check">
                    <input class="form-check-input product_cb" type="checkbox" value="${p.product_id}" id="prod_${p.product_id}">
                    <label class="form-check-label" for="prod_${p.product_id}">${p.product_name}</label>
                </div>
            `;
        });

        $('#productCheckList').html(html);
        $('#productSelectAll').prop('checked', false);
        updateProductDropText();
    }

    // ✅ Make global so it can be called anywhere
    window.loadProductList = function () {
        $.ajax({
            url: "../customer-product/{{ $id }}",
            method: "GET",
            success: function (products) {

                let normalHtml = '';
                let purchasedHtml = '';
                let normalIndex = 1;
                let purchasedIndex = 1;

                products.forEach((item) => {
                    const statusText = (item.order_details?.order_status?.status ?? item.status ?? '-');
                    const statusLower = (statusText || '').toString().trim().toLowerCase();

                    // ✅ SIMPLE rule:
                    // Purchased table shows ONLY "Purchased"
                    // Anything else goes back to normal list
                    const isPurchased = (statusLower === 'purchased');

                    // buttons
                    let actionButtons = `
                        <button class="btn btn-danger btn-sm deleteProduct" data-id="${item.cust_pro_id}">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;

                    if (item.order_details !== null) {
                        actionButtons += `
                            <button type="button" class="btn btn-success btn-sm editStatus"
                                data-id="${item.cust_pro_id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa fa-edit"></i>
                            </button>
                        `;
                    }

                    actionButtons += `
                        <button type="button" class="btn btn-success btn-sm orderProduct"
                            data-id="${item.cust_pro_id}"
                            data-name="${item.product.product_name}"
                            data-product="${item.product_id}"
                            data-branch="${item.branch_id}"
                            data-refno="${item.product.product_tag}"
                            data-branchname="${item.branch.branch_name}"
                            data-bs-toggle="modal" data-bs-target="#orderModal">
                            <i class="fa fa-shopping-cart" title="Order Product"></i>
                        </button>
                    `;

                    const row = `
                        <tr id="row-${item.cust_pro_id}">
                            <td>__SR__</td>
                            <td>${item.category?.category_name ?? '-'}</td>
                            <td>${item.product?.product_name ?? '-'}</td>
                            <td>${statusText}</td>
                            <td>${item.employee?.emp_name ?? '-'}</td>
                            <td>${actionButtons}</td>
                        </tr>
                    `;

                    if (isPurchased) {
                        purchasedHtml += row.replace('__SR__', purchasedIndex++);
                    } else {
                        normalHtml += row.replace('__SR__', normalIndex++);
                    }
                });

                $('#productTableBody').html(normalHtml);
                $('#purchasedProductTableBody').html(purchasedHtml);
            }
        });
    };

    $(document).ready(function () {

        // initial load
        loadProductList();

        // category change -> render products
        $('#category_id').on('change', function () {
            renderProductsForCategory($(this).val());
        });

        // select all
        $(document).on('change', '#productSelectAll', function () {
            $('.product_cb').prop('checked', this.checked);
            updateProductDropText();
        });

        // single checkbox
        $(document).on('change', '.product_cb', function () {
            $('#productSelectAll').prop('checked', $('.product_cb').length === $('.product_cb:checked').length);
            updateProductDropText();
        });

        // ✅ Add multiple products (same backend as before: one request per product_id)
        $('#addProductBtn').on('click', function () {
            $('.error-text').text('');

            const categoryId = $('#category_id').val();
            const empId = $('#emp_id').val();

            if (!categoryId) {
                $('#error-category_id').text('Please select category.');
                return;
            }
            if (!empId) {
                $('#error-emp_id').text('Please select employee.');
                return;
            }

            let productIds = $('.product_cb:checked').map(function () {
                return $(this).val();
            }).get();

            if (!productIds.length) {
                $('#error-product_id').text('Please select at least one product.');
                return;
            }

            let baseData = {
                _token: '{{ csrf_token() }}',
                cust_id: $('#cust_id').val(),
                category_id: categoryId,
                visit_id: $('#visit_id').val(),
                emp_id: empId,
                visit_date: $('#visit_date').val(),
                status: $('#productstatus').val()
            };

            let requests = productIds.map(pid => $.ajax({
                url: "{{ route('custProduct.store') }}",
                method: "POST",
                data: Object.assign({}, baseData, { product_id: pid })
            }));

            $.when.apply($, requests).done(function () {
                loadProductList();
                $('#productSelectAll').prop('checked', false);
                $('.product_cb').prop('checked', false);
                updateProductDropText();
            });
        });

    });

    // delete
    $(document).on('click', '.deleteProduct', function (event) {
        event.preventDefault();
        let id = $(this).data('id');

        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `../customer-product/delete/${id}`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        $(`#row-${id}`).remove();
                    } else {
                        alert('Failed to delete the product.');
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('An error occurred while deleting the product.');
                }
            });
        }
    });

    // edit status modal
    $(document).on('click', '.editStatus', function () {
        let id = $(this).data('id');
        $('#statusproduct_id').val(id);
    });
</script>

@endsection