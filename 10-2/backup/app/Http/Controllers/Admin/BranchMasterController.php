<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BranchMaster;
use Illuminate\Support\Facades\DB;

class BranchMasterController extends Controller
{

    public function index()
    {
        try
        {
            $BranchMaster = BranchMaster::orderBy('created_at','desc')->orderBy('branch_id','desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.branch.index', compact('BranchMaster'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try
        {
                $BranchMaster = BranchMaster::where(['isDelete'=>0,'iStatus'=>1])->get();
                return view('admin.branch.create',compact('BranchMaster'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    }

    public function store(Request $request)
    {
            $request->validate([
                'branch_name' => 'required',
                'branch_ip' => 'required|ip',
                'branch_emailId' => 'required|email',
                'branch_address' => 'required',
                'branch_phone' => 'required|digits_between:7,15',
            ], [
                'branch_name.required' => 'The branch name is required.',
                'branch_ip.required' => 'The branch IP is required.',
                'branch_ip.ip' => 'Please enter a valid IP address.',
                'branch_emailId.required' => 'The branch email is required.',
                'branch_emailId.email' => 'Please enter a valid email address.',
                'branch_address.required' => 'The branch address is required.',
                'branch_phone.required' => 'The branch phone number is required.',
                'branch_phone.digits_between' => 'The branch phone number must be between 7 to 15 digits.',
            ]);

        try
        {

            $branch_name=$request->branch_name;
            $BranchMaster = BranchMaster::where(['branch_name'=>$branch_name])->get();

            if(sizeof($BranchMaster) == 0)
            {
                    $BranchMaster = new BranchMaster();
                    $BranchMaster->branch_name=$request->branch_name;
                    $BranchMaster->branch_ip=$request->branch_ip;
                    $BranchMaster->branch_emailId=$request->branch_emailId;
                    $BranchMaster->branch_address=$request->branch_address;
                    $BranchMaster->branch_phone=$request->branch_phone;
                    $BranchMaster->save();
                    $branch_id=$BranchMaster->branch_id;

                  

                    return redirect()->route('branch.index')->with('success','Branch Created Successfully');
            }else
            {
                return back()->with('error','This data alredy exist!');
            }

        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function edit(BranchMaster $BranchMaster,$id)
    {
        try{

            $BranchMaster = BranchMaster::where(['isDelete'=>0,'iStatus'=>1,'branch_id' => $id])->first();
            return view('admin.branch.edit',compact('BranchMaster'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    }

    public function update(Request $request, BranchMaster $BranchMaster)
    {
            $request->validate([
                'branch_name' => 'required',
                'branch_ip' => 'required|ip',
                'branch_emailId' => 'required|email',
                'branch_address' => 'required',
                'branch_phone' => 'required|digits_between:7,15',
            ], [
                'branch_name.required' => 'The branch name is required.',
                'branch_ip.required' => 'The branch IP is required.',
                'branch_ip.ip' => 'Please enter a valid IP address.',
                'branch_emailId.required' => 'The branch email is required.',
                'branch_emailId.email' => 'Please enter a valid email address.',
                'branch_address.required' => 'The branch address is required.',
                'branch_phone.required' => 'The branch phone number is required.',
                'branch_phone.digits_between' => 'The branch phone number must be between 7 to 15 digits.',
            ]);

        try
        {

            
            $branch_name=$request->branch_name;
            $branch_id=$request->branch_id;

            $BranchMaster = BranchMaster::where(['branch_name'=>$branch_name])->whereNotIn('branch_id', [$branch_id])->first();

            if(empty($BranchMaster))
            {
                $data = array(
                    'branch_name'=>$request->branch_name,
                    'branch_id'=>$request->branch_id,
                    'branch_name'=>$request->branch_name,
                    'branch_ip'=>$request->branch_ip,
                    'branch_emailId'=>$request->branch_emailId,
                    'branch_address'=>$request->branch_address,
                    'branch_phone'=>$request->branch_phone,
                    
                    );
                BranchMaster::where("branch_id","=",$request->branch_id)->update($data);

                    return redirect()->route('branch.index')->with('success','Branch Updated Successfully');
            }else
            {
                return back()->with('error','branch Name alredy exist!');
            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    } 
    public function delete(Request $request)
    {        
        try
        {
            $id=$request->id;
            BranchMaster::where('branch_id','=',$id)->delete();
            return back()->with('success','Branch Deleted Successfully');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
  
}
