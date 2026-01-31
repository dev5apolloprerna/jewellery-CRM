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
                                <h5 class="card-title mb-0">Cancel Reason Report</h5>
                                 <!-- <a href="{{ route('empMaster.create') }}" class="btn btn-sm btn-primary">
                                    <i data-feather="plus"></i> Add New
                                </a> -->
                            </div>
                             <!-- <div class="card-body">
                                <form method="get" action="{{ route('reports.index') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="name">Search By Product Name</label>
                                                <input type="text" name="search" id="search" class="form-control" value="{{ old('search', isset($search) ? $search : '') }}">
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
                            </div>  -->
                       

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                   <table id="cancelReasonTable" class="table table-bordered table-striped nowrap align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Reasons for Not Buying</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($cancelledReasons as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->close_reason ?? 'N/A' }}</td>
                                                    <td>{{ $item->cancel_count }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">No cancellations found.</td>
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

    <style>
        #cancelReasonTable_filter label { font-weight: 600; margin-bottom: 0; }
        #cancelReasonTable_filter input { width: 260px; }
    </style>

    <script>
        $(document).ready(function () {

            $('#cancelReasonTable').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[2, 'desc']], // Count desc

                // ✅ Search LEFT, Buttons RIGHT
                dom:
                    "<'row mb-2 align-items-center'" +
                        "<'col-md-6 d-flex justify-content-start'f>" +
                        "<'col-md-6 d-flex justify-content-end'B>" +
                    ">" +
                    "<'row'<'col-12'tr>>" +
                    "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",

                buttons: [
                    {
                        text: 'Export to Excel',
                        className: 'btn btn-primary',
                        action: function () {
                            window.location.href = "{{ route('reports.export_cancel_reason_report') }}";
                        }
                    }
                ],

                initComplete: function () {
                    // ✅ Bootstrap search input
                    const $input = $('#cancelReasonTable_filter input');
                    $input.addClass('form-control form-control-sm');
                    $('#cancelReasonTable_filter label').addClass('d-flex align-items-center gap-2');
                }
            });

            // ✅ space between buttons
            $('.dt-buttons .btn').addClass('ms-2');
        });
    </script>
@endsection
