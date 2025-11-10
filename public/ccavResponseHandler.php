<?php 
include('Crypto.php');
ob_start();
error_reporting(E_ALL);
include('common.php');
$connect = new connect();
include('PHPMailer-master/PHPMailerAutoload.php');
// include('PHPMailer-master/src/PHPMailer.php');

?>
<!DOCTYPE html>
<html lang="en">


<?php header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
?>
<head>
    <?php
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    ?>
    <title>MB Herbals</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="assets/frontimages/icons/MB.png" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontvendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="assets/frontfont/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="assets/frontfont/iconic/css/material-design-iconic-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontfont/linearicons-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontvendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontvendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontvendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontvendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="assets/frontvendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontvendor/slick/slick.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontvendor/MagnificPopup/magnific-popup.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="assets/frontvendor/perfect-scrollbar/perfect-scrollbar.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="assets/frontcss/util.css">
    <link rel="stylesheet" type="text/css" href="assets/frontcss/main.css">
    <link rel="stylesheet" href="assets/frontcss/responsive.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    
    <style>

    h1 {
        font-size: 42px; 
    }
    
    #particles-js {
        position: absolute;
        height: 100vh;
        z-index: 2;
        width: 100%;
    }
    .thank-you-container {
        margin: 0 auto;
        max-width: 650px;
        padding: 0 4em;
    }
    .thank-you-box {
        background: #B6FFCD;
        background-color: #633718;
        color: #fff;
        padding: 1em 0.5em;
        border-radius: 5px;
        text-align: center;
        padding:50px 25px;
    }
    .return-black {
       margin: 20px 0;
       text-align: center;
       width: 100%;
       float: left;
      color: black;
    }
    
    
    .mt-50{
        margin-top:50px;
    }
    </style>
    
</head>

<body id="page-top">

