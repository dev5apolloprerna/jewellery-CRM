@extends('layouts.app')
@section('title', 'Purity List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

               <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="row">
                                <div class="col-lg-5">

                                    <div class="d-flex justify-content-between card-header">
                                        <h5 class="card-title mb-0">Add Purity</h5>
                                    </div>

                                    <div class="live-preview">
                                        <form method="POST" action="{{ route('purity.store') }}" autocomplete="off" enctype="multipart/form-data" onsubmit="return validatecatname()">
                                            @csrf

                                            <div class="modal-body">
                                               
                                                <div class="mt-4 mb-3">
                                                    Purity Name <span style="Purity:red;">*</span>
                                                    <input type="text" id="purity_value" name="purity_value" class="form-control"  value="{{ old('purity_value')}}" placeholder="Enter Purity Name"  maxlength="50" required>
                                                    @if($errors->has('purity_value'))
                                                         <span class="text-danger">
                                                            {{ $errors->first('purity_value') }}
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary mx-2" id="add-btn">Save</button>
                                                    <button type="reset" class="btn btn-primary mx-2" id="add-btn">Clear</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-1">
                                </div>

                                <div class="col-lg-5">
                                    <div class="d-flex justify-content-between card-header">
                                        <h5 class="card-title mb-0">Purity List</h5>
                                        <div class="modal-body">

                                        
                                        </div>
                                    </div>
                                    
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Purity Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($Category as $cat)
                                            <tr>
                                                <td>
                                                    {{ $i + $Category->perPage() * ($Category->currentPage() - 1) }}
                                                <td>{{ $cat->purity_value }}</td>

                                                <td>
                                                    <div>
                                                        <a class="mx-1" title="Edit" href="#"
                                                            onclick="getEditData(<?= $cat->purity_id ?>)"
                                                            data-bs-toggle="modal" data-bs-target="#showModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $cat->purity_id ?>);">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $Category->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Edit Modal Start-->
                <div class="modal fade flip" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Purity</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form onsubmit="return validateeditname()" method="POST" action="{{ route('purity.update') }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="purity_id" id="purity_id" value="">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        Purity Name <span style="Purity:red;">*</span>
                                        <input type="text" name="purity_value" class="form-control" onblur="validateeditname();" placeholder="Enter Purity Name" id="Editcustname" maxlength="50" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary mx-2" id="add-btn">Update</button>
                                        <button type="button" class="btn btn-primary mx-2"
                                            data-bs-dismiss="modal">Cancle</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--Edit Modal End -->

                <!--Delete Modal Start -->
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
                                        Puritys="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
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
                                    <form id="user-delete-form" method="POST" action="{{ route('purity.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" id="deleteid" value="">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete modal End -->

            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        function getEditData(id) {
            var url = "{{ route('purity.edit', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id,
                        id
                    },
                    success: function(data) {
                        //console.log(data);
                        var obj = JSON.parse(data);
                        $('#Editcustname').val(obj.purity_value);
                        $('#purity_id').val(id);
                    }
                });
            }
        }
    </script>

    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>

    <script>
        function validatecatname() 
        {
            var purity_value = $("#purity_value").val();
            var isValid = true;

           $.ajax({
                url: "{{ route('purity.validatename') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    purity_value: purity_value
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        alert('Purity Name Already Exists.');
                        $("#purity_value").val('');
                        isValid = false;
                    }
                }
            });
        
            return isValid;
        }
    </script>

    <script>
        function validateeditname() { 
            var editcatname = $("#Editcustname").val();
            var purity_id = $("#purity_id").val();
            var url = "{{ route('purity.validateeditname') }}";
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    editcatname: editcatname,purity_id:purity_id
                },
                success: function(data) {
                    console.log(data);
                    if (data == 1) {
                        alert('Purity  Name Already Exists.');
                        $("#Editcustname").val();
                        return false;
                    }
                }
            })
        }
    </script>

@endsection
