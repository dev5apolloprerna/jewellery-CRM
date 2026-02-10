@extends('layouts.app')

@section('title', 'Stock Analysis')

@section('content')
<style>
    #stockTable_filter label { font-weight: 600; }
    #stockTable_filter input { width: 260px; }
</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Messages --}}
            @include('common.alert')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Stock Analysis</h5>
                        </div>

                        <div class="card-body">
                            <table id="stockTable" class="table table-bordered table-striped nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Product Name</th>
                                        <th>Viewed</th>
                                        <th>Sold</th>
                                        <th>Conversion ratio</th>
                                        <th>Demand</th>
                                        <th>Product Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->view_product_count }}</td>
                                            <td>{{ $product->sold_product_count }}</td>
                                            <td>{{ number_format($product->conversion_ratio, 2) }}%</td>
                                            <td>{{ number_format($product->demand, 2) }}%</td>
                                            <td>{{ number_format($product->product_score, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div> {{-- card-body --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- DataTables CSS/JS + Buttons --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

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

    <script>
    $(document).ready(function () {
        const table = $('#stockTable').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[1, 'asc']],

            // ✅ Search LEFT, Buttons RIGHT
            dom:
                "<'row mb-2 align-items-center'"+
                    "<'col-md-6 d-flex justify-content-start'f>"+
                    "<'col-md-6 d-flex justify-content-end'B>"+
                ">"+
                "<'row'<'col-12'tr>>"+
                "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",

            buttons: [
                { extend: 'excelHtml5', title: 'Stock_Analysis', text: 'Export to Excel', className: 'btn btn-primary' },
                // { extend: 'pdfHtml5', title: 'Stock_Analysis', text: 'PDF', className: 'btn btn-primary',
                //   orientation: 'landscape', pageSize: 'A4'
                // },
                // { extend: 'print', title: 'Stock Analysis', text: 'Print', className: 'btn btn-primary' }
            ],

            initComplete: function () {
                // ✅ Add bootstrap classes to search input
                const $input = $('#stockTable_filter input');
                $input.addClass('form-control form-control-sm');
                $('#stockTable_filter label').addClass('d-flex align-items-center gap-2 mb-0');
            }
        });

        // ✅ Space between buttons
        $('.dt-buttons .btn').addClass('ms-2');
    });
    </script>
@endsection