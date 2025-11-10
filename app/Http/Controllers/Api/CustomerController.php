<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\BranchCustomer;



class CustomerController extends Controller
{
  public function index(Request $request)
  {

        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>$request->email])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                    $customer=Customer::select('customer.customerId','customer.userId as cid','customer.customername','customer.user_image','customer.customermobile','customer.customeremail','customer.iStatus as status','customer.isDelete as delete')->where(['customer.isDelete'=>0])->get();

                    if(sizeof($customer) != 0)
                    {
                            $target_path = '/userProfilePic/';

                        foreach ($customer as $list) 
                        {
                            $user=User::where(['id'=>$list->cid])->first();

                            $customerist[] = array(
                            'customer_id'=>$list->customerId,
                            'customer_userId'=>$list->cid,
                            'customer_name'=>$list->customername,
                            'customer_mobile'=>$list->customermobile,
                            'customer_email'=>$list->customeremail,
                            'customer_image'=>"https://sukti.in/". $target_path . $customer->user_image,
                            'iStatus'=>$list->status,
                            'isDelete'=>$list->delete
                            );
                        }

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Customer List',
                                'Customer' => $customerist
                            ]);


                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No Data Found!',
                                'Customer' => []
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
        
    public function create_customer(Request $request)
    {
            $User=User::where(['status'=>1,'mobile_number'=>$request->mobile])->get();
            $User1=User::where(['status'=>1,'email'=>$request->useremail])->get();
            $User2=Customer::where(['iStatus'=>1,'customeremail'=>$request->useremail])->get();
            if(sizeof($User) != 0)
            {
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Mobile Number already exists.',
                ], 401);   
            }else if(sizeof($User1) != 0 || sizeof($User2) != 0)
            {
             return response()->json([
                    'status' => 'error',
                    'message' => 'Email Address already exists.',
                ], 401);   
            }
            else{

            $User=new User();
            $User->first_name=$request->name ?? "";
            $User->email=$request->useremail ?? "";
            $User->mobile_number=$request->mobile ?? "";
            $User->role_id= 2;
            $User->password=Hash::make($request->cpassword);
            $User->save();
                
            $Customer=new Customer();
            $Customer->userId=$User->id;
            $Customer->customername=$request->name ?? "";
            $Customer->customermobile=$request->mobile ?? "";
            $Customer->customeremail=$request->useremail ?? "";
            $Customer->address=$request->address ?? "";
            $Customer->password=Hash::make($request->cpassword);
            $Customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Signup Successfully'
            ]);

            }
    
    }

    public function edit_customer(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>$request->email])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                    $User = User::where(['mobile_number'=>$request->mobile,'status'=>1])->whereNotIn('id', [$userData->id])->get(); 
                    $Customer = Customer::where(['customermobile'=>$request->mobile,'iStatus'=>1])->whereNotIn('userId', [$userData->id])
                    ->get();
                   
                    if(sizeof($User) != 0 || sizeof($Customer) != 0)
                    {
                         return response()->json([
                            'status' => 'error',    
                            'message' => 'Mobile Number already exists.',
                        ], 401);   
                    }
                    else
                    {

                        $User = User::where(["id"=>$userData->id])->update([
                             "first_name"=>$request->name ?? "",
                            "mobile_number"=>$request->mobile ?? "",
                            ]);
    
                        
                        $Customer = Customer::where(["userId"=>$userData->id])->update([
                                        "customername"=>$request->name ?? "",
                                        "customermobile"=>$request->mobile ?? "",
                                        "address"=> $request->address    ?? "",
                                    ]);
    
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Profile updated successfully'
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
   
    public function customer_changepassword(Request $request)
    {
         if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['mobile_number'=>trim($request->mobile_number)])->orWhere(['email'=>$request->email])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                    $user=User::where(['id'=>$request->user_id,'role_id'=>2])->first();

                    if(!empty($user))
                    {
                        $newpassword = $request->new_password;
                        $confirmpassword = $request->new_confirm_password;

                        if ($newpassword == $confirmpassword) 
                        {
                            $User = DB::table('users')
                                ->where(['status' => 1, 'id' => $request->user_id])
                                ->update([
                                    'password' => Hash::make($confirmpassword),
                                ]);
                                
                                $Customer = DB::table('customer')
                                ->where(['iStatus' => 1, 'userId' => $request->user_id])
                                ->update([
                                    'password' => Hash::make($confirmpassword),
                                ]);
                                return response()->json([
                                        'status' => 'success',
                                        'message' => 'Password Updated Successfully'
                                    ]);

                        } else 
                        {
                            return response()->json([
                                    'status' => 'error',
                                    'message' => 'password and confirm password does not match',
                                ], 401);
                        }
                    }else{
                     return response()->json([
                        'status' => 'error',
                        'message' => 'Requested User Not Found.',
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
   public function forgotpasswordmail(Request $request)
    {
        $OTP=random_int(100000, 999999);
        $Customer = DB::table('customer')->where(['customeremail' => $request->customeremail, 'iStatus' => 1, 'isDelete' => 0])->first();

        if (!empty($Customer)) 
        {
            $currentDate = date('Y-m-d H:i:s');
            $data = array
             (
                'otp'=>$OTP,
                'otpTimeOut'=>$currentDate
             );
            User::where("email","=",$request->customeremail)->update($data);

            $data = array(
                'customeremail' => $request->customeremail,
                'fetch' => $Customer
            );


          $SendEmailDetails = DB::table('sendemaildetails')->where(['id' => 8])->first();
          $sendmail =$request->customeremail;
            $msg = array(
                'FromMail' => $SendEmailDetails->strFromMail,
                'Title' => $SendEmailDetails->strTitle,
                'ToEmail' => $request->customeremail,
                'Subject' => $SendEmailDetails->strSubject
            );
            
            $root = $_SERVER['DOCUMENT_ROOT'];
            $file = file_get_contents($root .'/mailers/apiforgetpassword.html', 'r');
            $file = str_replace('#name', $data['fetch']->customername, $file);
            $file = str_replace('#otp',$OTP , $file);
            // dd($file);
            $setting = DB::table("setting")->select('email')->first();
            $toMail = $sendmail ; //$setting->email;// "shahkrunal83@gmail.com";//
            // dd($toMail);
            $to = $toMail;
            $subject = "OTP for forgot password";
            $message = $file;
            $header = "From:".$SendEmailDetails->strFromMail."\r\n";
            //$header .= "Cc:afgh@somedomain.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            
            $retval = mail($to,$subject,$message,$header);

                return response()->json([
                            'status' => 'success',
                            'message' => 'We have emailed your password reset OTP!'
                        ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email Is Not Registered.',
            ], 401);
            }

    }
     public function verifyOTP(Request $request)
    {

        $user=User::where(['email'=>$request->customeremail,'otp'=>$request->otp])->first();
        if(!empty($user))
        {
             $currentDate = strtotime(date('Y-m-d H:i:s'));
           $otpTimeOut = strtotime($user->otpTimeOut);

            // Add 15 minutes to the current time
            $currentDatePlus15Minutes = strtotime('+15 minutes', $otpTimeOut);

            if ($currentDate <= $currentDatePlus15Minutes) 
            {
               return response()->json([
                        'status' => 'success',
                        'message' => 'OTP verified Successfully!'
                    ]); 
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP Time Out!'
                ]); 
            }
        }
        else{
            return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP',
            ], 401);
        }

    }
    public function setPassword(Request $request)
    {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>$request->email])->first();
             if(!empty($userData))
             {
                    $user=User::where(['id'=>$userData->id,'role_id'=>2])->first();

                    if(!empty($user))
                    {
                        $newpassword = $request->new_password;
                        $confirmpassword = $request->new_confirm_password;

                        if ($newpassword == $confirmpassword) 
                        {
                            $User = DB::table('users')
                                ->where(['status' => 1, 'id' => $userData->id])
                                ->update([
                                    'password' => Hash::make($confirmpassword),
                                ]);
                                
                                $Customer = DB::table('customer')
                                ->where(['iStatus' => 1, 'userId' => $userData->id])
                                ->update([
                                    'password' => Hash::make($confirmpassword),
                                ]);
                                return response()->json([
                                        'status' => 'success',
                                        'message' => 'Password Updated Successfully'
                                    ]);

                        } else 
                        {
                            return response()->json([
                                    'status' => 'error',
                                    'message' => 'password and confirm password does not match',
                                ], 401);
                        }
                    }else
                    {
                      return response()->json([
                        'status' => 'error',
                        'message' => 'Requested User Not Found.',
                      ], 401);

                    }

             } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
       
    }
    public function userProfileImg(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>trim($request->email),'role_id'=>2])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {

                    $Customer=Customer::where(['userId'=>$userData->id,'iStatus'=>1,'isDelete'=>0])->first();

                    $userImg = '';
                    $img = '';
                    $root = $_SERVER['DOCUMENT_ROOT'];
                    if($request->user_image)
                    {
                        $imageName = rand(1000, 9999) ."_". time() . '.' . $request->user_image->extension();
    
                        // $EntrDate = date('Y-m-d',strtotime($application->created_at));
                        $EntrDate = $Customer->created_at;
    
                        $arr = explode(' ', $EntrDate);
                        $dateArrar = explode('-', $arr[0]);
                        $root = $_SERVER['DOCUMENT_ROOT'];
                        $destinationPath = $root . '/userProfilePic/';
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
    
                        $target_path = $destinationPath ."/";
                        $request->user_image->move($target_path, $imageName);
    

                    }else{
                       $userImg=$customer->user_image; 
                    }

                    $Customer = Customer::where(["customerid"=>$Customer->customerid])->update([
                         "user_image"=>$imageName ?? "",
                      ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'User Profile updated successfully'
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
    public function viewImage(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['email'=>$request->email])->first();
             if(!empty($userData))
             {
                if (Hash::check($request->password, $userData->password))
                {
                    $customer=Customer::select('customerId','userId','user_image','iStatus','isDelete')
                    ->where(['isDelete'=>0,'iStatus'=>1,'userId'=>$userData->id])->first();

                    if(!empty($customer))
                    {
                        $target_path = 'userProfilePic/';
                        if($customer->user_image != "")
                        {
                            $image="https://sukti.in/". $target_path . $customer->user_image;
                        }else{
                            $image="";
                        }

                        
                            return response()->json([
                                'status' => 'success',
                                'message' => 'user Image',
                                'userImg' => $image
                            ]);

                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No Data Found!',
                            'userImg' => []
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

}