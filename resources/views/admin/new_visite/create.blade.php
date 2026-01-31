@extends('layouts.app')
@section('title', 'Customer Detail')
@section('content')

<style>
  .tabs-wrap{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}
  .tab-link{
    text-decoration:none;border:1px solid #eee;background:#fff;padding:10px 14px;border-radius:12px;
    font-weight:600;color:#374151;display:flex;align-items:center;gap:10px;
    box-shadow:0 6px 18px rgba(0,0,0,.04);transition:.15s;
  }
  .tab-link:hover{transform:translateY(-1px)}
  .tab-link.active{background:#5c2323;border-color:#5c2323;color:#fff}
  .tab-ic{width:30px;height:30px;border-radius:10px;display:grid;place-items:center;background:#f3f4f6;color:#111827}
  .tab-link.active .tab-ic{background:rgba(255,255,255,.18);color:#fff}
  #notPurchasedReasonWrap{display:none;}
</style>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

@php
  $r = \Illuminate\Support\Facades\Route::currentRouteName();
  $cid = $Customer->customer_id ?? $id;
  $latest = $Customer->latestVisit ?? null;

  // Order Id for payment tab (optional)
  $orderId = null;
  if(isset($order) && !empty($order->order_id)){
    $orderId = $order->order_id;
  }
@endphp

<div class="tabs-wrap">
  <a class="tab-link {{ $r=='customer.history' ? 'active' : '' }}"
     href="{{ route('customer.history', $cid) }}">
    <span class="tab-ic"><i class="fa fa-eye"></i></span> History
  </a>

  @if($latest)
    @if(($latest->followup_status ?? 0) == 1)
      <a class="tab-link {{ $r=='newVisite.create' ? 'active' : '' }}"
         href="{{ route('newVisite.create', $cid) }}">
        <span class="tab-ic"><i class="fas fa-plus-circle"></i></span> New Visit
      </a>
    @endif
  @else
    <a class="tab-link {{ $r=='newVisite.create' ? 'active' : '' }}"
       href="{{ route('newVisite.create', $cid) }}">
      <span class="tab-ic"><i class="fas fa-plus-circle"></i></span> New Visit
    </a>
  @endif

  @if($latest)
    <a class="tab-link {{ $r=='newVisite.previous_visit' ? 'active' : '' }}"
       href="{{ route('newVisite.previous_visit', $cid) }}">
      <span class="tab-ic"><i class="fa fa-message"></i></span> Previous Visit
    </a>
  @endif

  <a class="tab-link {{ $r=='custOrder.index' ? 'active' : '' }}"
     href="{{ route('custOrder.index') }}">
    <span class="tab-ic"><i class="fa fa-shopping-bag"></i></span> Orders
  </a>

  @if($orderId)
    <a class="tab-link {{ $r=='orderPayment.index' ? 'active' : '' }}"
       href="{{ route('orderPayment.index', $orderId) }}">
      <span class="tab-ic"><i class="fa fa-credit-card"></i></span> Order Payment
    </a>
  @endif
</div>

@include('common.alert')

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="d-flex justify-content-between card-header">
        <h5 class="card-title text-uppercase fw-bold text-black mb-0">Customer Detail</h5>
      </div>

      <div class="card-body">

        {{-- Client Detail --}}
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
        <h5 class="card-title text-uppercase fw-bold text-black mb-2">Add customer view product</h5>

        <form id="regForm" method="POST" action="" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="cust_id" id="cust_id" value="{{ $id }}">
          <input type="hidden" name="visit_id" id="visit_id" value="{{ $feedback->visit_id ?? '' }}">
          <input type="hidden" name="visit_date" id="visit_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
          <input type="hidden" value="view" id="productstatus" name="status">

          <div class="row gy-4">

            <div class="col-lg-3 col-md-6">
              Category <span class="text-danger">*</span>
              <select class="form-control" name="category_id" id="category_id">
                <option value="">Select Category</option>
                @foreach($Category as $cat)
                  <option value="{{ $cat->category_id }}">{{ $cat->category_name }}</option>
                @endforeach
              </select>
              <span class="text-danger error-text" id="error-category_id"></span>
            </div>

            <div class="col-lg-3 col-md-6">
              Product <span class="text-danger">*</span>

              <div class="dropdown w-100">
                <button type="button"
                        class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                        id="productDropBtn"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        disabled>
                  Select category first
                </button>

                <div class="dropdown-menu w-100 p-2"
                     aria-labelledby="productDropBtn"
                     id="productDropdownMenu"
                     style="max-height:220px; overflow:auto;">
                  <div class="text-muted">Select category first</div>
                </div>
              </div>

              <span class="text-danger error-text" id="error-product_id"></span>
            </div>

            <div class="col-lg-3 col-md-6">
              Employee Name <span class="text-danger">*</span>
              <select class="form-control" name="emp_id" id="product_emp_id">
                <option value="">Select Employee</option>
                @foreach ($employees as $emp)
                  <option value="{{ $emp->emp_id }}">{{ $emp->emp_name }}</option>
                @endforeach
              </select>
              <span class="text-danger error-text" id="error-emp_id"></span>
            </div>

            <div class="col-lg-1 col-md-6">
              <button class="btn btn-primary mt-4" type="button" id="addProductBtn">Add</button>
            </div>

            {{-- 1) Normal Product List --}}
            <div class="mt-3">
              <h5 class="card-title text-uppercase fw-bold text-black mb-2">Product List</h5>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Employee</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="productTableBody"></tbody>
              </table>
            </div>

            {{-- 2) Purchased Product List (Amount + Total) --}}
            <div class="mt-3">
              <h5 class="card-title text-uppercase fw-bold text-black mb-2">Purchased Products</h5>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th class="text-end">Amount</th>
                    <th>Employee</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="purchasedTableBody"></tbody>
                <tfoot>
                  <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th class="text-end" id="purchasedTotal">0</th>
                    <th colspan="2"></th>
                  </tr>
                </tfoot>
              </table>
            </div>

            {{-- 3) Not Purchased Product List --}}
            <div class="mt-3">
              <h5 class="card-title text-uppercase fw-bold text-black mb-2">Not Purchased Products</h5>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Sr. No</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Employee</th>
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
          <h5 class="card-title text-uppercase fw-bold text-black mb-2">Next Followup</h5>

          <form action="{{ route('custFollowup.store') }}" method="POST">
            @csrf
            <div class="row gy-4">

              <input type="hidden" name="visit_id" value="{{ $feedback->visit_id ?? '' }}">
              <input type="hidden" name="cust_id" value="{{ $id }}">
              <input type="hidden" name="branch_id" value="{{ $Customer->branch_id }}">

              <div class="col-lg-3 col-md-6">
                <label>Status</label><br>
                <div class="btn-group" role="group">
                  <input type="hidden" name="followup_status" id="followup_status" value="{{ $feedback->followup_status ?? '0' }}">
                  <button type="button" id="btnOpen" class="btn btn-success">Open</button>
                  <button type="button" id="btnClose" class="btn btn-outline-danger">Close</button>
                </div>
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Close Reason</label>
                <select name="close_reason_id" class="form-control" id="close_reason">
                  <option value="">Select Reason</option>
                  @foreach($closereason as $cs)
                    <option value="{{ $cs->close_reason_id }}">{{ $cs->close_reason }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Remark</label>
                <input type="text" name="remark" class="form-control" value="{{ $feedback->remark ?? '' }}">
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Visit Date</label>
                <input type="date" name="visit_date" class="form-control"
                       value="{{ old('visit_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
              </div>

              <div class="col-lg-3 col-md-6">
                <label>Follow-up Date</label>
                <input type="date" name="next_followup_date" id="next_followup_date" class="form-control"
                       value="{{ $feedback->next_followup_date ?? '' }}">
              </div>

              <div class="col-lg-3 col-md-6">
                <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                <select class="form-control" name="emp_id" id="followup_emp_id" required>
                  <option value="">Select Employee</option>
                  @foreach ($employees as $emp)
                    <option value="{{ $emp->emp_id }}">{{ $emp->emp_name }}</option>
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
            @foreach($orderStatus as $s)
              <option value="{{ $s->order_status_id }}">{{ $s->status }}</option>
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

    <form id="orderForm" enctype="multipart/form-data">
      @csrf

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Order Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="max-height: 700px; overflow-y:auto;">
          <input type="hidden" name="product_id" id="orderProductId">
          <input type="hidden" name="cust_pro_id" id="ordercust_pro_id">
          <input type="hidden" name="branch_id" id="orderbranch_id">

          <div class="row">
            <div class="card-header d-flex align-items-center">
              <div class="col-md-3 mb-3"><strong>Branch:</strong> <span id="orderbranch_name"></span></div>
              <div class="col-md-5 mb-3"><strong>Product:</strong> <span id="orderProduct"></span></div>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Karat <span class="text-danger">*</span></label>
              <select name="karat" class="form-control" required>
                <option value="">Select Karat</option>
                @foreach ($purity as $prt)
                  <option value="{{ $prt->purity_id }}">{{ $prt->purity_value }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Color</label>
              <select class="form-control" name="color_id" id="color_id">
                <option value="">Select Color</option>
                @foreach ($color as $c)
                  <option value="{{ $c->color_id }}">{{ $c->color_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Weight</label>
              <input type="text" name="weight" class="form-control" maxlength="50" placeholder="Enter Weight">
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Size</label>
              <input type="text" name="size" class="form-control" maxlength="50" placeholder="Enter Size">
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Reference Tag Number</label>
              <input type="text" name="refer_tag_number" id="orderrefno" class="form-control" maxlength="50">
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Reference Image (Browse)</label>
              <input type="file" name="refer_image" id="refer_image" class="form-control" accept="image/*">
              <div class="mt-2">
                <img id="refer_image_preview" src="" style="display:none;width:80px;height:80px;object-fit:cover;" />
              </div>
              <input type="hidden" id="existing_refer_image_url" value="">
              <a id="existingRefImageLink" href="#" target="_blank" style="display:none;">View Existing</a>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Amount <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Rate Type</label>
              <select class="form-control" name="rate_type" id="rate_type">
                <option value="">Select Rate Type</option>
                <option value="Mk rate">Mk Rate</option>
                <option value="Z rate">Z Rate</option>
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Rate Fix/Open</label>
              <select class="form-control" name="rate_fix_open" id="rate_fix_open">
                <option value="">Select Rate</option>
                <option value="Fix" {{ ($Customer->rate ?? '') == 'Fix' ? 'selected' : '' }}>Fix</option>
                <option value="Open" {{ ($Customer->rate ?? '') == 'Open' ? 'selected' : '' }}>Open</option>
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Remark</label>
              <textarea name="remark" class="form-control" maxlength="255"></textarea>
            </div>

            {{-- ✅ Vendor dropdown (same pattern as file 2) --}}
            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Order Given To</label>
              <select class="form-control" name="given_to" id="given_to">
                <option value="">Select Vendor</option>
                <option value="__add_vendor__">+ Add New Vendor</option>
                @foreach ($vendor as $v)
                  <option value="{{ $v->vendor_id }}">{{ $v->contact_person }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Delivery Status <span class="text-danger">*</span></label>
              <select class="form-control" name="delivery_status" id="delivery_status" required>
                <option value="">Select Delivery Status</option>
                @foreach ($orderStatus as $status)
                  <option value="{{ $status->order_status_id }}">{{ $status->status }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
              <label class="form-label">Delivery Date</label>
              <input type="date" name="delivery_date" class="form-control">
            </div>

            <div class="col-lg-6 col-md-6 mt-3" id="notPurchasedReasonWrap">
              <label class="form-label">Reason (Not Purchased) <span class="text-danger">*</span></label>
              <select name="not_purchased_reason" class="form-control" id="not_purchased_reason">
                <option value="">Select Reason</option>
                @foreach($notPurchasereason as $cs)
                  <option value="{{ $cs->close_reason_id }}">{{ $cs->close_reason }}</option>
                @endforeach
              </select>
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

{{-- Vendor Modal (same as file 2) --}}
<div class="modal fade" id="vendorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Add Vendor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Vendor Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="vendor_name" placeholder="Enter vendor name">
          <small class="text-danger" id="vendor_name_err"></small>
        </div>

        <div class="mb-3">
          <label class="form-label">Mobile</label>
          <input type="text" class="form-control" id="vendor_mobile" placeholder="Enter mobile">
        </div>

        <div class="mb-3">
          <label class="form-label">Address</label>
          <textarea class="form-control" id="vendor_address" rows="2" placeholder="Enter address"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveVendorBtn" class="btn btn-primary">Save Vendor</button>
      </div>

    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  // ===== Category wise products map (from controller)
  const productsByCategory = @json($productsByCategory ?? []);

  function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
  }

  function updateProductDropText() {
    let count = $('.product_cb:checked').length;
    $('#productDropBtn').text(count ? (count + ' selected') : 'Select product(s)');
  }

  function renderProductsByCategory(catId) {
    const menu = $('#productDropdownMenu');
    menu.html('');
    updateProductDropText();

    if (!catId) {
      $('#productDropBtn').prop('disabled', true).text('Select category first');
      menu.html('<div class="text-muted">Select category first</div>');
      return;
    }

    const list = productsByCategory[catId] || [];
    $('#productDropBtn').prop('disabled', false).text('Select product(s)');

    if (!list.length) {
      menu.html('<div class="text-muted">No products in this category</div>');
      return;
    }

    let html = `
      <label class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="productSelectAll">
        <span class="form-check-label">Select All</span>
      </label>
      <hr class="my-2">
    `;

    list.forEach(p => {
      html += `
        <label class="form-check">
          <input class="form-check-input product_cb" type="checkbox" value="${p.product_id}">
          <span class="form-check-label">${escapeHtml(p.product_name)}</span>
        </label>
      `;
    });

    menu.html(html);
  }

  // ===== Vendor select2 (same as file 2)
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

  // ===== Not purchased reason toggle (based on selected text)
  function toggleNotPurchasedReason() {
    const txt = ($('#delivery_status option:selected').text() || '').toLowerCase();
    if (txt.includes('not purchased')) {
      $('#notPurchasedReasonWrap').show();
      $('#not_purchased_reason').prop('required', true);
    } else {
      $('#notPurchasedReasonWrap').hide();
      $('#not_purchased_reason').prop('required', false).val('');
    }
  }

  // ===== Load product list -> 3 tables
  function loadProductList() {
    $.ajax({
      url: "{{ route('newVisite.product', $id) }}",
      method: "GET",
      success: function (products) {

        let normalRows = '';
        let purchasedRows = '';
        let notPurchasedRows = '';

        let n1=1, n2=1, n3=1;
        let purchasedTotal = 0;

        if (!Array.isArray(products)) {
          $('#productTableBody').html('<tr><td colspan="6" class="text-center text-muted">No Data</td></tr>');
          $('#purchasedTableBody').html('<tr><td colspan="6" class="text-center text-muted">No Data</td></tr>');
          $('#notPurchasedTableBody').html('<tr><td colspan="6" class="text-center text-muted">No Data</td></tr>');
          $('#purchasedTotal').text('0');
          return;
        }

        products.forEach((item) => {

          const statusText =
            (item.order_details && item.order_details.order_status && item.order_details.order_status.status)
              ? item.order_details.order_status.status
              : (item.status ?? '-');

          const statusLower = String(statusText || '').toLowerCase();

          // classify:
          const isNotPurchased = statusLower.includes('not purchased');
          const isPurchased = (!isNotPurchased) && (item.order_details !== null);

          // amount (from order details)
          const amt = parseFloat(item.order_details?.amount ?? item.order_details?.iAmount ?? item.order_details?.total_amount ?? 0) || 0;

          // buttons
          let deleteBtn = `
            <button class="btn btn-danger btn-sm deleteProduct" data-id="${item.cust_pro_id}">
              <i class="fa fa-trash"></i>
            </button>
          `;

          let editBtn = '';
          if (item.order_details !== null) {
            editBtn = `
              <button type="button" class="btn btn-success btn-sm editStatus"
                data-id="${item.cust_pro_id}"
                data-bs-toggle="modal"
                data-bs-target="#editModal">
                <i class="fa fa-edit"></i>
              </button>
            `;
          }

          let orderBtn = `
            <button type="button" class="btn btn-success btn-sm orderProduct"
              data-id="${item.cust_pro_id}"
              data-name="${escapeHtml(item.product?.product_name ?? '')}"
              data-product="${item.product_id}"
              data-branch="${item.branch_id}"
              data-refno="${escapeHtml(item.product?.product_tag ?? '')}"
              data-branchname="${escapeHtml(item.branch?.branch_name ?? '')}"
              data-bs-toggle="modal"
              data-bs-target="#orderModal">
              <i class="fa fa-shopping-cart" title="Order Product"></i>
            </button>
          `;

          // ✅ Normal table actions: delete + edit(if ordered) + order
          // ✅ Purchased table actions: delete only (as you requested remove status + order icon)
          // ✅ NotPurchased table actions: delete + edit(if ordered) + order (optional) -> I kept order to retry purchase. If you want remove order here also, tell me.
          let actionNormal = deleteBtn + editBtn + orderBtn;
          let actionPurchased = deleteBtn; // only delete
          let actionNotPurchased = deleteBtn + editBtn + orderBtn;

          if (isPurchased) {
            purchasedTotal += amt;

            purchasedRows += `
              <tr id="row-${item.cust_pro_id}">
                <td>${n2++}</td>
                <td>${escapeHtml(item.category?.category_name ?? '-')}</td>
                <td>${escapeHtml(item.product?.product_name ?? '-')}</td>
                <td class="text-end">${amt.toFixed(2)}</td>
                <td>${escapeHtml(item.employee?.emp_name ?? '-')}</td>
                <td>${actionPurchased}</td>
              </tr>
            `;
            return;
          }

          if (isNotPurchased) {
            notPurchasedRows += `
              <tr id="row-${item.cust_pro_id}">
                <td>${n3++}</td>
                <td>${escapeHtml(item.category?.category_name ?? '-')}</td>
                <td>${escapeHtml(item.product?.product_name ?? '-')}</td>
                <td>${escapeHtml(statusText)}</td>
                <td>${escapeHtml(item.employee?.emp_name ?? '-')}</td>
                <td>${actionNotPurchased}</td>
              </tr>
            `;
            return;
          }

          // normal
          normalRows += `
            <tr id="row-${item.cust_pro_id}">
              <td>${n1++}</td>
              <td>${escapeHtml(item.category?.category_name ?? '-')}</td>
              <td>${escapeHtml(item.product?.product_name ?? '-')}</td>
              <td>${escapeHtml(statusText)}</td>
              <td>${escapeHtml(item.employee?.emp_name ?? '-')}</td>
              <td>${actionNormal}</td>
            </tr>
          `;
        });

        $('#productTableBody').html(normalRows || '<tr><td colspan="6" class="text-center text-muted">No products</td></tr>');
        $('#purchasedTableBody').html(purchasedRows || '<tr><td colspan="6" class="text-center text-muted">No purchased products</td></tr>');
        $('#notPurchasedTableBody').html(notPurchasedRows || '<tr><td colspan="6" class="text-center text-muted">No not-purchased products</td></tr>');
        $('#purchasedTotal').text(purchasedTotal.toFixed(2));
      }
    });
  }

  // ===== Add selected products
  function addSelectedProducts() {
    $('.error-text').text('');

    const categoryId = $('#category_id').val();
    const empId = $('#product_emp_id').val();

    if (!categoryId) { $('#error-category_id').text('Please select category.'); return; }
    if (!empId) { $('#error-emp_id').text('Please select employee.'); return; }

    const productIds = $('.product_cb:checked').map(function(){ return $(this).val(); }).get();
    if (!productIds.length) { $('#error-product_id').text('Please select at least one product.'); return; }

    $('#addProductBtn').prop('disabled', true);

    const baseData = {
      _token: '{{ csrf_token() }}',
      cust_id: $('#cust_id').val(),
      category_id: categoryId,
      visit_id: $('#visit_id').val(),
      emp_id: empId,
      visit_date: $('#visit_date').val(),
      status: $('#productstatus').val()
    };

    // sequential calls (safer)
    function send(i){
      if(i >= productIds.length){
        loadProductList();
        $('#addProductBtn').prop('disabled', false);
        $('#productSelectAll').prop('checked', false);
        $('.product_cb').prop('checked', false);
        updateProductDropText();
        return;
      }
      $.ajax({
        url: "{{ route('custProduct.store') }}",
        method: "POST",
        data: Object.assign({}, baseData, { product_id: productIds[i] }),
        complete: function(){ send(i+1); }
      });
    }
    send(0);
  }

  // ===== Document ready
  $(document).ready(function () {
    loadProductList();

    // category change
    $(document).on('change', '#category_id', function(){
      renderProductsByCategory($(this).val());
    });

    // select all
    $(document).on('change', '#productSelectAll', function(){
      $('.product_cb').prop('checked', this.checked);
      updateProductDropText();
    });

    // checkbox count
    $(document).on('change', '.product_cb', function(){
      $('#productSelectAll').prop('checked', $('.product_cb').length === $('.product_cb:checked').length);
      updateProductDropText();
    });

    // add products
    $(document).on('click', '#addProductBtn', addSelectedProducts);

    // order status change => toggle reason
    $(document).on('change', '#delivery_status', toggleNotPurchasedReason);

    // preview refer image
    $(document).on('change', '#refer_image', function(){
      const file = this.files && this.files[0];
      if (!file) return;
      const url = URL.createObjectURL(file);
      $('#refer_image_preview').attr('src', url).show();
    });
  });

  // ===== Delete product
  $(document).on('click', '.deleteProduct', function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this product?')) return;

    $.ajax({
      url: `/admin/customer-product/delete/${id}`,
      method: 'POST',
      data: { _token: '{{ csrf_token() }}' },
      success: function (response) {
        if (response.success) $(`#row-${id}`).remove();
        else alert('Failed to delete the product.');
      },
      error: function () { alert('An error occurred while deleting the product.'); }
    });
  });

  // ===== Edit status modal set id
  $(document).on('click', '.editStatus', function () {
    $('#statusproduct_id').val($(this).data('id'));
  });

  // ===== Order modal open + load existing details
  $(document).on('click', '.orderProduct', function () {

    const custProId = $(this).data('id');
    const productId = $(this).data('product');
    const branchId = $(this).data('branch');
    const productName = $(this).data('name');
    const branchName = $(this).data('branchname');
    const refNo = $(this).data('refno');

    $('#orderForm')[0].reset();
    $('#refer_image_preview').hide().attr('src','');
    $('#notPurchasedReasonWrap').hide();
    $('#not_purchased_reason').val('');

    $('#ordercust_pro_id').val(custProId);
    $('#orderProductId').val(productId);
    $('#orderbranch_id').val(branchId);
    $('#orderProduct').text(productName || '');
    $('#orderbranch_name').text(branchName || '');
    $('#orderrefno').val(refNo || '');

    initVendorSelect2();

    $.ajax({
      url: '/get-order-details/' + custProId,
      type: 'GET',
      success: function (response) {
        if (response && response.success) {
          const data = response.data || {};

          $('select[name="karat"]').val(data.karat);
          $('select[name="color_id"]').val(data.color_id);
          $('input[name="weight"]').val(data.weight);
          $('input[name="size"]').val(data.size);
          $('input[name="refer_tag_number"]').val(data.refer_tag_number);
          $('input[name="amount"]').val(data.amount);
          $('select[name="rate_fix_open"]').val(data.rate_fix_open);
          $('textarea[name="remark"]').val(data.remark);
          $('select[name="rate_type"]').val(data.rate_type);
          $('select[name="given_to"]').val(data.given_to).trigger('change');
          $('select[name="delivery_status"]').val(data.delivery_status);
          $('input[name="delivery_date"]').val(data.delivery_date);

          // existing image link
          if (data.refer_image_url) {
            $('#existing_refer_image_url').val(data.refer_image_url);
            $('#existingRefImageLink').show().attr('href', data.refer_image_url);
          }

          toggleNotPurchasedReason();
        }
      }
    });
  });

  // ===== Vendor dropdown: open modal on Add New Vendor
  $(document).on('change', '#given_to', function(){
    if ($(this).val() === '__add_vendor__') {
      $(this).val('').trigger('change');
      $('#vendor_name, #vendor_mobile, #vendor_address').val('');
      $('#vendor_name_err').text('');
      $('#vendorModal').modal('show');
    }
  });

  // ===== Save Vendor (same as file 2 - storeAjax)
  $(document).on('click', '#saveVendorBtn', function () {
    $('#vendor_name_err').text('');

    const name = ($('#vendor_name').val() || '').trim();
    const mobile = ($('#vendor_mobile').val() || '').trim();
    const address = ($('#vendor_address').val() || '').trim();

    if (!name) {
      $('#vendor_name_err').text('Vendor name is required.');
      return;
    }

    $.ajax({
      url: "{{ route('vendor.storeAjax') }}",
      method: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        contact_person: name,
        mobile_number: mobile,
        address: address
      },
      success: function (res) {
        if (res && res.success && res.vendor) {
          const v = res.vendor;

          // add option + select
          const opt = new Option(v.contact_person, v.vendor_id, true, true);
          $('#given_to').append(opt).trigger('change');

          $('#vendorModal').modal('hide');
        } else {
          alert(res.message || 'Failed to create vendor.');
        }
      },
      error: function (xhr) {
        alert('Failed to create vendor.');
      }
    });
  });

  // ===== Submit order (FormData)
  $('#orderForm').on('submit', function (e) {
    e.preventDefault();

    toggleNotPurchasedReason();

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
          alert('Error: ' + (response.message ?? 'Something went wrong'));
        }
      },
      error: function () {
        alert('An unexpected error occurred.');
      }
    });
  });

  // ===== Followup open/close buttons
  (function(){
    const statusField = document.getElementById('followup_status');
    const btnOpen = document.getElementById('btnOpen');
    const btnClose = document.getElementById('btnClose');
    const closeReason = document.getElementById('close_reason');
    const fDate = document.getElementById('next_followup_date');

    if(!statusField || !btnOpen || !btnClose) return;

    btnOpen.addEventListener('click', function () {
      statusField.value = '0';
      btnOpen.classList.add('btn-success');
      btnClose.classList.add('btn-outline-danger');
      closeReason.disabled = true;
      fDate.disabled = false;
    });

    btnClose.addEventListener('click', function () {
      statusField.value = '1';
      btnClose.classList.add('btn-danger');
      btnOpen.classList.add('btn-outline-success');
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
  })();
</script>
@endsection
