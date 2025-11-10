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
                             <div class="card-body">
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

@endsection

@section('scripts')
    <script>
     function genrateToexcel()
    {
        var Month = $('#month').val();
        var Year = $('#year').val();
        var Url = "{{route('reports.export_monthly_conversion',[":Month",":Year"])}}";
        Url = Url.replace(':Month', Month ? Month : '0');
        Url = Url.replace(':Year', Year ? Year : '0' );
    
        window.location.href = Url;
    }
          function myFunction() 
        {
            $('#month').val('');
            $('#year').removeAttr('value');
        }

    </script>
@endsection
