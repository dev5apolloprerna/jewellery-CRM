<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        if(($request->email != "") && $request->password != "")
        {
            $User = User::where(['email'=>$request->email])->first();
            if(!empty($User))
            {
                if ($User && Hash::check($request->password, $User->password)) 
                {
                
                    $credentials = request(['mobile_number','email', 'password']);
            
                    $token = auth()->guard('api')->attempt($credentials, ['exp' => Carbon::now()->addDays(7)->timestamp]);
    
                    $target_path = 'userProfilePic/';
    
                    if (!$token) 
                    {
                        //return response()->json(['error' => 'Unauthorized'], 401);
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Unauthorized User.',
                        ], 401);
                    } else 
                    {
                        $device_token = $token;
                        User::where('id',$User->id)->update([
                            'device_token' => $device_token
                        ]);
    
                        //$user = Auth::user();
                        $user = auth()->guard('api')->user();
                        $customer=Customer::where(['userId'=>$user->id])->first();
                        
                        if($customer->user_image != "")
                        {
                            $image="https://sukti.in/". $target_path . $customer->user_image;
                        }else{
                            $image ="";
                        }
                        
                        $userdata=array(
                           "id"=>$user->id,
                           "customer_id"=>$customer->customerid,
                            "name"=>$user->first_name,
                            "email"=>$user->email,
                            "mobile_number"=>$user->mobile_number,
                            "address"=>$customer->address ?? '-',
                            "image"=>$image,
                            "role_id"=>$user->role_id,
                            "status"=>$user->status
                        );
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Login Successfully.',
                            'user' => $userdata,
                            'key' => 'rzp_test_F1sbFWuynDVD5y',
                            'salt' => '138T9QwDedJsi1fY8NXJzTwV',
                            'authorisation' => [
                                'token' => $token,
                                'type' => 'bearer',
                            ]
                        ]);
                    }
                } else 
                {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Please enter valid password.',
                    ], 401);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        } else 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Login Id or Password is blank.',
            ], 401);
        }
    }
    
    public function logout()
    {
        // Auth::logout();
        // return response()->json([
        //     'status' => '1',
        //     'message' => 'Successfully logged out',
        // ]);
        auth()->guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json([
            'status' => '1',
            'user' => auth()->guard('api')->user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'sucess',
            'user' => auth()->guard('api')->user(),
            'authorisation' => [
                'token' => auth()->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function change_password(Request $request)
    {
        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();
            $userData = User::where(['mobile_number'=>trim($request->mobile_number),'role_id'=>1])->first();
             if(!empty($userData))
             {
                $user=User::where(['id'=>$user_login->id,'role_id'=>1])->first();
                if(!empty($user))
                {
                    $newpassword = $request->new_password;
                    $confirmpassword = $request->new_confirm_password;

                    if($newpassword == $confirmpassword) 
                    {
                        $Branch = DB::table('users')
                            ->where(['status' => 1, 'id' => $user_login->id])
                            ->update([
                                'password' => Hash::make($confirmpassword),
                            ]);
                            return response()->json([
                                    'status' => 'success',
                                    'message' => 'Password Updated Successfully'
                                ]);

                    }else 
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