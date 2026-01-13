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

                            {{-- Client Detail List --}}
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
                                {{-- keep these hidden because your JS sends them --}}
                                <input type="hidden" name="visit_id" id="visit_id" value="{{ $feedback->visit_id ?? '' }}">
                                <input type="hidden" name="visit_date" id="visit_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

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

                                            {{-- Dropdown with checkbox --}}
                                            <div class="dropdown w-100">
                                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start"
                                                        type="button"
                                                        id="productDropBtn"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                    Select product(s)
                                                </button>

                                                <div class="dropdown-menu p-2 w-100"
                                                     aria-labelledby="productDropBtn"
                                                     style="max-height:260px; overflow:auto;">

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="productSelectAll">
                                                        <label class="form-check-label" for="productSelectAll">Select All</label>
                                                    </div>

                                                    <div class="dropdown-divider"></div>

                                                    <div id="productCheckboxList" class="px-1">
                                                        <div class="text-muted small">Select category first.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="text-danger error-text" id="error-product_id"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-3">
                                        Employee Name <span style="color:red;">*</span>
                                        <select class="form-control" name="emp_id" id="product_emp_id">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->emp_id }}">
                                                    {{ $emp->emp_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text" id="error-emp_id"></span>
                                    </div>

                                    <input type="hidden" value="view" id="productstatus" name="status">

                                    <div class="col-lg-1 col-md-6">
                                        <div>
                                            <button class="btn btn-primary btn-user float-right mt-4 mx-2" type="button" id="addProductBtn">
                                                Add
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Normal list --}}
                                    <div class="mt-3">
                                        <h5 class="card-title text-uppercase fw-bold text-black mb-2">Product List</h5>
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

                                    {{-- Purchased list --}}
                                    <div class="mt-3">
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
                                            <tbody id="purchasedTableBody"></tbody>
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
                                        <input type="hidden" class="form-control" name="visit_id" value="{{ $feedback->visit_id ?? '' }}" readonly>
                                        <input type="hidden" class="form-control" name="cust_id" value="{{ $id }}" readonly>
                                        <input type="hidden" class="form-control" name="branch_id" value="{{ $Customer->branch_id }}" readonly>

                                        <div class="col-lg-3 col-md-6">
                                            <label>Status</label><br>
                                            <div class="btn-group" role="group" aria-label="Status">
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
                                                    <option value="{{ $cs->close_reason_id }}">{{$cs->close_reason}}</option>
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
                                            <input type="date" name="next_followup_date" id="next_followup_date" class="form-control" value="{{ $feedback->next_followup_date ?? '' }}">
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label for="emp_name" class="form-label">Employee Name <span style="color:red;">*</span></label>
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


{{-- Order Product Modal --}}
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <form id="orderForm" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="max-height: 700px; overflow-y: auto;">

                    <input type="hidden" name="product_id" id="orderProductId">
                    <input type="hidden" name="cust_pro_id" id="ordercust_pro_id" class="form-control" readonly>
                    <input type="hidden" name="branch_id" id="orderbranch_id">

                    <div class="row">
                        <div class="card-header d-flex align-items-center">
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
                            <label class="form-label">Karat <span style="color:red;">*</span></label>
                            <select name="karat" class="form-control">
                                <option value="">Select Karat</option>
                                @foreach ($purity as $prt)
                                    <option value="{{ $prt->purity_id  }}">{{ $prt->purity_value }}</option>
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
                            <label class="form-label">Weight </label>
                            <input type="text" name="weight" class="form-control" maxlength="50" placeholder="Enter Weight" >
                        </div>

                        <div class="col-lg-4 col-md-6 mt-3">
                            <label class="form-label">Size</label>
                            <input type="text" name="size" class="form-control" maxlength="50" placeholder="Enter Size">
                        </div>

                        <div class="col-lg-4 col-md-6 mt-3">
                            <label class="form-label">Reference Tag Number</label>
                            <input type="text" name="refer_tag_number" id="orderrefno" class="form-control" maxlength="50" placeholder="Enter Reference Tag Number">
                        </div>

                        {{-- ✅ browse option --}}
                        <div class="col-lg-4 col-md-6 mt-3">
                            <label class="form-label">Reference Image (Browse)</label>
                            <input type="file" name="refer_image_url" id="refer_image_url" class="form-control" accept="image/*">
                            <small class="text-muted" id="existingReferImage"></small>
                        </div>

                        <div class="col-lg-4 col-md-6 mt-3">
                            <label class="form-label">Amount <span style="color:red;">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control" maxlength="50" placeholder="Enter Amount" required>
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
                            <input type="text" name="rate_fix_open" class="form-control" maxlength="50" placeholder="Enter Rate Fix / Open">
                        </div>

                        <div class="col-lg-4 col-md-6 mt-3">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control" maxlength="255" placeholder="Enter Remark"></textarea>
                        </div>

                        <div class="col-lg-4 col-md-6 mt-3">
                            <label class="form-label">Order Given To</label>
                            <select class="form-control" name="given_to" id="given_to">
                                <option value="">Select Vendor</option>
                                @foreach ($vendor as $emp)
                                    <option value="{{ $emp->vendor_id }}">{{ $emp->contact_person }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4 col-md-6 mt-3">
                            <label class="form-label">Delivery Status <span style="color:red;">*</span></label>
                            <select class="form-control" name="delivery_status" id="delivery_status">
                                <option value="">Select Delivery Status</option>
                                @foreach ($orderStatus as $status)
                                    <option value="{{ $status->order_status_id }}">{{ $status->status }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ✅ not purchased reason --}}
                        <div class="col-lg-4 col-md-6 mt-3 d-none" id="notPurchasedReasonWrap">
                            <label class="form-label">Reason (Not Purchased) <span style="color:red;">*</span></label>
                            <textarea name="not_purchased_reason" id="not_purchased_reason" class="form-control" maxlength="255" placeholder="Enter reason"></textarea>
                        </div>

                        <div class="col-lg-4 col-md-6 mt-3">
                            <label class="form-label">Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control" maxlength="50">
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

