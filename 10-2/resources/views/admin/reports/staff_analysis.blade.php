@extends('layouts.app')

@section('title', 'Stock Analysis')

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
                                <h5 class="card-title mb-0">Sales Staff Analysis</h5>
                            </div>
                             

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="staffTable" class="table table-bordered table-striped nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Employee Name</th>
                                            <th>Branch Name</th>
                                            <th>Clients Attended</th>
                                            <th>Clients Converted</th>
                                            <th>Conversion Ratio</th>
                                            <th>Performance score</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                            @foreach ($employees as $emp) 
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $emp->emp_name }}</td>
                                                <td>{{ $emp->branch->branch_name }}</td>
                                                <td>{{ $emp->client_attended_count }}</td>
                                                <td>{{ $emp->client_converted_count }}</td>
                                                <td>{{ number_format($emp->conversion_ratio, 2) }}%</td>
                                                <td>{{ number_format($emp->performance_score, 2) }}</td>
                                                   
                                            </tr>
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
</div>
</div>
</div>

@endsection

@section('scripts')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    {{-- jQuery + DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    {{-- Buttons --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <style>
        #staffTable_filter label { font-weight: 600; margin-bottom: 0; }
        #staffTable_filter input { width: 260px; }
    </style>

    <script>
        $(document).ready(function () {
            $('#staffTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[1, 'asc']], // employee name

                // ✅ Search LEFT, Buttons RIGHT
                dom:
                    "<'row mb-2 align-items-center'" +
                        "<'col-md-6 d-flex justify-content-start'f>" +
                        "<'col-md-6 d-flex justify-content-end'B>" +
                    ">" +
                    "<'row'<'col-12'tr>>" +
                    "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",

                buttons: [
                    { extend: 'excelHtml5', title: 'Sales_Staff_Analysis', text: 'Export To Excel', className: 'btn btn-primary' },
                    /*{ extend: 'pdfHtml5', title: 'Sales_Staff_Analysis', text: 'PDF', className: 'btn btn-primary', orientation: 'landscape', pageSize: 'A4' },
                    { extend: 'print', title: 'Sales Staff Analysis', text: 'Print', className: 'btn btn-primary' }*/
                ],

                initComplete: function () {
                    // ✅ Bootstrap style for search input
                    const $input = $('#staffTable_filter input');
                    $input.addClass('form-control form-control-sm');
                    $('#staffTable_filter label').addClass('d-flex align-items-center gap-2');
                }
            });

            // ✅ space between buttons
            $('.dt-buttons .btn').addClass('ms-2');
        });
    </script>
@endsection
