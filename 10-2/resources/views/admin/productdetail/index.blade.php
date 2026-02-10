@extends('layouts.app')

@section('title', 'Product Photo List')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add Product Photos</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <form onsubmit="return validateFile()" action="{{ route('productdetail.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="productid" value="{{ $id }}">
                                        <div class="row gy-4" style="align-items: end;">
                                            <div class="col-lg-3 col-md-3">
                                                <div>
                                                    <span style="color:red;">*</span>Photo
                                                    <input type="file" class="form-control" name="strphoto[]" multiple
                                                        id="photovalidate" value="{{ old('strphoto') }}" autocomplete="off"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-md-3">
                                                <div id="viewimg">

                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-3">
                                                <button type="submit" class="btn btn-success btn-user float-right">Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <form role="form" method="POST" action="" name="frmparameter" id="frmparameter"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('post')
                                    <div class="form-group row">
                                        <div class="col-md-1">
                                            <input style="display: inline-block;margin-left: 17px;width: 141px;"
                                                id="Btnmybtn" class="btn btn-xs btn-danger mt-2 " onclick="multiDelete()"
                                                value="Delete Selected" name="submit" />
                                        </div>
                                    </div>
                                    <hr />

                                    <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">
                                                    <input type="checkbox" onclick="javascript:CheckAll();"
                                                        id="check_listall" class="md-check" value="">
                                                    <label for="check_listall">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                    </label>
                                                </th>
                                                <th width="2%">No</th>
                                                <th width="50%">Photo</th>
                                                <th width="50%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($Product as $product)
                                                <tr>
                                                    <td data-label="id">
                                                        <input type="checkbox" name="check_list[]"
                                                            id="check_list<?php echo $i; ?>" class="md-check"
                                                            value="<?php echo $product->productphotosid; ?>">
                                                        <label for="check_list<?php echo $i; ?>">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span></label>
                                                    </td>
                                                    <td>{{ $i + $Product->perPage() * ($Product->currentPage() - 1) }}
                                                    <td class="text-center">
                                                        <img src="{{ asset('ProductPhoto') . '/' . $product->strphoto }}"
                                                            style="width: 50px;height: 50px;">
                                                    </td>

                                                    <td style="display: flex">
                                                        <a class="mx-1" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $product->productphotosid ?>);">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>


                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $Product->appends(request()->except('page'))->links() }}
                                    </div>

                                </form>
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
                        <a class="btn btn-danger" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes,
                            Delete It!
                        </a>
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <form id="user-delete-form" method="POST"
                            action="{{ route('productdetail.delete', $product->id ?? '') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="productphotosid" id="deleteid" value="">

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
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>
    <script>
        function validateFile() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp', ""];
            var fileExtension = document.getElementById('photovalidate').value.split('.').pop().toLowerCase();
            var isValidFile = false;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }

            if (!isValidFile) {
                alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
            }

            return isValidFile;
        }
    </script>

    <script>
        function CheckAll() {
            if ($('#check_listall').is(":checked")) {
                $('input[type=checkbox]').each(function() {
                    $(this).prop('checked', true);
                });
            } else {
                $('input[type=checkbox]').each(function() {
                    $(this).prop('checked', false);
                });
            }
        }
    </script>

    <script>
        function multiDelete() {
            //alert('hello');
            if (confirm('Are You Sure You want to Delete?')) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('productdetail.deleteselected') }}",
                    data: $('#frmparameter').serialize(),
                    success: function(response) {
                        //alert(response);
                        if (response == 1) {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Product Photos Deleted Sucessfully.');
                            window.location.href = '';
                        } else {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Something want wrong,Please Try Again.');
                            window.location.href = '';
                        }
                        //return false;
                    }
                });
            }
            //});
            //return false;
        }
    </script>
@endsection
