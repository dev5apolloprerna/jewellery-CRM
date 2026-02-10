@extends('layouts.front')
@section('title', 'Success')
@section('content')

<div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                       <h1>Order Success</h1>
                        <ul>
                            <li><a href="{{route('FrontIndex')}}">home</a></li>
                            <li>Order Successfully</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->

    <!-- Shoping Cart -->
    <section class="about_section"> 
       <div class="container">
            <div class="row">
                <div class="col-md-12 " style="text-align: center;">
                    <h1>Thank you!</h1>    
                </div>
                <div class="col-md-12 mb-5" style="text-align: center;">
		            <p><br />Thank you for shopping with us. <br />  We will be shipping your order to you soon.</p>
		        </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