<header>
    <!-- Header desktop -->
    <div class="container-menu-desktop">
        <!-- Topbar -->
        <div class="top-bar">
            <div class="content-topbar flex-sb-m h-full container">
                <div class="left-top-bar">
                    Free shipping for standard order over ₹1000
                </div>

                <div class="right-top-bar flex-w h-full">
                    <a href="#" class="flex-c-m trans-04 p-lr-25">
                        Help & FAQs
                    </a>

                    <a href="#" class="flex-c-m trans-04 p-lr-25">
                        My Account
                    </a>
                    
                    <a href="https://www.mbherbals.com/Front/Login" class="flex-c-m trans-04 p-lr-25">
                        LOGIN
                    </a>
                    
                </div>
            </div>
        </div>

        <div class="wrap-menu-desktop">
            <nav class="limiter-menu-desktop container">

                <!-- Logo desktop -->
                <a href="https://www.mbherbals.com/" class="logo">
                    <img src="assets/frontimages/icons/MB.png" alt="IMG-LOGO">
                </a>

                <!-- Menu desktop -->
                <div class="menu-desktop">
                    <ul class="main-menu">
                        <li class="">
                            <a href="https://www.mbherbals.com/">Home</a>
                        </li>

                        <li class="label1" data-label1="hot">
                            <a href="https://www.mbherbals.com/Features">Features</a>
                        </li>

                        <li>
                            <a href="https://www.mbherbals.com/shop">shop</a>
                        </li>

                        <li>
                            <a href="https://www.mbherbals.com/aboutus">About us</a>
                        </li>

                        <li>
                            <a href="https://www.mbherbals.com/contact-us">Contact</a>
                        </li>
                    </ul>
                </div>

                <!-- Icon header -->
                <div class="wrap-icon-header flex-w flex-r-m main-menu">
                    <li>
                        <a href="#" class="fs-16"><i class="zmdi zmdi-account"></i></a>
                       
                        <ul class="sub-menu">
                            <li><a href="https://www.mbherbals.com/Front/Login">Login</a></li>
                            <li><a href="https://www.mbherbals.com/Register">Register</a></li>
                        </ul>
                       
                    </li>
                    
                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
                        <i class="zmdi zmdi-search"></i>
                    </div>

                    <div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart"
                        data-notify="0">
                        <i class="zmdi zmdi-shopping-cart"></i>
                    </div>

                </div>
            </nav>
        </div>
    </div>

    <!-- Header Mobile -->
    <div class="wrap-header-mobile">
        <!-- Logo moblie -->
        <div class="logo-mobile">
            <a href="https://www.mbherbals.com/"><img src="assets/frontimages/icons/MB.png"
                    alt="IMG-LOGO"></a>
        </div>

        <!-- Icon header -->
        <div class="wrap-icon-header flex-w flex-r-m m-r-15 main-menu">


            <li>
                <a href="#" class="fs-16"><i class="zmdi zmdi-account"></i></a>
                        <ul class="sub-menu">
                            <li><a href="https://www.mbherbals.com/Front/Login">Login</a></li>
                            <li><a href="https://www.mbherbals.com/Register">Register</a></li>
                        </ul>
                
            </li>


            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
                <i class="zmdi zmdi-search"></i>
            </div>

            <div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"
                data-notify="0">
                <i class="zmdi zmdi-shopping-cart"></i>
            </div>

            <!--<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart"-->
            <!--    data-notify="2">-->
            <!--    <i class="fa fa-heart"></i>-->
            <!--</div>-->


        </div>

        <!-- Button show menu -->
        <div class="btn-show-menu-mobile hamburger hamburger--squeeze">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </div>
    </div>


    <!-- Menu Mobile -->
    <div class="menu-mobile">
        <ul class="topbar-mobile">
            <li>
                <div class="left-top-bar">
                    Free shipping for standard order over ₹1000
                </div>
            </li>

            <li>
                <div class="right-top-bar flex-w h-full">
                    <a href="https://www.mbherbals.com/Front/Login" class="flex-c-m p-lr-10 trans-04">
                        LOGIN
                    </a>
                    
                </div>
            </li>
        </ul>

        <ul class="main-menu-m">
            <li>
                <a href="https://www.mbherbals.com/">Home</a>
                <span class="arrow-main-menu-m">

                </span>
            </li>
            <li>
                <a href="https://www.mbherbals.com/Features" class="label1 rs1" data-label1="hot">Features</a>
            </li>
            <li>
                <a href="https://www.mbherbals.com/shop">Shop</a>
            </li>
            <!--<li>-->
            <!--    <a href="https://www.mbherbals.com/shop">Blog</a>-->
            <!--</li>-->

            <li>
                <a href="https://www.mbherbals.com/aboutus">About</a>
            </li>

            <li>
                <a href="https://www.mbherbals.com/contact-us">Contact</a>
            </li>
        </ul>
    </div>

    <!-- Modal Search -->
    <div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
        <div class="container-search-header">
            <button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
                <img src="assets/frontimages/icons/icon-close2.png" alt="CLOSE">
            </button>

            <form class="wrap-search-header flex-w p-l-15">
                <button class="flex-c-m trans-04">
                    <i class="zmdi zmdi-search"></i>
                </button>
                <input class="plh3" type="text" name="search" placeholder="Search...">
            </form>
        </div>
    </div>

    <!-- Cart -->
    <div class="wrap-header-cart js-panel-cart">
        <div class="s-full js-hide-cart"></div>

        <div class="header-cart flex-col-l p-l-65 p-r-25">
            <div class="header-cart-title flex-w flex-sb-m p-b-8">
                <span class="mtext-103 cl2">
                    Your Cart
                </span>
                <div class="fs-35 lh-10 cl2 p-lr-5 pointer hov-cl1 trans-04 js-hide-cart">
                    <i class="zmdi zmdi-close"></i>
                </div>
            </div>

            <div class="header-cart-content flex-w js-pscroll">
                
                <div class="w-full">
                    <div class="header-cart-total w-full p-tb-40">
                        Total: 0
                    </div>

                    <div class="header-cart-buttons flex-w w-full">
                        <a href="{{ route('cart.list') }}"
                            class="flex-c-m stext-101 cl0 size-107 bg3 bor2 hov-btn3 p-lr-15 trans-04 m-r-8 m-b-10">
                            View Cart
                        </a>

                    </div>
                    <div>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button class="px-6 py-2 text-red-800 bg-red-300 new-btn">Remove All Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>

 <!--   <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('assets/frontimages/catagory/SHOP.jpg');">-->
	<!--	<h1 class="ltext-105 cl0 txt-center">-->
	<!--		Thank You-->
	<!--	</h1>-->
	<!--</section>-->

    <!-- Shoping Cart -->
    <div class="bg0 p-t-75 mt-50">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-10 col-xl-12 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">

                        <div class="">
                                <div id="particles-js"></div>
                                    <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
                            <div class="thank-you-container">
                            <div class="thank-you-box">
                              
                              <!-- <p class="lead">for contacting me</p> -->
                              <!--<p>Your Order is Reecived. Thanks for shopping with us.</p>-->
                            
                              <!--<a class="return-black" href="#">Continue shopping</a>-->
                            
