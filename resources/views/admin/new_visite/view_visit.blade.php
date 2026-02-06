@extends('layouts.app')
@section('title', 'Customer Visit')
@section('content')

<style>
  .tabs-wrap{display:flex;gap:10px;flex-wrap:wrap}
  .tab-link{
    text-decoration:none;
    border:1px solid #eee;
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
  .tab-link:hover{transform:translateY(-1px)}
  .tab-link.active{
    background:#5c2323;
    border-color:#5c2323;
    color:#fff;
  }
  .tab-ic{
    width:30px;height:30px;border-radius:10px;
    display:grid;place-items:center;
    background:#f3f4f6;color:#111827;
  }
  .tab-link.active .tab-ic{background:rgba(255,255,255,.18);color:#fff}
</style>

@php
    $fmt = fn($n) => number_format((float)($n ?? 0), 2);
@endphp


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">


@php
    $r = \Illuminate\Support\Facades\Route::currentRouteName();
    $cid = $Customer->customer_id; // adjust if different
    $latest = $Customer->latestVisit ?? null;

    $orderId = null;
    if(isset($order) && !empty($order->order_id)){
        $orderId = $order->order_id;
    }
    if(!$orderId && !empty($visitBlocks) && count($visitBlocks)){
        foreach($visitBlocks as $b){
            if(!empty($b['orderBreakdown']) && count($b['orderBreakdown'])){
                $orderId = $b['orderBreakdown'][0]['order_id'] ?? null;
                if($orderId) break;
            }
        }
    }
@endphp

<div class="tabs-wrap mb-3">
    {{-- Customer History (existing) --}}
    <a class="tab-link {{ $r=='customer.history' ? 'active' : '' }}"
       href="{{ route('customer.history', $cid) }}">
        <span class="tab-ic"><i class="fa fa-eye"></i></span>
         History
    </a>
    {{-- New Visit --}}
    @if($latest)
        @if(($latest->followup_status ?? 0) == 1)
            <a class="tab-link {{ $r=='newVisite.create' ? 'active' : '' }}"
               href="{{ route('newVisite.create', $cid) }}">
                <span class="tab-ic"><i class="fas fa-plus-circle"></i></span>
                New Visit
            </a>
        @endif
    @else
        <a class="tab-link {{ $r=='newVisite.create' ? 'active' : '' }}"
           href="{{ route('newVisite.create', $cid) }}">
            <span class="tab-ic"><i class="fas fa-plus-circle"></i></span>
            New Visit
        </a>
    @endif

    {{-- Previous Visit --}}
    @if($latest)
        <a class="tab-link {{ $r=='newVisite.previous_visit' ? 'active' : '' }}"
           href="{{ route('newVisite.previous_visit', $cid) }}">
            <span class="tab-ic"><i class="fa fa-message"></i></span>
            Previous Visit
        </a>
    @endif

    

    {{-- Orders List (NEW) --}}
    <a class="tab-link {{ $r=='custOrder.index' ? 'active' : '' }}"
       href="{{ route('custOrder.index') }}">
        <span class="tab-ic"><i class="fa fa-shopping-bag"></i></span>
        Orders
    </a>

    {{-- Order Payment (NEW) --}}
    @if($orderId)
        <a class="tab-link {{ $r=='orderPayment.index' ? 'active' : '' }}"
           href="{{ route('orderPayment.index', $orderId) }}">
            <span class="tab-ic"><i class="fa fa-credit-card"></i></span>
            Order Payment
        </a>
    @endif
</div>
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
