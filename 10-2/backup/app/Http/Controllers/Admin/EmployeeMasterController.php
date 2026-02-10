<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\BranchMaster; // assuming you have this

class EmployeeMasterController extends Controller
{
    public function index(Request $request)
    {
        try{

           $query = Employee::with('branch'); // Eager load branch

            if ($request->filled('search')) {
                $query->where('emp_name', 'like', '%' . $request->search . '%');
            }
            $employees = $query->orderBy('emp_id','desc')->paginate(env('PER_PAGE_COUNT'));
            
            $search=$request->search;
            return view('admin.employee.index', compact('employees','search'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try{

            $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get(); // for branch dropdown
            return view('admin.employee.add', compact('branches'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'      => 'required|exists:branch_master,branch_id',
            'emp_name'       => 'required|string|max:255',
            'emp_phone'      => 'required|digits_between:10,15|unique:employee_master,emp_phone',
            'emp_email'      => 'required|email|unique:employee_master,emp_email',
            'emp_dob'        => 'required|date',
            'accesOutside'   => 'required|in:Yes,No',
            'password'   => 'required',
        ]);
        try{
            
                Employee::create([
                    'branch_id'    => $request->branch_id,
                    'emp_name'     => $request->emp_name,
                    'emp_phone'    => $request->emp_phone,
                    'emp_email'    => $request->emp_email,
                    'emp_dob'      => $request->emp_dob,
                    'accesOutside' => $request->accesOutside,
                    'password'     => Hash::make($request->password),
                    'role_id'     => 2,
                ]);
            

            return redirect()->route('empMaster.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try{

            $employee = Employee::findOrFail($id);
            $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get(); // for branch dropdown
            return view('admin.employee.edit', compact('employee', 'branches'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'branch_id'      => 'required|exists:branch_master,branch_id',
            'emp_name'       => 'required|string|max:255',
            'emp_phone' => 'required|digits_between:10,15|unique:employee_master,emp_phone,' . $id . ',emp_id',
            'emp_email'      => 'required|email|unique:employee_master,emp_email,' . $id . ',emp_id',
            'emp_dob'        => 'required|date',
            'accesOutside'   => 'required|in:Yes,No',
        ]);
        try{   
            $employee->update($validated);
            return redirect()->route('empMaster.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
     public function changepassword($id)
    {
        $userid=$id;
        return view('admin.employee.changepassword', compact('userid'));
    }
    public function updatepassword(Request $request,$id)
    {
     try{

        $employee = Employee::where('emp_id', '=', $id)->first();

                $newpassword = $request->new_password;
                $confirmpassword = $request->new_confirm_password;

                if ($newpassword == $confirmpassword) 
                {
                        $Employee = DB::table('employee_master')
                        ->where(['emp_id' => $id])
                        ->update([
                            'password' => Hash::make($confirmpassword),
                        ]);
    
                    return back()->with('success', 'Employee Password Updated Successfully.');
                } else {
                    return back()->with('error', 'Password and Confirm Password does not match');
                }
        
         } catch (\Exception $e) {
            // Log the exception or handle it in any other way you prefer
            return redirect()->back()->with('error', 'An error occurred while updating the employee.');
        }
    }

    public function destroy(Request $request)
    {
        try{
            
            $employee = Employee::findOrFail($request->emp_id);
            $employee->delete();

            return redirect()->route('empMaster.index')->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
