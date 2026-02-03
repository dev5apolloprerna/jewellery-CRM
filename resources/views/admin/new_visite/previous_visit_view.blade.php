@extends('layouts.app')
@section('title', 'Customer Previous Visite')
@section('content')

<style>
.tabs-wrap{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-bottom:15px
}
.tab-link{
    text-decoration:none;
    border:1px solid #e5e7eb;
    background:#fff;
    padding:10px 14px;
    border-radius:12px;
    font-weight:600;
    color:#374151;
    display:flex;
    align-items:center;
    gap:10px;
    box-shadow:0 6px 18px rgba(0,0,0,.04);
    transition:.15s;
}
.tab-link:hover{
    transform:translateY(-1px)
}
.tab-link.active{
    background:#5c2323;
    border-color:#5c2323;
    color:#fff;
}
.tab-ic{
    width:30px;
    height:30px;
    border-radius:10px;
    display:grid;
    place-items:center;
    background:#f3f4f6;
    color:#111827;
}
.tab-link.active .tab-ic{
    background:rgba(255,255,255,.2);
    color:#fff
}

    #notPurchasedReasonWrap{display:none;}
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">


@php
    $route = \Illuminate\Support\Facades\Route::currentRouteName();
    $cid   = $Customer->customer_id ?? $id;
@endphp



<div class="tabs-wrap">

    {{-- History --}}
    <a href="{{ route('EMPcustomer.history', $cid) }}"
       class="tab-link {{ $route=='EMPcustomer.history' ? 'active' : '' }}">
        <span class="tab-ic"><i class="fa fa-eye"></i></span>
        History
    </a>

    {{-- New Visit --}}
    @if(($feedback->followup_status ?? 0) == 1)
        <a href="{{ route('newVisite.create', $cid) }}"
           class="tab-link {{ $route=='newVisite.create' ? 'active' : '' }}">
            <span class="tab-ic"><i class="fa fa-plus-circle"></i></span>
            New Visit
        </a>
    @endif


    {{-- Previous Visit (ACTIVE HERE) --}}
    <a href="{{ route('newVisite.previous_visit_view', $cid) }}"
       class="tab-link {{ $route=='newVisite.previous_visit_view' ? 'active' : '' }}">
        <span class="tab-ic"><i class="fa fa-history"></i></span>
        Previous Visit
    </a>

    {{-- Orders --}}
    <a href="{{ route('EMPcustOrder.index') }}"
       class="tab-link {{ $route=='EMPcustOrder.index' ? 'active' : '' }}">
        <span class="tab-ic"><i class="fa fa-shopping-bag"></i></span>
        Orders
    </a>
    {{-- Payment (only if order exists) --}}
    @if($orderId)
        <a href="{{ route('orderPayment.index', $orderId) }}"
           class="tab-link {{ $route=='EMPorderPayment.index' ? 'active' : '' }}">
            <span class="tab-ic"><i class="fa fa-credit-card"></i></span>
            Payment
        </a>
    @endif

