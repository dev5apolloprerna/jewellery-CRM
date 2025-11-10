@extends('layouts.app')

@section('title', 'Sales Staff Order Report')

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
                                <h5 class="card-title mb-0">Sales Staff Order Report</h5>
                            </div>
                             <div class="card-body">
                                <form method="POST" action="{{ route('reports.collection_report') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="name">Search By Employee Name</label>
                                                 <select name="emp_id" id="emp_id" class="form-control">
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->emp_id }}" {{ request('emp_id') == $emp->emp_id ? 'selected' : '' }}>{{ $emp->emp_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                            <input class="btn btn-primary mt-4"  type="submit" value="{{'Search'}}">
                                            <input class="btn btn-primary mt-4"  type="submit" onclick="myFunction()" value="{{'Reset'}}">
                                            <button onclick="genrateToexcel()" type="button" class="btn btn-primary mt-4"> 
                                                Export to Excel
                                            </button>

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
                                            <th>Date</th>
                                            <th width="2%">Employee Name</th>
                                            <th>Customer Name</th>
                                            <th>Mobile</th>
                                            <th>Item</th>
                                            <th>Karat</th>
                                            <th>Colour</th>
                                            <th>Weight</th>
                                            <th>Size</th>
                                            <th width="2%">Tag Number</th>
                                            <th>Image</th>
                                            <th width="8%">Order Given To</th>
                                            <th>Delivery Date</th>
                                            <th>Remarks</th>
                                            <th>Paid Amount</th>
                                            <th>Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                             <?php $i = 1;?>

                                            @foreach($orders as $order)
                                                @foreach($order->orderDetails as $detail)
                                                    <tr>
                                                        <td>{{ $i + $orders->perPage() * ($orders->currentPage() - 1) }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                                                        <td>{{ $detail->employee->emp_name ?? '-' }}</td>
                                                        <td>{{ $order->customer->customer_name ?? '-' }}</td>
                                                        <td>{{ $order->customer->customer_phone ?? '-' }}</td>
                                                        <td>{{ $detail->product->product_name ?? '-' }}</td>
                                                        <td>{{ $detail->karat }}</td>
                                                        <td>{{ $detail->color->color_name ?? '-' }}</td>
                                                        <td>{{ $detail->weight }}</td>
                                                        <td>{{ $detail->size }}</td>
                                                        <td>{{ $detail->refer_tag_number }}</td>
                                                        <td>
                                                            @if($detail->refer_image_url)
                                                                <a href="{{ $detail->refer_image_url }}" target="_blank">View</a>
                                                            @endif
                                                        </td>
                                                        <td>{{ $detail->vendor->contact_person ?? '-' }}</td>
                                                            <td>
                                                                @if(!empty($detail->delivery_date))
                                                                    {{ \Carbon\Carbon::parse($detail->delivery_date)->format('d-m-Y') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        <td>{{ $detail->remark }}</td>
                                                        <td>{{ $order->paid_amount }}</td>
                                                        <td>{{ $detail->amount ?? '-' }}</td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                            @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
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
     function genrateToexcel()
    {
        var empId = $('#emp_id').val();
        var Url = "{{route('reports.export_collection_report',[":empId"])}}";
        Url = Url.replace(':empId', empId);
        window.location.href = Url;
    }
          function myFunction() 
        {
            $('#emp_id').val('');
        }
    </script>
@endsection
