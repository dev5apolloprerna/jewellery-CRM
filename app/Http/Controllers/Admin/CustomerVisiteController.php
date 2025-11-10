<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\ProductCategory;
use App\Models\Customer;
use App\Models\CustomerVisit;
use App\Models\VisitDetail;
use App\Models\CustomerProduct;
use App\Models\Product;
use App\Models\Employee;
use App\Models\CloseReason;
use App\Models\BranchMaster;
use App\Models\Vendor;
use App\Models\Color;
use App\Models\Purity;
use App\Models\OrderStatus;
use Carbon\Carbon;

class CustomerVisiteController extends Controller
{
    public function index(Request $request,$id)
    {
        try
        {
            $Category = ProductCategory::orderBy('category_id', 'desc')->get();
            $Customer = Customer::findOrFail($id);
            $Followups = CustomerVisit::with('visitDetails','closereason')->where(['cust_id'=>$id])->get();
            $CustProducts = CustomerProduct::with('category','product','employee')->where(['cust_id'=>$id])->get();
            $Products = Product::orderBy('product_id', 'desc')->get();
        	$employees = Employee::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>2])->orderBy('emp_name', 'asc')->get();
            $feedback = CustomerVisit::with('visitDetails','closereason')->where(['cust_id'=>$id])->latest()->first();
            $closereason = CloseReason::orderBy('close_reason','asc')->get();


            $color = Color::all();
            $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
            $vendor = Vendor::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>3])->orderBy('contact_person', 'asc')->get();


            return view('admin.new_visite.index', compact('Category','Customer','id','Followups','Products','CustProducts','employees','feedback','closereason','color','branches','vendor'));
       } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }
    public function create($id)
    {
        $Category = ProductCategory::orderBy('category_id', 'desc')->get();
        $Customer = Customer::findOrFail($id);

        $CustProducts = CustomerProduct::with('category','product','employee','orderDetails')->where(['cust_id'=>$id])->get();
        $Products = Product::orderBy('product_id', 'desc')->get();
        $employees = Employee::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>2])->orderBy('emp_name', 'asc')->get();
        $closereason = CloseReason::orderBy('close_reason','asc')->get();

            $color = Color::all();
            $purity = Purity::all();
            $orderStatus = OrderStatus::all();
            $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
            $vendor = Vendor::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>3])->orderBy('contact_person', 'asc')->get();

        return view('admin.new_visite.create', compact('Category','Customer','id','Products','CustProducts','employees','closereason','color','branches','vendor','purity','orderStatus'));

    } 
     public function todayFollowup(Request $request)
    {
        $branchId = $request->branch_id;
        $today = Carbon::today();

        $query = CustomerVisit::where('iStatus', 1)
            ->where('followup_status', 0)
            ->when($branchId, function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })
                ->whereDate('next_followup_date', $today);

           /* if ($request->filled('next_followup_date')) {
                $query->whereDate('next_followup_date', $request->next_followup_date);
            }*/
            $followups = $query->paginate(env('PER_PAGE_COUNT'));

        return view('admin.new_visite.today_followup', compact('followups'));
    }

       
        public function overDue(Request $request)
        {
            $branchId = $request->branch_id;
            $today = Carbon::today();

            $followups = CustomerVisit::where('iStatus', 1)
                ->where('followup_status', 0)
               ->when($branchId, function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                })
                ->whereDate('next_followup_date', '<', $today)
                ->paginate(env('PER_PAGE_COUNT'));

            return view('admin.new_visite.overdue_followup', compact('followups'));
        }
     public function product($id)
        {
            try
            {
                $user = Auth::guard('web_employees')->user();
    
                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;
        
                    $products = CustomerProduct::with(['customer', 'product', 'employee','category','branch','orderDetails.OrderStatus'])->where(['cust_id'=>$id])->whereNull('visit_id')->orderBy('product_id','desc')->paginate(env('PER_PAGE_COUNT'));
                    return view('employee.cust_product.index', compact('products','id'));
                }
                else
                {
                    $products = CustomerProduct::with(['customer', 'product', 'employee','category','branch','orderDetails.OrderStatus'])->where(['cust_id'=>$id])->whereNull('visit_id')->orderBy('product_id','desc')->get();

                            return response()->json($products);

                return view('admin.cust_product.index', compact('products','id'));
                }
            } catch (\Exception $e) 
            {
                    return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

    public function previous_visit(Request $request,$id)
    {
        $prVisite = CustomerVisit::with('visitDetails','closereason','customer','employee')->where(['cust_id'=>$id])->orderBy('visit_id','desc')->paginate(env('PER_PAGE_COUNT'));
        return view('admin.new_visite.previous_visit',compact('prVisite'));

    }
    public function previous_visit_view(Request $request,$id)
    {
        try
        {
            $Category = ProductCategory::orderBy('category_id', 'desc')->get();
            $Followups = VisitDetail::where(['visit_id'=>$id])->orderBy('followup_detail_id','desc')->get();
            $CustProducts = CustomerProduct::with('category','product','employee','orderDetails.OrderStatus')->where(['visit_id'=>$id])->get();
            $Products = Product::orderBy('product_id', 'desc')->get();
            $employees = Employee::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>2])->orderBy('emp_name', 'asc')->get();
            $feedback = CustomerVisit::with('visitDetails','closereason')->where(['visit_id'=>$id])->latest()->first();
            $Customer = Customer::findOrFail($feedback->cust_id);
            
            $color = Color::all();
            $closereason = CloseReason::orderBy('close_reason','asc')->get();
            $purity = Purity::all();
            $orderStatus = OrderStatus::all();
            $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
            $vendor = Vendor::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>3])->orderBy('contact_person', 'asc')->get();

        
            return view('admin.new_visite.previous_visit_view', compact('Category','Customer','id','Followups','Products','CustProducts','employees','feedback','closereason','branches','color','vendor','purity','orderStatus'));
       } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }
    public function view_visit(Request $request,$id)
    {

        /*try
        {*/
            $Category = ProductCategory::orderBy('category_id', 'desc')->get();


            $Followups = VisitDetail::with('custVisit','custVisit.closereason')->where(['visit_id'=>$id])->orderBy('followup_detail_id','desc')->get();
            $CustProducts = CustomerProduct::with('category','product','employee','orderDetails.orderStatus')->where(['visit_id'=>$id])->get();
            $Products = Product::orderBy('product_id', 'desc')->get();
            $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
            $feedback = CustomerVisit::with('visitDetails','closereason')->where(['visit_id'=>$id])->latest()->first();
            $Customer = Customer::findOrFail($feedback->cust_id);

            $closereason = CloseReason::orderBy('close_reason','asc')->get();
            return view('admin.new_visite.view_visit', compact('Category','Customer','id','Followups','Products','CustProducts','employees','feedback','closereason'));
      /* } catch (\Exception $e) 
        {
            report($e);
            return false;
        }*/

    }
 

    public function show()
    {

    }
 }
?>