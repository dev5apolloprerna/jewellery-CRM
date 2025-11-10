<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Customer;
use App\Models\CustomerCategory;
use App\Models\CastMaster;
use App\Models\State;
use App\Models\BranchMaster;
use App\Models\VisitDetail;
use App\Models\CustomerProduct;
use App\Models\CustomerVisit;
use App\Models\CustOrder;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

class CustomerMasterController extends Controller
{
    public function index(Request $request)
    {
        try
        {

           $user = Auth::guard('web_employees')->user();

        if ($user && $user->emp_id != null && $user->branch_id != null) 
        {
            $empid = $user->emp_id;
            $branch_id = $user->branch_id;
        
            $query = Customer::with('branch','custCat','latestVisit','cast');
        
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('customer_name', 'like', '%' . $request->search . '%')
                      ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                      ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
                });
            }else{
                $query->where('branch_id', $branch_id);
            }
        
            $customers = $query->orderBy('customer_id','desc')->paginate(env('PER_PAGE_COUNT'));
            $search = $request->search;
        
            return view('employee.customer.index', compact('customers', 'search'));
        } else 
        {
            
            $query = Customer::with('branch', 'custCat','latestVisit');

            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('customer_name', 'like', '%' . $request->search . '%')
                      ->orWhere('customer_email', 'like', '%' . $request->search . '%')
                      ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
                });
            }

            $customers = $query->orderBy('customer_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            $search = $request->search;

            return view('admin.customer.index', compact('customers', 'search'));
        }

        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try
        {
            $custCatgory=CustomerCategory::where(['iStatus'=>1,'isDelete'=>0])->orderBy('cust_cat_name','asc')->get();
            $cast=CastMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('cast','asc')->get();
            $state=State::where(['iStatus'=>1,'isDelete'=>0])->orderBy('stateName','asc')->get();
             $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
            
                return view('employee.customer.add', compact('branch_id','custCatgory','state','cast'));

            }else{

                $branches = BranchMaster::pluck('branch_name', 'branch_id');
                return view('admin.customer.add', compact('branches','custCatgory','state','cast'));
            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string|unique:customer_master,customer_phone',
            'customer_type' => 'required',
            'branch_id' => 'required',
            'cast_id' => 'required',
        ]);

        try
        {
            $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) {
            $empid = $user->emp_id;
            $branch_id = $user->branch_id;
        
                $customer=Customer::create($request->all());
                return redirect()->route('EMPvisit.create',$customer->customer_id)->with('success', 'Customer added successfully');
            }else{
                $customer=Customer::create($request->all());
                return redirect()->route('newVisite.create',$customer->customer_id)->with('success', 'Customer added successfully');

            }
         } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try
        {
            $user = Auth::guard('web_employees')->user();
            $state=State::where(['iStatus'=>1,'isDelete'=>0])->orderBy('stateName','asc')->get();
            $custCatgory=CustomerCategory::where(['iStatus'=>1,'isDelete'=>0])->orderBy('cust_cat_name','asc')->get();
            $cast=CastMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('cast','asc')->get();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
            
                $customer = Customer::findOrFail($id);
                return view('employee.customer.edit', compact('customer','branch_id','custCatgory','state','cast'));

            }else{
                $customer = Customer::findOrFail($id);
                $branches = BranchMaster::pluck('branch_name', 'branch_id');
                return view('admin.customer.edit', compact('customer', 'branches','custCatgory','state','cast'));

            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try{

            $customer = Customer::findOrFail($id);

            $request->validate([
                'customer_name' => 'required|string',
                 'customer_phone' => [
                    'required',
                    'string',
                    Rule::unique('customer_master', 'customer_phone')->ignore($id, 'customer_id'),
                ],
                'customer_type' => 'required',
                'branch_id' => 'required',
                'cast_id' => 'required',
            ]);

            $customer->update($request->all());

             $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
            
                 return redirect()->route('EMPcustomer.index')->with('success', 'Customer updated successfully');
            }else{

                 return redirect()->route('customer.index')->with('success', 'Customer updated successfully');
             }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

public function destroy(Request $request)
    {
        try{
            
            $customer = Customer::findOrFail($request->customer_id);
            $customer->delete();
            
             $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                return redirect()->route('EMPcustomer.index')->with('success', 'Customer deleted successfully.');
            }else{

                return redirect()->route('customer.index')->with('success', 'Customer deleted successfully.');
            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function show()
    {
        
    }
    public function validateCustomer (Request $request)
    {
        // try {
        
            $exists = Customer::where([
                'customer_phone' => $request->customer_phone
            ])->exists();
  
            return response()->json($exists ? 1 : 0);
        /*} catch (\Exception $e) {
            report($e);
            return response()->json(0); // return 0 on error to avoid breaking frontend
        }*/
    }

    public function validateEditCustomer(Request $request)
    {
        try{
        $data = Customer::where(['iStatus' => 1, 'isDelete' => 0, 'customer_phone' => $request->customer_phone])->whereNotIN('customer_id',[$request->customer_id])->count();
        if ($data > 0) 
        {
            echo 1;
        } else {
            echo 0;
        }
        } catch (\Exception $e) {

        report($e);
 
        return false;
        }
    }
    public function history($id)
    {
        $user = Auth::guard('web_employees')->user();
        $custId=$id;
            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                    $user = auth()->user();
                    $empid = $user->emp_id ?? null;
                    $branch_id = $user->branch_id ?? null;



                $customer = Customer::with(['cast','branch'])->findOrFail($id);

                // All visits for this customer (newest first) + followups (hasMany) + close reason
                $visits = CustomerVisit::where('cust_id', $id)
                    ->with([
                        'closereason',
                        'visitDetails',            // IMPORTANT: hasMany using localKey visit_id (see model note below)
                        'visitDetails.employee',
                    ])
                    ->orderByDesc('visit_date')
                    ->get();

                $visitBlocks = [];
                $grand = ['amount'=>0.0, 'net'=>0.0, 'paid'=>0.0, 'due'=>0.0];

                foreach ($visits as $visit) {
                    $visitId = $visit->visit_id; // both tables use visit_id

                    // Viewed products for this visit
                    $viewProducts = CustomerProduct::with([
                            'category','product','employee',
                            'orderDetails','orderDetails.color','orderDetails.vendor',
                        ])
                        ->where('cust_id', $id)
                        ->where('visit_id', $visitId)
                        ->where('status', 'view')
                        ->get();

                    // Purchased products for this visit
                    $purchasedProducts = CustomerProduct::with([
                            'category','product','employee',
                            'orderDetails','orderDetails.color','orderDetails.vendor','orderDetails.OrderStatus',
                        ])
                        ->where('cust_id', $id)
                        ->where('visit_id', $visitId)
                        ->where('status', '!=', 'view')
                        ->get();

                    // Orders (money lives on CustOrder)
                    // If your total columns are different, replace with your column names.
                    $orders = CustOrder::where('cust_id', $id)
                        ->where('visit_id', $visitId)
                        ->get(['order_id','amount','net_total','paid_amount','due_amount']);

                    $orderBreakdown = $orders->map(fn($o) => [
                        'order_id'  => $o->order_id,
                        'amount'    => (float) $o->amount,
                        'net_total' => (float) $o->net_total,
                        'paid'      => (float) $o->paid_amount,
                        'due'       => (float) $o->due_amount,
                    ])->values();

                    // Per-visit totals
                    $visitAmount = (float) $orders->sum('amount');
                    $visitNet    = (float) $orders->sum('net_total');
                    $visitPaid   = (float) $orders->sum('paid_amount');
                    $visitDue    = (float) $orders->sum('due_amount');

                    // Grand totals
                    $grand['amount'] += $visitAmount;
                    $grand['net']    += $visitNet;
                    $grand['paid']   += $visitPaid;
                    $grand['due']    += $visitDue;

                    $visitBlocks[] = [
                        'visit'             => $visit,
                        'followups'         => $visit->visitDetails, // ALL followups (hasMany)
                        'viewProducts'      => $viewProducts,
                        'purchasedProducts' => $purchasedProducts,
                        'orderBreakdown'    => $orderBreakdown,
                        'totals'            => [
                            'amount'=>$visitAmount, 'net'=>$visitNet, 'paid'=>$visitPaid, 'due'=>$visitDue,
                        ],
                    ];
                }

                return view('employee.customer.history_detail', compact('customer','visitBlocks','grand'));
            }else{
    
                 $customer = Customer::with(['cast','branch'])->findOrFail($custId);

                $visits = CustomerVisit::with(['closereason', 'visitDetails', 'visitDetails.employee'])
                    ->where('cust_id', $custId)
                    ->orderByDesc('visit_date')
                    ->get();

                $visitBlocks = [];
                $grand = ['net'=>0.0, 'paid'=>0.0, 'due'=>0.0, 'amount'=>0.0];

                foreach ($visits as $visit) {
                    $visitId = $visit->visit_id; // <<< use visit_id

                    $viewProducts = CustomerProduct::with([
                            'category','product','employee',
                            'orderDetails','orderDetails.color','orderDetails.vendor'
                        ])
                        ->where('cust_id', $custId)
                        ->where('visit_id', $visitId)
                        ->where('status', 'view')
                        ->get();

                    $purchasedProducts = CustomerProduct::with([
                            'category','product','employee',
                            'orderDetails','orderDetails.color','orderDetails.vendor'
                        ])
                        ->where('cust_id', $custId)
                        ->where('visit_id', $visitId)
                        ->where('status', '!=', 'view')
                        ->get();

                    // amounts live on CustOrder: amount, net_total, paid_amount, due_amount
                    $orders = CustOrder::where('cust_id', $custId)
                        ->where('visit_id', $visitId)
                        ->get(['order_id','amount','net_total','paid_amount','due_amount']);

                    $orderBreakdown = $orders->map(fn($o) => [
                        'order_id'  => $o->order_id,
                        'amount'    => (float)$o->amount,
                        'net_total' => (float)$o->net_total,
                        'paid'      => (float)$o->paid_amount,
                        'due'       => (float)$o->due_amount,
                    ])->values();

                    $visitAmount = (float)$orders->sum('amount');
                    $visitNet    = (float)$orders->sum('net_total');
                    $visitPaid   = (float)$orders->sum('paid_amount');
                    $visitDue    = (float)$orders->sum('due_amount');

                    $grand['amount'] += $visitAmount;
                    $grand['net']    += $visitNet;
                    $grand['paid']   += $visitPaid;
                    $grand['due']    += $visitDue;

                    $visitBlocks[] = [
                        'visit'             => $visit,
                        'followups'         => $visit->visitDetails,   // now ALL followups
                        'viewProducts'      => $viewProducts,
                        'purchasedProducts' => $purchasedProducts,
                        'orderBreakdown'    => $orderBreakdown,
                        'totals'            => [
                            'amount'=>$visitAmount, 'net'=>$visitNet, 'paid'=>$visitPaid, 'due'=>$visitDue,
                        ],
                    ];
                }

                return view('admin.customer.history_detail', compact('customer','visitBlocks','grand'));

               
            }
    }
}