<?php

	error_reporting(E_ALL);
	
	$workingKey='B90C6621BA3506A295BF693949C28214';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	echo "<center>";
    $name = "";
    $address = "";
    $state = "";
    $city = "";
    $pincode = "";
    $order_id = "";
    $delivery_tel = "";
    $amount = 0;
    $billing_email = "";
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==0)	$order_id=$information[1];
		if($i==3)	$order_status=$information[1];
		if($i==19)	$name=$information[1];
		if($i==20)	$address=$information[1];
		if($i==22)	$state=$information[1];
		if($i==21)	$city=$information[1];
		if($i==23)	$pincode=$information[1];
		if($i==10)	$amount=$information[1];
		if($i==25)	$delivery_tel=$information[1];
		if($i==18)	$billing_email=$information[1];
	}
    // echo $billing_email;exit;
    // echo "Name : ". $name."<br />";
    // echo "<br /> Address : ".$address;
    // echo "<br /> State :".$state;
    // echo "<br /> City :".$city;
    // echo "<br /> Pincode : ".$pincode;
    // echo "<br /> Order Id".$order_id;
    // echo "<br /> Mobile: ".$delivery_tel;
    // echo "<br /> amount: ".$amount;
	if($order_status==="Success")
	{
	    echo "<h1>Thank you!</h1>";
		echo "<p><br />Thank you for shopping with us. <br /> Your transaction is successful. We will be shipping your order to you soon.</p>";
	    
	    $order = mysqli_fetch_assoc(mysqli_query($dbconn,"select * from `order` where order_id='".$order_id."'"));
		
		$file = file_get_contents("mailers/checkoutmail.html", "r");
// 		$root = $_SERVER['DOCUMENT_ROOT'];
//         $file = file_get_contents($root . '/mailers/checkoutmail.html', 'r');

        $file = str_replace('#name', $name, $file);
        $file = str_replace('#address', $address, $file);
        $file = str_replace('#state', $state, $file);
        $file = str_replace('#city', $city, $file);
        $file = str_replace('#pincode', $pincode, $file);
        
        $orderDetails = mysqli_query($dbconn,"SELECT *,(select productphotos.strphoto from productphotos where productphotos.productid=product.productId and productphotos.strphoto order by productphotos.productphotosid limit 1) as strphoto FROM `orderdetail` inner join product on orderdetail.productId=product.productId where orderID='".$order_id."'");
        $tableProductTr = "";
        if(mysqli_num_rows($orderDetails) > 0){
            $i = 1;
            while($orderDetail = mysqli_fetch_assoc($orderDetails)){
                $tableProductTr .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$orderDetail['productname'].'</td>
                    <td><img width="48" height="48" src=https://mbherbals.com/Product/'.$orderDetail['strphoto'].'></td>
                    <td>'.$orderDetail['weight'].'</td>
                    <td>'.$orderDetail['quantity'].'</td>
                    <td>'.$orderDetail['amount'].'</td>
                </tr>';
                $i++;
            }
        }
        $file = str_replace('#tableProductTr', $tableProductTr, $file);
        $file = str_replace('#amount', $order['amount'], $file);
        $file = str_replace('#shipping_Charges', $order['shipping_Charges'], $file);
        $file = str_replace('#netAmount', $order['netAmount'], $file);
        
        $to = $order['shipping_email'];
        $subject = "MB Herbals Order";
                
        $message = $file;
        
        $header = "From:info@mbherbals.com\r\n";
        //$header .= "Cc:afgh@somedomain.com \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";
        
        $retval = mail($to,$subject,$message,$header);
        
        $setting = mysqli_fetch_assoc(mysqli_query($dbconn,"select email from `setting` limit 1 "));
        $toMail = $setting->email; // "shahkrunal83@gmail.com";//
        $retval = mail($toMail,$subject,$message,$header);
        // if($retval == true) {
        //     echo "Message sent successfully...";
        // }else {
        //     echo "Message could not be sent...";
        // }
        
		
	}
	else if($order_status==="Aborted")
	{
	    echo "<h1>Opps!</h1>";
		echo "<p><br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail</p>";
	
	}
	else if($order_status==="Failure")
	{
	    echo "<h1>Opps!</h1>";
		echo "<p><br>Thank you for shopping with us.However,the transaction has been declined.</p>";
	}
	else
	{
	    echo "<h1>Opps!</h1>";
		echo "<p><br>Security Error. Illegal access detected.</p>";
	
	}

	echo "<br><br>";

