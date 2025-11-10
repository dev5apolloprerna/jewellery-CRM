<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerVisit;
use App\Models\VisitDetail;
use App\Models\CustomerProduct;
use App\Models\CustOrder;
use App\Models\Employee;

use App\Models\BranchMaster;

use Carbon\Carbon;

class CustomerFollowupController extends Controller
{
       public function index(Request $request,$id)
    {
        try
        {
             $user = Auth::guard('web_employees')->user();

             if ($user && $user->emp_id != null && $user->branch_id != null) 
             {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                $followups = CustomerVisit::with(['customer', 'employee','branch'])->where(['cust_id'=>$id])->orderBy('visit_id','desc')->paginate(env('PER_PAGE_COUNT'));
                return view('employee.cust_followup.index', compact('followups','id'));
            }else{
               $query = CustomerVisit::with(['customer', 'employee', 'branch'])
                        ->where('cust_id', $id);

                    // Filter by date range if both dates are provided
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $query->whereBetween('next_followup_date', [$request->from_date, $request->to_date]);
                    }

                    // Filter by emp_id if provided
                    if ($request->filled('emp_id')) {
                        $query->where('emp_id', $request->emp_id);
                    }

                    // Get paginated results
                    $followups = $query->orderBy('visit_id', 'desc')->paginate(env('PER_PAGE_COUNT'));

                $employees=Employee::where(['iStatus'=>1,'isDelete'=>0])->get();
                return view('admin.cust_followup.index', compact('followups','id','employees'));

            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function viewByBranch(Request $request, $branchId)
    {
        $today = Carbon::today();

        $query = CustomerVisit::where('iStatus', 1)
            ->where('followup_status', 0)
            ->where('branch_id', $branchId)
            ->whereDate('next_followup_date', '<=', $today);

        if ($request->filled('next_followup_date')) {
            $query->whereDate('next_followup_date', $request->next_followup_date);
        }

        if ($request->filled('emp_id')) {
            $query->where('emp_id', $request->emp_id);
        }

        $followups = $query->paginate(env('PER_PAGE_COUNT'));

        $employees = Employee::where('branch_id', $branchId)->get();

        return view('admin.cust_followup.index', compact('followups', 'employees', 'branchId'));
    }


    public function create($id)
    {
        try{
                 $user = Auth::guard('web_employees')->user();

                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;
        
                    $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                    return view('employee.cust_followup.add', compact('branch_id', 'employees','id'));
                }else
                {
                    $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                    $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                    return view('admin.cust_followup.add', compact('branches', 'employees','id'));
                }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'cust_id' => 'required',
            'remark' => 'required',
            'visit_date' => 'required',
            'emp_id' => 'required',
            'next_followup_date' => 'required_if:followup_status,0',
            'close_reason_id' => 'required_if:followup_status,1',
        ]);
             try{
                 $user = Auth::guard('web_employees')->user();
                $id=$request->cust_id;

                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;

                     if($request->followup_status == 0 && $request->visit_id)
                        {
                            $followup = CustomerVisit::findOrFail($request->visit_id);
                             $followup->update($request->all());

                        }else if($request->followup_status == 1 &&  $request->visit_id)
                        {
                            $followup = CustomerVisit::findOrFail($request->visit_id);
                            if($followup->followup_status == 1)
                            {
                                return redirect()->route('EMPvisit.previous_visit',$id)->with('error', 'Customer Follow-up already closed.');   
        
                            }
                            $followup->update($request->all());

                        }
                        else
                        {
                            $data=$request->all();
                            $data['branch_id']=$user->branch_id;
                            $followup=CustomerVisit::create($data);

                                CustomerProduct::whereNull('visit_id')
                                ->where('cust_id', $request->cust_id)
                                ->update(['visit_id' => $followup->visit_id]);                                
                                
                                
                                CustOrder::whereNull('visit_id')
                                ->where('cust_id', $request->cust_id)
                                ->update(['visit_id' => $followup->visit_id]);
                        }
                        if($request->followup_status != 1 )
                        {

                           VisitDetail::create([
                                'visit_id'     => $followup->visit_id,
                                'cust_id'       =>  $id,
                                'visit_date'  => $request->visit_date ?? '',
                                'next_followup_date'  => $request->next_followup_date ?? '',
                                'emp_id'  => $request->emp_id ?? '',
                                'branch_id'  => $user->branch_id ?? '',
                                'remark'      => $request->remark ?? ''
                            ]);
                        }
                        
                    return redirect()->route('EMPvisit.previous_visit',$id)->with('success', 'Customer Follow-up added successfully.');   

                }else
                {
                    
                    $emp=Employee::findOrFail($request->emp_id);
                    
                        if($request->followup_status == 0 && $request->visit_id)
                        {
                            $followup = CustomerVisit::findOrFail($request->visit_id);
                            $data=$request->all();
                            $data['branch_id']=$emp->branch_id;

                            $followup->update($data);

                        }else if($request->followup_status == 1 &&  $request->visit_id)
                        {
                            $followup = CustomerVisit::findOrFail($request->visit_id);
                            if($followup->followup_status == 1)
                            {
                                return redirect()->route('newVisite.previous_visit',$id)->with('error', 'Customer Follow-up already closed.');   
        
                            }
                            $data=$request->all();
                            $data['branch_id']=$emp->branch_id;

                            $followup->update($data);

                        }
                        else
                        {
                           $data=$request->all();
                            $data['branch_id']=$emp->branch_id;

                            $followup=CustomerVisit::create($data);

                                CustomerProduct::whereNull('visit_id')
                                ->where('cust_id', $request->cust_id)
                                ->update(['visit_id' => $followup->visit_id]);
                                
                                 CustOrder::whereNull('visit_id')
                                ->where('cust_id', $request->cust_id)
                                ->update(['visit_id' => $followup->visit_id]);
                        }
                        if($request->followup_status != 1 )
                        {
                           VisitDetail::create([
                                'visit_id'     => $followup->visit_id,
                                'cust_id'       =>  $id,
                                'visit_date'  => $request->visit_date ?? '',
                                'next_followup_date'  => $request->next_followup_date ?? '',
                                'emp_id'  => $request->emp_id ?? '',
                                'branch_id'  => $emp->branch_id ?? '',
                                'remark'      => $request->remark ?? ''
                            ]);
                        }
                        
                    return redirect()->route('newVisite.previous_visit',$id)->with('success', 'Customer Follow-up added successfully.');   

                }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try{
             $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                    $followup = CustomerFollowup::findOrFail($id);
                    $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                    return view('employee.cust_followup.edit', compact('followup', 'branch_id', 'employees','id'));

                }else{
                    $followup = CustomerFollowup::findOrFail($id);
                    $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                    $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                    return view('admin.cust_followup.edit', compact('followup', 'branches', 'employees','id'));

                }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'cust_id' => 'required',
            'remark' => 'required',
            'branch_id' => 'required',
            'emp_id' => 'required',
            'next_date' => 'required|date',
        ]);
        try{
                 $user = Auth::guard('web_employees')->user();

                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;
        
                    $followup = CustomerFollowup::findOrFail($id);
                    $followup->update($request->all());
                    $idd=$request->cust_id;
                    return redirect()->route('EMPcustFollowup.index',[$idd])->with('success', 'Customer Follow-up updated successfully.');

                }else{
                    $followup = CustomerFollowup::findOrFail($id);
                    $followup->update($request->all());
                    $idd=$request->cust_id;
                    return redirect()->route('custFollowup.index',[$idd])->with('success', 'Customer Follow-up updated successfully.');

                }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try
        {
                $user = Auth::guard('web_employees')->user();

                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;
        
                    CustomerFollowup::findOrFail($id)->delete();
                    return redirect()->route('EMPcustFollowup.index')->with('success', 'Customer Follow-up deleted successfully.');

                }else{
                    CustomerFollowup::findOrFail($id)->delete();
                    return redirect()->route('custFollowup.index')->with('success', 'Customer Follow-up deleted successfully.');

                }
         } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
