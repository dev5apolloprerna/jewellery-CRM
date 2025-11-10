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
                                    <div class="col-md-3"><strong>Branch:</strong> {{ $Customer->branch->branch_name ?? '-' }}</div>
                                    <div class="col-md-3"><strong>State:</strong> {{ $Customer->state->stateName ?? '-' }}</div>
                                    <div class="col-md-3"><strong>City:</strong> {{ $Customer->city }}</div>
                                    <div class="col-md-3"><strong>Address:</strong> {{ $Customer->address ?? '-' }}</div>
                                </div>
                            </div>

                          
                                            <div class="mt-3">
                                                <h6 class="text-uppercase fw-bold mt-4 mb-2">Product List</h6>
                                                    <table class="table table-bordered" >
                                                        <thead>
                                                            <tr>
                                                                <th>Sr. No</th>
                                                                <th>Product Category</th>
                                                                <th>Product Name</th>
                                                                <th>Visit Date</th>
                                                                <!-- <th>Product Amount</th> -->
                                                                <th>Status</th>
                                                                <th>Attended By</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="productTableBody">
                                                            @if(sizeof($CustProducts) != 0)
                                                                @foreach($CustProducts as $key => $product)
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>{{ $product->category->category_name }}</td>
                                                                        <td>{{ $product->product->product_name }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($product->visit_date)->format('d-m-Y') }}</td>
                                                                        <td>{{ $product->status ?? '-' }}</td>
                                                                        <td>{{ $product->employee->emp_name }}</td>
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
                                <div class="card-body">
                                 <h6 class="text-uppercase fw-bold mt-4 mb-2">Edit Order </h6>
                                    <form method="POST" action="{{ isset($order) ? route('custOrder.update', $order->order_id) : route('custOrder.store') }}">
                                        @csrf
                                        @if(isset($order))
                                            @method('PUT')
                                        @endif
                                        <div class="row gy-4">
                                        <input type="hidden" name="cust_id" value="{{ $order->cust_id ?? ($id ?? '') }}" class="form-control" required><br>

                                            <div class="col-lg-3 col-md-6">
                                                <label>Status</label><br>
                                                    <div class="btn-group" role="group" aria-label="Status">
                                                        <input type="hidden" name="status" id="status" value="{{ $order->status ?? '1' }}">
                                                        
                                                        <button type="button" id="btnOpen" class="btn btn-success">
                                                            Received
                                                        </button>
                                                        
                                                        <button type="button" id="btnClose" class="btn btn-outline-danger">
                                                            Delivered
                                                        </button>
                                                    </div>                                                    
                                                     </button>
                                                     @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label">Branch  <span style="color:red;">*</span></label>
                                            <select name="branch_id" class="form-control" required>
                                                <option value="">Select Branch</option>
                                                @foreach ($branches as  $b)
                                                    <option value="{{ $b->branch_id }}" {{ old('branch_id', $order->branch_id ?? '') == $b->branch_id ? 'selected' : '' }}>
                                                        {{ $b->branch_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_id') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                         <div class="col-lg-3 col-md-6">
                                            <label for="emp_name" class="form-label">Employee Name <span style="color:red;">*</span></label>
                                            <select class="form-control" name="emp_id" id="emp_id" required>
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->emp_id }}" {{ old('emp_id', $order->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
                                                        {{ $emp->emp_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('emp_id') <small class="text-danger">{{ $message }}</small> @enderror

                                        </div>

                                        

                                        <div class="col-lg-3 col-md-6">
                                             <label>Amount <span style="color:red;">*</span></label>
                                            <input type="number" name="amount" value="{{ $order->amount ?? '' }}" class="form-control"  placeholder="Enter Amount" maxlength="50" required><br>
                                            @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Net Total<span style="color:red;">*</span></label>
                                            <input type="number" name="net_total" value="{{ $order->net_total ?? '' }}" placeholder="Enter Net Total" class="form-control" maxlength="50" required><br>
                                            @error('net_total') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Advance Payment <span style="color:red;">*</span></label>
                                            <input type="number" name="advance_payment" value="{{ $order->advance_payment ?? '' }}" class="form-control" maxlength="50" placeholder="Enter Advance Payment" required><br>
                                            @error('advance_payment') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Remark <span style="color:red;">*</span></label>
                                            <textarea name="remark" class="form-control" maxlength="255" placeholder="Enter Remark" required>{{ $order->remark ?? '' }}</textarea><br>
                                            @error('remark') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Rate Type <span style="color:red;">*</span></label>
                                            <input type="text" name="rate_type" value="{{ $order->rate_type ?? '' }}" placeholder="Enter Rate Type" class="form-control" maxlength="100" required><br>
                                            @error('rate_type') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                      <div class="col-lg-3 col-md-6">
                                            <label>Delivery Type <span style="color:red;">*</span></label>
                                            <input type="text" name="delivery_type" value="{{ $order->delivery_type ?? '' }}" placeholder="Enter Delivery Type" class="form-control" maxlength="50" required><br>
                                            @error('delivery_type') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                        <div class="card-footer mt-2">
                                            <div class="mb-3" style="float: right;">
                                            <button type="submit" class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                        </div>
                                        </div>
                                    </form>
                                </div>

                                    {{-- Followup History --}}
                            <h6 class="text-uppercase fw-bold mt-4 mb-2">Ordered Product</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Karat</th>
                                    <th>Color Name</th>
                                    <th>Weight</th>
                                    <th>Size</th>
                                    <th>Tag No.</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Net Total</th>
                                    <th>Given To</th>
                                    <th>Actions</th>

                                </thead>
                                <tbody>
                                 @if($orderDetails->isEmpty())
                                            <tr>
                                                <td colspan="9" class="text-center">No Customer Product found.</td>
                                            </tr>
                                         @else
                                           @foreach($orderDetails as $index => $detail)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $detail->product->product_name }}</td>
                                                <td>{{ $detail->karat }}</td>
                                                <td>{{ $detail->color_id }}</td>
                                                <td>{{ $detail->weight }}</td>
                                                <td>{{ $detail->size }}</td>
                                                <td>{{ $detail->refer_tag_number }}</td>
                                                <td>{{ $detail->status }}</td>
                                                <td>{{ $detail->amount }}</td>
                                                <td>{{ $detail->net_total }}</td>
                                                <td>{{ $detail->given_to }}</td>
                                                <td>
                                                    <div>                                                        
                                                        <a href="{{ route('custOrderDetail.edit', $detail->detail_order_id) }}"><i class="fa fa-edit"></i></a>
                                                        <a class="" href="#" data-bs-toggle="modal"
                                                                title="Delete" data-bs-target="#deleteRecordModal"
                                                                onclick="deleteData(<?= $detail->detail_order_id ?>);">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                        
                                                    </div>
                                                </td>
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
@endsection
@section('scripts')
<script>
    const statusField = document.getElementById('status');
    const btnOpen = document.getElementById('btnOpen');
    const btnClose = document.getElementById('btnClose');

    btnOpen.addEventListener('click', function () {
        statusField.value = '1';
        btnOpen.classList.add('btn-success');
        btnOpen.classList.remove('btn-outline-success');
        btnClose.classList.add('btn-outline-danger');
        btnClose.classList.remove('btn-danger');
    });

    btnClose.addEventListener('click', function () {
        statusField.value = '2';
        btnClose.classList.add('btn-danger');
        btnClose.classList.remove('btn-outline-danger');
        btnOpen.classList.add('btn-outline-success');
        btnOpen.classList.remove('btn-success');
    });

</script>
@endsection