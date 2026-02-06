@extends('layouts.app')

@section('title', 'Customer Previous Visit List')

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
    .tab-link:hover{ transform:translateY(-1px) }
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
        color:#fff;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @php
                $route = \Illuminate\Support\Facades\Route::currentRouteName();
                $cid   = $id; // customer_id passed from controller
            @endphp

            {{-- ✅ Tabs --}}
            <div class="tabs-wrap">
                {{-- History --}}
                <a href="{{ route('EMPcustomer.history', $cid) }}"
                   class="tab-link {{ $route=='EMPcustomer.history' ? 'active' : '' }}">
                    <span class="tab-ic"><i class="fa fa-eye"></i></span>
                    History
                </a>

                {{-- New Visit --}}
                <a href="{{ route('EMPvisit.create', $cid) }}"
                   class="tab-link {{ $route=='EMPvisit.create' ? 'active' : '' }}">
                    <span class="tab-ic"><i class="fa fa-plus-circle"></i></span>
                    New Visit
                </a>

                {{-- Previous Visit (ACTIVE HERE) --}}
                <a href="{{ route('EMPvisit.previous_visit', $cid) }}"
                   class="tab-link {{ in_array($route, ['EMPvisit.previous_visit','EMPvisit.previous_visit_view','EMPvisit.previous_visit_view']) ? 'active' : '' }}">
                    <span class="tab-ic"><i class="fa fa-history"></i></span>
                    Previous Visit
                </a>

                {{-- Orders --}}
                <a href="{{ route('EMPcustOrder.index') }}"
                   class="tab-link {{ $route=='EMPcustOrder.index' ? 'active' : '' }}">
                    <span class="tab-ic"><i class="fa fa-shopping-bag"></i></span>
                    Orders
                </a>

                 @if($orderId)
                    <a class="tab-link {{ $route=='EMPorderPayment.index' ? 'active' : '' }}"
                       title="Payment Details"
                       href="{{ route('EMPorderPayment.index', $orderId) }}">
                        <span class="tab-ic"><i class="fa fa-credit-card"></i></span>
                        Payment
                    </a>
                @endif

                {{-- Back to Customer List --}}
                <a href="{{ route('EMPcustomer.index') }}" class="tab-link">
                    <span class="tab-ic"><i class="fa fa-arrow-left"></i></span>
                    Back
                </a>
            </div>

            {{-- Alert Messages --}}
            @include('common.alert')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header" style="display:flex;justify-content:space-between;">
                            <h5 class="card-title mb-0">Customer Previous Visit List</h5>

                            <a href="{{ route('EMPcustomer.index') }}" class="btn btn-sm btn-primary">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- List --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer Name</th>
                                        <th>Customer Email</th>
                                        <th>Customer Mobile</th>
                                        <th>Branch Name</th>
                                        <th>Visite Date</th>
                                        <th>Employee Name</th>
                                        <th>Visite Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($prVisite as $pr)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pr->customer->customer_name ?? '-' }}</td>
                                            <td>{{ $pr->customer->customer_email ?? '-' }}</td>
                                            <td>{{ $pr->customer->customer_phone ?? '-' }}</td>
                                            <td>{{ $pr->branch->branch_name ?? 'N/A' }}</td>
                                            <td>{{ !empty($pr->visit_date) ? \Carbon\Carbon::parse($pr->visit_date)->format('d-m-Y') : '-' }}</td>
                                            <td>{{ $pr->employee->emp_name ?? '-' }}</td>
                                            <td>{{ ($pr->followup_status ?? 0) == 1 ? 'Close' : 'Open' }}</td>
                                            <td>
                                                <div>
                                                    @if(($pr->followup_status ?? 0) == 0)
                                                        <a class="mx-1" title="Edit"
                                                           href="{{ route('EMPvisit.previous_visit_view', $pr->visit_id) }}">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    <a class="mx-1" title="View Visit"
                                                       href="{{ route('EMPvisit.view_visit', $pr->visit_id) }}">
                                                        <i class="fas fa-eye"></i>
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
                                {{ $prVisite->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function deleteData(id) {
        $("#deleteid").val(id);
    }
    function myFunction() {
        $('#search').removeAttr('value');
    }
</script>
@endsection
