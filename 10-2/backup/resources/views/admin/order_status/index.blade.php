@extends('layouts.app')
@section('title', 'Order Status List')
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
                                        <h5 class="card-title mb-0">Add Order Status</h5>
                                    </div>

                                    <div class="live-preview">
                                        <form method="POST" action="{{ route('orderStatus.store') }}" autocomplete="off" enctype="multipart/form-data" onsubmit="return validatecatname()">
                                            @csrf

                                            <div class="modal-body">
                                               
                                                <div class="mt-4 mb-3">
                                                    Order Status Name <span style="color:red;">*</span>
                                                    <input type="text" id="order_status" name="status" class="form-control"  value="{{ old('status')}}" placeholder="Enter Order Status Name"  maxlength="50" required>
                                                    @if($errors->has('status'))
                                                         <span class="text-danger">
                                                            {{ $errors->first('status') }}
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" class="btn btn-primary mx-2" id="add-btn">Save</button>
                                                    <input type="reset" class="btn btn-primary mx-2" id="add-btn" value="Clear">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-lg-1">
                                </div>

                                <div class="col-lg-5">
                                    <div class="d-flex justify-content-between card-header">
                                        <h5 class="card-title mb-0">Order Status List</h5>
                                        <div class="modal-body">

                                        
                                        </div>
                                    </div>
                                    
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Order Status Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($orderStatus as $cat)
                                            <tr>
                                                <td>
                                                    {{ $i + $orderStatus->perPage() * ($orderStatus->currentPage() - 1) }}
                                                <td>{{ $cat->status }}</td>

                                                <td>
                                                    <div>
                                                    @if (!in_array($cat->status, [1, 2]))
                                                        <a class="mx-1" title="Edit" href="#"
                                                            onclick="getEditData(<?= $cat->order_status_id ?>)"
                                                            data-bs-toggle="modal" data-bs-target="#showModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $cat->order_status_id ?>);">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $orderStatus->links() }}
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
                                <h5 class="modal-title" id="exampleModalLabel">Edit Order Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form onsubmit="return validateeditname()" method="POST" action="{{ route('orderStatus.update') }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="order_status_id" id="order_status_id" value="">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        Order Status Name <span style="color:red;">*</span>
                                        <input type="text" name="status" class="form-control" onblur="validateeditname();" placeholder="Enter Order Status Name" id="Editcustname" maxlength="50" required>
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
                                        Order Statuss="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
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
                                    <form id="user-delete-form" method="POST" action="{{ route('orderStatus.delete') }}">
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
            var url = "{{ route('orderStatus.edit', ':id') }}";
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
                        $('#Editcustname').val(obj.status);
                        $('#order_status_id').val(id);
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
            var status = $("#status").val();
            var isValid = true;

           $.ajax({
                url: "{{ route('orderStatus.validatename') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    status: status
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        alert('Order Status Name Already Exists.');
                        $("#status").val('');
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
            var order_status_id = $("#order_status_id").val();
            var url = "{{ route('orderStatus.validateeditname') }}";
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    editcatname: editcatname,order_status_id:order_status_id
                },
                success: function(data) {
                    console.log(data);
                    if (data == 1) {
                        alert('Order Status  Name Already Exists.');
                        $("#Editcustname").val();
                        return false;
                    }
                }
            })
        }
    </script>

@endsection
