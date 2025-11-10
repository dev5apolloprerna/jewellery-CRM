@extends('layouts.app')
@section('title', 'Customer Order Detail')
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
                            <h5 class="card-title text-uppercase fw-bold text-black mb-0">Customer Order Detail</h5>
                            <a href="{{ route('EMPcustOrder.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Back
                                </a>
                        </div>

                        <div class="card-body">

                            {{-- Client Data List --}}
                            <div class="border p-3 mb-4">
                                <h6 class="text-uppercase fw-bold mb-3">Client Detail</h6>
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

                          
                                    <div class="mt-3">
                                        <h6 class="text-uppercase text-black fw-bold mt-4 mb-2">Product List</h6>
                                            <table class="table table-bordered" >
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No</th>
                                                        <th>Product Name</th>
                                                        <th>Amount</th>
                                                        <th>Net Amount</th>
                                                        <th>Karat</th>
                                                        <th>Weight</th>
                                                        <th>Refer Tag Number</th>
                                                        <th>Given To</th>
                                                        <th>Order Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="productTableBody">
                                                    @if(sizeof($CustProducts) != 0)
                                                        @foreach($CustProducts as $key => $product)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $product->product->product_name }}</td>
                                                                <td>{{ $product->amount ?? '-' }}</td>
                                                                <td>{{ $product->net_total ?? '-' }}</td>
                                                                <td>{{ $product->karat ?? '-' }}</td>
                                                                <td>{{ $product->weight ?? '-' }}</td>
                                                                <td>{{ $product->refer_tag_number ?? '-' }}</td>
                                                                <td>{{ $product->vendor->contact_person ?? '-' }}</td>
                                                                <td>{{ $product->orderStatus->status ?? '-' }}</td>

                                                                <td>
                                                                <button type="button" class="btn btn-success btn-sm editStatus" data-bs-target="#editModal_{{ $product->cust_pro_id }}"  data-bs-toggle="modal"><i class="fa fa-edit"></i></button>
        
                                                                    </td>
