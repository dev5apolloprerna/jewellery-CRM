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
                                <a href="{{ route('custOrder.index') }}"
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
                                                <h6 class="text-uppercase fw-bold mt-4 mb-2">Ordered Product List</h6>
                                                    <table class="table table-bordered" >
                                                        <thead>
                                                            <tr>
                                                                <!--<th>Sr. No</th>-->
                                                                <th>Product Category</th>
                                                                <th>Product Name</th>
                                                                <th>Karat</th>
                                                                <th>Color</th>
                                                                <th>Weight</th>
                                                                <th>Size</th>
                                                                <th>Order Given To </th>
                                                                <th>Delivery Date</th>
                                                                <th>Rate Type</th>
                                                                <th>Rate Fix/Open</th>
                                                                <th>Refer Tag Number</th>
                                                                <th>Refer Image</th>
                                                                <th>Status</th>
                                                                <th>Attended By</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="productTableBody">
                                                            @if(sizeof($CustProducts) != 0)
                                                                @foreach($CustProducts as $key => $product)
                                                                    <tr>
                                                                        <!--<td width="10%">{{ $key + 1 }}</td>-->
                                                                        <td width="10%">{{ $product->category->category_name }}</td>
                                                                        <td width="12%">{{ $product->product->product_name }}</td>
                                                                        <td width="5%">{{ $product->orderDetails->karat ?? '-' }}</td>
                                                                        <td width="10%">{{ $product->orderDetails->color->color_name ?? '-' }}</td>
                                                                        <td width="5%">{{ $product->orderDetails->weight ?? '-' }}</td>
                                                                        <td width="5%">{{ $product->orderDetails->size ?? '-' }}</td>
                                                                        <td width="8%">{{ $product->orderDetails->vendor->contact_person ?? '-' }}</td>
                                                                        <td width="10%">    {{ optional($product->orderDetails)->delivery_date ? date('d-m-Y', strtotime($product->orderDetails->delivery_date)) : '-' }}
</td>
                                                                        <td width="7%">{{ $product->orderDetails->rate_type ?? '-' }}</td>
                                                                        <td width="7%">{{ $product->orderDetails->rate_fix_open ?? '-' }}</td>
                                                                        <td width="7%">{{ $product->orderDetails->refer_tag_number ?? '-' }}</td>
                                                                        <td width="10%">
                                                                            @if(optional($product->orderDetails)->refer_image_url != null)
                                                                            <a href="{{ $product->orderDetails->refer_image_url ?? '-' }}" target='_blank'>Image</a>
                                                                            @else
                                                                            {{ '-' }}
                                                                            @endif
                                                                            </td>
                                                                        <td width="10%">{{ $product->orderDetails->OrderStatus->status ?? '-' }}</td>
                                                                        <td width="10%">{{ $product->employee->emp_name }}</td>
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
                                            <td width="10%">{{ $key + 1 }}</td>
                                            <td width="10%">{{ \Carbon\Carbon::parse($followup->visit_date)->format('d-m-Y') }}</td>
                                            <td width="10%">{{ \Carbon\Carbon::parse($followup->next_followup_date)->format('d-m-Y') }}</td>
                                            <td width="10%">{{ $followup->employee->emp_name ?? '-' }}</td>
                                            <td width="10%">{{ $followup->custVisit->followup_status == 0 ? 'Open' : ($followup->custVisit->followup_status == 1 ? 'Close' : '') }}</td>
                                            <td width="10%">{{ $followup->custVisit->closereason->close_reason ?? '-' }}</td>
                                            <td width="10%">{{ $followup->remark }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="3" class="text-center">No Data Found</td>
                                </tr>

                                @endif
                                </tbody>
                            </table>
                            
                            <h6 class="text-uppercase fw-bold mt-4 mb-2">Payment History</h6>
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
                                                <td width="10%">{{ $loop->iteration }}</td>
                                                <td width="10%">{{ $payment->amount }}</td>
                                                <td width="10%">{{ $payment->amount }}</td>
                                                <td width="10%">{{ $payment->paid_amount }}</td>
                                                <td width="10%">{{ $payment->due_amount }}</td>
                                                <td width="10%">{{ \Carbon\Carbon::parse($payment->next_followup_date)->format('d-m-Y') }}</td>
                                               
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
