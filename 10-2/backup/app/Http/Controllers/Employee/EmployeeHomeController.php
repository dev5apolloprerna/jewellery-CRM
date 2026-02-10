<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Role;

use App\Models\Customer;
use App\Models\CustomerProduct;
use App\Models\CustOrder;
use App\Models\CustOrderDetail;
use App\Models\CustomerVisit;
use App\Models\PaymentDetail;

use Carbon\Carbon;

use Session;
use Mail;

class EmployeeHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:web_employees']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   public function index()
{
    $emp_id = Auth::user()->emp_id;
//    $branch_id = Auth::user()->branch_id;


 $branchId = auth()->user()->branch_id; // Or get it from session

    $today = Carbon::today();

    // Today follow-ups
    $todayCount = CustomerVisit::where('iStatus', 1)->where('followup_status', 0)->whereDate('next_followup_date', $today)->where('branch_id', $branchId)->where('emp_id', $emp_id)->count();
            $PaymentReminder = PaymentDetail::whereDate('next_followup_date', $today)->count();

    // Overdue follow-ups
    $overdueCount = CustomerVisit::where('iStatus', 1)->where('followup_status', 0)->whereDate('next_followup_date', '<', $today)->where('branch_id', $branchId)->where('emp_id', $emp_id)->count();

    //attended client 
    $attendedClient=CustomerProduct::where(['emp_id'=>$emp_id])->count();
    $convertedClient=CustomerProduct::where(['emp_id'=>$emp_id,'status'=>'ordered'])->count();
if ($attendedClient > 0) {
    $convertedRatio = round(($convertedClient / $attendedClient) * 100, 2);
} else {
    $convertedRatio = 0; // or null or 'N/A', depending on how you want to handle it
}

    return view('employee.home', compact('todayCount', 'overdueCount','attendedClient','convertedClient','convertedRatio','PaymentReminder'));

}

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        $session = Auth::user()->emp_id;
        // dd($session);
        $users = Employee::where('employee_master.emp_id',  $session)
            ->first();
        // dd($users);

        return view('employee.profile', compact('users'));
    }


    public function EditProfile()
    {
        $roles = Role::where('id', '!=', '1')->get();

        return view('employee.Editprofile', compact('roles'));
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
   public function updateProfile(Request $request)
    {
        $user_role_id=session()->get('user_role_id');
        $userId=session()->get('emp_id');
       
        #Validations
        $request->validate([
            'emp_name'   => 'required',
            'emp_email'  => 'required|email|unique:employee_master,emp_email,' . $userId . ',emp_id',
            'emp_phone' => 'required|numeric|digits:10',
            'emp_phone2' => 'required|numeric|digits:10',
            'emp_dob' => 'required',
        ]);

        try 
        {
            DB::beginTransaction();

            if($user_role_id == 2)
            {
                Employee::where(['emp_id'=>$userId])->update([
                'emp_name' => $request->emp_name,
                'emp_email' => $request->emp_email,
                'emp_phone' => $request->emp_phone,
                'emp_phone2' => $request->emp_phone2,
                'emp_dob' => $request->emp_dob,
                ]);

            }

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $session = Auth::user()->emp_id;

        $role = auth()->user()->role_id;


        $user = Employee::where('emp_id', '=', $session)->where(['iStatus' => 1])->first();

        if (Hash::check($request->current_password, $user->password)) 
        {
            $newpassword = $request->new_password;
            $confirmpassword = $request->new_confirm_password;

            if ($newpassword == $confirmpassword) {
                $User = DB::table('employee_master')
                    ->where(['iStatus' => 1, 'emp_id' => $session])
                    ->update([
                        'password' => Hash::make($confirmpassword),
                    ]);
                
                Auth::logout();
                $request->session()->forget('emp_id');
                $request->session()->forget('emp_name');
                $request->session()->forget('emp_phone');
                $request->session()->forget('emp_email');
                $request->session()->forget('user_role_id');
                $request->session()->forget('branch_id');

                return redirect()->route('user_login')->with('success', 'Your password has been successfully changed!');

                // return back()->with('success', 'User Password Updated Successfully.');
            } else {
                return back()->with('error', 'password and confirm password does not match');
            }
        } else {
            return back()->with('error', 'Current Password does not match');
        }
    }

    public function PasswordForgot(Request $request)
    {
        try{
        $Employee = Employee::where(['email' => trim($request->user_email), 'iStatus' => 1, 'isDelete' => 0])->first();
        if (!empty($Employee)) 
        {
            $token = Str::random(64);
            $data = array(
                'useremail' => $request->user_email,
                'fetch' => $Employee,
                'token' => $token,
            );

            $update = DB::table('users')
                ->where(['status' => 1, 'id' => $Employee->userId])
                ->update([
                    'token' => $token,
                ]);

            $SendEmailDetails = DB::table('sendemaildetails')
                ->where(['id' => 8])
                ->first();
                $sendmail =$request->user_email;
            $msg = array(
                'FromMail' => $SendEmailDetails->strFromMail,
                'Title' => $SendEmailDetails->strTitle,
                'ToEmail' => $request->user_email,
                'Subject' => $SendEmailDetails->strSubject
            );
           
            $root = $_SERVER['DOCUMENT_ROOT'];
            $file = file_get_contents($root . '/mailers/forgetpassword.html', 'r');
            $file = str_replace('#name', $data['fetch']->customername, $file);
            $file = str_replace('#email', 'https://platinumhrsolutions.in/user/New-Password/' . $token, $file);
            // dd($file);
            $setting = DB::table("setting")->select('email')->first();
            $toMail = $sendmail ; //$setting->email;// "shahkrunal83@gmail.com";//
            // dd($toMail);
            $to = $toMail;
            $subject = $SendEmailDetails->strSubject;
            $message = $file;
            $header = "From:".$SendEmailDetails->strFromMail."\r\n";
            //$header .= "Cc:afgh@somedomain.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            
            $retval = mail($to,$subject,$message,$header);

            return back()->with('success', 'We have emailed your password reset link!');
        } else {
            return back()->with('error', 'Email Is Not Registered');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    public function newpassword(Request $request, $token)
    {
        return view('Employee.newpassword', ['token' => $token]);
    }

    public function newpasswordsubmit(Request $request)
    {
          $validatedData = $request->validate([
                'newpassword' => 'required|min:6',
                'confirmpassword' => 'required|min:6'
            ], [
                'newpassword.required' => 'The New Password is required',
                'confirmpassword.required' => 'The Confirm Password is required'
            ]);
        $newpassword = $request->newpassword;
        $confirmpassword = $request->confirmpassword;

        $UsersData = DB::table('users')->where(['token' => $request->token, 'status' => 1])->first();
        
        if ($UsersData->token == $request->token) 
        {
            if ($newpassword == $confirmpassword) {
                $Users = DB::table('users')
                    ->where(['status' => 1, 'id' => $UsersData->id])
                    ->update([
                        'password' => Hash::make($request->confirmpassword),
                        'token' => null,
                    ]);

                    $Employee = DB::table('employee')
                    ->where(['iStatus' => 1, 'isDelete' => 0, 'userId' => $UsersData->id])
                    ->update([
                        'password' => Hash::make($request->confirmpassword),
                    ]);
                return redirect()->route('user_login')->with('success', 'Your password has been successfully changed!');
            } else {
                return back()->with('error', 'Password And Confirm Password Does Not Match.');
            }
        } else {
            return back()->with('error', 'Token Not Match.');
        }
    }

}
