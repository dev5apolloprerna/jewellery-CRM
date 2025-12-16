<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\CustOrder;
use App\Models\CustOrderDetail;
use App\Models\Customer;
use App\Models\CustomerFollowup;
use App\Models\Employee;
use App\Models\BranchMaster;
use App\Models\CustomerProduct;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\VisitDetail;
use App\Models\CustomerVisit;
use App\Models\Color;
use App\Models\Vendor;
use App\Models\OrderStatus;

use Carbon\Carbon;

class CustomerOrderController extends Controller
{
    public function index(Request $request,$date = null)
    {
        try{
            $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                //$orders = CustOrder::with(['branch','customer','customerVisit','orderDetails.employee'])->where(['emp_id'=>$empid])->whereNotNull('visit_id')->orderBy('order_id','desc')->paginate(env('PER_PAGE_COUNT'));
                $orders = CustOrder::with([
                            'branch', 
                            'customer', 
                            'customerVisit', 
                            'orderDetails.employee', 
                            'orderDetails.OrderStatus',
                            'payment_detail'
                        ])
                        ->whereHas('orderDetails', function ($q) use ($branch_id) {
                            $q->where('branch_id', $branch_id);
                            $q->where('delivery_status', '!=',9);
                        })
                        ->whereNotNull('visit_id');
                
                            if ($date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                                $orders->whereHas('payment_detail', function ($q) use ($date) {
                                    $q->whereDate('next_followup_date', Carbon::parse($date));
                                });
                            }


                
                    $orders = $orders->orderBy('order_id', 'desc')
                     ->paginate(env('PER_PAGE_COUNT'));


                $orderStatus = OrderStatus::all();

                return view('employee.cust_order.index', compact('orders','orderStatus'));

            }else{
                $orders = CustOrder::with([
                            'branch',
                            'customer',
                            'customerVisit',
                            'orderDetails.employee',
                            'orderDetails.OrderStatus',
                            'payment_detail'
                        ])
                        ->whereNotNull('visit_id')
                        ->whereHas('orderDetails', function ($q) {
                            $q->where('delivery_status', 9);
                        });

                    if ($date) 
                    {
                        $orders->whereHas('payment_detail', function ($q) use ($date) {
                            $q->whereDate('next_followup_date', Carbon::parse($date));
                        });
                    }

                    $orders = $orders->orderBy('order_id', 'desc')
                        ->paginate(env('PER_PAGE_COUNT'));

              
                $orderStatus = OrderStatus::all();

                return view('admin.cust_order.index', compact('orders','orderStatus'));

            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

public function purchased(Request $request,$date = null)
    {
        try{
            $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                //$orders = CustOrder::with(['branch','customer','customerVisit','orderDetails.employee'])->where(['emp_id'=>$empid])->whereNotNull('visit_id')->orderBy('order_id','desc')->paginate(env('PER_PAGE_COUNT'));
                $orders = CustOrder::with([
                            'branch', 
                            'customer', 
                            'customerVisit', 
                            'orderDetails.employee', 
                            'orderDetails.OrderStatus',
                            'payment_detail'
                        ])
                        ->whereHas('orderDetails', function ($q) use ($branch_id) {
                            $q->where('branch_id', $branch_id)->where('delivery_status', 9);
                        })
                        ->whereNotNull('visit_id');
                
                        if ($date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                            $orders->whereHas('payment_detail', function ($q) use ($date) {
                                $q->whereDate('next_followup_date', Carbon::parse($date));
                            });
                        }
                    $orders = $orders->orderBy('order_id', 'desc')
                     ->paginate(env('PER_PAGE_COUNT'));


                $orderStatus = OrderStatus::all();

                return view('employee.cust_order.purchased', compact('orders','orderStatus'));

            }else{
                $orders = CustOrder::with([
                            'branch',
                            'customer',
                            'customerVisit',
                            'orderDetails.employee',
                            'orderDetails.OrderStatus',
                            'payment_detail'
                        ])
                        ->whereNotNull('visit_id')
                        ->whereHas('orderDetails', function ($q) {
                            $q->where('delivery_status', 9);
                        });

                    if ($date) 
                    {
                        $orders->whereHas('payment_detail', function ($q) use ($date) {
                            $q->whereDate('next_followup_date', Carbon::parse($date));
                        });
                    }

                    $orders = $orders->orderBy('order_id', 'desc')
                        ->paginate(env('PER_PAGE_COUNT'));

              
                $orderStatus = OrderStatus::all();

                return view('admin.cust_order.purchased', compact('orders','orderStatus'));

            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function create($id)
    {
        try
        {    
            $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                return view('employee.cust_order.form', compact('employees', 'branch_id','id'));
            }else{
                $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                return view('admin.cust_order.form', compact('employees', 'branches','id'));
            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function orderProduct(Request $request)
    {
        /*try
        {*/
   
            $product = CustomerProduct::with('customervisit')->find($request->cust_pro_id);
            $visitId = $product->customervisit->visit_id ?? null;

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found.']);
            }
               
             if($request->delivery_status == 1 || $request->delivery_status == 2)
                {
                    // Toggle status (1 to 0 or 0 to 1)
                    if($request->delivery_status == 1)
                    {
                        $status='delivered';
                    }elseif($request->delivery_status == 2)
                    {
                        
                        $status='ordered';
                    }
                    CustomerProduct::where('cust_pro_id', $request->cust_pro_id)->update(['status' => $status]);

                }
            $customer = Customer::findOrFail($product->cust_id);

            $order = CustOrder::where('cust_id', $product->cust_id)
                ->where('visit_id', $visitId)
                // ->where('emp_id', $product->emp_id)
                ->where('branch_id', $customer->branch_id)
                ->first();
    
            if (!$order) {
                if($request->paid_amount != 0)
                {
                    $due_amount = $request->amount - $request->paid_amount;
                }else{
                      $due_amount = $request->amount;
                }
            
                $order = new CustOrder();
                $order->cust_id = $product->cust_id;
                $order->visit_id = $visitId;
                // $order->emp_id = $product->emp_id;
                $order->branch_id = $customer->branch_id;
                $order->amount = $request->amount;
                $order->net_total = $request->amount;
                $order->paid_amount = $request->paid_amount;
                $order->due_amount = $due_amount;
                $order->rate_type = $request->rate_type;
                $order->save();
            } else {
            
                // Add new values to existing totals
                $order->amount += $request->amount;
                $order->net_total += $request->amount;
                $order->paid_amount += $request->paid_amount;
                $order->due_amount = $order->net_total - $order->paid_amount;
                $order->save();
            }
            
            $existingOrderDetail = CustOrderDetail::where('cust_pro_id', $request->cust_pro_id)->first();


            if ($existingOrderDetail) 
            {

                    $existingOrderDetail->karat=$request->karat;
                    $existingOrderDetail->color_id=$request->color_id;
                    $existingOrderDetail->weight=$request->weight;
                    $existingOrderDetail->size=$request->size;
                    $existingOrderDetail->refer_tag_number=$request->refer_tag_number;
                    $existingOrderDetail->refer_image_url=$request->refer_image_url;
                    $existingOrderDetail->status=$request->status;
                    $existingOrderDetail->amount=$request->amount;
                    $existingOrderDetail->net_total=$request->amount;
                    $existingOrderDetail->given_to=$request->given_to;
                    $existingOrderDetail->remark = $request->remark;
                    $existingOrderDetail->delivery_date = $request->delivery_date;
                    $existingOrderDetail->delivery_status = $request->delivery_status;
                    $existingOrderDetail->rate_type = $request->rate_type;
                    $existingOrderDetail->rate_fix_open = $request->rate_fix_open;
                    $existingOrderDetail->save();

                $orderDetailsedit = CustOrderDetail::where('order_id', $existingOrderDetail->order_id)->get();
                
                $totalAmount = $orderDetailsedit->sum('amount');
                $totalNetTotal = $orderDetailsedit->sum('net_total');
                
                // Keep paid_amount same, just adjust due_amount based on updated totals
                $order->amount = $totalAmount;
                $order->net_total = $totalNetTotal;
                $order->due_amount = $order->net_total - $order->paid_amount;
                $order->save();

            }else{
                $orderDetails = new CustOrderDetail();
                $orderDetails->order_id = $order->order_id;
                $orderDetails->cust_id = $order->cust_id;
                $orderDetails->emp_id = $product->emp_id;
                $orderDetails->branch_id = $order->branch_id;
                $orderDetails->cust_pro_id = $request->cust_pro_id;
                $orderDetails->product_id = $request->product_id;
                $orderDetails->karat=$request->karat;
                $orderDetails->color_id=$request->color_id;
                $orderDetails->weight=$request->weight;
                $orderDetails->size=$request->size;
                $orderDetails->refer_tag_number=$request->refer_tag_number;
                $orderDetails->refer_image_url=$request->refer_image_url;
                $orderDetails->status=$request->status;
                $orderDetails->amount=$request->amount;
                $orderDetails->net_total=$request->amount;
                $orderDetails->given_to=$request->given_to;
                $orderDetails->remark = $request->remark;
                $orderDetails->delivery_date = $request->delivery_date;
                $orderDetails->delivery_status = $request->delivery_status;
                $orderDetails->rate_type = $request->rate_type;
                $orderDetails->rate_fix_open = $request->rate_fix_open;
                
                $orderDetails->save();
                
            }
            $product->status = 'ordered';
            $product->save();
    
        

        return response()->json([
            'success' => true,
            'message' => 'Product ordered successfully']);
        /*} catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }*/
    }
    public function store(Request $request)
    {
         $request->validate([
            'cust_id' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'emp_id' => 'required|numeric',
            'amount' => 'nullable|numeric',
            'net_total' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric',
            'remark' => 'nullable|string',
            'rate_type' => 'nullable|string',
            'delivery_type' => 'nullable|string',
        ]);
         try{
            
            $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                CustOrder::create($request->all());
                $id=$request->cust_id;
                return redirect()->route('EMPcustOrder.index',[$id])->with('success', 'Order created successfully.');
            }else{

                CustOrder::create($request->all());
                $id=$request->cust_id;
                return redirect()->route('custOrder.index',[$id])->with('success', 'Order created successfully.');
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
        
                $order = CustOrder::findOrFail($id);
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();

                return view('employee.cust_order.form', compact('order', 'employees', 'branch_id'));

            }else{
                $order = CustOrder::with('customerVisit')->findOrFail($id);
                $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                $Customer = Customer::findOrFail($order->cust_id);
                $CustProducts = CustomerProduct::with('category','product','employee')->where(['visit_id'=>$order->customerVisit->visit_id])->get();
                $orderDetails = CustOrderDetail::with(['product'])->where('order_id', $order->order_id)->orderBy('detail_order_id','desc')->paginate(env('PER_PAGE_COUNT')); 

                $color = Color::all();
                $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                $vendor = Vendor::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>3])->orderBy('contact_person', 'asc')->get();

                return view('admin.cust_order.edit', compact('order', 'employees', 'branches','Customer','id','CustProducts','orderDetails','color','branches','vendor'));

            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function detail($id)
    {
        $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
    
                $Followups = VisitDetail::where(['visit_id'=>$id])->orderBy('followup_detail_id','desc')->get();
    
                $CustProducts = CustomerProduct::with('category','product','employee','orderDetails','orderDetails.color','orderDetails.vendor')->where(['visit_id'=>$id])->get();
    
                $feedback = CustomerVisit::with('visitDetails','closereason')->where(['visit_id'=>$id])->latest()->first();
    
                $Customer = Customer::findOrFail($feedback->cust_id);
                $orderIds = CustOrder::where('visit_id', $id)->pluck('order_id');
    
                $paymentDetail = PaymentDetail::whereIn('order_id', $orderIds)
                    ->orderBy('payment_detail_id', 'desc')
                    ->paginate(env('PER_PAGE_COUNT'));
            

                return view('employee.cust_order.order_detail', compact('Customer','id','Followups','CustProducts','feedback','paymentDetail'));
            }else{
    
                $Followups = VisitDetail::with('custVisit','custVisit.closereason')->where(['visit_id'=>$id])->orderBy('followup_detail_id','desc')->get();
    
                $CustProducts = CustomerProduct::with('category','product','employee','orderDetails','orderDetails.color','orderDetails.vendor')->where(['visit_id'=>$id])->get();
    
                $feedback = CustomerVisit::with('visitDetails','closereason')->where(['visit_id'=>$id])->latest()->first();
    
                $Customer = Customer::findOrFail($feedback->cust_id);
                $orderIds = CustOrder::where('visit_id', $id)->pluck('order_id');
    
                $paymentDetail = PaymentDetail::whereIn('order_id', $orderIds)
                    ->orderBy('payment_detail_id', 'desc')
                    ->paginate(env('PER_PAGE_COUNT'));
            
                
                return view('admin.cust_order.order_detail', compact('Customer','id','Followups','CustProducts','feedback','paymentDetail'));
               
            }
    }
    public function edit_old($id)
    {
        try{

             $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                $order = CustOrder::findOrFail($id);
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();

                return view('employee.cust_order.form', compact('order', 'employees', 'branch_id'));

            }else{
                $order = CustOrder::findOrFail($id);
                $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();

                return view('admin.cust_order.form', compact('order', 'employees', 'branches'));

            }
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'cust_id' => 'required|numeric',
            'branch_id' => 'required|numeric',
            'emp_id' => 'required|numeric',
            'amount' => 'nullable|numeric',
            'net_total' => 'nullable|numeric',
            'advance_payment' => 'nullable|numeric',
            'remark' => 'nullable|string',
            'rate_type' => 'nullable|string',
            'delivery_type' => 'nullable|string',
        ]);
         try{

             $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                $order = CustOrder::findOrFail($id);
                $order->update($request->all());
                $idd=$request->cust_id;
                return redirect()->route('EMPcustOrder.index',[$idd])->with('success', 'Order updated successfully.');   

            }else{
                $order = CustOrder::findOrFail($id);
                $order->update($request->all());
                $idd=$request->cust_id;
                return redirect()->route('custOrder.index',[$idd])->with('success', 'Order updated successfully.');

            }
         } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        try{

            $order = CustOrder::findOrFail($id);
            return view('admin.cust_order.show', compact('order'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
 public function destroy(Request $request)
    {
        try{
            $id=$request->order_id;
             CustOrderDetail::where(['order_id'=>$id])->delete();
             Payment::where(['order_id'=>$id])->delete();
             PaymentDetail::where(['order_id'=>$id])->delete();
             CustOrder::findOrFail($id)->delete();

            return redirect()->back()->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
?>