</div>

            {{-- Alert Messages --}}
            @include('common.alert')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="d-flex justify-content-between card-header">
                            <h5 class="card-title text-uppercase fw-bold text-black mb-0">Customer Previous Visite</h5>
                            <div class="page-title-right">
                                @if(($feedback->followup_status ?? 0) == 1)
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

                            <hr>

                            <h5 class="card-title text-uppercase fw-bold text-black mb-2">Add customer view product</h5>

                            <form id="regForm" method="POST" action="javascript:void(0);" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="visit_id" id="visit_id" value="{{ $feedback->visit_id ?? '' }}">
                                <input type="hidden" name="cust_id" id="cust_id" value="{{ $Customer->customer_id }}">
                                <input type="hidden" id="visit_date" value="{{ $feedback->visit_date ?? \Carbon\Carbon::now()->format('Y-m-d') }}">
                                <input type="hidden" value="view" id="productstatus" name="status">

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
                                            <button type="button"
                                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                    id="productDropBtn"
                                                    data-bs-toggle="dropdown"
                                                    data-bs-auto-close="outside"
                                                    aria-expanded="false"
                                                    disabled>
                                                Select category first
                                            </button>

                                            <div class="dropdown-menu w-100 p-2" aria-labelledby="productDropBtn"
                                                 id="productDropdownMenu"
                                                 style="max-height:250px; overflow:auto;">
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

                                    <div class="col-lg-1 col-md-6 mt-4">
                                        <button class="btn btn-primary" type="button" id="addProductBtn">Add</button>
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

                                    {{-- 2) Purchased Product List (no status + no order icon) --}}
                                    <div class="mt-3">
                                        <h5 class="card-title text-uppercase fw-bold text-black mb-2">Purchased Product List</h5>
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
                                            <tbody id="purchasedProductTableBody"></tbody>
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
                                        <h5 class="card-title text-uppercase fw-bold text-black mb-2">Not Purchased Product List</h5>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Category</th>
                                                <th>Product</th>
                                                <th>Status</th>
                                                <th>Employee</th>
                                                <th>Reason</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="notPurchasedProductTableBody"></tbody>
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
                                        <input type="hidden" name="cust_id" value="{{ $Customer->customer_id }}">
                                        <input type="hidden" name="branch_id" value="{{ $Customer->branch_id }}">

                                        <div class="col-lg-3 col-md-6">
                                            <label>Status</label><br>
                                            <div class="btn-group" role="group">
                                                <input type="hidden" name="followup_status" id="followup_status" value="{{ $feedback->followup_status ?? '0' }}">

                                                <button type="button" id="btnOpen"
                                                        class="btn {{ ($feedback->followup_status ?? '0') == '0' ? 'btn-success' : 'btn-outline-success' }}">
                                                    Open
                                                </button>

                                                <button type="button" id="btnClose"
                                                        class="btn {{ ($feedback->followup_status ?? '0') == '1' ? 'btn-danger' : 'btn-outline-danger' }}">
                                                    Close
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Close Reason</label>
                                            <select name="close_reason_id" class="form-control" id="close_reason"
                                                    {{ ($feedback->followup_status ?? '0') != '1' ? 'disabled' : '' }}>
                                                <option value="">Select Reason</option>
                                                @foreach($closereason as $cs)
                                                    <option value="{{ $cs->close_reason_id }}"
                                                        {{ ($feedback->close_reason_id ?? '') == $cs->close_reason_id ? 'selected' : '' }}>
                                                        {{ $cs->close_reason }}
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
                                            <input type="date" name="visit_date" class="form-control"
                                                   value="{{ old('visit_date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Follow-up Date</label>
                                            <input type="date" name="next_followup_date" id="next_followup_date" class="form-control"
                                                   value="{{ $feedback->next_followup_date ?? '' }}"
                                                   {{ ($feedback->followup_status ?? '0') == '1' ? 'disabled' : '' }}>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label class="form-label">Employee Name <span class="text-danger">*</span></label>
                                            <select class="form-control" name="emp_id" id="followup_emp_id" required>
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $emp)
                                                    <option value="{{ $emp->emp_id }}"
                                                        {{ old('emp_id', $feedback->emp_id ?? '') == $emp->emp_id ? 'selected' : '' }}>
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
                                            <td>{{ $followup->employee->emp_name ?? '-' }}</td>
                                            <td>{{ $followup->followup_status == 0 ? 'Open' : 'Close' }}</td>
                                            <td>{{ $followup->closereason->close_reason ?? '-' }}</td>
                                            <td>{{ $followup->remark ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="7" class="text-center">No Data Found</td></tr>
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
                            <div class="col-md-3 mb-3">
                                <strong>Branch Name:</strong> <span id="orderbranch_name"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Product Name:</strong> <span id="orderProduct"></span>
                            </div>
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
                            <select class="form-control" name="color_id">
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
                            <label class="form-label">Reference Image</label>
                            <input type="file" class="form-control" name="refer_image" id="refer_image" accept="image/*">
                            <div class="mt-2">
                                <img id="refer_image_preview" src="" style="display:none;width:80px;height:80px;object-fit:cover;" />
                            </div>
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

                        {{-- ✅ Vendor dropdown with + Add New Vendor (no vendor.store) --}}
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

                         <div class="col-lg-4 col-md-6 mt-2" id="notPurchasedReasonWrap" style="display:none;">
                              <label class="form-label">Reason (Not Purchased)</label>
                              <select class="form-control" name="not_purchased_reason_id" id="not_purchased_reason_id">
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

{{-- Vendor Modal --}}
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // ✅ Category wise products map
    const productsByCategory = @json($productsByCategory ?? []);

    function escapeHtml(str){
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

   function toggleNotPurchasedReason() {
        const txt = ($('#delivery_status option:selected').text() || '').toLowerCase();

        if (txt.includes('not purchased')) {
            $('#notPurchasedReasonWrap').show();
            $('#not_purchased_reason_id').prop('required', true);
        } else {
            $('#notPurchasedReasonWrap').hide();
            $('#not_purchased_reason_id')
                .prop('required', false)
                .val('');
        }
    }

    // ✅ ONE loadProductList with 3 tables + total
    function loadProductList() {
        $.ajax({
            url: "../customer-product/{{ $id }}",
            method: "GET",
            success: function (products) {

                let normalHtml = '';
                let purchasedHtml = '';
                let notPurchasedHtml = '';
                let n1 = 1, n2 = 1, n3 = 1;
                let purchasedTotal = 0;

                (products || []).forEach((item) => {
                    const statusText = (item.order_details?.order_status?.status ?? item.status ?? '-');
                    const statusLower = String(statusText).toLowerCase();

                    const isNotPurchased = statusLower.includes('not purchased');
                    const isPurchased = (!isNotPurchased) && (item.order_details !== null);

                    const amt = parseFloat(item.order_details?.amount ?? item.order_details?.iAmount ?? item.order_details?.total_amount ?? 0) || 0;
                    if (isPurchased) purchasedTotal += amt;

                    // buttons
                    const deleteBtn = `
                        <button class="btn btn-danger btn-sm deleteProduct" data-id="${item.cust_pro_id}">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;

                    let editBtn = '';
                    if (item.order_details !== null) {
                        editBtn = `
                            <button type="button" class="btn btn-success btn-sm editStatus"
                                data-id="${item.cust_pro_id}" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa fa-edit"></i>
                            </button>
                        `;
                    }

                    const orderBtn = `
                        <button type="button" class="btn btn-success btn-sm orderProduct"
                            data-id="${item.cust_pro_id}"
                            data-name="${escapeHtml(item.product?.product_name ?? '')}"
                            data-product="${item.product_id}"
                            data-branch="${item.branch_id}"
                            data-refno="${escapeHtml(item.product?.product_tag ?? '')}"
                            data-branchname="${escapeHtml(item.branch?.branch_name ?? '')}"
                            data-bs-toggle="modal" data-bs-target="#orderModal">
                            <i class="fa fa-shopping-cart" title="Order Product"></i>
                        </button>
                    `;

                    // ✅ Purchased: only delete (no status + no order)
                    const actionPurchased = deleteBtn;

                    // ✅ Normal: delete + edit(if ordered) + order
                    const actionNormal = deleteBtn + editBtn + orderBtn;

                    // ✅ Not Purchased: delete + edit + order (if you want remove order here also, tell me)
                    const actionNotPurchased = deleteBtn + editBtn + orderBtn;

                    if (isPurchased) {
                        purchasedHtml += `
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
                    const reason = item.order_details?.not_purchased_reason_text ?? '-';

console.log(item.order_details);
                        notPurchasedHtml += `
                            <tr id="row-${item.cust_pro_id}">
                                <td>${n3++}</td>
                                <td>${escapeHtml(item.category?.category_name ?? '-')}</td>
                                <td>${escapeHtml(item.product?.product_name ?? '-')}</td>
                                <td>${escapeHtml(statusText)}</td>
                                <td>${escapeHtml(item.employee?.emp_name ?? '-')}</td>
                                        <td>${escapeHtml(reason)}</td>

                                <td>${actionNotPurchased}</td>
                            </tr>
                        `;
                        return;
                    }


                    normalHtml += `
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

                $('#productTableBody').html(normalHtml || `<tr><td colspan="6" class="text-center text-muted">No products</td></tr>`);
                $('#purchasedProductTableBody').html(purchasedHtml || `<tr><td colspan="6" class="text-center text-muted">No purchased products</td></tr>`);
                $('#notPurchasedProductTableBody').html(notPurchasedHtml || `<tr><td colspan="6" class="text-center text-muted">No not-purchased products</td></tr>`);
                $('#purchasedTotal').text(purchasedTotal.toFixed(2));
            }
        });
    }

    // ✅ Add selected products
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

        function send(i){
            if(i >= productIds.length){
                $('#addProductBtn').prop('disabled', false);
                loadProductList();
                $('.product_cb').prop('checked', false);
                $('#productSelectAll').prop('checked', false);
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

    // vendor select2 (only if select2 present)
    function initVendorSelect2() {
        if (!$.fn.select2) return;
        if ($('#given_to').hasClass('select2-hidden-accessible')) {
            $('#given_to').select2('destroy');
        }
        $('#given_to').select2({
            width: '100%',
            dropdownParent: $('#orderModal'),
            placeholder: 'Select Vendor',
            allowClear: true
        });
    }

    $(document).ready(function () {
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

        $(document).on('click', '#addProductBtn', addSelectedProducts);

        $(document).on('change', '#delivery_status', toggleNotPurchasedReason);

        $(document).on('change', '#refer_image', function () {
            const file = this.files && this.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            $('#refer_image_preview').attr('src', url).show();
        });
    });

    // delete
    $(document).on('click', '.deleteProduct', function (event) {
        event.preventDefault();
        let id = $(this).data('id');
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
            error: function () { alert('An error occurred while deleting the product.'); }
        });
    });

    // edit
    $(document).on('click', '.editStatus', function () {
        $('#statusproduct_id').val($(this).data('id'));
    });

    // open order modal + fill
    $(document).on('click', '.orderProduct', function () {

        const custProId = $(this).data('id');
        const productId = $(this).data('product');
        const branchId = $(this).data('branch');
        const productName = $(this).data('name');
        const branchName = $(this).data('branchname');
        const refNo = $(this).data('refno');

        $('#orderForm')[0].reset();
        $('#refer_image_preview').hide().attr('src','');
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
                    let data = response.data || {};

                    $('select[name="karat"]').val(data.karat);
                    $('select[name="color_id"]').val(data.color_id);
                    $('input[name="weight"]').val(data.weight);
                    $('input[name="size"]').val(data.size);
                    $('input[name="refer_tag_number"]').val(data.refer_tag_number);
                    $('input[name="amount"]').val(data.amount);
                    $('textarea[name="remark"]').val(data.remark);
                    $('select[name="rate_type"]').val(data.rate_type);
                    $('select[name="rate_fix_open"]').val(data.rate_fix_open);
                    $('select[name="given_to"]').val(data.given_to).trigger('change');
                    $('select[name="delivery_status"]').val(data.delivery_status);
                    $('select[name="not_purchased_reason_id"]').val(data.not_purchased_reason);
                    $('input[name="delivery_date"]').val(data.delivery_date);

                    toggleNotPurchasedReason();
                }
            }
        });
    });

    // vendor dropdown: open modal
    $(document).on('change', '#given_to', function(){
        if ($(this).val() === '__add_vendor__') {
            $(this).val('').trigger('change');
            $('#vendor_name, #vendor_mobile, #vendor_address').val('');
            $('#vendor_name_err').text('');
            $('#vendorModal').modal('show');
        }
    });

    // save vendor
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
                    const opt = new Option(v.contact_person, v.vendor_id, true, true);
                    $('#given_to').append(opt).trigger('change');
                    $('#vendorModal').modal('hide');
                } else {
                    alert(res.message || 'Failed to create vendor.');
                }
            },
            error: function () { alert('Failed to create vendor.'); }
        });
    });

    // submit order
    $('#orderForm').on('submit', function (e) {
        e.preventDefault();
        toggleNotPurchasedReason();

        if (!confirm('Are you sure you want to order this product?')) return;

        let fd = new FormData(this);

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
                    alert('Error: ' + (response.message || 'Something went wrong'));
                }
            },
            error: function () { alert('An unexpected error occurred.'); }
        });
    });

    // followup buttons
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
    })();
</script>
@endsection
