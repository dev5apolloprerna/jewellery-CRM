<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\BranchMaster;
use Session;
use Mail;

class EmployeeLoginController extends Controller
{
    //
    public function loginform()
    {
        return view('employee.userLogin');
    }
    public function login(Request $request)
{
    
    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);

    $email = $request->email;
    $password = $request->password;
    $user = Employee::where(['emp_email' => $email, 'iStatus' => 1])->first();

    if ($user && $user->role_id == 2) 
    {
        // Get employee branch's allowed IP address
        $branch = BranchMaster::where('branch_id', $user->branch_id)->first();

        if (!$branch) {
            return redirect()->back()->with('error', 'Branch not found.');
        }

        // Get user's IP address
        //$currentIp = $request->ip(); // or use getClientIp(true) if behind proxy
        $currentIp = '152.59.4.79'; // or use getClientIp(true) if behind proxy

        // Compare IPs
        if ($branch->branch_ip !== $currentIp && $user->accesOutside == 'No') 
        {
            return redirect()->back()->with('error', 'Login not allowed from this IP address.');
        }

        $credentials = [
            'emp_email' => $email,
            'password' => $password
        ];

        if (Auth::guard('web_employees')->attempt($credentials)) {

            $user = Employee::select('emp_name', 'emp_id', 'emp_email', 'branch_id','emp_phone')->where('emp_id', $user->emp_id)->first();

            if ($user) {
                $request->session()->put('emp_id', $user->emp_id);
                $request->session()->put('emp_name', $user->emp_name);
                $request->session()->put('emp_phone', $user->emp_phone);
                $request->session()->put('emp_email', $user->emp_email);
                $request->session()->put('branch_id', $user->branch_id);
                $request->session()->put('user_role_id', '2');

                return redirect()->route('userhome');
            } else {
                return redirect()->back()->with('error', 'User Not Found');
            }

        } else {
            return redirect()->back()->with('error', 'Incorrect Email or Password');
        }

    } else {
        return redirect()->back()->with('error', 'Inactive User Cannot Login. Please Contact Admin.');
    }
}

    public function logout(Request $request)
    {
            Auth::guard('web_employees')->logout();
        $request->session()->invalidate();
        // Regenerate the session token to prevent session fixation attacks
        $request->session()->regenerateToken();


        $request->session()->forget('emp_id');
        $request->session()->forget('emp_name');
        $request->session()->forget('emp_phone');
        $request->session()->forget('emp_email');
        $request->session()->forget('user_role_id');
        $request->session()->forget('branch_id');
        return view('employee.logout');
    }

}