@endsection


@section('scripts')
<script>
$(document).ready(function () {

    // ✅ Products for dropdown (category-wise)
    const ALL_PRODUCTS = @json($Products->map(function($p){
        return [
            'id' => $p->product_id,
            'name' => $p->product_name,
            'category_id' => $p->category_id
        ];
    }));

    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, function (m) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]);
        });
    }

    function updateProductDropText() {
        let count = $('.product_cb:checked').length;
        $('#productDropBtn').text(count ? (count + ' selected') : 'Select product(s)');
    }

    function renderProductCheckboxes(categoryId) {
        $('#error-product_id').text('');

        if (!categoryId) {
            $('#productCheckboxList').html('<div class="text-muted small">Select category first.</div>');
            $('#productSelectAll').prop('checked', false);
            updateProductDropText();
            return;
        }

        const list = ALL_PRODUCTS.filter(p => String(p.category_id) === String(categoryId));

        let html = '';
        if (list.length === 0) {
            html = '<div class="text-muted small px-1">No products found for this category.</div>';
        } else {
            html = list.map(p => `
                <div class="form-check">
                    <input class="form-check-input product_cb" type="checkbox" value="${p.id}" id="p_${p.id}">
                    <label class="form-check-label" for="p_${p.id}">${escapeHtml(p.name)}</label>
                </div>
            `).join('');
        }

        $('#productCheckboxList').html(html);
        $('#productSelectAll').prop('checked', false);
        updateProductDropText();
    }

    // category change => show only category products
    $(document).on('change', '#category_id', function () {
        renderProductCheckboxes($(this).val());
    });

    // select all
    $(document).on('change', '#productSelectAll', function () {
        $('.product_cb').prop('checked', this.checked);
        updateProductDropText();
    });

    // checkbox count
    $(document).on('change', '.product_cb', function () {
        $('#productSelectAll').prop('checked', $('.product_cb').length === $('.product_cb:checked').length);
        updateProductDropText();
    });

    // ✅ GLOBAL function to avoid "loadProductList is not defined"
    window.loadProductList = function () {
        $.ajax({
            url: "{{ route('EMPvisit.product', $id) }}",
            method: "GET",
            success: function (products) {

                if (!Array.isArray(products)) {
                    $('#productTableBody').html('<tr><td colspan="6" class="text-center">No Data</td></tr>');
                    $('#purchasedTableBody').html('');
                    return;
                }

                let normalRows = '';
                let purchasedRows = '';
                let n1 = 1, n2 = 1;

                products.forEach((item) => {

                    const statusText = (item.order_details && item.order_details.order_status && item.order_details.order_status.status)
                        ? item.order_details.order_status.status
                        : (item.status ?? '-');

                    const isNotPurchased = String(statusText).toLowerCase().includes('not purchased');
                    const isPurchased = (item.order_details !== null) && !isNotPurchased;

                    // Actions
                    let actionButtons = `
                            <button class="btn btn-danger btn-sm deleteProduct" data-id="${item.cust_pro_id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;

                        // ✅ show Edit only if ordered (order_details exists)
                        if (item.order_details !== null) {
                            actionButtons += `
                                <button type="button"
                                    class="btn btn-success btn-sm editStatus"
                                    data-id="${item.cust_pro_id}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal">
                                    <i class="fa fa-edit"></i>
                                </button>
                            `;
                        }

                        // ✅ Order button always visible (ordered or not ordered)
                        actionButtons += `
                            <button type="button"
                                class="btn btn-success btn-sm orderProduct"
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


                    const rowHtml = `
                        <tr id="row-${item.cust_pro_id}">
                            <td>${isPurchased ? n2++ : n1++}</td>
                            <td>${item.category?.category_name ?? '-'}</td>
                            <td>${item.product?.product_name ?? '-'}</td>
                            <td>${statusText}</td>
                            <td>${item.employee?.emp_name ?? '-'}</td>
                            <td>${actionButtons}</td>
                        </tr>
                    `;

                    if (isPurchased) purchasedRows += rowHtml;
                    else normalRows += rowHtml;

                });

                $('#productTableBody').html(normalRows || '<tr><td colspan="6" class="text-center">No Data</td></tr>');
                $('#purchasedTableBody').html(purchasedRows || '<tr><td colspan="6" class="text-center">No Purchased Data</td></tr>');
            }
        });
    };

    // Add products (multiple)
    function addProductsSequential(productIds, idx, baseData) {
        if (idx >= productIds.length) {
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
            data: Object.assign({}, baseData, { product_id: productIds[idx] }),
            complete: function () {
                addProductsSequential(productIds, idx + 1, baseData);
            }
        });
    }

    $(document).on('click', '#addProductBtn', function () {
        $('.error-text').text('');

        let categoryId = $('#category_id').val();
        if (!categoryId) {
            $('#error-category_id').text('Please select category.');
            return;
        }

        let productIds = $('.product_cb:checked').map(function () { return $(this).val(); }).get();
        if (productIds.length === 0) {
            $('#error-product_id').text('Please select at least one product.');
            return;
        }

        let empId = $('#product_emp_id').val();
        if (!empId) {
            $('#error-emp_id').text('Please select employee.');
            return;
        }

        $('#addProductBtn').prop('disabled', true);

        let baseData = {
            _token: '{{ csrf_token() }}',
            cust_id: $('#cust_id').val(),
            category_id: categoryId,
            visit_id: $('#visit_id').val(),
            emp_id: empId,
            visit_date: $('#visit_date').val(),
            status: $('#productstatus').val()
        };

        addProductsSequential(productIds, 0, baseData);
    });

    // init list
    loadProductList();


    // delete
    $(document).on('click', '.deleteProduct', function (event) {
        event.preventDefault();

        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: `/admin/customer-product/delete/${id}`,
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        $(`#row-${id}`).remove();
                    } else {
                        alert('Failed to delete the product.');
                    }
                },
                error: function () {
                    alert('An error occurred while deleting the product.');
                }
            });
        }
    });

    // edit status modal
    $(document).on('click', '.editStatus', function () {
        $('#statusproduct_id').val($(this).data('id'));
    });

    // not purchased reason toggle (based on option text)
    function toggleNotPurchasedReason() {
        const text = $('#delivery_status option:selected').text().toLowerCase();
        const isNotPurchased = text.includes('not purchased');

        $('#notPurchasedReasonWrap').toggleClass('d-none', !isNotPurchased);
        if (!isNotPurchased) $('#not_purchased_reason').val('');
    }
    $(document).on('change', '#delivery_status', toggleNotPurchasedReason);

    // order modal open
    $(document).on('click', '.orderProduct', function () {
        let custProId = $(this).data('id');
        let productId = $(this).data('product');
        let branchId = $(this).data('branch');
        let productName = $(this).data('name');
        let branchName = $(this).data('branchname');
        let refNo = $(this).data('refno');

        $('#orderForm')[0].reset();
        $('#existingReferImage').text('');
        $('#notPurchasedReasonWrap').addClass('d-none');
        $('#not_purchased_reason').val('');

        $('#ordercust_pro_id').val(custProId);
        $('#orderProductId').val(productId);
        $('#orderbranch_id').val(branchId);
        $('#orderProduct').text(productName);
        $('#orderbranch_name').text(branchName);
        $('#orderrefno').val(refNo);

        $.ajax({
            url: '/get-order-details/' + custProId,
            type: 'GET',
            success: function (response) {
                if (response && response.success) {
                    let data = response.data;

                    $('select[name="karat"]').val(data.karat);
                    $('select[name="color_id"]').val(data.color_id);
                    $('input[name="weight"]').val(data.weight);
                    $('input[name="size"]').val(data.size);
                    $('input[name="refer_tag_number"]').val(data.refer_tag_number);

                    // cannot set file input value; just show existing
                    if (data.refer_image_url) {
                        $('#existingReferImage').text('Existing: ' + data.refer_image_url);
                    }

                    $('input[name="amount"]').val(data.amount);
                    $('input[name="rate_fix_open"]').val(data.rate_fix_open);
                    $('textarea[name="remark"]').val(data.remark);
                    $('select[name="rate_type"]').val(data.rate_type);
                    $('select[name="given_to"]').val(data.given_to);
                    $('select[name="delivery_status"]').val(data.delivery_status);
                    $('input[name="delivery_date"]').val(data.delivery_date);

                    toggleNotPurchasedReason();
                }
            }
        });
    });

    // order submit (FormData because file upload)
    $('#orderForm').on('submit', function (e) {
        e.preventDefault();

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
                    location.reload();
                } else {
                    alert('Error: ' + (response.message ?? 'Something went wrong'));
                }
            },
            error: function () {
                alert('An unexpected error occurred.');
            }
        });
    });

    // followup open/close
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

});
</script>
@endsection
