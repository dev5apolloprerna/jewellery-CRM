<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Payment;

class RazorpayController extends Controller
{
	public function successPayment(Request $request)
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
                    $api = new Api($request->key, $request->salt);
                     $OrderAmount = $request->amount * 100;
                    $orderData = [
                        'receipt'         => $request->order_id.'-'.date('dmYHis'),
                        'amount'          => $OrderAmount,
                        'currency'        => 'INR',

                    ];
                    $razorpayOrder = $api->order->create($orderData);
                    $orderId = $razorpayOrder['id'];
                    $receiptId=$request->order_id.'-'.date('dmYHis');

                    $Payment=new Payment();
                    $Payment->order_id=$orderId;
                    $Payment->oid=$request->order_id;
                    $Payment->razorpay_payment_id=$request->razorpay_payment_id;
                    $Payment->razorpay_order_id=$orderId;
                    $Payment->razorpay_signature=$request->razorpay_signature;
                    $Payment->receipt=$receiptId;
                    $Payment->amount=$request->amount;
                    $Payment->currency="INR";
                    $Payment->status="Success";
                    $Payment->iPaymentType=1;
                    $Payment->iSubscriptionYear=1;
                    $Payment->Remarks="Online Payment";
                    $Payment->save();        
                    
                	
                	$receipt=$Payment->id.'-'.date('dmYHis');
                	$updateData = Payment::where('id',$Payment->id)->update(['receipt'=>$receipt]);               
                
                
                return response()->json([
                        'status' => 'success',
                        'message' => 'Success Payment'
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
    public function FailPayment(Request $request)
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
                    $api = new Api($request->key, $request->salt);
                     $OrderAmount = $request->amount * 100;

                    $orderData = [
                        'receipt'         => $request->order_id.'-'.date('dmYHis'),
                        'amount'          => $OrderAmount,
                        'currency'        => 'INR',

                    ];
                    $razorpayOrder = $api->order->create($orderData);
                    $orderId = $razorpayOrder['id'];
                    $receiptId=$request->order_id.'-'.date('dmYHis');
                    
                	$Payment=new Payment();
                    $Payment->order_id=$orderId;
                    $Payment->receipt=$receiptId;
                	$Payment->oid=$request->order_id;
                	$Payment->amount=$request->amount;
                	$Payment->currency="INR";
                	$Payment->status="Fail";
                	$Payment->iPaymentType=1;
                	$Payment->iSubscriptionYear=1;
                	$Payment->Remarks="Online Payment";
                	$Payment->save();                 
                
                return response()->json([
                        'status' => 'success',
                        'message' => 'Payment Failed'
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
}