@extends('layouts.app')

@section('title', 'Today’s Follow-ups')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                           <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Today’s Follow-ups</h5>
                            
                            
                        </div>

                           <!--   <div class="card-body">
                                <form method="POST" action="{{route('EMPvisit.today')}}" class="row g-3 mb-4">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="name">From Date </label>
                                                <input type="date" name="next_followup_date" id="next_followup_date" value="{{ request('next_followup_date') }}" class="form-control">
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
                            </div> 
                        </div> -->
                       
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
                                            <th>Customer Name</th>
                                            <th>Employee</th>
                                            <th>Visit Date</th>
                                            <th>Followup Date</th>
                                            <th>Follow-up Status</th>
                                            <th>Remark</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($followups->count() > 0)

                                       @foreach($followups as $index => $visit)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $visit->customer->customer_name ?? 'N/A' }}</td>
                                                <td>{{ $visit->employee->emp_name ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($visit->next_followup_date)->format('d-m-Y') }}</td>
                                                <td>{{ $visit->followup_status == 1 ? 'Close' : 'Open' }}</td>
                                                <td>{{ $visit->remark ?? '-' }}</td>
                                                <td>
                                                    <div>
                                                        <a class="mx-1" title="Edit"
                                                            href="{{ route('newVisite.previous_visit_view', $visit->visit_id) }}">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center">No Customer Followup found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $followups->appends(request()->except('page'))->links() }}
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
        function editpassword(id) {
            $("#GetId").val(id);
        }

        function deleteData(id) {
            $("#deleteid").val(id);
        }
        function myFunction() {
            $('#next_followup_date').val('');
        }
    </script>
@endsection