// 	echo "<table cellspacing=4 cellpadding=4  class='table-shopping-cart'>";
// 	for($i = 0; $i < $dataSize; $i++) 
// 	{
// 		$information=explode('=',$decryptValues[$i]);
// 		if($information[1] != null){
//     	    echo '<tr><td>'.$i.'</td><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
// 		}
// 	}
//     echo "</table><br>";
//echo "<a href='https://www.mbherbals.com/'><button class='flex-c-m stext-101 cl2 size-119 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-10'>Shop Now</button></a>";
	echo "</center>";
?>
</div>
                            </div>
</div>


                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Footer -->
<footer class="bg3 p-t-75 p-b-32">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-4 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Quick Link
                </h4>

                <ul>
                    <li class="p-b-10">
                        <a href="https://www.mbherbals.com/" class="stext-107 cl7 hov-cl1 trans-04">
                            Home
                        </a>
                    </li>
                    
                     <li class="p-b-10">
                        <a href="https://www.mbherbals.com/aboutus" class="stext-107 cl7 hov-cl1 trans-04">
                            About Us
                        </a>
                    </li>

                      <li class="p-b-10">
                        <a href="https://www.mbherbals.com/Features" class="stext-107 cl7 hov-cl1 trans-04">
                            Features
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="https://www.mbherbals.com/shop" class="stext-107 cl7 hov-cl1 trans-04">
                            Shop
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="https://www.mbherbals.com/contact-us" class="stext-107 cl7 hov-cl1 trans-04">
                            Contact
                        </a>
                    </li> 
                </ul>
            </div>

            <div class="col-sm-6 col-lg-4 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Other Link
                </h4>

                <ul>
                    <li class="p-b-10">
                        <a href="https://www.mbherbals.com/Privacy-Policy" class="stext-107 cl7 hov-cl1 trans-04">
                            Privacy Policy
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="https://www.mbherbals.com/Term-&-Condition" class="stext-107 cl7 hov-cl1 trans-04">
                            Term & Condition
                        </a>
                    </li>

                    <li class="p-b-10">
                        <a href="https://www.mbherbals.com/Refund-Policy" class="stext-107 cl7 hov-cl1 trans-04">
                            Refund Policy
                        </a>
                    </li>

                </ul>
            </div>

            <div class="col-sm-6 col-lg-4 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    GET IN TOUCH
                </h4>

                <p class="stext-107 cl7 size-201">
                    Any questions? Let us know in store at 8th floor, 379 Hudson St, New York, NY 10018 or call us
                    on (+1) 96 716 6879
                </p>

                <div class="p-t-27">
                    <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                        <i class="fa fa-facebook"></i>
                    </a>

                    <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                        <i class="fa fa-instagram"></i>
                    </a>

                    <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16">
                        <i class="fa fa-pinterest-p"></i>
                    </a>
                </div>
            </div>

            
        </div>

        <div class="p-t-40">
            <div class="flex-c-m flex-w p-b-18">
                <a href="#" class="m-all-1">
                    <img src="assets/frontimages/icons/icon-pay-01.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="assets/frontimages/icons/icon-pay-02.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="assets/frontimages/icons/icon-pay-03.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="assets/frontimages/icons/icon-pay-04.png" alt="ICON-PAY">
                </a>

                <a href="#" class="m-all-1">
                    <img src="assets/frontimages/icons/icon-pay-05.png" alt="ICON-PAY">
                </a>
            </div>

            <p class="stext-107 cl6 txt-center">
                Copyright &copy;
                <script>
                    document.write(new Date().getFullYear());
                </script> MB Herbal All rights reserved |

            </p>
        </div>
    </div>
