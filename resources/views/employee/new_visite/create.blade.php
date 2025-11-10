@extends('layouts.app')
@section('title', 'Customer Detail')
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
                            <h5 class="card-title text-uppercase fw-bold text-black mb-0">Customer Detail</h5>
                        </div>

                        <div class="card-body">

                            {{-- Client Data List --}}
                            <div class="border p-3 mb-4">
                                <h6 class="text-uppercase fw-bold mb-3">Client Detail List</h6>
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

                            <hr>
                            <!-- {{-- Product List --}} -->

                            <h6 class="card-title text-uppercase text-black fw-bold mt-4 mb-2">Add customer view product</h6>

                            <form id="regForm" method="POST" action="" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="cust_id" id="cust_id" value="{{ $id }}">

                                        <div class="row gy-4">
                                            <div class="col-lg-3 col-md-6">
                                                <div>
                                                   Category <span style="color:red;">*</span>
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
                                                    Product <span style="color:red;">*</span>
                                                    <select class="form-control" name="product_id" id="product_id">
                                                        <option value="">Select Product</option>
                                                        @foreach($Products as $cat)
                                                        <option value="{{$cat->product_id}}">{{ $cat->product_name }}</option>
                                                        @endforeach 
                                                    </select>
                                                    <span class="text-danger error-text" id="error-product_id"></span>
                                                </div>
                                            </div>
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
                                           
                                            <div class="col-lg-1 col-md-6"><div>
                                                <button class="btn btn-primary btn-user float-right mt-4 mx-2" type="button" id="addProductBtn">Add</button>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <h6 class="card-title text-black text-uppercase fw-bold mt-4 mb-2">Product List</h6>
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
                                <h6 class="card-title text-uppercase fw-bold mb-3 text-black">Next Followup</h6>
                                    <!-- folloowup form start -->
                                    <form action="{{ route('custFollowup.store') }}" method="POST">
                                            @csrf
                                            <div class="row gy-4">
                                            <input type="hidden" class="form-control" name="visit_id" value="{{ $feedback->visit_id ?? '' }}" readonly>
                                            <input type="hidden" class="form-control" name="cust_id" value="{{ $id }}" readonly>
                                            <input type="hidden" class="form-control" name="branch_id" value="{{ $Customer->branch_id }}" readonly>

                                            <div class="col-lg-3 col-md-6">
                                                <label>Status</label><br>
                                                    <div class="btn-group" role="group" aria-label="Status">
                                                        <input type="hidden" name="followup_status" id="followup_status" value="{{ $feedback->followup_status ?? '0' }}">
                                                        
                                                        <button type="button" id="btnOpen" class="btn btn-success">
                                                            Open
                                                        </button>
                                                        
                                                        <button type="button" id="btnClose" class="btn btn-outline-danger">
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

                                                <select name="close_reason_id" class="form-control" id="close_reason">
                                                    <option value="">Select Reason</option>
                                                    @foreach($closereason as $cs)
                                                    <option value="{{ $cs->close_reason_id }}" >{{$cs->close_reason}}</option>
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
                                                <input type="date" name="next_followup_date" id="next_followup_date" class="form-control" value="{{ $feedback->next_followup_date ?? '' }}" >

                                                @error('next_followup_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                            <div class="col-lg-3 col-md-6">
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
                                           <div class="col-lg-3 col-md-6 mt-5">
                                                
                                                <button type="submit" class="btn btn-success">Save</button>
                                                <a href="{{ route('customer.index') }}" class="btn btn-danger">Back</a>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    
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
                            @foreach($orderStatus as $s)
                            <option value="{{ $s->order_status_id  }}">{{ $s->status}}</option>
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
               <label for="refer_image_url" class="form-label">Reference Image URL<span style="color:red;"></span></label>
                 <input type="text" name="refer_image_url" class="form-control"  value="{{ old('refer_image_url', $detail->refer_image_url ?? '') }}" placeholder="Enter Reference Tag Number"  maxlength="50" >
                @error('refer_image_url') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

        
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
$(document).ready(function () {
    $('#addProductBtn').click(function (e) {
        e.preventDefault();
    $('.error-text').text(''); // Clear previous errors

        $.ajax({
            url: "{{ route('custProduct.store') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                cust_id: $('#cust_id').val(),
                category_id: $('#category_id').val(),
                product_id: $('#product_id').val(),
                visit_id: $('#visit_id').val(),
                emp_id: $('#emp_id').val(),
                visit_date: $('#visit_date').val(),
                status: $('#productstatus').val()
            },
             success: function (response) {
                    if (response.success) {
                        loadProductList();
                        $('#yourFormId')[0].reset(); // reset form if needed
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $(`#error-${key}`).text(value[0]); // show error next to input
                        });
                    }
                }
        });
    });

    function loadProductList() {
        $.ajax({
            url: "{{ route('EMPvisit.product', $id) }}",
            method: 'GET',
            success: function (products) {
                let html = '';
                products.forEach((item, index) => {
                    html += `<tr id="row-${item.cust_pro_id}">
                        <td>${index + 1}</td>
                        <td>${item.category.category_name}</td>
                        <td>${item.product.product_name}</td>
                        <td>${item.order_details?.order_status?.status ?? item.status}</td>
                        <td>${item.employee.emp_name}</td>
                        <td>
                            <button class="btn btn-danger btn-sm deleteProduct" data-id="${item.cust_pro_id}"><i class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-success btn-sm editStatus" data-id="${item.cust_pro_id}" data-bs-toggle="modal"    data-bs-target="#editModal"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-success btn-sm orderProduct" data-id="${item.cust_pro_id}" data-name="${item.product.product_name}" data-product="${item.product_id}" data-branch="${item.branch_id}" data-refno="${item.product.product_tag}" data-branchname="${item.branch.branch_name}"  data-bs-toggle="modal"  data-bs-target="#orderModal"><i class="fa fa-shopping-cart" title="Order Product"></i></button>
                        </td>
                    </tr>`;
                });
                $('#productTableBody').html(html);
            }
        });
    }

    loadProductList(); // Load on page load
});

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-based
    const year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

$(document).on('click', '.deleteProduct', function (event) {
    event.preventDefault(); // prevent page refresh

    let id = $(this).data('id');
    if (confirm('Are you sure you want to delete this product?')) {
        $.ajax({
            url: `/employee/customer-product/delete/${id}`,
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
                $('input[name="rate_type"]').val(data.rate_type);
                $('input[name="rate_fix_open"]').val(data.rate_fix_open);
                $('textarea[name="remark"]').val(data.remark);
                $('select[name="given_to"]').val(data.given_to);
                $('select[name="delivery_status"]').val(data.delivery_status);
                $('input[name="delivery_date"]').val(data.delivery_date);
            }
            
        }
    });
});


// When the form is submitted
$('#orderForm').on('submit', function (e) {
    e.preventDefault(); // prevent default form submission

    if (!confirm('Are you sure you want to order this product?')) {
        return; // stop if user cancels
    }

    let formData = $(this).serialize(); // serialize all form data

    $.ajax({
        url: '{{ route("custOrder.orderProduct") }}',
        method: 'POST',
        data: formData,
        success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#orderModal').modal('hide');
                                     location.reload();             

                        //loadProductList(); // fallback
                 
                } else {
                    alert('Error: ' + response.message);
                }
            },
        error: function () {
            alert('An unexpected error occurred.');
        }
    });
});
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
@endsection