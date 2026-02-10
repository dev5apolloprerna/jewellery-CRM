@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')

    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Edit Employee</h4>
                            <div class="page-title-right">
                                <a href="{{ route('empMaster.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    
                                    <form action="{{ route('empMaster.update', $employee->emp_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row gy-4">

                                        @include('admin.employee.form', ['employee' => $employee])

                                    </div>
                                        <div class="card-footer mt-2">
                                            <div class="mb-3" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Update</button>
                                            <a class="btn btn-primary float-right mr-3 mb-3 mx-2"
                                                href="{{ route('empMaster.index') }}">Cancel</a>
                                        </div>
                                        </div>
                                    </form>
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
          function validateInput(input) {
    // Remove non-numeric characters
    let value = input.value.replace(/\D/g, '');
    // Limit to 5 digits
    value = value.substring(0, 7);
    // Update the input value
    input.value = value;
  }
         $(document).ready(function()
            {
                  $('#productname').on('input', function(){
                    var originalName = $(this).val();
                    var formattedName = originalName.replace(/\s+/g, '-');
                    $('#slugname').val(formattedName);
                  });
            });
        function validateFile1() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp'];
            var fileExtension = document.getElementById('strPhoto').value.split('.').pop().toLowerCase();
            var isValidFile = false;
            var image = document.getElementById('strPhoto').value;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (image != "") {
                if (!isValidFile) 
                {
                    $('#strPhoto').val('');
                    alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                }
                return isValidFile;
            }

            return true;
        }
        function validateFile() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp'];
            var fileExtension = document.getElementById('strPhoto2').value.split('.').pop().toLowerCase();
            var isValidFile = false;
            var image = document.getElementById('strPhoto2').value;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (image != "") {
                if (!isValidFile) 
                {
                    $('#strPhoto2').val('');
                    alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                }
                return isValidFile;
            }

            return true;
        }
       
    </script>



    {{-- Add photo --}}
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#hello').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#strPhoto").change(function() {
            html =
                '<img src="' + readURL(this) +
                '"   id="hello" width="70px" height = "70px" > ';
            $('#viewimg').html(html);
        });
    </script>

  
@endsection
