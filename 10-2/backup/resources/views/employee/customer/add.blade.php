@extends('layouts.app')

@section('title', 'Add Customer')

@section('content')


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add Customer</h4>
                            <div class="page-title-right">
                                <a href="{{ route('EMPcustomer.index') }}"
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

                                          <form action="{{ route('EMPcustomer.store') }}" method="POST">
                                                @csrf
                                        <div class="row gy-4">

                                                @include('employee.customer.form', ['customer' => null])
                                        </div>
                                        <div class="card-footer mt-2">
                                            <div class="mb-3" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                            <button type="reset" class="btn btn-primary float-right mr-3 mb-3 mx-2">Clear</button>
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
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>

    <script>
        
          function validateInput(input) 
          {
                let value = input.value.replace(/\D/g, '');
                value = value.substring(0, 7);
                input.value = value;
         }
            function validatedata() 
        {
             var customer_phone = $("#customer_phone").val();
            var isValid = true;
    
           $.ajax({
                url: "{{ route('EMPcustomer.validateCustomer') }}",
                type: 'GET',
                async: false, // Make it synchronous to block form submission
                data: {
                    customer_phone: customer_phone
                },
                success: function(data) {
                    if (data == 1) 
                    {
                        $("#customer_phone").val('');

                       // alert('Service Name Already Exists.');
                        $("#alertModalBody").text('The Customer You Are Trying to Create Is Already Exists.');
                        var alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                        alertModal.show();
                        isValid = false;
                    }
                }
            });
        
            return isValid;
        }
        function validateFile() {
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
                    $('#strPhoto2').val('');
                    alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                }
                return isValidFile;
            }

            return true;
        }
        </script>
@endsection
