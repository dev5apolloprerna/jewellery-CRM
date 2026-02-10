<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FirebasePushController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\Offer;
use App\Models\State;
use App\Models\Shipping;
use App\Models\CustomerCouponApplyed;

use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    private $web_url;
  /*  public function __construct()
    {
        $this->middleware('auth:api');
        $this->web_url =  "http://getdemo.in/";
    }
    */
    public function cart(Request $request)
    {
       if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email)])->first();
             if(!empty($userData))
            {
                if (Hash::check($request->password, $userData->password))
                {
                 $customerId=Customer::select('customerid','userId')->where(['userId'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();
    
                 $Cart=Cart::where(['iStatus'=>1,'isDelete'=>0,'customerUserId'=>$customerId->userId,'customerid'=>$customerId->customerid])->orderBy("cartId",'Desc')->get();

                    if(sizeof($Cart) != 0)
                    {
                        $total_amount=0;
                        $order_id=0;
                        $customer_name="";
                        $target_path = 'Product/';
    
                        //$iStatus = 1;
                        $iCounter = 0;
                        $total_amount=0;
                        
                        foreach($Cart as $val)
                        {
                            $total_amount +=$val->amount;

                            $yourcart[] = array(
                                "cartId"=>$val->cartId,
                                "customer_id"=>$val->customerid,
                                "product_id"=>$val->productId,
                                "product_image"=>"https://sukti.in/". $target_path.$val->product_image,
                                "product_name"=>$val->product_name,
                                "quantity"=>$val->quantity,
                                "price"=>$val->price,
                                "weight" => $val->weight,
                                "amount" => $val->amount
                            );
                        }
                        
                       
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Your Cart',
                            "Total Amount" => $total_amount,
                            'Your Cart' => $yourcart
                        ]);
    
                    } else 
                    {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No Data Found!',
                        ]);
                    }
                }
                else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }

            }
            else 
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }
        else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    
    public function addtoCart(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();

            $customer=Customer::where(['userId'=>$userData->id,'customer.iStatus'=>1,'customer.isDelete'=>0])->first();
            if (Hash::check($request->password, $userData->password))
            {
                if(!empty($userData) && !empty($customer))
                 {
                 $getcart=Cart::where(['productId'=>$request->product_id,'customerid'=>$customer->customerid])->get();
                  if(sizeof($getcart) == 0)
                  {

                       $product=Product::select('productname'
                        ,DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto"))->where(['productId'=>$request->product_id])->first();

                        $total_amount=($request->quantity*$request->price);
                        $data11=array(
                        "customerid"=>$customer->customerid,
                        "customerUserId"=>$customer->userId,
                        "productId"=>$request->product_id,
                        "product_name"=>$product->productname,
                        "product_image"=>$product->strphoto,
                        "quantity"=>$request->quantity,
                        "weight"=>$request->weight,
                        "price"=>$request->price,
                        "amount"=>$total_amount
                    );
                    
                    $Cart = Cart::create($data11);

                    return response()->json([

                        'status' => 'success',
                        'cartId' => $Cart->id,
                        'message' => 'Product Added to Cart successfully'
                    ]);
                  }else{
                     return response()->json([
                    'status' => 'error',
                    'message' => 'Product Already Added to Cart.',
                    ], 401);
                  }

             }else 
                {
                    return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                    ], 401);
                }
            } else {
                return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    
    public function editCart(Request $request)
    {
       if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();

            $customer=Customer::where(['userId'=>$userData->id,'customer.iStatus'=>1,'customer.isDelete'=>0])->first();
            if (Hash::check($request->password, $userData->password))
            {
                 if(!empty($userData) && !empty($customer))
                 {

                  $jsonArray=json_encode($request->cartDetail);

                    $phpArray = json_decode($jsonArray, true);
                    foreach ($phpArray['Your Cart'] as $cart) {
                        $Cart = Cart::where(["cartId" => $cart['cartId']])->update([
                            "productid" => $cart['product_id'],
                            "quantity" => $cart['quantity'],
                            "price" => $cart['price'],
                            "weight" => $cart['weight'],
                            "amount" => $cart['amount']
                        ]);
                    }
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Cart updated successfully'
                        ]);
                  }else 
                {
                    return response()->json([
                                            'status' => 'error',
                    'message' => 'User Not Found.',
                    ], 401);
                }


            } else {
                return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    
    public function deleteProductFromCart(Request $request)
    {
     if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();
            $customer=Customer::select('userId')->where(['userId'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();
            if (Hash::check($request->password, $userData->password))
           {
                 if(!empty($userData) && !empty($customer))
                 {
                    $Cart=Cart::where(['cartId'=>$request->cartId,"customerUserId"=>$customer->userId])->delete();

                     return response()->json([
                        'status' => 'success',
                        'message' => 'Product deleted From cart successfully'
                    ]);
                }else 
                {
                    return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                    ], 401);
                }
            } else {
                return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    
    public function couponapply(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();

            $customer=Customer::where(['userId'=>$userData->id,'customer.iStatus'=>1,'customer.isDelete'=>0])->first();
            if (Hash::check($request->password, $userData->password))
            {
                 if(!empty($userData) && !empty($customer))
                 {
                    $session = $customer->customerid;
                    $Offer = Offer::where(['iStatus' => 1, 'isDelete' => 0, 'offercode' => $request->coupon])->first();
                   if($Offer != null)
                   {
                           $CouponApply = CustomerCouponApplyed::where(['customerId' => $session, 'offerId' => $Offer->id])->count();
                    }
                    $Today = date('Y-m-d');
                    $Coupon = $request->coupon ?? "";
                    $Total = $request->totalAmount ?? 0;
                    $Percentage = $Offer->type ?? null;
                    $OfferCode = $Offer->offercode ?? null;
                    $total_amount = 0;

                    if ($Coupon == $OfferCode) 
                    {
                        if ($Total >= $Offer->minvalue) {
                            if (($Today >= $Offer->startdate) && ($Today <= $Offer->enddate)) 
                            {

                                $result = (($Total * 1)) * (($Percentage * 1) / (100 * 1));
                                $resultround = round($result);
                                $data = array(
                                    'offerId' => $Offer->id,
                                    'customerId' => $session ?? 0,
                                    'result' => $resultround,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    "strIP" => $request->ip()
                                );
                                $Coupon = CustomerCouponApplyed::create($data);
                                $total_amount=$Total-$Coupon['result'];
                                $coupondata=array(
                                    'offerId'=>$Offer->id,
                                    'discount'=>$Coupon['result'],
                                    'totalAmount'=>$total_amount
                                );

                                return response()->json([
                                    'status' => 'success',
                                    'message' => 'Coupon Code Apply Successfully!',
                                    'data' => $coupondata
                                ]);
                               
                            } else 
                            {
                                return response()->json([
                                'status' => 'error',
                                'message' => 'Coupon is expired!.',
                                ], 401);
                            }
                        } else 
                        {
                              return response()->json([
                                'status' => 'error',
                                'message' => 'Order value must be minvalue ('.$Offer->minvalue.') of coupon code!',
                                ], 401);
                        }
                    } else 
                    {
                         return response()->json([
                                'status' => 'error',
                                'message' => 'Coupon Code Not Match!',
                                ], 401);
                    }

                
             }else 
                {
                    return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                    ], 401);
                }
            } else {
                return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
     public function checkout(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();

            $customer=Customer::where(['userId'=>$userData->id,'customer.iStatus'=>1,'customer.isDelete'=>0])->first();
             if(!empty($userData) && !empty($customer))
             {
                if (Hash::check($request->password, $userData->password))
                {

                    if(!empty($request->cartDetail))
                    {

                     $jsonArray=json_encode($request->cartDetail);

                    $phpArray = json_decode($jsonArray, true);
                    foreach ($phpArray['Your Cart'] as $cart) {
                        $Cart = Cart::where(["cartId" => $cart['cartId']])->update([
                            "productid" => $cart['product_id'],
                            "quantity" => $cart['quantity'],
                            "price" => $cart['price'],
                            "weight" => $cart['weight'],
                            "amount" => $cart['amount']
                        ]);
                    }
                    }

                $cart=Cart::where(['customerUserId'=>$customer->userId,'iStatus'=>1,'isDelete'=>0])->get();
                $totalAmount=0;
                if(sizeof($cart) != 0)
                {
                    
                    foreach($cart as $value)
                    {
                        $totalAmount +=$value->amount;
                        $OrderDetails=new OrderDetail();
                        $OrderDetails->orderID = 0;
                        $OrderDetails->customerid = $customer->customerid;
                        $OrderDetails->productId = $value->productId;
                        $OrderDetails->quantity = $value->quantity;
                        $OrderDetails->weight = $value->weight;
                        $OrderDetails->rate = $value->price;
                        $OrderDetails->amount = $value->amount;
                        $OrderDetails->isPayment = 0;
                        $OrderDetails->created_at = date('Y-m-d H:i:s');
                        $OrderDetails->strIP = $request->ip();
                        $OrderDetails->save();

                   
             }
                return response()->json([
                        'status' => 'success',
                        'message' => 'Order placed successfully',
                    ]);
                }else{
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Order Not placed successfully',
                    ]);
                }

            }else 
            {
                return response()->json([
                    'status' => 'error1',
                    'message' => 'Invalid login.',
                ], 401);
            }
        } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    public function submitOrder(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();

            $customer=Customer::where(['userId'=>$userData->id,'customer.iStatus'=>1,'customer.isDelete'=>0])->first();
             if(!empty($userData) && !empty($customer))
             {
                if (Hash::check($request->password, $userData->password))
                {

                 
                $cart=Cart::where(['customerUserId'=>$customer->userId,'iStatus'=>1,'isDelete'=>0])->get();
                $totalAmount=0;
                if(sizeof($cart) != 0)
                {
                    $Order=new Order();
                    $Order->customerid = $customer->customerid;
                    $Order->cutomerName= $request->billFirstName . ' ' . $request->billLastName;
                    $Order->mobile= $request->billPhone ?? 0;
                    $Order->email= $request->billEmail ?? '';
                    $Order->address=$request->billAddress. ' ' .$request->billAddress2;
                    $Order->state=$request->billState;
                    $Order->city=$request->billCity;
                    $Order->pincode=$request->billPinCode;
                    $Order->shipping_cutomerName = $request->billFirstName . ' ' . $request->billLastName;
                    $Order->shipping_companyName = $request->billCompanyName;
                    $Order->shipping_GSTNumber = $request->billGSTNumber;
                    $Order->shipping_mobile = $request->billPhone;
                    $Order->shipping_email = $request->billEmail;
                    $Order->shiiping_address1 = $request->billAddress;
                    $Order->shiiping_address2 = $request->billAddress2;
                    $Order->shipping_city = $request->billCity;
                    $Order->shiiping_state = $request->billState;
                    $Order->shipping_pincode = $request->billPinCode;
                    $Order->amount = $request->amount;
                    $Order->discount = $request->discount;
                    $Order->shipping_Charges = $request->shippingcharges ?? 0;
                    $Order->netAmount = $request->netamount;
                    $Order->created_at = date('Y-m-d H:i:s');
                    $Order->strIP = $request->ip();

                    $Order->save();

                    $OrderDetailsUpdate = OrderDetail::where(['customerid'=>$customer->customerid,"orderID"=>0])
                    ->update(["orderID"=>$Order->id]);

               if($Order->id != "")
               {
                  foreach($cart as $value)
                    {

                     $Cart=Cart::where(['cartId'=>$value->cartId,"customerUserId"=>$customer->userId])->delete();
                    }
               }


                return response()->json([
                        'status' => 'success',
                        'message' => 'Order Submitted successfully',
                        'order_id' => $Order->id
                    ]);
            }else{
              return response()->json([
                        'status' => 'error1',
                        'message' => 'Order Not Submitted Successfully.',
                    ], 401);   
            }
                  

                }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    
      public function processing_order(Request $request)
    {   
          if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                $customer=Customer::select('customerid')->where(['userId'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();

                $Order=Order::select('order.*',DB::raw('(select states.name from states where order.shiiping_state=states.id limit 1) as state'),DB::raw('(SELECT order_id FROM card_payment WHERE order.order_id=card_payment.oid LIMIT 1) as orderNo'))->orderBy('order_id', 'desc')->where(['iStatus' => 1, 'isDelete' => 0, 'isPayment' => 0,'customerid'=>$customer->customerid])->get();
                if(sizeof($Order) != 0)
                {
                    $total_amount=0;
                    $totalquantity=0;
                    $order_id=0;
                    $customer_name="";
                    foreach($Order as $val)
                    {
                         if($val->isPayment == 0)
                        {
                         $order_status ="Pending";
                        }else if($val->isPayment == 1){

                        $order_status ="Dispatched";
                        }else{
                          $order_status ="Cancelled";   
                        }
                        $OrderDetails=OrderDetail::where(['orderID'=>$val->order_id])->get();
                        foreach ($OrderDetails as $key => $value) 
                        {
                            $totalquantity +=$value->quantity;
                        }
                        $PendingOrder[]=array(
                              'order_id' => $val->order_id,
                              'order_no' => $val->orderNo ?? '-',
                              'order_data' => date('d-m-Y',strtotime($val->created_at)),
                              'quantity'=>$totalquantity,
                              'total_amount'=>$val->netAmount,
                               "order_status"=>$order_status,

                        );
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Pending Order',
                        'Pending Order' => $PendingOrder
                    ]);

                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No Data Found!',
                    ]);
                }

            }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }

             } 
             else 
             {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
     public function delivered_order(Request $request)
    {   
          if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                $customer=Customer::select('customerid')->where(['userId'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();

                $Order=Order::select('order.*',DB::raw('(select states.name from states where order.shiiping_state=states.id limit 1) as state'),DB::raw('(select courier.name from courier where order.courier=courier.id limit 1) as couriername'),DB::raw('(SELECT order_id FROM card_payment WHERE order.order_id=card_payment.oid LIMIT 1) as orderNo'))->orderBy('order_id', 'desc')->where(['iStatus' => 1, 'isDelete' => 0, 'isPayment' => 1,'customerid'=>$customer->customerid])->get();
                if(sizeof($Order) != 0)
                {
                    $total_amount=0;
                    $totalquantity=0;
                    $order_id=0;
                    $customer_name="";
                    foreach($Order as $val)
                    {
                         if($val->isPayment == 0)
                        {
                         $order_status ="Pending";
                        }else if($val->isPayment == 1){

                        $order_status ="Dispatched";
                        }else{
                          $order_status ="Cancelled";   
                        }
                        $OrderDetails=OrderDetail::where(['orderID'=>$val->order_id])->get();
                        foreach ($OrderDetails as $key => $value) 
                        {
                            $totalquantity +=$value->quantity;
                        }
                        $DeliveredOrder[]=array(
                              'order_id' => $val->order_id,
                              'order_no' => $val->orderNo ?? '-',
                              'order_data' => date('d-m-Y',strtotime($val->created_at)),
                              'quantity'=>$totalquantity,
                              'total_amount'=>$val->netAmount,
                               "courier_name"=>$val->couriername,
                               "docket_no"=>$val->docketNo,
                               "order_status"=>$order_status,

                        );
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Delivered Order',
                        'Delivered Order' => $DeliveredOrder
                    ]);

                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No Data Found!',
                    ]);
                }

            }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }

             } 
             else 
             {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    
     public function cancel_order(Request $request)
    {   
          if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                $customer=Customer::select('customerid')->where(['userId'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();

                $Order=Order::select('order.*',DB::raw('(select states.name from states where order.shiiping_state=states.id limit 1) as state'),DB::raw('(select courier.name from courier where order.courier=courier.id limit 1) as couriername'),DB::raw('(SELECT order_id FROM card_payment WHERE order.order_id=card_payment.oid LIMIT 1) as orderNo'))->orderBy('order_id', 'desc')->where(['iStatus' => 1, 'isDelete' => 0, 'isPayment' => 2,'customerid'=>$customer->customerid])->get();
                if(sizeof($Order) != 0)
                {
                    $total_amount=0;
                    $totalquantity=0;
                    $order_id=0;
                    $customer_name="";
                    foreach($Order as $val)
                    {
                         if($val->isPayment == 0)
                        {
                         $order_status ="Pending";
                        }else if($val->isPayment == 1){

                        $order_status ="Dispatched";
                        }else{
                          $order_status ="Cancelled";   
                        }
                        $OrderDetails=OrderDetail::where(['orderID'=>$val->order_id])->get();
                        foreach ($OrderDetails as $key => $value) 
                        {
                            $totalquantity +=$value->quantity;
                        }
                        $cancelOrder[]=array(
                              'order_id' => $val->order_id,
                              'order_no' => $val->orderNo ?? '-',
                              'order_data' => date('d-m-Y',strtotime($val->created_at)),
                              'quantity'=>$totalquantity,
                              'total_amount'=>$val->netAmount,
                               "order_status"=>$order_status,

                        );
                    }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Cancelled Order',
                        'Cancelled Order' => $cancelOrder
                    ]);

                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No Data Found!',
                    ]);
                }

            }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }

             } 
             else 
             {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
    
   public function order_details(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email)])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                $Order=Order::select('order.*',DB::raw('(select states.name from states where order.shiiping_state=states.id limit 1) as shipping_state'),DB::raw('(select states.name from states where order.state=states.id limit 1) as state'),DB::raw('(SELECT order_id FROM card_payment WHERE order.order_id=card_payment.oid LIMIT 1) as orderId'))->where(['iStatus'=>1,'isDelete'=>0,'order_id'=>$request->order_id])->first();

                if(!empty($Order))
                {
                    $total_amount=0;
                    $order_id=0;
                    $customer_name="";
                    $order_status="";
                    $discount="";
                    $newOrder=[];
                    if($Order->isPayment == 0)
                        {
                         $order_status ="Pending";
                        }else if($Order->isPayment == 1){

                        $order_status ="Dispatched";
                        }else{
                          $order_status ="Cancelled";   
                        }
                    
                        // $total_amount =$Order->total_amount;
                        $order_id =$Order->orderId;
                        $discount =$Order->discount;
                        $order_date =date('d-m-Y H:i A',strtotime($Order->created_at));

                    $customer=Customer::select('customername','customermobile','customeremail')->where(['customerid'=>$Order->customerid,'iStatus'=>1,'isDelete'=>0])->first();
                        
                    $OrderDetails=OrderDetail::where(['iStatus'=>1,'isDelete'=>0,'customerid'=>$Order->customerid,'orderID' => $Order->order_id])->get();



                        $Products = array();

                        foreach($OrderDetails as $value)
                        {
                            $product=Product::select('product.productname','productId',DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto"))->where(['productId'=>$value->productId])->first();
                         $Products1 = array(
                            "product_id"=>$value->productId,
                            "product_name"=>$product->productname,
                            "quantity"=>$value->quantity,
                            "price"=>$value->rate,
                            "weight"=>$value->weight,
                            "product_photo" =>"https://sukti.in/Product/" . $product->strphoto,

                        );

                         array_push($Products,$Products1);

                        }          
                        $billing_address=$Order->address.",".$Order->city.",".$Order->state.",".$Order->pincode;            
                        $shipping_address=$Order->shiiping_address1.",".$Order->shiiping_address2.",".$Order->shipping_city.",".$Order->shipping_state.",".$Order->shipping_pincode;            

                        $data=array(
                              'order_no' => $order_id,
                              'order_date'=>$order_date,
                              'customer_Name' => $Order->cutomerName ?? "",
                              'customer_Mobile' => $Order->mobile ?? "",
                              'customer_Email' => $Order->email ?? "",
                              'billing_address' => $billing_address ?? "",
                              'shipping_address' => $shipping_address ?? "",
                               "order_status" => $order_status,
                               "amount" => $Order->amount,
                               "shipping_charge" => $Order->shipping_Charges,
                               "discount" => $discount ?? "0",
                               "total_amount" => $Order->netAmount,
                               "Products"=>$Products,

                        );
                        array_push($newOrder,$data);
                    

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Order detail',
                        'Order details' => $newOrder
                    ]);

                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No Data Found!',
                        ]);
                    }
                    }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }


             } 
             else 
             {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
    }
      
          public function cart_total(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['mobile_number'=>trim($request->mobile_number)])->first();
             
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password) && $userData->status == $request->status)
                {
                    $customerId=Customer::select('customer_id')->where(['customer_user_id'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();
                    $OrderCount=OrderDetail::where(['order_details.iStatus'=>1,'order_details.isDelete'=>0,'order_details.iCustomerId'=>$customerId->customer_id,'order_details.order_id'=>0])->orderBy("order_detail_id",'Desc')->count();

                return response()->json([
                    'status' => 'success',
                    'message' => 'cart count',
                     'cart_count'=>$OrderCount
                ]);
                }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }


            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }

    }
      public function state_list()
    {
        $state=State::orderBy('name', 'asc')->get();
         if(!empty($state))
        {
                 foreach($state as $val)
                {

                    $stateList[] = array(
                        "id"=>$val->id,
                        "state_name"=>$val->name
                    );
                }
                
               
                return response()->json([
                    'status' => 'success',
                    'message' => 'State List',
                    'state' => $stateList
                ]);

        } else 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'No Data Found!',
            ]);
        }
    }
   public function shipping_charges(Request $request)
    {
        $State=State::where(['id'=>$request->id])->first();
         if(!empty($State))
        {
            if($State->id == "1"){

                $Shipping=Shipping::where(['id'=>2])->first();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Shipping Charge',
                    'shipping' => $Shipping->rate
                ]);
            }else{
               $Shipping=Shipping::where(['id'=>3])->first();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Shipping Charge',
                    'shipping' => $Shipping->rate
                ]); 
            }   

        } else 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'No Data Found!',
            ]);
        }
    }
}