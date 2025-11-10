<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\CustOrder;
use App\Models\CustOrderDetail;
use App\Models\Customer;
use App\Models\CustomerFollowup;
use App\Models\Employee;
use App\Models\BranchMaster;
use App\Models\CustomerProduct;
use App\Models\PaymentDetail;
use App\Models\Payment;
use App\Models\Color;
use App\Models\OrderStatus;
use App\Models\Vendor;


class OrderPaymentController extends Controller
{
    public function index(Request $request,$id)
    {
        // try{
            $user = Auth::guard('web_employees')->user();

            if ($user && $user->emp_id != null && $user->branch_id != null) 
            {
                $empid = $user->emp_id;
                $branch_id = $user->branch_id;
        
                $order = CustOrder::with('customerVisit','orderDetails')->findOrFail($id);
                $orderDetailIds = $order->orderDetails->pluck('detail_order_id')->toArray(); // Collection of all detail IDs
                $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                $Customer = Customer::findOrFail($order->cust_id);
                $CustProducts = CustOrderDetail::with(['product','vendor','orderStatus'])->where(['order_id'=>$order->order_id])->get();
                $paymentDetail = PaymentDetail::where('order_id', $order->order_id) // <- use whereIn
                    ->orderBy('payment_detail_id', 'desc')
                    ->paginate(env('PER_PAGE_COUNT'));
            $color = Color::all();
            $orderStatus = OrderStatus::all();
            $vendor = Vendor::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>3])->orderBy('contact_person', 'asc')->get();

                 return view('employee.payment.index',compact('order', 'employees', 'branches','Customer','id','CustProducts','paymentDetail','orderDetailIds','color','vendor','orderStatus'));

            }else{
              
                $order = CustOrder::with('customerVisit','orderDetails')->findOrFail($id);
                $orderDetailIds = $order->orderDetails->pluck('detail_order_id')->toArray(); // Collection of all detail IDs
                $branches = BranchMaster::where(['iStatus'=>1,'isDelete'=>0])->orderBy('branch_name', 'asc')->get();
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                $Customer = Customer::findOrFail($order->cust_id);
                $CustProducts = CustOrderDetail::with(['product','vendor','orderStatus'])->where(['order_id'=>$order->order_id])->get();
                $paymentDetail = PaymentDetail::where('order_id', $order->order_id) // <- use whereIn
                    ->orderBy('payment_detail_id', 'desc')
                    ->paginate(env('PER_PAGE_COUNT'));

            $color = Color::all();
            $orderStatus = OrderStatus::all();
            $vendor = Vendor::where(['iStatus'=>1,'isDelete'=>0,'role_id'=>3])->orderBy('contact_person', 'asc')->get();


                return view('admin.payment.index', compact('order', 'employees', 'branches','Customer','id','CustProducts','paymentDetail','orderDetailIds','color','vendor','orderStatus'));

            }
        /*} catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }*/
    }
 
        public function store(Request $request,$id)
        {
          $validatedData = $request->validate([
                'amount' => 'required|numeric',
                'net_amount' => 'required|numeric',
                'paid_amount' => 'nullable|numeric',
                'next_followup_date' => function ($attribute, $value, $fail) use ($request) {
                    $dueAmount = ($request->net_amount - $request->paid_amount)-$request->amount_to_be_paid;
                    if ($dueAmount > 0 && empty($value)) {
                        $fail('The next payment follow-up date is required when there is a due amount.');
                    }
                }
            ]);

            DB::beginTransaction();
            try 
            {
                $orderId = $id;
                $netTotal = $request->net_amount;
                $paidAmount = $request->paid_amount + $request->amount_to_be_paid;
                $dueAmount = ($netTotal - $paidAmount);

                $payment=Payment::where(['order_id'=>$id])->first();
                if($payment && $payment->due_amount == 0)
                {
                      return back()->with('error', 'Payment already Completed .');
                }else
                {

                    if($payment)
                    {                    
                        $payment->total_amount = $netTotal;
                        $payment->paid_amount = $paidAmount;
                        $payment->due_amount = $dueAmount;
                        $payment->save();
                    }else{
                        $payment = new Payment();
                        $payment->order_id = $orderId;
                        $payment->total_amount = $netTotal;
                        $payment->paid_amount = $request->amount_to_be_paid;
                        $payment->due_amount = $dueAmount;
                        $payment->save();
                    }

                    // 2. Save into order_payment_detail table
                    $paymentDetail = new PaymentDetail();
                    $paymentDetail->payment_id = $payment->payment_id;
                    $paymentDetail->order_id = $orderId;
                    $paymentDetail->amount = $request->amount;
                    $paymentDetail->paid_amount = $request->amount_to_be_paid;
                    $paymentDetail->due_amount = $dueAmount;
                    $paymentDetail->next_followup_date = $request->next_followup_date ?? '';
                    $paymentDetail->save();

                    // 3. Update cust_order table
                    $order = CustOrder::find($orderId);


                     if ($order) {
                           CustOrder::where('order_id', $orderId)->update([
                                'net_total' => $netTotal,
                                'paid_amount' => $paidAmount,
                                'due_amount' => $dueAmount,
                            ]);
                        }

             }

                DB::commit();

                return back()->with('success', 'Payment and order updated successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error: ' . $e->getMessage());
            }
        }
        


  
    public function destroy($id)
    {
        try{
             CustomerFollowup::findOrFail($id)->delete();

            return redirect()->back()->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
?>
