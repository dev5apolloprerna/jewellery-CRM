<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductReview;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;



class ReviewController extends Controller
{
    public function addReview(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email)])->first();
            if(!empty($userData))
            {

                if (Hash::check($request->password, $userData->password))
                {   
                    $customer=Customer::select('customerid','userId','customername','customeremail','user_image')->where(['userId'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();
                    $getreview=ProductReview::where(['iProjectId'=>$request->product_id,'iCustomerId'=>$customer->customerid])->get();
                    if(sizeof($getreview) == 0)
                    {
                        if($customer->user_image != "")
                        {
                            $image=$customer->user_image;
                        }else{
                            $image="";
                        }

                        $data = array(
                            "iProjectId" => $request->product_id,
                            "iCustomerId" => $customer->customerid,
                            "iRate" => $request->rating,
                            "strMessage" => $request->comment,
                            "strName" => $customer->customername,
                            "strEmail" => $customer->customeremail,
                            "userImage" => $image,
                            "strIP" => $request->ip()
                        );
                        $review = ProductReview::create($data);
                        return response()->json([

                            'status' => 'success',
                            'message' => 'Product Review Added Successfully'
                        ]);  
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Product Review Already Added .',
                        ], 401);
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
    public function viewReview(Request $request)
        {
            $viewReview=ProductReview::where(['iProjectId'=>$request->product_id])->get();
            if(sizeof($viewReview) != 0)
            {
                foreach($viewReview as $val)
                {
                    $customer=Customer::where(['customerid'=>$val->iCustomerId,'iStatus'=>1,'isDelete'=>0])->first();
                     $target_path = 'userProfilePic/';
                    if($customer->user_image != "")
                    {
                        $image="https://sukti.in/". $target_path . $customer->user_image;
                    }else{
                        $image="";
                    }
    
                    $reviewList[] = array(
                       'customer_name'=>$customer->customername,
                       'comment'=>$val->strMessage,
                       'rating'=>$val->iRate,
                        'userImage' => $image,
                    );
                }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Review List',
                        'Review' => $reviewList
                    ]);
    
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Review not found!',
                    'Review' => []
                ]);
            }
                   
        }
}