<!--                                                                <td>
                                                                    <a href="" data-bs-toggle="modal"  data-bs-target="#orderModal" onclick="viewData(<?= $product->detail_order_id ?>)" title="Edit Product"><i class="fa fa-edit" ></i>
                                                                </td>-->

                                                            </tr>
                                                            <div class="modal fade flip" id="editModal_{{ $product->cust_pro_id }}" tabindex="-1" aria-hidden="true">
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
                                                                            <input type="hidden" name="product_id" id="statusproduct_id" value="{{ $product->cust_pro_id }}">
                                                            
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
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="3" class="text-center">No Data Found</td>
                                                        </tr>

                                                        @endif
                                                </tbody>
                                            </table>
                                    </div>
                                    <hr>
                                <div class="card-body">
                                 <h6 class="card-title text-uppercase fw-bold mt-4 mb-2 text-black">Add Payment Detail </h6>
                                    <form method="POST" action="{{ route('orderPayment.store', [$order->order_id]) }}" id="paymentForm">
                                        @csrf
                                        @if(isset($order))
                                            @method('PUT')
                                        @endif
                                        <div class="row gy-4">
                                        <input type="hidden" name="cust_id" value="{{ $order->cust_id ?? ($id ?? '') }}" class="form-control" required><br>

                                        <div class="col-lg-3 col-md-6">
                                             <label>Amount <span style="color:red;"></span></label>
                                            <input type="number" name="amount" value="{{ $order->amount ?? '' }}" class="form-control"  placeholder="Enter Amount" maxlength="50" readonly><br>
                                            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Net Total<span style="color:red;"></span></label>
                                            <input type="number" name="net_amount" value="{{ $order->net_total ?? '' }}" placeholder="Enter Net Total" class="form-control" maxlength="50" readonly><br>
                                            @error('net_total') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                         <div class="col-lg-3 col-md-6">
                                            <label>Paid Amount <span style="color:red;"></span></label>
                                            <input type="number" name="paid_amount" value="{{ $order->paid_amount ?? '' }}" class="form-control" maxlength="50" placeholder="Enter Paid Payment" readonly><br>
                                            @error('paid_amount') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label>Amount to be paid <span style="color:red;">*</span></label>
                                            <input type="number" name="amount_to_be_paid" value="{{ $order->due_amount ?? '' }}" class="form-control" maxlength="50" placeholder="Enter Amount To Be Paid" required><br>
                                            @error('paid_amount') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        @if($order->due_amount != 0)
                                        <div class="col-lg-3 col-md-6">
                                            <label>Next Payment Follwup Date<span style="color:red;">*</span></label>
                                            <input type="date" name="next_followup_date" value="{{ $order->next_followup_date ?? '' }}" placeholder="Enter Next Payment Followup Date" class="form-control" maxlength="50"><br>
                                            @error('next_followup_date') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        @endif
                                        <div class="col-lg-1 col-md-6 mt-5">
                                            <button type="submit" class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>

                                    {{-- Followup History --}}
                            <h6 class="text-uppercase fw-bold mt-4 mb-2">Payment Detail</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Amount</th>
                                    <th>Net Total</th>
                                    <th>Paid Amount</th>
                                    <th>Remaining Amount</th>
                                    <th>Next Payment Followup Date</th>
                                    <!--<th>Actions</th>-->

                                </thead>
                                <tbody>
                                 @if($paymentDetail->isEmpty())
                                            <tr>
                                                <td colspan="9" class="text-center">No Customer Product found.</td>
                                            </tr>
                                         @else
                                           @foreach($paymentDetail as $index => $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $payment->amount }}</td>
                                                <td>{{ $payment->amount }}</td>
                                                <td>{{ $payment->paid_amount }}</td>
                                                <td>{{ $payment->due_amount }}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->next_followup_date)->format('d-m-Y') }}</td>
                                                <!--<td>
                                                    <div>                                                        
                                                        <a href="{{ route('custOrderDetail.edit', $orderDetailIds) }}"><i class="fa fa-edit"></i></a>
                                                        <a class="" href="#" data-bs-toggle="modal"
                                                                title="Delete" data-bs-target="#deleteRecordModal"
                                                                onclick="deleteData(<?= $payment->detail_order_id ?>);">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                        
                                                    </div>
                                                </td>-->
                                            </tr>
                                        @endforeach
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
          <input type="hidden" name="detail_order_id" id="detail_order_id">

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
                <input type="text" name="karat" id="karat" class="form-control" value="{{ old('karat', $detail->karat ?? '') }}" maxlength="100" placeholder="Enter Karat" required>
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
                <input type="text" step="0.01" name="weight" id="weight" class="form-control" value="{{ old('weight', $detail->weight ?? '') }}" maxlength="50" placeholder="Enter Weight"  required>
                @error('weight') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
                 <label for="size" class="form-label">Size <span style="color:red;"></span></label>
                <input type="text" name="size" id="size" class="form-control"  value="{{ old('size', $detail->size ?? '') }}" maxlength="50" placeholder="Enter Size" >
                @error('size') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
               <label for="refer_tag_number" class="form-label">Reference Tag Number <span style="color:red;"></span></label>
                 <input type="text" name="refer_tag_number" id="refer_tag_number" class="form-control"  value="{{ old('refer_tag_number', $detail->refer_tag_number ?? '') }}" placeholder="Enter Reference Tag Number"  maxlength="50" >
                @error('refer_tag_number') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-lg-4 col-md-6 mt-3">
               <label for="refer_image_url" class="form-label">Reference Image URL<span style="color:red;"></span></label>
                 <input type="text" name="refer_image_url" id="refer_image_url" class="form-control"  value="{{ old('refer_image_url', $detail->refer_image_url ?? '') }}" placeholder="Enter Reference Tag Number"  maxlength="50" >
                @error('refer_image_url') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label for="amount" class="form-label">Amount <span style="color:red;">*</span></label>
                <input type="number" step="0.01" name="amount" id="amount" class="form-control"  value="{{ old('amount', $detail->amount ?? '') }}" maxlength="50" placeholder="Enter Amount" required>
                @error('amount') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
             <div class="col-lg-4 col-md-6 mt-3">
              <label for="rate_type" class="form-label">Rate Type <span style="color:red;"></span></label>
                <input type="text" step="0.01" name="rate_type" class="form-control"  id="rate_type" value="{{ old('rate_type', $detail->rate_type ?? '') }}" maxlength="50" placeholder="Enter Rate Type">
                @error('rate_type') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
             <div class="col-lg-4 col-md-6 mt-3">
              <label for="rate_fix_open" class="form-label">Rate Fix/Open <span style="color:red;"></span></label>
                <input type="text" step="0.01" name="rate_fix_open" class="form-control" id="rate_fix_open"  value="{{ old('rate_fix_open', $detail->rate_fix_open ?? '') }}" maxlength="50" placeholder="Enter Rate Fix/Open">
                @error('rate_fix_open') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
            <div class="col-lg-4 col-md-6 mt-3">
                <label>Remark <span style="color:red;">*</span></label>
                <textarea name="remark" class="form-control" id="remark" maxlength="255" placeholder="Enter Remark" required>{{ $order->remark ?? '' }}</textarea><br>
                @error('remark') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-lg-4 col-md-6 mt-3">
              <label for="given_to" class="form-label">Given To <span style="color:red;"></span></label>
                <select class="form-control" name="given_to" id="given_to" >
                    <option value="">Select Vendor</option>
                    @foreach ($vendor as $emp)
                        <option value="{{ $emp->vendor_id }}">
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
                    <option value="1" {{ old('delivery_status', $order->delivery_status ?? '') == 1 ? 'selected' : '' }}>Ordered</option>
                    <option value="2" {{ old('delivery_status', $order->delivery_status ?? '') == 2 ? 'selected' : '' }}>Delivered</option>
                </select>
                            @error('delivery_status') <small class="text-danger">{{ $message }}</small> @enderror

            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label for="delivery_date" class="form-label">Delivery Date <span style="color:red;">*</span></label>
                <input type="date" name="delivery_date" id="delivery_date" class="form-control" placeholder="Enter Given To" value="{{ old('delivery_date', $detail->delivery_date ?? '') }}" maxlength="50" >
                @error('delivery_date') <small class="text-danger">{{ $message }}</small> @enderror

            </div>
      
    </div>
        <div class="modal-footer mt-3">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection

