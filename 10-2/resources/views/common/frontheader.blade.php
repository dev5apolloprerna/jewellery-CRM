<?php 
  $id=session()->get('user.customerid');
  $name=session()->get('user.name');
?>
<style type="text/css">
  .suggestions {
    list-style: none;
    padding: 0;
    position: absolute;
    top: 45px;
    background: #fff;
    z-index: 999;
    left: 98px;
    text-align: left;
    padding-left: 20px;
/*    width:inherit;*/
width: 378px;
  }

.suggestions li {
    cursor: pointer;
    padding: 5px;
}

.suggestions li:hover {
    background-color: #f0f0f0;
}

</style>
<!-- Header -->
<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-12 col-12">
            <!-- Top Left -->
            <div class="top-left row">
                <ul class="social">
								<li><a href="https://www.youtube.com/channel/UCI-cYMk39CwADINnPO3JeuA" target="_blank"><i class="ti-youtube"></i></a></li>
								<li><a href="https://www.facebook.com/profile.php?id=61557963543284" target="_blank"><i class="ti-facebook"></i></a></li>
								<li><a href="https://twitter.com/Sukti_lakshmi_"  target="_blank"><i class="ti-twitter"></i></a></li>
								<!--<li><a href="https://www.instagram.com/sukti_lakhsmi_corporation/" target="_blank"><i class="fa fa-linkedin"></i></a></li>-->
								<li><a href="https://www.instagram.com/sukti_lakhsmi_corporation/" target="_blank"><i class="ti-instagram"></i></a></li>
							</ul>
              <ul class="list-main">
                <li><a href="tel:8000923771">
                  <i class="ti-headphone-alt"></i> +91 8000923771</a></li>
                <li><a href="mailto:support@sukti.in">
                  <i class="ti-email"></i> support@sukti.in</a></li>
              </ul>
              
            </div>
            <!--/ End Top Left -->
          </div>
          <div class="col-lg-5 col-md-12 col-12">
            <!-- Top Right -->
            <div class="right-content">
              <ul class="list-main">
                <!-- <li><i class="ti-location-pin"></i> Store location</li> -->
                <!--<li><i class="ti-alarm-clock"></i> <a href="{{route('NewFrontProduct')}}">Daily deal</a></li>-->
                @if(isset($id) && (!empty($id)))  
                <!--<li><i class="ti-user"></i> <a href="user-profile.php">My account</a></li>-->
                <li><i class="ti-user"></i> <a href="{{ route('FrontUserprofile') }}">Welcome {{$name}}</a></li>
                <li><i class="ti-power-off"></i><a href="{{ route('FrontLogout') }}">Logout</a></li>
                @else
                <li><i class="ti-power-off"></i><a href="{{ route('FrontLogin') }}">Login</a></li>
                <li><i class="ti-user"></i><a href="{{ route('FrontRegister') }}">Sign up</a></li>
                @endif
              </ul>
            </div>
            <!-- End Top Right -->
          </div>
        </div>
      </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner">
      <div class="container">
        <div class="row">
          <div class="col-lg-2 col-md-2 col-12">
            <!-- Logo -->
            <div class="logo">
              <a href="{{ route('FrontIndex') }}"><img src="{{asset('assets/frontassets/images/logo.png')}}" alt="logo"></a>
            </div>
            <!--/ End Logo -->
            <!-- Search Form -->
            <div class="search-top">
              <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
              <!-- Search Form -->
              <div class="search-top">
                <form id="myForm"  action="{{ route('FrontProduct') }}" method="POST"  novalidate="novalidate" >
                  @csrf
                  <input type="text" id="searchProduct1"  placeholder="Search here..." name="search">
                                        <ul class="suggestions"></ul>

                  <button value="search" type="submit"><i class="ti-search"></i></button>
                </form>
              </div>
              <!--/ End Search Form -->
            </div>
            <!--/ End Search Form -->
            <div class="mobile-nav"></div>
          </div>
          <div class="col-lg-8 col-md-7 col-12">
          <form id="myForm" action="{{ route('FrontProduct') }}" method="POST"  novalidate="novalidate" >
            @csrf
            <div class="search-bar-top">
              <div class="search-bar">
                <select name="category">
                <option value="" selected="">All</option>
                   <?php
                      $Category = App\Models\Category::orderBy('categoryname', 'asc')
                          ->where(['iStatus' => 1, 'isDelete' => 0,'subcategoryid'=>0])
                          ->get();
                     ?>
                    @foreach ($Category as $category)
                    <option value="{{$category->categoryId}}" @if(isset($categorysearch)) @if($categorysearch == $category->categoryId){{ 'selected' }} @endif  @endif>{{ $category->categoryname }}</option>
                    @endforeach
                </select>
                  <input placeholder="Search Products Here....." id="searchProduct2" type="search" name="searchProduct" value="{{ old('search', isset($search) ? $search : '') }}">
                                        <ul class="suggestions"></ul>

                  <button class="btnn" type="submit"><i class="ti-search"></i></button>
              </div>
            </div>
           </form>
          </div>
          <div class="col-lg-2 col-md-3 col-12">
            <div class="right-bar">
              <!-- Search Form -->
               @if(isset($id) && (!empty($id))) 
              <div class="sinlge-bar">
                <a href="{{ route('FrontWishlist') }}" class="single-icon"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
              </div>
              <div class="sinlge-bar">
                <a href="{{ route('FrontUserprofile') }}" class="single-icon"><i class="fa fa-user-circle-o"
                    aria-hidden="true"></i></a>
              </div>
              @endif
              <?php 
                $count = \Cart::getContent()->count();
                $cartItems = \Cart::getContent(); 
              ?>
               
              <div class="sinlge-bar shopping">
                <a href="#" class="single-icon"><i class="ti-bag"></i> <span
                    class="total-count">{{ $count }}</span></a>
                     <?php 
                   if($count)
                {  
                ?>
                <!-- Shopping Item -->
                <div class="shopping-item">
                  <div class="dropdown-cart-header">
                    <span>{{ $count }} &nbsp; Items</span>
                    <a href="{{route('FrontCart')}}">View Cart</a>
                  </div>
                 
                  <ul class="shopping-list">

                    @foreach ($cartItems as $item)


                    <li>
                        <form action="{{ route('cartRemove') }}" method="POST">
                            @csrf
                              <input type="hidden" value="{{ $item->id }}" name="id">
                              <button type="submit">
                      <a class="remove" title="Remove this item">
                          <i class="fa fa-remove"></i></a>
                          </button></form>
                      <a class="cart-img" href="#">
                          <?php if($item->attributes->image){ ?>
                            <img src="{{ $item->attributes->image }}" alt="Product" >
                            <?php }else{ ?>
                            <img src="{{ asset('assets/images/noimage.png') }}">
                            <?php } ?>
                        </a>
                      <h4><a href="product-detail.php">{{ $item->name }}</a></h4>
                      <p class="quantity">{{ $item->quantity }} x - <span class="amount">&#x20B9;  {{ $item->price }}</span></p>
                    </li>
                    @endforeach
                  </ul>
                  <div class="bottom">
                    <div class="total">
                      <span>Total</span>
                      <span class="total-amount">&#x20B9;  {{ Cart::getSubTotal();  }}</span>
                    </div>
                    <a href="{{ route('FrontCheckout') }}" class="btn animate">Checkout</a>
                  </div>
                </div>
                <!--/ End Shopping Item -->
                <?php } ?>
              </div>
            
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Header Inner -->
    <div class="header-inner">
      <div class="container">
        <div class="cat-nav-head">
          <div class="row">
            <div class="col-lg-3">
              <div class="all-category">
                <h3 class="cat-heading"><i class="fa fa-bars" aria-hidden="true"></i>CATEGORIES</h3>
                <ul class="main-category">
                  <?php
                    $Category = App\Models\Category::orderBy('categoryname', 'asc')
                        ->where(['iStatus' => 1, 'isDelete' => 0,'subcategoryid'=>0])
                        ->get();
                    // $subcategory=
                    ?>
                    @foreach ($Category as $category)
                  <li class="main-mega">
                    <a href="{{ route('Frontcategory',[$category->slugname]) }}">{{ $category->categoryname }} 
                      <i class="fa fa-angle-right"
                        aria-hidden="true"></i>
                      </a>
                      <?php
                      $SubCategory = App\Models\Category::orderBy('categoryname', 'asc')
                          ->where(['iStatus' => 1, 'isDelete' => 0, 'subcategoryid' => $category->categoryId])
                          ->get();
                      ?>
                       <?php  if(sizeof($SubCategory) != 0)
                       { ?>
                        <ul class="sub-category">
                          @foreach ($SubCategory as $subcategory)
                              <li><a href="{{ route('FrontProduct',[$subcategory->slugname]) }}">{{ $subcategory->categoryname }}</a></li>
                          @endforeach
                        </ul>
                       <?php } ?>
                  </li>
                @endforeach
                </ul>
              </div>
            </div>
            <div class="col-lg-9 col-12">
              <div class="menu-area">
                <!-- Main Menu -->
                <nav class="navbar navbar-expand-lg">
                  <div class="navbar-collapse">
                    <div class="nav-inner">
                      <ul class="nav main-menu menu navbar-nav">
                        <li  class="@if (request()->routeIs('FrontIndex')) {{ 'active' }} @endif"><a href="{{ route('FrontIndex') }}">Home</a></li>
                        <li class="@if (request()->routeIs('NewFrontProduct')) {{ 'active' }} @endif"> <a href="{{route('NewFrontProduct')}}">New Arrivals</a></li>
                        <!-- <li ><a href="productlisting.php">About Us</a></li> -->
                        <li class="@if (request()->routeIs('FrontProduct')) {{ 'active' }} @endif"><a href="#">Product<i class="ti-angle-down"></i></a>
                      

                       <ul class="dropdown">
                        <?php
                          $Product = App\Models\Product::select('product.*','category.iStatus')->orderBy('ProductId', 'desc')
                              ->where(['product.iStatus' => 1, 'product.isDelete' => 0,'category.iStatus'=>1])->join('category', 'product.categoryId', '=', 'category.categoryId')->skip(0)->take(10)->get();
                         ?>
                        @foreach ($Product as $pval)
                        <li><a href="{{ route('FrontProductdetail',[$pval->slugname]) }}">{{ $pval->productname }}</a></li>
                        @endforeach
                       </ul>
                    </li>
                        
                        <li class="@if (request()->routeIs('FrontAbout')) {{ 'active' }} @endif"><a href="{{ route('FrontAbout') }}">About Us</a></li>
                       <li class="@if (request()->routeIs('FrontContact')) {{ 'active' }} @endif"><a href="{{ route('FrontContact') }}">Approch Us</a></li>
                         <li class="@if (request()->routeIs('FrontCareer')) {{ 'active' }} @endif"><a href="{{ route('FrontCareer') }}">Career</a></li>
                      </ul>
                    </div>
                  </div>
                </nav>
                <!--/ End Main Menu -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ End Header Inner -->
  </header>
  <!--/ End Header -->
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


  <script>
    $(document).on('click', '.suggestions li', function() {
    var suggestion = $(this).text();
    $('#searchProduct1 , #searchProduct2').val(suggestion);
    $('.suggestions').empty();
});

    $(document).ready(function() 
    {
        $("#searchProduct1 , #searchProduct2").keyup(function()
        {
        var query = $(this).val();

        $.ajax({
            url: '/autosuggest',
            type: 'GET',
            data: {query: query},
            success: function(response) {
                var suggestions = response;
                $('.suggestions').empty();
                $.each(suggestions, function(index, suggestion) {
                    $('.suggestions').append('<li>' + suggestion.productname + '</li>');
                });
            }
        });
    });
});

  </script>