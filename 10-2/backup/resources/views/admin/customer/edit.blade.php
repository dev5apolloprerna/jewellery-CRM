@extends('layouts.app')

@section('title', 'Edit Customer')

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
                            <h4 class="mb-sm-0">Edit Customer</h4>
                            <div class="page-title-right">
                                <a href="{{ route('customer.index') }}"
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
                                    
                                    <form action="{{ route('customer.update', $customer->customer_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row gy-4">

                                        @include('admin.customer.form', ['customer' => $customer])

                                    </div>
                                        <div class="card-footer mt-2">
                                            <div class="mb-3" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Update</button>
                                            <a class="btn btn-primary float-right mr-3 mb-3 mx-2"
                                                href="{{ route('customer.index') }}">Cancel</a>
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
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="alertModalLabel">Customer Already Exists</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="alertModalBody">
        <!-- Message will be inserted here dynamically -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')

    <script>
             function validatedata() { 
        var customer_phone = $("#customer_phone").val();
        var customer_id = $("#customer_id").val();
        var url = "{{ route('customer.validateEditCustomer') }}";
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                customer_phone: customer_phone,customer_id:customer_id
            },
            success: function(data) {
                console.log(data);
                if (data == 1) {
                        $("#customer_phone").val('');

                       // alert('Service Name Already Exists.');
                        $("#alertModalBody").text('The Customer You Are Trying to Create Is Already Exists.');
                        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                        alertModal.show();
                        isValid = false;
                }
            }
        })
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
