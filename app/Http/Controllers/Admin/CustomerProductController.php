<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\Customer;
use App\Models\CustomerProduct;
use App\Models\Employee;
use App\Models\BranchMaster; // assuming you have this
use App\Models\Product; // assuming you have this
use App\Models\CustomerVisit; // assuming you have this
use App\Models\CustOrder; // assuming you have this
use App\Models\CustOrderDetail; // assuming you have this

class CustomerProductController extends Controller
{
        public function index($id)
        {
            try
            {
                $user = Auth::guard('web_employees')->user();
    
                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;
        
                    $products = CustomerProduct::with(['customer', 'product', 'employee','category','branch','orderDetails.OrderStatus'])->where(['visit_id'=>$id])->orderBy('product_id','desc')->get();
                            return response()->json($products);

                    //return view('employee.cust_product.index', compact('products','id'));
                }
                else
                {
                    $products = CustomerProduct::with(['customer', 'product', 'employee','category','branch','orderDetails.OrderStatus'])->where(['visit_id'=>$id])->orderBy('product_id','desc')->get();

                            return response()->json($products);

                   //return view('admin.cust_product.index', compact('products','id'));
                }
            } catch (\Exception $e) 
            {
                    return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

        public function create($id)
        {
            try{
                  $user = Auth::guard('web_employees')->user();

                    if ($user && $user->emp_id != null && $user->branch_id != null) 
                    {
                        $empid = $user->emp_id;
                        $branch_id = $user->branch_id;
                    
    
                        $customers = Customer::where(['customer_id'=>$id])->first();
                        $products = Product::select('product_name','product_id')->where(['iStatus'=>1,'isDelete'=>0])
                            ->orderBy('product_name', 'asc')
                            ->get();
                        $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                        return view('employee.cust_product.add', compact('customers', 'products', 'employees','id'));
                    }else{
                        $customers = Customer::where(['customer_id'=>$id])->first();
                        $products = Product::select('product_name','product_id')->where(['iStatus'=>1,'isDelete'=>0])
                            ->orderBy('product_name', 'asc')
                            ->get();
                        $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                        return view('admin.cust_product.add', compact('customers', 'products', 'employees','id'));
    
                    }
            } catch (\Exception $e) 
            {
                    return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

        public function store(Request $request)
        {

            $validator = Validator::make($request->all(), [
                    'category_id' => 'required',
                    'product_id' => 'required',
                    'emp_id' => 'required',
                    // 'quantity' => 'required|integer',
                    'status' => 'required',
                ]);

            if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
            /*try{*/
                     $user = Auth::guard('web_employees')->user();
                     $branch = Employee::where(['emp_id'=>$request->emp_id])->first();

                    if ($user && $user->emp_id != null && $user->branch_id != null) 
                    {
                        $empid = $user->emp_id;
                        $branch_id = $user->branch_id;
                    
                        $id=$request->cust_id;
                        $data = $request->all(); // Correct way to assign request data
                        $data['visit_date'] = date('Y-m-d');
                        $data['branch_id'] = $branch->branch_id;
                        
                         $product = CustomerProduct::create($data);
                       // return redirect()->route('EMPcustProduct.index',[$id])->with('success', 'Customer Product added successfully');
                    
                    return response()->json(['success' => true, 'data' => $product]);
                    }else{

                            $data = $request->all(); // Correct way to assign request data
                            $data['visit_date'] = date('Y-m-d');
                            $data['branch_id'] = $branch->branch_id;

                            $product = CustomerProduct::create($data);
                            
                        return response()->json(['success' => true, 'data' => $product]);

                    }

           /* } catch (\Exception $e) 
            {
                    return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }*/
        }

        public function edit($id)
        {
            try{

                  $user = Auth::guard('web_employees')->user();

                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;
                
                $custProduct = CustomerProduct::findOrFail($id);
                $products = Product::select('product_name','product_id')->where(['iStatus'=>1,'isDelete'=>0])
                    ->orderBy('product_name', 'asc')
                    ->get();
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                $id=$custProduct->cust_id;
                return view('employee.cust_product.edit', compact('custProduct', 'products', 'employees','id'));

            }else{
                 $custProduct = CustomerProduct::findOrFail($id);
                $products = Product::select('product_name','product_id')->where(['iStatus'=>1,'isDelete'=>0])
                    ->orderBy('product_name', 'asc')
                    ->get();
                $employees = Employee::where(['iStatus'=>1,'isDelete'=>0])->orderBy('emp_name', 'asc')->get();
                $id=$custProduct->cust_id;
                return view('admin.cust_product.edit', compact('custProduct', 'products', 'employees','id'));

            }
            } catch (\Exception $e) 
            {
                    return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'product_id' => 'required',
                'emp_id' => 'required',
                'quantity' => 'required|integer',
                'visit_date' => 'required|date',
            ]);
            try{


                 $user = Auth::guard('web_employees')->user();

                if ($user && $user->emp_id != null && $user->branch_id != null) 
                {
                    $empid = $user->emp_id;
                    $branch_id = $user->branch_id;
                
                    CustomerProduct::findOrFail($id)->update($request->all());
                    $id=$request->cust_id;
                    return redirect()->route('EMPcustProduct.index',[$id])->with('success', 'Customer Product updated successfully');
                }else{

                    CustomerProduct::findOrFail($id)->update($request->all());
                    $id=$request->cust_id;
                    return redirect()->route('custProduct.index',[$id])->with('success', 'Customer Product updated successfully');
                }
            } catch (\Exception $e) 
            {
                    return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }
        public function changeStatus(Request $request)
        {
            
            $product = CustomerProduct::findOrFail($request->product_id);
   
             if($request->status == 1 || $request->status == 2)
                {
                    // Toggle status (1 to 0 or 0 to 1)
                    if($request->status == 1)
                    {
                        $status='delivered';
                    }elseif($request->status == 2)
                    {
                        
                        $status='ordered';
                    }
                    CustomerProduct::where('cust_pro_id', $request->product_id)->update(['status' => $status]);

                }
            
            $CustOrderDetail = CustOrderDetail::where('cust_pro_id', $request->product_id)->first();
            
            if ($CustOrderDetail) {
                $CustOrderDetail->delivery_status = $request->status; // or ->delivery_status if that's the actual column
                $CustOrderDetail->save();
            
            }
            return redirect()->back()->with('success', 'Product status updated successfully');
        }

        public function destroy($id)
        {
             try{
                       $product = CustomerProduct::findOrFail($id);
                
                        $orderDetail = CustOrderDetail::where('cust_pro_id', $id)->first();
                
                        if ($orderDetail) 
                        {
                        
                            $orderId = $orderDetail->order_id;
                    
                            $orderDetail->delete();
                            $remainingProductsCount = CustOrderDetail::where('order_id', $orderId)->count();
                    
                            $order = CustOrder::find($orderId);
                    
                            if ($remainingProductsCount == 0) {
                                if ($order) {
                                    $order->delete();
                                }
                            } else {
                                $totalAmount = CustOrderDetail::where('order_id', $orderId)->sum('amount');
                                $advancePayment = $order->advance_payment ?? 0;
                    
                                $order->amount = $totalAmount;
                                $order->net_total = $totalAmount;
                                $order->due_amount = $totalAmount - $advancePayment;
                                $order->save();
                            }            
                        }
                
            
                        // Finally, delete the product
                        $product->delete();
                
                        return response()->json(['success' => true]);
                            

                    return response()->json(['success' => false, 'message' => 'Product not found']);

             } catch (\Exception $e) 
            {
                    return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }

}
