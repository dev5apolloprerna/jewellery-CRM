@extends('layouts.app')
@section('title', 'Product List')
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
                                        <h5 class="card-title mb-0">Add Product</h5>
                                    </div>

                                    <div class="live-preview">
                                        <form method="POST" action="{{ route('product.store') }}" autocomplete="off"
                                            enctype="multipart/form-data" onsubmit="return validateproductname()">
                                            @csrf

                                            <div class="modal-body">
                                               
                                                <div class="mt-4 mb-3">
                                                    Product Name <span style="color:red;">*</span>
                                                    <input type="text" id="product_name" name="product_name" class="form-control"  value="{{ old('product_name')}}" placeholder="Enter Product Name" maxlength="100" required>
                                                    @if($errors->has('product_name'))
                                                         <span class="text-danger">
                                                            {{ $errors->first('product_name') }}
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="modal-body">
                                                <div class="mt-4 mb-3">
                                                    Product Tag <span style="color:red;">*</span>
                                                    <input type="text" id="product_tag" name="product_tag" class="form-control"  value="{{ old('product_tag')}}" placeholder="Enter Product Tag" onblur="return validatetag()"  value="{{ old('photo')}}" required>
                                                    @if($errors->has('product_tag'))
                                                         <span class="text-danger">
                                                            {{ $errors->first('product_tag') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mt-4 mb-3">
                                                    Product Image <span style="color:red;"></span>
                                                    <input type="file" id="product_photo" name="product_photo" class="form-control"  value="{{ old('product_photo')}}" placeholder="Enter Product Image" onChange="validateFile(this.value)" value="{{ old('photo')}}" >
                                                    @if($errors->has('product_photo'))
                                                         <span class="text-danger">
                                                            {{ $errors->first('product_photo') }}
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
                                        <h5 class="card-title mb-0">Product List</h5>
                                        <div class="modal-body">

                                        
                                        </div>
                                    </div>
                                    
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Product Image</th>
                                            <th scope="col">Product Tag</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($Product as $prod)
                                            <tr>
                                                <td>
                                                    {{ $i + $Product->perPage() * ($Product->currentPage() - 1) }}
                                                <td>{{ $prod->product_name }}</td>
                                                 <td>
                                                    @php
                                                        $photoPath = 'Product/' . $prod->product_photo;
                                                    @endphp
                                                    
                                                    @if(empty($prod->product_photo) || !file_exists($photoPath))
                                                        <img src="{{ asset('/assets/images/noimage.png') }}" width="50px" height="50px">
                                                    @else
                                                        <img src="/Product/{{ $prod->product_photo }}" width="50px" height="50px">
                                                    @endif
                                                </td>
                                                <td>{{ $prod->product_tag }}</td>
                                                <td>
                                                    <div>
                                                        <a class="mx-1" title="Edit" href="#"
                                                            onclick="getEditData(<?= $prod->product_id ?>)"
                                                            data-bs-toggle="modal" data-bs-target="#showModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>

                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $prod->product_id ?>);">
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
                                    {{ $Product->links() }}
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
                                <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form onsubmit="return validateeditname()" method="POST" action="{{ route('product.update') }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="product_id" id="product_id" value="">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        Product Name <span style="color:red;">*</span>
                                        <input type="text" name="product_name" class="form-control"
                                            onblur="validateeditname();" placeholder="Enter Product Name" id="Editcustname" maxlength="100" required>
                                    </div>
                                    <div class="mb-3">
                                        Product Tag <span style="color:red;">*</span>
                                        <input type="text" name="product_tag"  class="form-control"
                                             placeholder="Enter Product Tag" id="EditproductTag" maxlength="100" onblur="return validateedittag()" required>
                                    </div>
                                    <div class="mb-3">
                                        Product Image <span style="color:red;">*</span>
                                        <input type="file" id="editImage" name="product_photo" class="form-control"  accept="image/*" onchange="return validateFile1()">
                                        <input type="hidden" name="hiddenphoto" id="hiddenphoto"  class="form-control">
                                        <p id="error" style="color:red"></p>
                                       
                                        <img src="" width="50px" height="50px" id="editphoto">
                                        
                                        <div id="error"></div>
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
                                    <form id="user-delete-form" method="POST" action="{{ route('product.delete') }}">
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
            var url = "{{ route('product.edit', ':id') }}";
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

                         var obj = JSON.parse(data);
                        if(obj.product_photo == null){

                            var imageUrl="/assets/images/noImage.png";
                        }else{

                            var imageUrl="/Product/"+obj.product_photo;
                        }

                        $('#Editcustname').val(obj.product_name);
                        $('#product_id').val(id);
                        $("#hiddenphoto").val(obj.product_photo);
                        $('#editphoto').attr('src', imageUrl);
                        $('#EditproductTag').val(obj.product_tag);

                    }
                });
            }
        }
        function validateFile() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp'];
            var fileExtension = document.getElementById('product_photo').value.split('.').pop().toLowerCase();
            var isValidFile = false;
            var image = document.getElementById('product_photo').value;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (image != "") {
                if (!isValidFile) 
                {
                    $('#product_photo').val('');
                    alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                }
                return isValidFile;
            }

            return true;
        }

         function validateFile1() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp'];
            var fileExtension = document.getElementById('editImage').value.split('.').pop().toLowerCase();
            var isValidFile = false;
            var image = document.getElementById('editImage').value;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (image != "") {
                if (!isValidFile) 
                {
                    $('#editImage').val('');
                    alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                }
                return isValidFile;
            }

            return true;
        }
        function validateproductname() 
        {
            var product_name = $("#product_name").val();
            var isValid = true;

           $.ajax({
                url: "{{ route('product.validatename') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    product_name: product_name
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        alert('Product Name Already Exists.');
                        $("#cust_cat_name").val('');
                        isValid = false;
                    }
                }
            });
        
            return isValid;
        }
        function validatetag() 
        {
            var product_tag = $("#product_tag").val();
            var isValid = true;

           $.ajax({
                url: "{{ route('product.validatetag') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    product_tag: product_tag
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        alert('Product Tag Already Exists.');
                        $("#cust_cat_name").val('');
                        isValid = false;
                    }
                }
            });
        
            return isValid;
        }
        
        function validateeditname() 
        {
            var product_name = $("#Editcustname").val();
            var product_id = $("#product_id").val();
            var isValid = true;

           $.ajax({
                url: "{{ route('product.validateeditname') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    product_name: product_name,product_id:product_id
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        alert('Product Name Already Exists.');
                        $("#cust_cat_name").val('');
                        isValid = false;
                    }
                }
            });
        
            return isValid;
        }
        function validateedittag() 
        {
            var product_tag = $("#edit_product_tag").val();
            var product_id = $("#product_id").val();
            var isValid = true;

           $.ajax({
                url: "{{ route('product.validateedittag') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    product_tag: product_tag,product_id:product_id
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        alert('Product Name Already Exists.');
                        $("#cust_cat_name").val('');
                        isValid = false;
                    }
                }
            });
        
            return isValid;
        }
    </script>

    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>

@endsection
