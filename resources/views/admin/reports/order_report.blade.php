@extends('layouts.app')

@section('title', 'Order Reeport')

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
                                <h5 class="card-title mb-0">Order Report</h5>
                            </div>
                             <div class="card-body">
                                <form method="POST" action="{{ route('reports.order_report') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">From Date</label>
                                                <input type="date" name="from_date" id="from_date" class="form-control" placeholder="From Date" value="{{ request('from_date') }}">
                                            </div>
                                        </div>
                                          <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">To Date</label>
                                                        <input type="date" name="to_date" id="to_date" class="form-control" placeholder="To Date" value="{{ request('to_date') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Employee</label>
                                                <select name="emp_id" id="emp_id" class="form-control">
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp->emp_id }}" {{ request('emp_id') == $emp->emp_id ? 'selected' : '' }}>{{ $emp->emp_name }}</option>
                                                    @endforeach
                                                </select>

                                                </div>
                                            </div>
                                            
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <!-- <input class="btn btn-primary mt-4"  type="submit" value="{{'Search'}}"> -->
                                            <input class="btn btn-primary mt-4"  type="submit" onclick="myFunction()" value="{{'Reset'}}">
                                            <!-- <button onclick="genrateToexcel()" type="button" class="btn btn-primary mt-4"> 
                                                Export to Excel
                                            </button>
 -->
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
                                <table id="orderTable" class="table table-bordered table-striped nowrap align-middle" style="width:100%">

                                        <thead>
                                            <tr>
                                                <!--<th>No</th>-->
                                                <th>Date</th>
                                                <th>Sales Person</th>
                                                <th>Customer Name</th>
                                                <th>Mobile</th>
                                                <th>Item</th>
                                                <th>Karat</th>
                                                <th>Colour</th>
                                                <th>Weight</th>
                                                <th>Size</th>
                                                <th>Reference Tag</th>
                                                <th>Reference Image</th>
                                                <th>Remark</th>
                                                <th>Order Given To</th>
                                                <th>Delivery Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                             <?php $i = 1;?>
                                            @foreach ($orders as $order)
                                                @foreach ($order->orderDetails as $detail)
                                                    <tr>                                                                                               
                                                        <!--<td>{{ $i + $orders->perPage() * ($orders->currentPage() - 1) }}</td>-->
                                                        <td>{{ date('d-m-Y',strtotime($order->created_at)) }}</td>
                                                        <td>{{ $detail->employee->emp_name ?? '' }}</td>
                                                        <td>{{ $order->customer->customer_name ?? '' }}</td>
                                                        <td>{{ $order->customer->customer_phone ?? '' }}</td>
                                                        <td>{{ $detail->product->product_name ?? '' }}</td>
                                                        <td>{{ $detail->karat ?? '-' }}</td>
                                                        <td>{{ $detail->color->color_name ?? '-' }}</td>
                                                        <td>{{ $detail->weight ?? '-' }}</td>
                                                        <td>{{ $detail->size ?? '-' }}</td>
                                                        <td>{{ $detail->refer_tag_number }}</td>
                                                        <td><a href="{{ $detail->refer_image_url }}" target="_blank">Image</a></td>
                                                        <td>{{ $detail->remark ?? '-' }}</td>
                                                        <td>{{ $detail->vendor->contact_person ?? '-' }}</td>
                                                        <td>   @if($detail->delivery_date)
                                                                    {{ date('d-m-Y',strtotime($detail->delivery_date)) ?? '-' }}
                                                                @else
                                                                    {{ '-' }}
                                                                @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">

                                        <!-- {{ $orders->links() }} -->
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<style>
    #orderTable_filter label { font-weight: 600; margin-bottom: 0; }
    #orderTable_filter input { width: 260px; }
</style>

<script>
$(document).ready(function () {

    // ✅ DataTable
    $('#orderTable').DataTable({
        responsive: true,
        pageLength: 25,
        ordering: true,
        order: [[0, 'desc']], // Date column desc

        // ✅ Search LEFT, Export RIGHT
        dom:
            "<'row mb-2 align-items-center'" +
                "<'col-md-6 d-flex justify-content-start'f>" +
                "<'col-md-6 d-flex justify-content-end'B>" +
            ">" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",

        // ✅ Right side excel export button (route based)
        buttons: [
            {
                text: 'Export to Excel',
                className: 'btn btn-primary',
                action: function () {
                    genrateToexcel();
                }
            }
        ],

        initComplete: function () {
            const $input = $('#orderTable_filter input');
            $input.addClass('form-control form-control-sm');
            $('#orderTable_filter label').addClass('d-flex align-items-center gap-2 mb-0');
        }
    });

    $('.dt-buttons .btn').addClass('ms-2');

    // ✅ Auto search without clicking search button (server-side form submit)
    $('#from_date, #to_date, #emp_id').on('change', function () {
        $('#myForm').submit();
    });

});

// ✅ Export route (same as yours)
function genrateToexcel()
{
    var fromDate = $('#from_date').val();
    var toDate = $('#to_date').val();
    var empId = $('#emp_id').val();

    var Url = "{{ route('reports.export_order_reports',[":fromDate",":toDate",":empId"]) }}";
    Url = Url.replace(':fromDate', fromDate ? fromDate : '0');
    Url = Url.replace(':toDate', toDate ? toDate : '0');
    Url = Url.replace(':empId', empId ? empId : '0');

    window.location.href = Url;
}

// ✅ Reset (fixed ids) + auto submit
function myFunction()
{
    $('#from_date').val('');
    $('#to_date').val('');
    $('#emp_id').val('');
    $('#myForm').submit();
}
</script>
@endsection