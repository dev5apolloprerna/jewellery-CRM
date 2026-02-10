 <!--header area start-->

    <!--offcanvas menu area start-->
    <div class="off_canvars_overlay">

    </div>
    <div class="offcanvas_menu">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="canvas_open">
                        <a href="javascript:void(0)"><i class="icon-menu"></i></a>
                    </div>
                    <div class="offcanvas_menu_wrapper">
                        <div class="canvas_close">
                            <a href="javascript:void(0)"><i class="icon-x"></i></a>
                        </div>

                        <div class="header_social text-right">
                            <ul>
                                <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                <li><a href="#"><i class="ion-social-googleplus-outline"></i></a></li>
                                <li><a href="#"><i class="ion-social-youtube-outline"></i></a></li>
                                <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                            </ul>
                        </div>

                        <div class="call-support">
                            <p><a href="tel:+91 8780418312">+91 8780418312</a> Customer Support</p>
                        </div>
                        <div id="menu" class="text-left ">
                            <ul class="offcanvas_main_menu">
                                <li class="menu-item-has-children active">
                                    <a href="{{route('FrontIndex')}}">Home</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="{{route('FrontAbout')}}">about Us</a>
                                </li>
                                 <li class="menu-item-has-children">
                                    <a href="#">Products</a>
                                    <ul class="sub-menu">
                                        <?php
                                            $Category = App\Models\Category::where('subcategoryid', 0)
                                                ->where(['iStatus' => 1, 'isDelete' => 0])
                                                ->orderBy('categoryname', 'asc')
                                                ->get();
                                          ?>
                                 @foreach ($Category as $category)
                                    <li><a href="{{ route('FrontCategory', $category->slugname) }}">{{ $category->categoryname }}</a></li>
                                    @endforeach

                                    </ul>
                                </li>
                                
                                <li class="menu-item-has-children">
                                    <a href="{{route('FrontContactUs')}}"> Contact Us</a>
                                </li>
                            </ul>
                        </div>
                        <div class="offcanvas_footer">
                            <span><a href="#"><i class="fa fa-envelope-o"></i> info@yourdomain.com</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--offcanvas menu area end-->

    <header>
        <div class="main_header">
            <div class="header_top">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">

                        </div>
                        <div class="col-lg-6">
                            <div class="header_social text-right p-2">
                                <ul>
                                    <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                    <li><a href="#"><i class="ion-social-googleplus-outline"></i></a></li>
                                    <li><a href="#"><i class="ion-social-youtube-outline"></i></a></li>
                                    <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                    <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header_middle">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-3">
                            <div class="logo">
                                <a href="{{route('FrontIndex')}}"><img src="{{asset('assets/frontassets/img/logo/logo.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-6 col-sm-7 col-8">
                            <div class="header_right_info">
                                <div class="search_container mobail_s_none">
                                    <form id="myForm" action="{{ route('searchproduct') }}" method="POST"  novalidate="novalidate" >
                                    @csrf
                                        <div class="hover_category">
                                            <select class="select_option" name="category" id="categori2">
                                            <option selected value="">Select a categories</option>
                                                <?php
                                                  $Category = App\Models\Category::orderBy('categoryname', 'asc')
                                                      ->where(['iStatus' => 1, 'isDelete' => 0,'subcategoryid'=>0])
                                                      ->get();
                                                 ?>
                                                @foreach ($Category as $category)
                                                <option value="{{$category->categoryId}}" @if(isset($categorysearch)) @if($categorysearch == $category->categoryId){{ 'selected' }} @endif  @endif>{{ $category->categoryname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="search_box">
                                        <input type="text" placeholder="Search" name="searchProduct" value="{{ old('search', isset($search) ? $search : '') }}">
                                            <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="header_account_area">
                                    <div class="header_account_list register">
                                         <?php
                                        $session = Session::get('customerid');
                                        $sessionname = Session::get('customername');
                                        if($session){
                                        ?>
                                        <ul>
                                            <li>
                                                <a href="#">
                                                    Welcome, {{ $sessionname }}</a>
                                            </li>
                                            <li><span>/</span></li>
                                            <li>
                                                <a href="{{ route('myaccount') }}"><i class="zmdi zmdi-account-circle"></i>
                                                    My Account</a>
                                            </li>
                                            <li><span>/</span></li>

                                            <li>
                                                <a href="{{ route('logout') }}"><i class="zmdi zmdi-square-right"></i>
                                                    Logout</a>
                                            </li>
                                        </ul>
                                        <?php }else{ ?>
                                        <ul>
                                            <li><a href="{{route('register')}}">Register</a></li>
                                            <li><span>/</span></li>
                                            <li><a href="{{route('FrontLogin')}}">Login</a></li>
                                        </ul>
                                        <?php } ?>
                                    </div>
                                    <div class="header_account_list header_wishlist">
                                        <!-- <a href="wishlist#"><span class="lnr lnr-heart"></span> <span class="item_count">3</span> </a> -->
                                    </div>
                                    <?php 
                                        $count = \Cart::getContent()->count();
                                        $cartItems = \Cart::getContent(); 
                                      ?>
                                    <div class="header_account_list  mini_cart_wrapper">
                                        <a href="javascript:void(0)"><span class="lnr lnr-cart"></span><span
                                                class="item_count">{{ $count }}</span></a>
                                        <!--mini cart-->
                                        <?php 
                                        if($count)  {  ?>
                                        <div class="mini_cart">
                                            <div class="cart_gallery">
                                                <div class="cart_close">
                                                    <div class="cart_text">
                                                        <h3>cart</h3>
                                                    </div>
                                                    <div class="mini_cart_close">
                                                        <a href="javascript:void(0)"><i class="icon-x"></i></a>
                                                    </div>
                                                </div>
                                                @foreach ($cartItems as $item)
                                                <div class="cart_item">
                                                    <div class="cart_img">
                                                        <a href="#">
                                                            <?php if($item->attributes->image){ ?>
                                                                <img src="{{ asset('Product') . '/' . $item->attributes->image }}" alt="Product" >
                                                                <?php }else{ ?>
                                                                <img src="{{ asset('Product') . '/' . $item->attributes->image }}">
                                                                <?php } ?>
                                                            </a>
                                                    </div>
                                                    <div class="cart_info">
                                                        <a href="#"> {{ $item->name }}</a>
                                                        <p>{{ $item->quantity }} x <span>₹ {{ $item->price }}</span></p>
                                                    </div>
                                                    <form action="{{ route('cart.remove') }}" method="POST">
                                                        @csrf
                                                <input type="hidden" value="{{ $item->id }}" name="id">
                                                    <div class="cart_remove">
                                                        <button type="submit"><i class="icon-x"></i></button>
                                                    </div>
                                                    </form>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="mini_cart_table">
                                                <div class="cart_table_border">
                                                   <!--  <div class="cart_total">
                                                        <span>Sub total:</span>
                                                        <span class="price">&#8377;125.00</span>
                                                    </div> -->
                                                    <div class="cart_total mt-10">
                                                        <span>total:</span>
                                                        <span class="price">&#8377; {{ Cart::getTotal() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mini_cart_footer">
                                                <div class="cart_button">
                                                    <a href="{{ route('cart.list') }}"><i class="fa fa-shopping-cart"></i> View cart</a>
                                                </div>
                                                 @if ($count)
                                                <div class="cart_button">
                                                    <a href="{{ route('checkout') }}"><i class="fa fa-sign-in"></i> Checkout</a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                         <?php } ?>
                                        <!--mini cart end-->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="header_bottom sticky-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mobail_s_block">
                            <div class="search_container">
                                <form action="#">
                                    <div class="hover_category">

                                        <select class="select_option" name="select" id="categori3">
                                            <option selected value="">Select a categories</option>
                                            <?php
                                                  $Category = App\Models\Category::orderBy('categoryname', 'asc')
                                                      ->where(['iStatus' => 1, 'isDelete' => 0,'subcategoryid'=>0])
                                                      ->get();
                                                 ?>
                                                @foreach ($Category as $category)
                                                <option value="{{$category->categoryId}}">{{$category->categoryname}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="search_box">
                                        <input placeholder="Search product..." type="text">
                                        <button type="submit"><span class="lnr lnr-magnifier"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">

                        </div>
                        <div class="col-lg-6">
                            <!--main menu start-->
                            <div class="main_menu menu_position">
                                <nav>
                                    <ul>
                                        <li><a class="active" href="{{route('FrontIndex')}}">home<i class="fa fa-angle-down"></i></a>

                                        </li>
                                        <li><a href="{{route('FrontAbout')}}">About Us</a>

                                        </li>

                                        <li><a href="#">Products <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub_menu pages">
                                               <?php
                                            $Category = App\Models\Category::where('subcategoryid', 0)
                                                ->where(['iStatus' => 1, 'isDelete' => 0])
                                                ->orderBy('categoryname', 'asc')
                                                ->get();
                                          ?>
                                            @foreach ($Category as $category)
                                                <li><a href="{{ route('FrontCategory', $category->slugname) }}">{{ $category->categoryname }}</a></li>
                                                @endforeach

                                            </ul>
                                        </li>
                                        <li><a href="{{ route('FrontContactUs') }}"> Contact Us</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <!--main menu end-->
                        </div>
                        <div class="col-lg-3">
                            <div class="call-support">
                                <p><a href="tel:+91 8780418312">+91 8780418312</a> Customer Support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--header area end-->