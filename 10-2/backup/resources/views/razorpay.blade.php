@extends('layouts.front')
@section('title', 'Payment')
@section('content')


<style>
    .ship-head{
            padding: 6px;
    background: #840d19;
    color: white;
    font-size: 16px;
    text-transform: uppercase;

    }
    
    .ship-inp{
        border:none;
        margin-bottom:0px;
        width:100%;
    }
    
    .b-none{
        border:none !important;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="{{ asset('/front/css/main.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('front/css/bootstrap-4.5.0.min.css')}}" rel="stylesheet">
    


<!--<body>-->
<!--    <div class="container">-->
<!--        <br>-->
<!--        <div class="text-center">-->
<!--            <img src="{{ asset('/assets/frontimages/icons/Kwality.png')}}" class="main-logo" width="128" alt="Kwality" title="Kwality">-->
<!--        </div>-->
<!--        <hr>-->
        <section class="bg-img1 txt-center p-lr-15 p-tb-92" 
    	    style="background-image: url({{ asset('assets/frontimages/catagory/SHOP.jpg') }});">
    		<h2 class="ltext-105 cl0 txt-center">
    		   Payment
    		</h2>
    	</section>	
        <div class="row">


            <!--<div class="col-md-4"></div>-->
            <div class="col-md-12" style="margin-top: 15px;">
                
                <table width="40%" class="mx-auto" height="100" border='1' align="center">

                    <tr>
                        <td class="ship-head" colspan="2">Shipping information :</td>
                    </tr>
                    <tr>
                        <td style="width: 30%;">Shipping Name :</td>
                        <td><input class="ship-inp" type="text" name="billing_name" value="{{ $Order['shipping_cutomerName'] }}" /></td>
                    </tr>
                    <tr>
                        <td>Shipping Address :</td>
                        <td>
                            <?php $address = trim($Order['shiiping_address1']); ?>
                            <div class="ship-inp" name="full_address" id="full_address" cols="30" rows="7">
                                {{ $address . ',' . $Order['shipping_city'] . ',' . $Order['shiiping_state'] . ',' . $Order['shipping_pincode'] }}
                            </div>
                        </td>
                    </tr>
        
                    <tr>
                        <td>Shipping Tel :</td>
                        <td><input class="ship-inp" type="text" name="billing_tel" value="{{ $Order['shipping_mobile'] }}" /></td>
                    </tr>
        
                    <tr>
                        <td>Shipping Email :</td>
                        <td><input class="ship-inp" type="text" name="billing_email" value="{{ $Order['shipping_email'] }}" /></td>
                    </tr>
        
                    <tr>
                        <td>Amount :</td>
                        <td><input class="ship-inp" type="text" name="amount" value="{{ $Order['netAmount'] }}" readonly /></td>
                    </tr>
                    <tr>
                        <td>Currency :</td>
                        <td><input class="ship-inp" type="text" name="currency" value="INR" /></td>
                    </tr>
                    
                     
                </table>
                <table  width="40%" class="mx-auto  mb-5" height="100" border='1' align="center">
                     <!--<tr class="">
                        <td><a href="" class="pay_now flex-c-m stext-101 cl0 size-116 bg3  hov-btn3 p-lr-15 trans-04 pointer mb-0" data-amount="{{ $Order['netAmount'] }}" data-mobile="{{ $Order['shipping_mobile'] }}" data-email="{{ $Order['shipping_email'] }}" data-profile-id="{{ $Order['order_id'] }}" data-order-id="{{$orderId}}">Pay Now</a></td>
                        <td><a class="flex-c-m stext-101 cl0 size-116 bg3  hov-btn3 p-lr-15 trans-04 pointer" href="{{ route('FrontIndex') }}">Cancel</a></td>
                    </tr>
                     --> 
                </table>
            </div>    
                 <input type="hidden" id="data-key" value="{{ env('RAZORPAY_KEY') }}">
                        <input type="hidden" id="data-amount" value="{{ $Order['netAmount']; }}">
                        <input type="hidden" id="data-mobile" value="{{ $Order['shipping_mobile']; }}">
                        <input type="hidden" id="data-email" value="{{ $Order['shipping_email']; }}">
                        <input type="hidden" id="data-profile-id" value="{{ $Order['customerid']; }}">
                        <input type="hidden" id="data-description" value="Rozerpay">
                        <input type="hidden" id="data-order-id" value="{{$orderId}}">
                        <input type="hidden" id="data-image" value="https://www.itsolutionstuff.com/frontTheme/images/logo.png">
            </div>
            <!-- col // -->
        </div>
        <!-- row.// -->
    <!--</div>-->
    <!--container.//-->
    <!--<br><br><br>-->
@endsection
    @section('scripts')
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>

    $(document).ready(function() 
    {
    // Assuming your form has an id of "myForm"
    /*$('#myForm').submit(function(e) 
    {*/
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $('body').on('click', '.pay_now', function(e) {
            var totalAmount = $("#data-amount").val();
            totalAmount = totalAmount * 100;
            var order_id = $("#data-order-id").val();
            var profile_id = $("#data-profile-id").val();
            var mobile = $("#data-mobile").val();
            var email = $("#data-email").val();
            var url = "{{route('razprpay.success')}}";
            var options = {
                "key": "{{ env('RAZORPAY_KEY') }}",
                "amount": totalAmount, // 2000 paise = INR 20 order generate ?yes
                "currency": "INR",
                "mobile": mobile,
                "email": email,
                "order_id": order_id,
                "handler": function(response) {
                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_signature : response.razorpay_signature,
                            razorpay_order_id : response.razorpay_order_id,
                            orderId : order_id,
                            profile_id : profile_id
                        },
                        success: function(msg) {
                            if(msg == 1){
                                window.location.href = "{{route('razorpay.thankyou')}}";
                            }
                            else{
                                window.location.href = "{{route('razorpay.RazorFail')}}";
                            }

                        },
                        error: function (jqXHR, exception) {
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            alert(msg);

                        },
                    });
                },
                "prefill": {
                    "contact": mobile,
                    "email": email,
                },
                "theme": {
                    "color": "#528FF0"
                },
                "modal": {
                        "ondismiss": function() 
                        {
                          var url = "{{route('razorpay.cancel')}}"; 
                                   window.location.href = url;

                        }
                    }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        });
    // });
        /*document.getElementsClass('buy_plan1').onclick = function(e){
        rzp1.open();
        e.preventDefault();
        }*/
    </script>
@endsection