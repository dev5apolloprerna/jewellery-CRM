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
                             <div class="card-body">
                                <form method="POST" action="{{ route('reports.staff_analysis') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="name">Search By Employee Name</label>
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

@endsection

@section('scripts')
    <script>
     function genrateToexcel()
    {
        var search = $('#search').val();
        var Url = "{{route('reports.export_staff_analysis',[":search"])}}";
        Url = Url.replace(':search', search);
        window.location.href = Url;
    }
          function myFunction() 
        {
            $('#search').removeAttr('value');
        }
    </script>
@endsection
