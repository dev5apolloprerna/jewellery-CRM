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
                                <a href="{{ route('EMPcustomer.index') }}"
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

                            <hr>
                            <h6 class="card-title text-uppercase fw-bold mt-4 mb-2">Add customer view product</h6>

                            <form id="regForm" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" class="form-control" name="visit_id" id="visit_id" value="{{ $feedback->visit_id ?? '' }}" readonly>
                                <input type="hidden" class="form-control" name="cust_id" id="cust_id" value="{{ $Customer->customer_id }}" readonly>

                                {{-- ✅ needed because old code was sending visit_date but field was commented --}}
                                <input type="hidden" name="visit_date" id="visit_date" value="{{ $feedback->visit_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}">

                                <div class="row gy-4">

                                    {{-- Category --}}
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

                                    {{-- ✅ Product (Category-wise + Multi checkbox dropdown) --}}
                                    <div class="col-lg-3 col-md-6">
                                        <div>
                                            <span style="color:red;">*</span>Product

                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                        type="button" id="productDropBtn"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                    Select product(s)
                                                </button>

                                                <div class="dropdown-menu w-100 p-2" id="productDropMenu"
                                                     style="max-height:260px; overflow:auto;">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="productSelectAll">
                                                        <label class="form-check-label" for="productSelectAll">Select All</label>
                                                    </div>
                                                    <div class="dropdown-divider"></div>

                                                    <div id="productCheckboxList">
                                                        <small class="text-muted">Select category first.</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="text-danger error-text" id="error-product_id"></span>
                                        </div>
                                    </div>

                                    {{-- Employee --}}
                                    <div class="col-lg-3 col-md-3">
                                        Employee Name <span style="color:red;">*</span>
                                        <select class="form-control" name="emp_id" id="emp_id">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->emp_id }}">
                                                    {{ $emp->emp_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text" id="error-emp_id"></span>
                                    </div>

                                   

                                    {{-- Add button --}}
                                    <div class="col-lg-1 col-md-6 mt-5">
                                        <div>
                                            <button class="btn btn-primary btn-user float-right mb-3 mx-2"
                                                    type="button" id="addProductBtn">Add</button>
                                        </div>
                                    </div>

                                    {{-- Product List (NOT Purchased) --}}
                                    <div class="mt-3">
                                        <h6 class="card-title text-uppercase text-black fw-bold mt-4 mb-2">Product List</h6>
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
                                            <tbody id="productTableBody"></tbody>
                                        </table>
                                    </div>

                                    {{-- Purchased Product List (ONLY Purchased) --}}
                                    <div class="mt-3">
                                        <h6 class="card-title text-uppercase text-black fw-bold mt-4 mb-2">Purchased Product List</h6>
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
                                            <tbody id="purchasedTableBody"></tbody>
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

                            {{-- Next Followup --}}
                            <div class="border p-3 mb-4">
                                <h6 class="card-title text-black text-uppercase fw-bold mb-3">Next Followup</h6>

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

                                                <button type="button" id="btnOpen"
                                                    class="btn {{ ($feedback->followup_status ?? '') == '0' ? 'btn-success' : 'btn-outline-success' }}">
                                                    Open
                                                </button>

                                                <button type="button" id="btnClose"
                                                    class="btn {{ ($feedback->followup_status ?? '') == '1' ? 'btn-danger' : 'btn-outline-danger' }}">
                                                    Close
                                                </button>
                                            </div>
                                            @error('followup_status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Close Reason</label>
                                            <select name="close_reason_id" class="form-control" id="close_reason" {{ optional($feedback)->followup_status != '1' ? 'disabled' : '' }}>
                                                <option value="">Select Reason</option>
                                                @foreach($closereason as $cs)
                                                    <option value="{{ $cs->close_reason_id }}"
                                                        {{ optional($feedback)->close_reason_id == $cs->close_reason_id ? 'selected' : '' }}>
                                                        {{$cs->close_reason}}
                                                    </option>
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

                                        <div class="col-lg-3 col-md-6">
                                            <label>Follow-up Date</label>
                                            <input type="date" name="next_followup_date" id="next_followup_date" class="form-control"
                                                value="{{ $feedback->next_followup_date ?? '' }}" {{ optional($feedback)->status == '1' ? 'disabled' : '' }}>
                                            @error('next_followup_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- ✅ changed id to avoid duplicate #emp_id --}}
                                        <div class="col-lg-3 col-md-6">
                                            <label for="followup_emp_id" class="form-label">Employee Name <span style="color:red;">*</span></label>
                                            <select class="form-control" name="emp_id" id="followup_emp_id" required>
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
                                    </div>
                                </form>
                            </div>

                            {{-- Followup History --}}
                            <h6 class="text-uppercase fw-bold mt-4 mb-2">Followup History</h6>
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
                                            <td>{{ $followup->employee->emp_name }}</td>
                                            <td>{{ $followup->followup_status == 0 ? 'Open' : ($followup->followup_status == 1 ? 'Close' : '') }}</td>
                                            <td>{{ $followup->closereason->close_reason ?? '-' }}</td>
                                            <td>{{ $followup->remark }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No Data Found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div> {{-- card-body --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Edit Status Modal --}}
<div class="modal fade flip" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Edit Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
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
                                <option value="{{ $status->order_status_id }}">{{ $status->status }}</option>
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

{{-- Order Product Modal --}}
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="orderForm" method="POST" enctype="multipart/form-data">
        @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="orderModalLabel">Order Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
          <input type="hidden" name="product_id" id="orderProductId">
          <input type="hidden" name="cust_pro_id" value="" id="ordercust_pro_id" class="form-control" readonly>

          <div class="row">
                <div class="card-header d-flex align-items-center">
                    <input type="hidden" id="orderbranch_id" value="">
                    <div class="col-md-3 mb-3">
                        <strong>Branch Name:</strong> <span id="orderbranch_name"></span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>Product Name:</strong> <span id="orderProduct"></span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="karat" class="form-label">Karat <span style="color:red;">*</span></label>
                    <select name="karat" id="karat" class="form-control">
                        <option value="">Select Karat</option>
                        @foreach ($purity as $prt)
                            <option value="{{ $prt->purity_id }}">{{ $prt->purity_value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="color_id" class="form-label">Color</label>
                    <select class="form-control" name="color_id" id="color_id">
                        <option value="">Select Color</option>
                        @foreach ($color as $c)
                            <option value="{{ $c->color_id }}">{{ $c->color_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="weight" class="form-label">Weight <span style="color:red;">*</span></label>
                    <input type="text" name="weight" class="form-control" maxlength="50" placeholder="Enter Weight" required>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="size" class="form-label">Size</label>
                    <input type="text" name="size" class="form-control" maxlength="50" placeholder="Enter Size">
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="refer_tag_number" class="form-label">Reference Tag Number</label>
                    <input type="text" name="refer_tag_number" id="orderrefno" class="form-control" maxlength="50" placeholder="Enter Reference Tag Number">
                </div>

                {{-- ✅ Browse instead of URL --}}
                <div class="col-lg-4 col-md-6 mt-3">
                    <label class="form-label">Reference Image (Browse)</label>
                    <input type="hidden" name="existing_refer_image_url" id="existing_refer_image_url">
                    <input type="file" name="refer_image_url" id="refer_image_url" class="form-control" accept="image/*">
                    <small><a href="#" id="existingRefImageLink" target="_blank" style="display:none;">View existing image</a></small>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="amount" class="form-label">Amount <span style="color:red;">*</span></label>
                    <input type="number" step="0.01" name="amount" class="form-control" maxlength="50" placeholder="Enter Amount" required>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="rate_type" class="form-label">Rate Type</label>
                    <select class="form-control" name="rate_type" id="rate_type">
                        <option value="">Select Rate Type</option>
                        <option value="Mk rate">Mk Rate</option>
                        <option value="Z rate">Z Rate</option>
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="rate_fix_open" class="form-label">Rate Fix/Open</label>
                    <input type="text" name="rate_fix_open" class="form-control" maxlength="50" placeholder="Enter Rate Fix/Open">
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label>Remark</label>
                    <textarea name="remark" class="form-control" maxlength="255" placeholder="Enter Remark"></textarea>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="given_to" class="form-label">Order Given To</label>
                    <select class="form-control" name="given_to" id="given_to">
                        <option value="">Select Vendor</option>
                        @foreach ($vendor as $emp)
                            <option value="{{ $emp->vendor_id }}">{{ $emp->contact_person }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="delivery_status" class="form-label">Delivery Status <span style="color:red;">*</span></label>
                    <select class="form-control" name="delivery_status" id="delivery_status">
                        <option value="">Select Delivery Status</option>
                        @foreach ($orderStatus as $status)
                            <option value="{{ $status->order_status_id }}">{{ $status->status }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- ✅ Show only when Not Purchased --}}
                <div class="col-lg-4 col-md-6 mt-3" id="notPurchasedReasonWrap" style="display:none;">
                    <label class="form-label">Reason (Not Purchased)</label>
                    <select class="form-control" name="not_purchased_reason_id" id="not_purchased_reason_id">
                        <option value="">Select Reason</option>
                        @foreach($closereason as $cs)
                            <option value="{{ $cs->close_reason_id }}">{{ $cs->close_reason }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4 col-md-6 mt-3">
                    <label for="delivery_date" class="form-label">Delivery Date</label>
                    <input type="date" name="delivery_date" class="form-control" maxlength="50">
                </div>
          </div>

          <div class="modal-footer mt-3">
            <button type="submit" class="btn btn-primary">Confirm Order</button>
          </div>

        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
/**
 * ✅ Category-wise Products + Multi Checkbox Add
 * ✅ Split tables: Product List (NOT Purchased) + Purchased (ONLY Purchased)
 * ✅ Uses same URLs/routes already used in this file
 */

(function () {

    // Build products array from $Products (already available in this page)
    const ALL_PRODUCTS = @json(
        $Products->map(function($p){
            return [
                'product_id' => $p->product_id,
                'product_name' => $p->product_name,
                'category_id' => $p->category_id ?? null
            ];
        })->values()
    );

    function updateProductDropText() {
        const count = $('.product_cb:checked').length;
        $('#productDropBtn').text(count ? (count + ' selected') : 'Select product(s)');
    }

    function renderProductsByCategory(categoryId) {
        $('#error-product_id').text('');
        $('#productSelectAll').prop('checked', false);

        if (!categoryId) {
            $('#productCheckboxList').html('<small class="text-muted">Select category first.</small>');
            updateProductDropText();
            return;
        }

        const list = ALL_PRODUCTS.filter(p => String(p.category_id) === String(categoryId));

        if (!list.length) {
            $('#productCheckboxList').html('<small class="text-muted">No products found.</small>');
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

        $('#productCheckboxList').html(html);
        updateProductDropText();
    }

    // Prevent dropdown closing when clicking inside
    $(document).on('click', '#productDropMenu', function(e){ e.stopPropagation(); });

    // ✅ GLOBAL (fix: loadProductList not defined)
    window.loadProductList = function () {
        $.ajax({
            url: "{{ route('EMPcustProduct.index', $id) }}",
            method: "GET",
            success: function (products) {

                let normalHtml = '';
                let purchasedHtml = '';
                let n1 = 1, n2 = 1;

                products.forEach((item) => {

                    const statusText = (item.order_details?.order_status?.status ?? item.status ?? '-');
                    const statusLower = String(statusText).trim().toLowerCase();
                    const isOrderPlaced = !!item.order_details;

                    // ✅ Purchased table ONLY for "Purchased"
                    const isPurchased = (isOrderPlaced && statusLower === 'purchased');

                    let actionButtons = `
                        <button class="btn btn-danger btn-sm deleteProduct" data-id="${item.cust_pro_id}">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;

                    // Edit button only if order placed
                    if (isOrderPlaced) {
                        actionButtons += `
                            <button type="button" class="btn btn-success btn-sm editStatus"
                                data-id="${item.cust_pro_id}"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal">
                                <i class="fa fa-edit"></i>
                            </button>
                        `;
                    }

                    // Order button
                    actionButtons += `
                        <button type="button" class="btn btn-success btn-sm orderProduct"
                            data-id="${item.cust_pro_id}"
                            data-name="${item.product?.product_name ?? ''}"
                            data-product="${item.product_id}"
                            data-branch="${item.branch_id}"
                            data-refno="${item.product?.product_tag ?? ''}"
                            data-branchname="${item.branch?.branch_name ?? ''}"
                            data-bs-toggle="modal"
                            data-bs-target="#orderModal">
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
                        purchasedHtml += row.replace('__SR__', n2++);
                    } else {
                        normalHtml += row.replace('__SR__', n1++);
                    }
                });

                $('#productTableBody').html(normalHtml || `<tr><td colspan="6" class="text-center text-muted">No products</td></tr>`);
                $('#purchasedTableBody').html(purchasedHtml || `<tr><td colspan="6" class="text-center text-muted">No purchased products</td></tr>`);
            }
        });
    };

    $(document).ready(function () {

        // Initial render
        renderProductsByCategory($('#regForm #category_id').val());
        loadProductList();

        // Category change
        $(document).on('change', '#regForm #category_id', function () {
            renderProductsByCategory($(this).val());
        });

        // Select all
        $(document).on('change', '#productSelectAll', function () {
            $('.product_cb').prop('checked', this.checked);
            updateProductDropText();
        });

        // Single checkbox
        $(document).on('change', '.product_cb', function () {
            $('#productSelectAll').prop('checked', $('.product_cb').length === $('.product_cb:checked').length);
            updateProductDropText();
        });

        // ✅ Add multiple products (one request per product_id, same backend)
        $('#addProductBtn').on('click', function (e) {
            e.preventDefault();
            $('.error-text').text('');

            const categoryId = $('#regForm #category_id').val();
            const empId = $('#regForm #emp_id').val();

            if (!categoryId) { $('#error-category_id').text('Please select category.'); return; }
            if (!empId) { $('#error-emp_id').text('Please select employee.'); return; }

            const productIds = $('.product_cb:checked').map(function(){ return $(this).val(); }).get();
            if (!productIds.length) { $('#error-product_id').text('Please select at least one product.'); return; }

            const baseData = {
                _token: '{{ csrf_token() }}',
                cust_id: $('#regForm #cust_id').val(),
                category_id: categoryId,
                visit_id: $('#regForm #visit_id').val(),
                emp_id: empId,
                visit_date: $('#regForm #visit_date').val(),
                status: $('#regForm #productstatus').val()
            };

            const requests = productIds.map(pid => $.ajax({
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

    // Delete product (same url used in file)
    $(document).on('click', '.deleteProduct', function (event) {
        event.preventDefault();
        const id = $(this).data('id');

        if (!confirm('Are you sure you want to delete this product?')) return;

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
    });

    // Edit status modal
    $(document).on('click', '.editStatus', function () {
        $('#statusproduct_id').val($(this).data('id'));
    });

    // Order modal open (same url used in file)
    $(document).on('click', '.orderProduct', function () {
        let custProId = $(this).data('id');
        let productId = $(this).data('product');
        let branchId = $(this).data('branch');
        let productName = $(this).data('name');
        let branchName = $(this).data('branchname');
        let refNo = $(this).data('refno');

        $('#ordercust_pro_id').val(custProId);
        $('#orderProductId').val(productId);
        $('#orderbranch_id').val(branchId);
        $('#orderProduct').text(productName);
        $('#orderbranch_name').text(branchName);
        $('#orderrefno').val(refNo);

        $('#orderForm')[0].reset();

        // hide reason + existing image link
        $('#notPurchasedReasonWrap').hide();
        $('#not_purchased_reason_id').val('');

        $('#existing_refer_image_url').val('');
        $('#existingRefImageLink').hide().attr('href', '#');

        $.ajax({
            url: '/jewellery_crm/get-order-details/' + custProId,
            type: 'GET',
            success: function (response) {
                if (response.success && response.data) {
                    let data = response.data;

                    $('select[name="karat"]').val(data.karat);
                    $('select[name="color_id"]').val(data.color_id);
                    $('input[name="weight"]').val(data.weight);
                    $('input[name="size"]').val(data.size);
                    $('input[name="refer_tag_number"]').val(data.refer_tag_number);
                    $('input[name="amount"]').val(data.amount);
                    $('input[name="rate_fix_open"]').val(data.rate_fix_open);
                    $('textarea[name="remark"]').val(data.remark);
                    $('select[name="rate_type"]').val(data.rate_type);
                    $('select[name="given_to"]').val(data.given_to);
                    $('select[name="delivery_status"]').val(data.delivery_status);
                    $('input[name="delivery_date"]').val(data.delivery_date);

                    // existing image url (cannot set file input)
                    if (data.refer_image_url) {
                        $('#existing_refer_image_url').val(data.refer_image_url);
                        $('#existingRefImageLink').attr('href', data.refer_image_url).show();
                    }

                    // show reason if Not Purchased
                    const txt = ($('#delivery_status option:selected').text() || '').toLowerCase();
                    if (txt.includes('not purchased')) {
                        $('#notPurchasedReasonWrap').show();
                    }
                }
            }
        });
    });

    // Delivery status change -> show/hide reason
    $(document).on('change', '#delivery_status', function () {
        const txt = ($('#delivery_status option:selected').text() || '').toLowerCase();
        if (txt.includes('not purchased')) {
            $('#notPurchasedReasonWrap').show();
        } else {
            $('#notPurchasedReasonWrap').hide();
            $('#not_purchased_reason_id').val('');
        }
    });

    // ✅ Submit order with FormData (for file upload)
    $('#orderForm').on('submit', function (e) {
        e.preventDefault();

        if (!confirm('Are you sure you want to order this product?')) return;

        const fd = new FormData(this);

        $.ajax({
            url: '{{ route("custOrder.orderProduct") }}',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $('#orderModal').modal('hide');
                    loadProductList(); // ✅ moves between tables based on status
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function () {
                alert('An unexpected error occurred.');
            }
        });
    });

})();
</script>

<script>
    // Followup toggle (your existing logic)
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
        closeReason.disabled = true;
        fDate.disabled = false;
    });

    btnClose.addEventListener('click', function () {
        statusField.value = '1';
        btnClose.classList.add('btn-danger');
        btnClose.classList.remove('btn-outline-danger');
        btnOpen.classList.add('btn-outline-success');
        btnOpen.classList.remove('btn-success');
        closeReason.disabled = false;
        fDate.disabled = true;
    });

    if (statusField.value === '1') {
        closeReason.disabled = false;
        fDate.disabled = true;
    } else {
        closeReason.disabled = true;
        fDate.disabled = false;
    }
</script>
@endsection
