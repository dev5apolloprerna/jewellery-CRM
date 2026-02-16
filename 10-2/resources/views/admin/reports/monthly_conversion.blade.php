@extends('layouts.app')

@section('title', 'Monthly Conversion Report')

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
                                <h5 class="card-title mb-0">Monthly Conversion Report</h5>
                            </div>
                             <!-- <div class="card-body">
                                <form method="POST" action="{{ route('reports.monthly_conversion') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Search By Month</label>
                                                 <select name="month" id="month" class="form-control">
                                                    <option value="">Select Month</option>
                                                    @foreach(range(1, 12) as $m)
                                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">Search By Year</label>
                                             <input type="number" name="year" id="year" class="form-control" placeholder="Year (e.g. 2025)" value="{{ request('year') }}">

                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <input class="btn btn-primary mt-4" type="submit" value="{{'Search'}}">
                                            <input class="btn btn-primary mt-4" type="submit" onclick="myFunction()" value="{{'Reset'}}">
                                            <button onclick="genrateToexcel()" type="button" class="btn btn-primary mt-4"> 
                                                Export to Excel
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>  -->
                       

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="monthlyTable" class="table table-bordered table-striped nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Total Clients Visited</th>
                                            <!-- <th>Total Products Viewed</th> -->
                                            <th>Total Products Sold</th>
                                            <th>Conversion Ratio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $row)
                                            <tr>
                                            <td>{{ \Carbon\Carbon::parse($row->month . '-01')->format('F Y') }}</td>
                                            <td>{{ $row->total_clients_visited }}</td>
                                            <!-- <td>{{ $row->total_clients_viewed }}</td> -->
                                            <td>{{ $row->total_clients_sold }}</td>
                                            <td>{{ $row->conversion_ratio }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data found for selected filters</td>
                                            </tr>
                                        @endforelse
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
    #monthlyTable_filter label { font-weight: 600; margin-bottom: 0; }
    #monthlyTable_filter input { width: 260px; }
</style>

<script>
    $(document).ready(function () {
        $('#monthlyTable').DataTable({
            responsive: true,
            pageLength: 25,
            ordering: true,
            order: [[0, 'desc']], // month sort

            // ✅ Search LEFT, Button RIGHT
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
                const $input = $('#monthlyTable_filter input');
                $input.addClass('form-control form-control-sm');
                $('#monthlyTable_filter label').addClass('d-flex align-items-center gap-2 mb-0');
            }
        });

        // ✅ space between dt buttons if you add more later
        $('.dt-buttons .btn').addClass('ms-2');
    });

    function genrateToexcel() {
        var Month = $('#month').val();
        var Year = $('#year').val();

        var Url = "{{ route('reports.export_monthly_conversion',[":Month",":Year"]) }}";
        Url = Url.replace(':Month', Month ? Month : '0');
        Url = Url.replace(':Year', Year ? Year : '0');

        window.location.href = Url;
    }

    function myFunction() {
        $('#month').val('');
        $('#year').val('');
        // optional: submit after reset
        // $('#myForm').submit();
    }
</script>
@endsection 