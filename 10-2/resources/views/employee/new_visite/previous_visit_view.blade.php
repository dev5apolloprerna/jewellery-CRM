@extends('layouts.app')
@section('title', 'Customer Previous Visite')
@section('content')

<style>
  .tabs-wrap{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}
  .tab-link{
    text-decoration:none;border:1px solid #eee;background:#fff;
    padding:10px 14px;border-radius:12px;font-weight:600;color:#374151;
    display:flex;align-items:center;gap:10px;
    box-shadow:0 6px 18px rgba(0,0,0,.04);
    transition:.15s;
  }
  .tab-link:hover{transform:translateY(-1px)}
  .tab-link.active{background:#5c2323;border-color:#5c2323;color:#fff}
  .tab-ic{width:30px;height:30px;border-radius:10px;display:grid;place-items:center;background:#f3f4f6;color:#111827}
  .tab-link.active .tab-ic{background:rgba(255,255,255,.18);color:#fff}
  .select2-container .select2-selection--single{height:38px}
  .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:36px}
  .select2-container--default .select2-selection--single .select2-selection__arrow{height:36px}
</style>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

@php
  $r = \Illuminate\Support\Facades\Route::currentRouteName();
  $cid = $id ?? ($Customer->customer_id ?? null);
  $latest = $Customer->latestVisit ?? null;
@endphp

<div class="tabs-wrap">
  <a class="tab-link {{ $r=='EMPcustomer.history' ? 'active' : '' }}" href="{{ route('EMPcustomer.history', $cid) }}">
    <span class="tab-ic"><i class="fa fa-eye"></i></span> History
  </a>

  @if($latest)
    @if(($latest->followup_status ?? 0) == 1)
      <a class="tab-link {{ $r=='EMPvisit.create' ? 'active' : '' }}" href="{{ route('EMPvisit.create', $cid) }}">
        <span class="tab-ic"><i class="fas fa-plus-circle"></i></span> New Visit
      </a>
    @endif
  @else
    <a class="tab-link {{ $r=='EMPvisit.create' ? 'active' : '' }}" href="{{ route('EMPvisit.create', $cid) }}">
      <span class="tab-ic"><i class="fas fa-plus-circle"></i></span> New Visit
    </a>
  @endif

  @if($latest)
    <a class="tab-link {{ $r=='EMPvisit.previous_visit_view' ? 'active' : '' }}" href="{{ route('EMPvisit.previous_visit_view', $cid) }}">
      <span class="tab-ic"><i class="fa fa-message"></i></span> Previous Visit
    </a>
  @endif

  <a class="tab-link {{ $r=='EMPcustOrder.index' ? 'active' : '' }}" href="{{ route('EMPcustOrder.index') }}">
    <span class="tab-ic"><i class="fa fa-shopping-bag"></i></span> Orders
  </a>

  @if(!empty($orderId))
    <a class="tab-link {{ $r=='EMPorderPayment.index' ? 'active' : '' }}" href="{{ route('EMPorderPayment.index', $orderId) }}">
      <span class="tab-ic"><i class="fa fa-credit-card"></i></span> Payment
    </a>
  @endif
</div>

@include('common.alert')

<div class="row">
  <div class="col-lg-12">
    <div class="card">

      <div class="d-flex justify-content-between card-header">
        <h5 class="card-title text-uppercase fw-bold text-black mb-0">Customer Previous Visite</h5>
        <div class="page-title-right">
          @if(($feedback->followup_status ?? 0) == 1)
            <a href="{{ route('newVisite.create',$id) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Add New Visit</a>
          @endif
          <a href="{{ route('EMPcustomer.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Back</a>
        </div>
      </div>

      <div class="card-body">

        {{-- Client Data --}}
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

        <form id="regForm" method="POST" action="javascript:void(0)">
          @csrf
          <input type="hidden" name="visit_id" id="visit_id" value="{{ $feedback->visit_id ?? '' }}">
          <input type="hidden" name="cust_id" id="cust_id" value="{{ $Customer->customer_id }}">
          <input type="hidden" name="visit_date" id="visit_date" value="{{ $feedback->visit_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}">

          <div class="row gy-4">
            <div class="col-lg-3 col-md-6">
              <span class="text-danger">*</span> Category
              <select class="form-control" name="category_id" id="category_id">
                <option value="">Select Category</option>
                @foreach($Category as $cat)
                  <option value="{{$cat->category_id}}">{{ $cat->category_name }}</option>
                @endforeach
              </select>
              <span class="text-danger error-text" id="error-category_id"></span>
            </div>

            <div class="col-lg-3 col-md-6">
              <span class="text-danger">*</span> Product
              <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="productDropBtn" data-bs-toggle="dropdown" aria-expanded="false">
                  Select product(s)
                </button>

                <div class="dropdown-menu w-100 p-2" id="productDropMenu" style="max-height:260px; overflow:auto;">
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

            <div class="col-lg-3 col-md-3">
              Employee Name <span class="text-danger">*</span>
              <select class="form-control" name="emp_id" id="emp_id">
                <option value="">Select Employee</option>
                @foreach ($employees as $emp)
                  <option value="{{ $emp->emp_id }}">{{ $emp->emp_name }}</option>
                @endforeach
              </select>
              <span class="text-danger error-text" id="error-emp_id"></span>
            </div>

            <div class="col-lg-1 col-md-6 mt-5">
              <button class="btn btn-primary btn-user float-right mb-3 mx-2" type="button" id="addProductBtn">Add</button>
            </div>

            {{-- Product List --}}
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

            {{-- Purchased Product List --}}
            <div class="mt-3">
              <h6 class="card-title text-uppercase text-black fw-bold mt-4 mb-2">Purchased Product List</h6>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>Product Category</th>
                    <th>Product Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Attended By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="purchasedTableBody"></tbody>
              </table>
            </div>

            {{-- Not Purchased Product List --}}
            <div class="mt-3">
              <h6 class="card-title text-uppercase text-black fw-bold mt-4 mb-2">Not Purchased Product List</h6>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>Product Category</th>
                    <th>Product Name</th>
                    <th>Status</th>
                    <th>Attended By</th>
                    <th>Reason</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="notPurchasedTableBody"></tbody>
              </table>
            </div>

          </div>
        </form>

        <hr>

        {{-- Next Followup --}}
        <div class="border p-3 mb-4">
          <h6 class="card-title text-black text-uppercase fw-bold mb-3">Next Followup</h6>
          <form action="{{ route('custFollowup.store') }}" method="POST">
            @csrf
            <div class="row gy-4">
              <input type="hidden" name="visit_id" value="{{ $feedback->visit_id ?? '' }}">
              <input type="hidden" name="cust_id" value="{{ $Customer->customer_id }}">
              <input type="hidden" name="branch_id" value="{{ $Customer->branch_id }}">

              <div class="col-lg-3 col-md-6">
                <label>Status</label><br>
                <div class="btn-group" role="group">
                  <input type="hidden" name="followup_status" id="followup_status" value="{{ $feedback->followup_status ?? '0' }}">
                  <button type="button" id="btnOpen" class="btn {{ ($feedback->followup_status ?? '') == '0' ? 'btn-success' : 'btn-outline-success' }}">Open</button>
                  <button type="button" id="btnClose" class="btn {{ ($feedback->followup_status ?? '') == '1' ? 'btn-danger' : 'btn-outline-danger' }}">Close</button>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Close Reason</label>
                <select name="close_reason_id" class="form-control" id="close_reason" {{ optional($feedback)->followup_status != '1' ? 'disabled' : '' }}>
                  <option value="">Select Reason</option>
                  @foreach($closereason as $cs)
                    <option value="{{ $cs->close_reason_id }}" {{ optional($feedback)->close_reason_id == $cs->close_reason_id ? 'selected' : '' }}>
                      {{$cs->close_reason}}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Remark</label>
                <input type="text" name="remark" class="form-control" value="{{ $feedback->remark ?? '' }}">
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Visit Date</label>
                <input type="date" name="visit_date" class="form-control" value="{{ old('visit_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Follow-up Date</label>
                <input type="date" name="next_followup_date" id="next_followup_date" class="form-control" value="{{ $feedback->next_followup_date ?? '' }}" {{ optional($feedback)->status == '1' ? 'disabled' : '' }}>
              </div>

              <div class="col-lg-3 col-md-6">
                <label for="followup_emp_id" class="form-label">Employee Name <span class="text-danger">*</span></label>
                <select class="form-control" name="emp_id" id="followup_emp_id" required>
                  <option value="">Select Employee</option>
                  @foreach ($employees as $emp)
                    <option value="{{ $emp->emp_id }}" {{ old('emp_id', $feedback->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
                      {{ $emp->emp_name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-lg-3 col-md-6 mt-5">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('customer.index') }}" class="btn btn-danger">Back</a>
              </div>

            </div>
          </form>
        </div>

      </div>
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
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('custProduct.changeStatus') }}">
        @csrf
        <input type="hidden" name="product_id" id="statusproduct_id">
        <div class="modal-body">
          <label><span class="text-danger">*</span> Status</label>
          <select class="form-control" name="status" id="Editreview_status">
            <option value="">Select Status</option>
            @foreach ($orderStatus as $status)
              <option value="{{ $status->order_status_id }}">{{ $status->status }}</option>
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>

    </div>
  </div>
</div>

{{-- Order Product Modal --}}
<div class="modal fade" id="orderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="orderForm" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Order Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
          <input type="hidden" name="product_id" id="orderProductId">
          <input type="hidden" name="cust_pro_id" id="ordercust_pro_id">

           <div class="row">
              <div class="card-header d-flex align-items-center">
                  <div class="col-md-3 mb-3">
                      <strong>Branch Name:</strong> <span id="orderbranch_name"></span>
                  </div>
                  <div class="col-md-6 mb-3">
                      <strong>Product Name:</strong> <span id="orderProduct"></span>
                  </div>
              </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Karat <span class="text-danger">*</span></label>
              <select name="karat" class="form-control">
                <option value="">Select Karat</option>
                @foreach ($purity as $prt)
                  <option value="{{ $prt->purity_id }}">{{ $prt->purity_value }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Color</label>
              <select class="form-control" name="color_id">
                <option value="">Select Color</option>
                @foreach ($color as $c)
                  <option value="{{ $c->color_id }}">{{ $c->color_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Weight <span class="text-danger">*</span></label>
              <input type="text" name="weight" class="form-control">
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Size</label>
              <input type="text" name="size" class="form-control">
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Reference Tag Number</label>
              <input type="text" name="refer_tag_number" id="orderrefno" class="form-control">
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Reference Image</label>
              <input type="hidden" name="existing_refer_image_url" id="existing_refer_image_url">
              <input type="file" name="refer_image_url" class="form-control" accept="image/*">
              <small><a href="#" id="existingRefImageLink" target="_blank" style="display:none;">View existing image</a></small>
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Amount <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="amount" class="form-control">
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Rate Type</label>
              <select class="form-control" name="rate_type">
                <option value="">Select Rate Type</option>
                <option value="Mk rate">Mk Rate</option>
                <option value="Z rate">Z Rate</option>
              </select>
            </div>


            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Rate Fix/Open</label>
              <select class="form-control" name="rate_fix_open">
                <option value="">Select Rate</option>
                <option value="Fix" {{ ($Customer->rate ?? '') == 'Fix' ? 'selected' : '' }}>Fix</option>
                <option value="Open" {{ ($Customer->rate ?? '') == 'Open' ? 'selected' : '' }}>Open</option>
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
                <label class="form-label">Remark</label>
                <textarea name="remark" class="form-control" maxlength="255"></textarea>
            </div>


            {{-- Vendor --}}
            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Order Given To</label>
              <select class="form-control" name="given_to" id="given_to">
                <option value="">Select Vendor</option>
                <option value="__add_vendor__">+ Add New Vendor</option>
                @foreach ($vendor as $v)
                  <option value="{{ $v->vendor_id }}">{{ $v->contact_person }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Delivery Status <span class="text-danger">*</span></label>
              <select class="form-control" name="delivery_status" id="delivery_status">
                <option value="">Select Delivery Status</option>
                @foreach ($orderStatus as $status)
                  <option value="{{ $status->order_status_id }}">{{ $status->status }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-2" id="notPurchasedReasonWrap" style="display:none;">
              <label class="form-label">Reason (Not Purchased)</label>
              <select class="form-control" name="not_purchased_reason_id" id="not_purchased_reason_id">
                <option value="">Select Reason</option>
                @foreach($notPurchasereason as $cs)
                  <option value="{{ $cs->close_reason_id }}">{{ $cs->close_reason }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-2">
              <label class="form-label">Delivery Date</label>
              <input type="date" name="delivery_date" class="form-control">
            </div>

          </div>
        </div>

        <div class="modal-footer mt-3">
          <button type="submit" class="btn btn-primary">Confirm Order</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Vendor Modal --}}
<div class="modal fade" id="vendorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa fa-user-plus"></i> Add New Vendor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="alert alert-success py-2" id="vm_success" style="display:none"></div>

        <div class="mb-2">
          <label>Vendor Name <span class="text-danger">*</span></label>
          <input type="text" id="vm_contact_person" class="form-control">
          <small class="text-danger" id="vm_err_contact"></small>
        </div>

        <div class="mb-2">
          <label>Company Name</label>
          <input type="text" id="vm_company_name" class="form-control">
        </div>

        <div class="mb-2">
          <label>Phone</label>
          <input type="text" id="vm_phone" class="form-control">
        </div>

        <div class="mb-2">
          <label>Alt Phone</label>
          <input type="text" id="vm_phone2" class="form-control">
        </div>

        <div class="mb-2">
          <label>Email</label>
          <input type="email" id="vm_email" class="form-control">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveVendorBtn">Save Vendor</button>
      </div>

    </div>
  </div>
</div>

@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
(function () {

  // Products list
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
        </div>`;
    });

    $('#productCheckboxList').html(html);
    updateProductDropText();
  }

  // prevent dropdown close
  $(document).on('click', '#productDropMenu', function(e){ e.stopPropagation(); });

  // ===== load list (3 tables)
  window.loadProductList = function () {
  $.ajax({
    url: "{{ route('EMPcustProduct.index', $id) }}",
    method: "GET",
    success: function (products) {

      let normalHtml = '';
      let purchasedHtml = '';
      let notPurchasedHtml = '';

      let n1 = 1, n2 = 1, n3 = 1;

      products.forEach((item) => {

        const statusText = (item.order_details?.order_status?.status ?? item.status ?? '-');
        const statusLower = String(statusText).trim().toLowerCase();
        const isOrderPlaced = !!item.order_details;

        const isPurchased = (isOrderPlaced && statusLower === 'purchased');
        const isNotPurchased = (isOrderPlaced && statusLower.includes('not purchased'));


        const amount = (item.order_details?.amount ?? 0);

        // ✅ reason name from close_reason table (via relation)
        const notPurchasedReasonText =
          item.order_details?.notPurchasedReason?.close_reason ?? '-';

        // action buttons
        let actionButtons = `
          <button class="btn btn-danger btn-sm deleteProduct" data-id="${item.cust_pro_id}">
            <i class="fa fa-trash"></i>
          </button>
        `;

        if (!isPurchased && !isNotPurchased) {

          if (isOrderPlaced) {
            actionButtons += `
              <button type="button" class="btn btn-success btn-sm editStatus"
                data-id="${item.cust_pro_id}" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="fa fa-edit"></i>
              </button>`;
          }

          actionButtons += `
            <button type="button" class="btn btn-success btn-sm orderProduct"
              data-id="${item.cust_pro_id}"
              data-name="${item.product?.product_name ?? ''}"
              data-product="${item.product_id}"
              data-branch="${item.branch_id}"
              data-refno="${item.product?.product_tag ?? ''}"
              data-branchname="${item.branch?.branch_name ?? ''}"
              data-bs-toggle="modal" data-bs-target="#orderModal">
              <i class="fa fa-shopping-cart" title="Order Product"></i>
            </button>`;
        }

        // Normal row (6 columns)
        const rowNormal = `
          <tr id="row-${item.cust_pro_id}">
            <td>__SR__</td>
            <td>${item.category?.category_name ?? '-'}</td>
            <td>${item.product?.product_name ?? '-'}</td>
            <td>${statusText}</td>
            <td>${item.employee?.emp_name ?? '-'}</td>
            <td>${actionButtons}</td>
          </tr>
        `;

        // Purchased row (7 columns)
        const rowPurchased = `
          <tr id="row-${item.cust_pro_id}">
            <td>__SR__</td>
            <td>${item.category?.category_name ?? '-'}</td>
            <td>${item.product?.product_name ?? '-'}</td>
            <td>${amount}</td>
            <td>${statusText}</td>
            <td>${item.employee?.emp_name ?? '-'}</td>
            <td>${actionButtons}</td>
          </tr>
        `;
        const reasonText = item.order_details?.not_purchased_reason?.close_reason ?? '-';
console.log(reasonText);
        // Not Purchased row (7 columns + reason)
        const rowNotPurchased = `
          <tr id="row-${item.cust_pro_id}">
            <td>__SR__</td>
            <td>${item.category?.category_name ?? '-'}</td>
            <td>${item.product?.product_name ?? '-'}</td>
            <td>${statusText}</td>
            <td>${item.employee?.emp_name ?? '-'}</td>
            <td>${reasonText}</td>
            <td>${actionButtons}</td>
          </tr>
        `;


        if (isPurchased) purchasedHtml += rowPurchased.replace('__SR__', n2++);
        else if (isNotPurchased) notPurchasedHtml += rowNotPurchased.replace('__SR__', n3++);
        else normalHtml += rowNormal.replace('__SR__', n1++);
      });

      $('#productTableBody').html(normalHtml || `<tr><td colspan="6" class="text-center text-muted">No products</td></tr>`);
      $('#purchasedTableBody').html(purchasedHtml || `<tr><td colspan="7" class="text-center text-muted">No purchased products</td></tr>`);
     $('#notPurchasedTableBody').html(
        notPurchasedHtml || `<tr><td colspan="7" class="text-center text-muted">No not-purchased products</td></tr>`
      );

    }
  });
};


  // ===== Vendor select2
  function initVendorSelect2() {
    if ($('#given_to').hasClass('select2-hidden-accessible')) {
      $('#given_to').select2('destroy');
    }
    $('#given_to').select2({
      width: '100%',
      dropdownParent: $('#orderModal'),
      placeholder: 'Select Vendor',
      allowClear: true,
      templateResult: function (data) {
        if (!data.id) return data.text;
        if (data.id === '__add_vendor__') {
          return $('<span style="font-weight:600;color:#0d6efd;"><i class="fa fa-plus"></i> Add New Vendor</span>');
        }
        return data.text;
      }
    });
  }

  // ===== ready
  $(document).ready(function () {
    renderProductsByCategory($('#category_id').val());
    loadProductList();

    $(document).on('change', '#category_id', function () {
      renderProductsByCategory($(this).val());
    });

    $(document).on('change', '#productSelectAll', function () {
      $('.product_cb').prop('checked', this.checked);
      updateProductDropText();
    });

    $(document).on('change', '.product_cb', function () {
      $('#productSelectAll').prop('checked', $('.product_cb').length === $('.product_cb:checked').length);
      updateProductDropText();
    });

    $('#addProductBtn').on('click', function (e) {
      e.preventDefault();
      $('.error-text').text('');

      const categoryId = $('#category_id').val();
      const empId = $('#emp_id').val();

      if (!categoryId) { $('#error-category_id').text('Please select category.'); return; }
      if (!empId) { $('#error-emp_id').text('Please select employee.'); return; }

      const productIds = $('.product_cb:checked').map(function(){ return $(this).val(); }).get();
      if (!productIds.length) { $('#error-product_id').text('Please select at least one product.'); return; }

      const baseData = {
        _token: '{{ csrf_token() }}',
        cust_id: $('#cust_id').val(),
        category_id: categoryId,
        visit_id: $('#visit_id').val(),
        emp_id: empId,
        visit_date: $('#visit_date').val()
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

  // delete
  $(document).on('click', '.deleteProduct', function (event) {
    event.preventDefault();
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this product?')) return;

    $.ajax({
      url: `../customer-product/delete/${id}`,
      method: 'POST',
      data: { _token: '{{ csrf_token() }}' },
      success: function (response) {
        if (response.success) loadProductList();
        else alert('Failed to delete the product.');
      },
      error: function () { alert('An error occurred while deleting the product.'); }
    });
  });

  // edit status
  $(document).on('click', '.editStatus', function () {
    $('#statusproduct_id').val($(this).data('id'));
  });

  // open order modal
  $(document).on('click', '.orderProduct', function () {
    const custProId = $(this).data('id');

    $('#ordercust_pro_id').val(custProId);
    $('#orderProductId').val($(this).data('product'));
    $('#orderProduct').text($(this).data('name') || '');
    $('#orderbranch_name').text($(this).data('branchname') || '');
    $('#orderrefno').val($(this).data('refno') || '');

    $('#orderForm')[0].reset();
    $('#notPurchasedReasonWrap').hide();
    $('#not_purchased_reason_id').val('');
    $('#existing_refer_image_url').val('');
    $('#existingRefImageLink').hide().attr('href', '#');

    initVendorSelect2();

    $.ajax({
      url: '/jewellery_crm/get-order-details/' + custProId,
      type: 'GET',
      success: function (response) {
        if (response.success && response.data) {
          const data = response.data;

          $('select[name="karat"]').val(data.karat);
          $('select[name="color_id"]').val(data.color_id);
          $('input[name="weight"]').val(data.weight);
          $('input[name="size"]').val(data.size);
          $('input[name="refer_tag_number"]').val(data.refer_tag_number);
          $('input[name="amount"]').val(data.amount);
          $('textarea[name="remark"]').val(data.remark);
          $('select[name="rate_type"]').val(data.rate_type);

          $('#given_to').val(data.given_to).trigger('change');
          $('#delivery_status').val(data.delivery_status);
          $('input[name="delivery_date"]').val(data.delivery_date);

          if (data.refer_image_url) {
            $('#existing_refer_image_url').val(data.refer_image_url);
            $('#existingRefImageLink').attr('href', data.refer_image_url).show();
          }

          const txt = ($('#delivery_status option:selected').text() || '').toLowerCase();
          if (txt.includes('not purchased')) $('#notPurchasedReasonWrap').show();
        }
      }
    });
  });

  // delivery status toggle
  $(document).on('change', '#delivery_status', function () {
    const txt = ($('#delivery_status option:selected').text() || '').toLowerCase();
    if (txt.includes('not purchased')) $('#notPurchasedReasonWrap').show();
    else { $('#notPurchasedReasonWrap').hide(); $('#not_purchased_reason_id').val(''); }
  });

  // select2 add vendor -> open modal
  $(document).on('select2:select', '#given_to', function (e) {
    if (e.params.data.id === '__add_vendor__') {
      $('#given_to').val('').trigger('change');
      $('#vm_err_contact').text('');
      $('#vm_success').hide().text('');
      $('#vm_contact_person,#vm_company_name,#vm_phone,#vm_phone2,#vm_email').val('');
      $('#vendorModal').modal('show');
    }
  });

  // save vendor
  $(document).on('click', '#saveVendorBtn', function () {
    $('#vm_err_contact').text('');
    $('#vm_success').hide().text('');

    const contact_person = ($('#vm_contact_person').val() || '').trim();
    const company_name = ($('#vm_company_name').val() || '').trim();
    const phone = ($('#vm_phone').val() || '').trim();
    const phone2 = ($('#vm_phone2').val() || '').trim();
    const email = ($('#vm_email').val() || '').trim();

    if (!contact_person) { $('#vm_err_contact').text('Vendor Name is required.'); return; }

    $.ajax({
      url: "{{ route('vendor.storeAjax') }}",
      type: "POST",
      data: { _token: "{{ csrf_token() }}", contact_person, company_name, phone, phone2, email },
      success: function (res) {
        if (!res || !res.success) { alert(res?.message || 'Failed to add vendor'); return; }

        const v = res.data;
        if ($('#given_to option[value="' + v.vendor_id + '"]').length === 0) {
          $('#given_to option[value="__add_vendor__"]').before(
            `<option value="${v.vendor_id}">${v.contact_person}</option>`
          );
        }

        initVendorSelect2();
        $('#given_to').val(v.vendor_id).trigger('change');
        $('#vendorModal').modal('hide');
      },
      error: function (xhr) {
        if (xhr.status === 422 && xhr.responseJSON?.errors) {
          const e = xhr.responseJSON.errors;
          if (e.contact_person) $('#vm_err_contact').text(e.contact_person[0]);
          else alert('Validation error.');
          return;
        }
        alert('Server error while saving vendor.');
      }
    });
  });

  // submit order
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
          loadProductList();
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function () { alert('An unexpected error occurred.'); }
    });
  });

})();
</script>

<script>
  // Followup toggle
  const statusField = document.getElementById('followup_status');
  const btnOpen = document.getElementById('btnOpen');
  const btnClose = document.getElementById('btnClose');
  const closeReason = document.getElementById('close_reason');
  const fDate = document.getElementById('next_followup_date');

  if (btnOpen && btnClose) {
    btnOpen.addEventListener('click', function () {
      statusField.value = '0';
      btnOpen.classList.add('btn-success');
      btnOpen.classList.remove('btn-outline-success');
      btnClose.classList.add('btn-outline-danger');
      btnClose.classList.remove('btn-danger');
      if (closeReason) closeReason.disabled = true;
      if (fDate) fDate.disabled = false;
    });

    btnClose.addEventListener('click', function () {
      statusField.value = '1';
      btnClose.classList.add('btn-danger');
      btnClose.classList.remove('btn-outline-danger');
      btnOpen.classList.add('btn-outline-success');
      btnOpen.classList.remove('btn-success');
      if (closeReason) closeReason.disabled = false;
      if (fDate) fDate.disabled = true;
    });

    if (statusField && statusField.value === '1') {
      if (closeReason) closeReason.disabled = false;
      if (fDate) fDate.disabled = true;
    } else {
      if (closeReason) closeReason.disabled = true;
      if (fDate) fDate.disabled = false;
    }
  }
</script>
@endsection
