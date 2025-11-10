@extends('layouts.app')
@section('title', 'Customer Visit')
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
                            <h5 class="card-title text-uppercase fw-bold text-black mb-0">Customer Visit</h5>
                            <div class="page-title-right">
                                 @if($feedback->followup_status == 1)
                                <a href="{{ route('newVisite.create',$id) }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Add New Visit
                                </a>
                                @endif
                                <a href="{{ route('newVisite.previous_visit',$Customer->customer_id) }}"
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

                            
                            <!-- {{-- Product List --}} -->

                                            
                                            <div class="mt-3">
                                                <h6 class="text-uppercase fw-bold mt-4 mb-2">Product List</h6>
                                                    <table class="table table-bordered" >
                                                        <thead>
                                                            <tr>
                                                                <th>Sr. No</th>
                                                                <th>Product Category</th>
                                                                <th>Product Name</th>
                                                                <!-- <th>Visit Date</th> -->
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
                                                                        <!-- <td>{{ \Carbon\Carbon::parse($product->visit_date)->format('d-m-Y') }}</td> -->
                                                                        <td>{{ $product->orderDetails->OrderStatus->status ?? $product->status }}</td>
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
                                            <td>{{ $followup->employee->emp_name ?? '-' }}</td>
                                            <td>{{ $followup->custVisit->followup_status == 0 ? 'Open' : ($followup->custVisit->followup_status == 1 ? 'Close' : '') }}</td>
                                            <td>{{ $followup->custVisit->closereason->close_reason ?? '-' }}</td>
                                            <td>{{ $followup->remark }}</td>
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
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
