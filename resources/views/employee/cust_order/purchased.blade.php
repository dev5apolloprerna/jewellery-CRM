@extends('layouts.app')

@section('title', 'Customer Order List')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                           <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Customer Order List</h5>
                            
                            <div class="btn-group">
                                
                                <a href="{{ route('EMPcustomer.index') }}" class="btn btn-sm btn-primary" style="margin-left: 10px;">
                                    Back
                                </a>
                            </div>
                        </div>

                             <!-- <div class="card-body">
                                <form method="post" action="{{ route('empMaster.index') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="name">Search By product Name</label>
                                                <input type="text" name="search" id="search" class="form-control" value="{{ old('search', isset($search) ? $search : '') }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <input class="btn btn-primary" style="margin-top: 15%;" type="submit" value="{{'Search'}}">
                                            <input class="btn btn-primary" style="margin-top: 15%;" type="submit" onclick="myFunction()" value="{{'Reset'}}">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> --> 
                        </div>
                       
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Customer Name</th>
                                            <th>Branch Name </th>
                                            <th>Amount</th>
                                            <th>Net Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Due Amount</th>
                                            <th>Order Id </th>
                                            <th>Purchase Date </th>
                                            <th>Visite Date </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                           @forelse($orders as $order)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->customer ? $order->customer->customer_name : 'N/A' }}</td>
                                                <td>{{ $order->branch->branch_name }}</td>
                                                <td>{{ $order->amount ?? '-' }}</td>
                                                <td>{{ $order->net_total ?? '-' }}</td>
                                                <td>{{ $order->paid_amount ?? '-' }}</td>
                                                <td>{{ $order->due_amount ?? '-' }}</td>
                                                <td>{{ $order->order_id ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->customerVisit->visit_date)->format('d-m-Y') }}</td>
                                                <td>
                                                    <div>       
                                                        <!-- <a class="" href="#" data-bs-toggle="modal"
                                                                title="Edit Order Status" data-bs-target="#editModal_{{ $order->order_id }}">
                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                        </a> -->
                                                        <!-- <a href="{{ route('EMPcustOrder.edit', $order->order_id) }}"><i class="fa fa-edit"></i></a> -->
                                                        <a class="" href="#" data-bs-toggle="modal"
                                                                title="Delete" data-bs-target="#deleteRecordModal"
                                                                onclick="deleteData(<?= $order->order_id ?>);">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                         
                                                       <a class="" href="{{ route('EMPorderPayment.index', $order->order_id) }}">
                                                            <i class="fa fa-credit-card" title="Payment Details"></i>
                                                        </a>
                                                        <a class="mx-1" title="Customer Order Detail"
                                                            href="{{ route('EMPcustOrder.detail', $order->visit_id) }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade flip" id="editModal_{{ $order->order_id }}" tabindex="-1" aria-hidden="true">
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

                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No Customer Product found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $orders->appends(request()->except('page'))->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!--Delete Modal -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record
                                ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes,
                            Delete It!
                        </a>
                        <button type="button" class="btn w-sm btn-primary mx-2" data-bs-dismiss="modal">Close</button>
                        <form action="{{ route('EMPcustOrder.destroy', $item->order_id ?? '') }}" id="user-delete-form" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" id="deleteid" value="">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Delete Modal -->

@endsection

@section('scripts')
    <script>
        function editpassword(id) {
            $("#GetId").val(id);
        }

        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>
@endsection