</footer>

    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>

    <script>
    particlesJS('particles-js',

{
  "particles": {
    "number": {
      "value": 80,
      "density": {
        "enable": true,
        "value_area": 1299.3805191013182
      }
    },
    "color": {
      "value": ["#5D47BA","#FFDBFF","#FB5435","#E00A30","#04CEF9"]
    },
    "shape": {
      "type": "star",
      "stroke": {
        "width": 0,
        "color": "#000000"
      },
      "polygon": {
        "nb_sides": 5
      },
      "image": {
        "src": "img/github.svg",
        "width": 100,
        "height": 100
      }
    },
    "opacity": {
      "value": 1,
      "random": false,
      "anim": {
        "enable": false,
        "speed": 1,
        "opacity_min": 0.1,
        "sync": false
      }
    },
    "size": {
      "value": 15,
      "random": true,
      "anim": {
        "enable": false,
        "speed": 40,
        "size_min": 0.1,
        "sync": false
      }
    },
    "line_linked": {
      "enable": false,
      "distance": 150,
      "color": "#ffffff",
      "opacity": 0.4,
      "width": 1
    },
    "move": {
      "enable": true,
      "speed": 6,
      "direction": "top",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false,
      "attract": {
        "enable": false,
        "rotateX": 600,
        "rotateY": 1200
      }
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": {
        "enable": true,
        "mode": "repulse"
      },
      "onclick": {
        "enable": true,
        "mode": "push"
      },
      "resize": true
    },
    "modes": {
      "grab": {
        "distance": 400,
        "line_linked": {
          "opacity": 1
        }
      },
      "bubble": {
        "distance": 400,
        "size": 40,
        "duration": 2,
        "opacity": 8,
        "speed": 3
      },
      "repulse": {
        "distance": 200,
        "duration": 0.4
      },
      "push": {
        "particles_nb": 4
      },
      "remove": {
        "particles_nb": 2
      }
    }
  },
  "retina_detect": true
}

);
</script>
<!--===============================================================================================-->
<script src="assets/frontvendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="assets/frontvendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
<script src="assets/frontvendor/bootstrap/js/popper.js"></script>
<script src="assets/frontvendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="assets/frontvendor/select2/select2.min.js"></script>
<script>
    $(".js-select2").each(function() {
        $(this).select2({
            minimumResultsForSearch: 20,
            dropdownParent: $(this).next('.dropDownSelect2')
        });
    })
</script>
<!--===============================================================================================-->
<script src="assets/frontvendor/daterangepicker/moment.min.js"></script>
<script src="assets/frontvendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
<script src="assets/frontvendor/slick/slick.min.js"></script>
<script src="assets/frontjs/slick-custom.js"></script>
<!--===============================================================================================-->
<script src="assets/frontvendor/parallax100/parallax100.js"></script>
<script>
    $('.parallax100').parallax100();
</script>
<!--===============================================================================================-->
<script src="assets/frontvendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
<script>
    $('.gallery-lb').each(function() { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
                enabled: true
            },
            mainClass: 'mfp-fade'
        });
    });
</script>
<!--===============================================================================================-->
<script src="assets/frontvendor/isotope/isotope.pkgd.min.js"></script>
<!--===============================================================================================-->
<script src="assets/frontvendor/sweetalert/sweetalert.min.js"></script>
<script>
    $('.js-addwish-b2').on('click', function(e) {
        e.preventDefault();
    });

    $('.js-addwish-b2').each(function() {
        var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
        $(this).on('click', function() {
            swal(nameProduct, "is added to wishlist !", "success");

            $(this).addClass('js-addedwish-b2');
            $(this).off('click');
        });
    });

    $('.js-addwish-detail').each(function() {
        var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

        $(this).on('click', function() {
            swal(nameProduct, "is added to wishlist !", "success");

            $(this).addClass('js-addedwish-detail');
            $(this).off('click');
        });
    });

    /*---------------------------------------------*/

    $('.js-addcart-detail').each(function() {
        var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
        $(this).on('click', function() {
            swal(nameProduct, "is added to cart !", "success");
        });
    });
</script>
<!--===============================================================================================-->
<script src="assets/frontvendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script>
    $('.js-pscroll').each(function() {
        $(this).css('position', 'relative');
        $(this).css('overflow', 'hidden');
        var ps = new PerfectScrollbar(this, {
            wheelSpeed: 1,
            scrollingThreshold: 1000,
            wheelPropagation: false,
        });

        $(window).on('resize', function() {
            ps.update();
        })
    });
</script>
<!--===============================================================================================-->
<script src="assets/frontjs/main.js"></script>

<!-- Testimonial Start  -->

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js">
</script>

    <!--@yield('scripts')-->


</body>

</html>
