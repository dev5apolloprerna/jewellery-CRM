@extends('layouts.app')
@section('title', 'Product Category List')
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
                                        <h5 class="card-title mb-0">Add Product Category</h5>
                                    </div>

                                    <div class="live-preview">
                                        <form method="POST" action="{{ route('productCategory.store') }}" autocomplete="off"
                                            enctype="multipart/form-data" onsubmit="return validateproductname()">
                                            @csrf

                                            <div class="modal-body">
                                               
                                                <div class="mt-4 mb-3">
                                                    Product Category Name <span style="color:red;">*</span>
                                                    <input type="text" id="category_name" name="category_name" class="form-control"  value="{{ old('category_name')}}" placeholder="Enter Product Category Name" maxlength="100"  required>
                                                    @if($errors->has('category_name'))
                                                         <span class="text-danger">
                                                            {{ $errors->first('category_name') }}
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
                                        <h5 class="card-title mb-0">Product Category List</h5>
                                        <div class="modal-body">

                                        
                                        </div>
                                    </div>
                                    
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Product Category Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($Category as $cat)
                                            <tr>
                                                <td>
                                                    {{ $i + $Category->perPage() * ($Category->currentPage() - 1) }}
                                                <td>{{ $cat->category_name }}</td>

                                                <td>
                                                    <div>
                                                        <a class="mx-1" title="Edit" href="#"
                                                            onclick="getEditData(<?= $cat->category_id ?>)"
                                                            data-bs-toggle="modal" data-bs-target="#showModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>

                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $cat->category_id ?>);">
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
                                <h5 class="modal-title" id="exampleModalLabel">Edit Product Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form onsubmit="return validateeditname()" method="POST" action="{{ route('productCategory.update') }}">
                                @csrf
                                <input type="hidden" name="category_id" id="category_id" value="">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        Product Category Name <span style="color:red;">*</span>
                                        <input type="text" name="category_name" class="form-control" onblur="validateeditname();" placeholder="Enter Product Category Name" id="Editcategoryname" maxlength="100" required>
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
                                    <form id="user-delete-form" method="POST" action="{{ route('productCategory.delete') }}">
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
            var url = "{{ route('productCategory.edit', ':id') }}";
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
                        $('#Editcategoryname').val(obj.category_name);
                        $('#category_id').val(id);
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
        function validateproductname() 
        {
             var category_name = $("#category_name").val();
            var isValid = true;

           $.ajax({
                url: "{{ route('productCategory.validatename') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    category_name: category_name
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        alert('Product Category Name Already Exists.');
                        $("#category_name").val('');
                        isValid = false;
                    }
                }
            });
        
            return isValid;
        }
    </script>

    <script>
        function validateeditname() { 
            var editcatname = $("#Editcategoryname").val();
            var category_id = $("#category_id").val();
            var url = "{{ route('productCategory.validateeditname') }}";
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    editcatname: editcatname,category_id:category_id
                },
                success: function(data) {
                    console.log(data);
                    if (data == 1) {
                        alert('Product Category Name Already Exists.');
                        $("#Editcategoryname").val('');
                        return false;
                    }
                }
            })
        }
    </script>

@endsection
