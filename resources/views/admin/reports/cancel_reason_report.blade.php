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
                                 <button onclick="genrateToexcel()" type="button" class="btn btn-primary mt-4"> 
                                    Export to Excel
                                </button>

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
                                    <table class="table table-bordered">
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
    <script>
        function genrateToexcel()
    {
        var Url = "{{route('reports.export_cancel_reason_report')}}";
        window.location.href = Url;
    }
        function deleteData(id) {
            $("#deleteid").val(id);
        }
          function myFunction() 
        {
            $('#search').removeAttr('value');
        }
    </script>
@endsection
