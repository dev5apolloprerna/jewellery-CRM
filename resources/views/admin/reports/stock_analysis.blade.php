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
                                <h5 class="card-title mb-0">Stock Analysis</h5>
                            </div>
                             <div class="card-body">
                                <form method="POST" action="{{ route('reports.stock_analysis') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="name">Search By Product Name</label>
                                                <input type="text" name="search" id="search" class="form-control" value="{{ old('search', isset($search) ? $search : '') }}">
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
                                <div class="d-flex justify-content-center mt-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--Delete Modal -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record
                                ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes,
                            Delete It!
                        </a>
                        <button type="button" class="btn w-sm btn-primary mx-2" data-bs-dismiss="modal">Close</button>
                        <form action="{{ route('empMaster.destroy', $employee->emp_id ?? '') }}" id="user-delete-form" method="POST">
                            @csrf
                            <input type="hidden" name="emp_id" id="deleteid" value="">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Delete Modal -->

@endsection

@section('scripts')
    <script>
     function genrateToexcel()
    {
        var search = $('#search').val();
        var Url = "{{route('reports.export_stock_analysis',[":search"])}}";
        Url = Url.replace(':search', search);
        window.location.href = Url;
    }
          function myFunction() 
        {
            $('#search').removeAttr('value');
        }
    </script>
@endsection