@section('scripts')
<script>
 function viewData(orderId) {
        //let baseUrl = window.location.origin+"/attendance_system"; // Gets "http://127.0.0.1:8000"
        //let baseUrl = window.location.origin; // Gets "http://127.0.0.1:8000"

        $.ajax({
        
        url: '/jewellery_crm/cust-order-detail/'+ orderId+'/edit' , // Correct full URL
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            // Populate modal fields with response data
            $('#detail_order_id').val(response.detail_order_id);
            $('#branch_id').val(response.branch_id);
            $('#orderbranch_name').text(response.branch.branch_name);
            $('#orderProduct').text(response.product.product_name);
            $('#karat').val(response.karat);
            $('#color_id').val(response.color_id);
            $('#weight').val(response.weight);
            $('#size').val(response.size);
            $('#refer_tag_number').val(response.refer_tag_number);
            $('#refer_image_url').val(response.refer_image_url);
            $('#amount').val(response.amount);
            $('#remark').val(response.remark);
            $('#given_to').val(response.given_to);
            $('#delivery_status').val(response.delivery_status);
            $('#delivery_date').val(response.delivery_date);
            $('#rate_type').val(response.rate_type);
            $('#rate_fix_open').val(response.rate_fix_open);

        },
        error: function() {
            alert('Failed to fetch salary details.');
        }
    });
}


// When the form is submitted
$('#orderForm').on('submit', function (e) {
    e.preventDefault(); // prevent default form submission

    if (!confirm('Are you sure you want to update this product?')) {
        return; // stop if user cancels
    }

    let formData = $(this).serialize(); // serialize all form data

    $.ajax({
        url: '{{ route("custOrderDetail.update") }}',
        method: 'POST',
        data: formData,
        success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#orderModal').modal('hide');
                    location.reload();             
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
@endsection