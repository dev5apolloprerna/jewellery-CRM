<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Razorpay\Api\Api;
use Redirect, Response;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class RazorpayController extends Controller
{
    public function index($id)
    {
        $Order = Order::where("order_id", $id)->where(['iStatus' => 1, 'isDelete' => 0])->first();
        //dd($Order);
        $price = $Order->netAmount;

        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $OrderAmount = $price * 100;
        $orderData = [
            'receipt'         => $id . '-' . date('dmYHis'),
            'amount'          => $OrderAmount,
            'currency'        => 'INR',
        ];
        $razorpayOrder = $api->order->create($orderData);
        $orderId = $razorpayOrder['id'];
        $data = array(
            'order_id' => $orderId,
            'oid' => $id,
            'amount' => $price,
            'currency' => 'INR',
            'receipt' => $razorpayOrder['receipt'],
        );
        Payment::insert($data);
        // dd($Order); frontview.dataFrom
        return view('razorpay', compact('Order', 'orderId'));
    }
    public function cancel(Request $request)
    {
        try{
        \Cart::clear();
        return redirect()->route('FrontIndex')->with('error'," Your Payment cancel Successfully");
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }

    }

    public function razorPaySuccess(Request $request)
    {
            try {
     
        $orderId = $request->orderId;
        $data = [

            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
            'razorpay_order_id' => $request->razorpay_order_id,

        ];
        Payment::where('order_id', $orderId)->update($data);

        $payment=Payment::where('order_id',$orderId)->first();

        $stringdata = $orderId . '|' . $request->razorpay_payment_id;
        $generated_signature = hash_hmac('sha256', $stringdata, env('RAZORPAY_SECRET'));
        $razorpay_signature = $request->razorpay_signature;


        if ($generated_signature == $razorpay_signature) 
        {
            $updateData = Payment::where('order_id', $orderId)->update([
                'status' => 'Success',
                'iPaymentType' => 1,
                'iSubscriptionYear' => 1,
                "Remarks" => "Online Payment"
            ]);
            if ($updateData) {
                $updateProfileData = array(
                    'iRazorpayStatus' => 1
                );
                Order::where("order_id", $request->profile_id)->update($updateProfileData);
            }

            $Order = Order::select('order.*',DB::raw('(select state.stateName from state where order.shiiping_state=state.stateId limit 1) as state'))->where("order_id", $payment->oid)->first();

            $SendEmailDetails = DB::table('sendemaildetails')
                ->where(['id' => 9])
                ->first();

            $root = $_SERVER['DOCUMENT_ROOT'];
            $file = file_get_contents($root . '/mailers/checkoutmail.html', 'r');

            $address = $Order['shiiping_address1'] . ',' . $Order['shiiping_address2'];
            $file = str_replace('#name', $Order['shipping_cutomerName'], $file);
            $file = str_replace('#email', $Order['shipping_email'], $file);
            $file = str_replace('#mobile', $Order['shipping_mobile'], $file);
            $file = str_replace('#address', $address, $file);
            $file = str_replace('#state', $Order['state'], $file);
            $file = str_replace('#city', $Order['shipping_city'], $file);
            $file = str_replace('#pincode', $Order['shipping_pincode'], $file);
            $file = str_replace('#amount', $Order['amount'], $file);
            $file = str_replace('#discount', $Order['discount'], $file);
            $file = str_replace('#netAmount', $Order['netAmount'], $file);
            $file = str_replace('#shipping_Charges', $Order['shipping_Charges'], $file);

            $html = "";
            $i = 1;
            $iTotal = 0;
            $cartItems = \Cart::getContent();

            foreach ($cartItems as $cartItem) {
                $html .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $cartItem['name'] . '</td>
                    <td><img width="48" height="48" src=https://getdemo.in/Product/Thumbnail/' . $cartItem['attributes']['image'] . '></td>
                    <td>' . $cartItem['weight'] . '</td>
                    <td>' . $cartItem['quantity'] . '</td>
                    <td>' . $cartItem['price'] . '</td>
            
                </tr>';
                $i++;
            }
            $file = str_replace('#tableProductTr', $html, $file);

            // dd($file);
            $setting = DB::table("setting")->select('email')->first();
            $toMail = $setting->email; // "shahkrunal83@gmail.com";//

            $to = $toMail;
            $subject = $SendEmailDetails->strSubject;
            // dd($subject);
            $message = $file;
            // dd($message);
            $header = "From:" . $SendEmailDetails->strFromMail . "\r\n";
            //$header .= "Cc:afgh@somedomain.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";

            //$retval = mail($to, $subject, $message, $header);


            $to1 = $Order['shipping_email'];
            $subject2 = "Order Detail From Tonuge Twister Order No :#".$Order['order_id'];
            $message2 = $file;
            $header = "From:".$SendEmailDetails->strFromMail."\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            
            //$retval = mail($to1,$subject2,$message2,$header);
            
            \Cart::clear();
            return 1;
            // $arr = array('msg' => 'Payment successfully credited', 'status' => true);
            // return Response()->json($arr);
        } else {
            $updateData = Payment::where('order_id', $orderId)->update(['status' => 'Fail']);
            \Cart::clear();
            return 0;
            // $arr = array('msg' => 'Payment Faild', 'status' => false);
            // return Response()->json($arr);
        }

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
        //b7453e2d214e295aa9f94ebe04ef9652ccc744a365ad94c5eeb758281a2266df

    }

    public function RazorThankYou()
    {
        return view('thankyouPage');
    }

    public function RazorFail()
    {
        return view('paymentFail');
    }
}
