@extends('layouts.app')
@section('title', 'Branch List')
@section('content')

<?php $profileId = Request::segment(3);?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Branch List
                                    <a href="{{ route('branch.create') }}" style="float: right;" class="btn btn-sm btn-primary">
                                        <i class="far fa-plus"></i> Add Branch
                                    </a>

                                </h5>
                                
                            </div>
                             <div class="row">
                                <div class="col-lg-12">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class=" table table-bordered table-striped table-hover datatable">
                                                    <thead>
                                                        <tr>
                                                            <th width="50">
                                                            Sr No
                                                            </th>
                                                            <th>
                                                               Branch Name
                                                            </th>
                                                            <th>
                                                               Branch IP
                                                            </th>
                                                            <th>
                                                               Branch Email Id
                                                            </th>
                                                            <th>
                                                               Branch Phone
                                                            </th>
                                                            
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                     @if(count($BranchMaster) > 0)
                                                    <?php $i = 1;
                                                    ?>
                                                        @foreach($BranchMaster as $key => $branch)
                                                            <tr data-entry-id="{{ $branch->branchId }}">
                                                                <td>
                                                                    {{ $i + $BranchMaster->perPage() * ($BranchMaster->currentPage() - 1) }}
                                                                </td>
                                                                <td>
                                                                    {{ $branch->branch_name ?? '' }}
                                                                </td>
                                                                <td>
                                                                    {{ $branch->branch_ip ?? '' }}
                                                                </td><td>
                                                                    {{ $branch->branch_emailId ?? '' }}
                                                                </td><td>
                                                                    {{ $branch->branch_phone ?? '' }}
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex gap-2">
                                                                        <a class="" title="Edit"
                                                                            href="{{ route('branch.edit', $branch->branch_id) }}">
                                                                            <i class="far fa-edit"></i>
                                                                        </a>
                                                                        <!-- @if(!isset($branch->branch) && $branch->branch == "") -->

                                                                        <a class="" href="#" data-bs-toggle="modal"
                                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                                            onclick="deleteData(<?= $branch->branch_id ?>);">
                                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                                        </a>
                                                                        <!-- @endif -->

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php $i++; ?>
                                                        @endforeach
                                                        @else
                                                         <tr>
                                                            <td colspan="3">
                                                                <center>
                                                            No data Found
                                                        </center>
                                                            <td>

                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                             <div class="d-flex justify-content-center mt-3">
                                                {{ $BranchMaster->links() }}
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
                                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                                    </lord-icon>
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
                                    <button type="button" class="btn w-sm btn-primary mx-2"
                                        data-bs-dismiss="modal">Close</button>
                                    <form id="user-delete-form" method="POST" action="{{ route('branch.destroy') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" id="deleteid" value="">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

@endsection
@section('scripts')
    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>

@endsection

