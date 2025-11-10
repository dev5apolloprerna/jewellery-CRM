@extends('layouts.app')

@section('title', 'Customer List')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"
                                style="display: flex;
                            justify-content: space-between;">
                                <h5 class="card-title mb-0">Customer List</h5>
                                <a href="{{ route('EMPcustomer.create') }}" class="btn btn-sm btn-primary">
                                    <i data-feather="plus"></i> Add New
                                </a>
                            </div>
                             <div class="card-body">
                                <form method="post" action="{{ route('EMPcustomer.index') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="name">Search By Customer Name</label>
                                                <input type="text" name="search" id="search" class="form-control" value="{{ old('search', isset($search) ? $search : '') }}" placeholder="Search By Name , Email , or Mobile Number">
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
                            </div> 
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
                                            <th>Branch Name</th>
                                            <th>Customer Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Refrence By</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @forelse ($customers as $cust)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $cust->branch->branch_name ?? 'N/A' }}</td>
                                                <td>{{ $cust->customer_name }}</td>
                                                <td>{{ $cust->customer_phone }}</td>
                                                <td>{{ $cust->customer_email }}</td>
                                                <td>{{ $cust->refer_by }}</td>
                                                <td>{{ $cust->custCat->cust_cat_name ?? '-' }}</td>
                                                <td>
                                                     <div>
                                                        <a class="mx-1" title="Edit"
                                                            href="{{ route('EMPcustomer.edit', $cust->customer_id) }}">
                                                            <i class="far fa-edit"></i>
                                                        </a>

                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $cust->customer_id ?>);">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                        @if($cust->latestVisit)
                                                            @if($cust->latestVisit->followup_status == 1)
                                                                    <a class="mx-1" title="New Visit" href="{{ route('EMPvisit.create', $cust->customer_id) }}">
                                                                        <i class="fas fa-plus-circle"></i>
                                                                    </a>
                                                            @endif
                                                            @else
                                                            <a class="mx-1" title="New Visit" href="{{ route('EMPvisit.create', $cust->customer_id) }}"><i class="fas fa-plus-circle"></i>
                                                            </a>
                                                        @endif
                                                         @if($cust->latestVisit)
                                                        <a class="mx-1" title="Customer Previous Visit"
                                                            href="{{ route('EMPvisit.previous_visit', $cust->customer_id) }}">
                                                            <i class="fa fa-message"></i>
                                                        </a>
                                                        @endif
                                                     <a class="mx-1" title="Customer Order"
                                                            href="{{ route('EMPcustomer.history', $cust->customer_id) }}">
                                                            <i class="fa fa-eye"></i>
                                                        </a> 
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No Customers found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $customers->appends(request()->except('page'))->links() }}
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
                        <form action="{{ route('customer.destroy', $customer->customer_id ?? '') }}" id="user-delete-form" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" id="deleteid" value="">

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

        function deleteData(id) {
            $("#deleteid").val(id);
        }
          function myFunction() 
        {
            $('#search').removeAttr('value');
        }
    </script>
@endsection
