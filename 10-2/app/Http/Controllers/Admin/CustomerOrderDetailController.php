<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\CustOrder;
use App\Models\CustOrderDetail;
use App\Models\Employee;
use App\Models\BranchMaster;
use App\Models\Product;
use App\Models\Color;
use Illuminate\Http\Request;

class CustomerOrderDetailController extends Controller
{
    
    public function edit($detailId)
    { 
        try{
                $detail = CustOrderDetail::with(['branch','color','product'])->findOrFail($detailId);
                return json_encode($detail);

        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function getOrderDetails($cust_pro_id)
    {
        $order = CustOrderDetail::where('cust_pro_id', $cust_pro_id)->first();
    
        if ($order) {
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null
            ]);
        }
    }

    // Update detail
    public function update(Request $request)
    {
        // try{
            $detailId=$request->detail_order_id;
                $detail = CustOrderDetail::findOrFail($detailId);

            if ($detail) {
                $orderDetails = $detail; // Since you're updating the existing one
                $order = CustOrder::findOrFail($detail->order_id);

                // Update detail fields
                $orderDetails->order_id = $order->order_id;
                $orderDetails->cust_id = $order->cust_id;
                $orderDetails->branch_id = $order->branch_id;
                // $orderDetails->cust_pro_id = $request->cust_pro_id;
                // $orderDetails->product_id = $request->product_id;
                $orderDetails->karat = $request->karat;
                $orderDetails->color_id = $request->color_id;
                $orderDetails->weight = $request->weight;
                $orderDetails->size = $request->size;
                $orderDetails->refer_tag_number = $request->refer_tag_number;
                $orderDetails->refer_image_url = $request->refer_image_url;
                $orderDetails->status = $request->status;
                $orderDetails->amount = $request->amount;
                $orderDetails->net_total = $request->amount;
                $orderDetails->given_to = $request->given_to;
                $orderDetails->remark = $request->remark;
                $orderDetails->delivery_date = $request->delivery_date;
                $orderDetails->rate_type = $request->rate_type;
                $orderDetails->rate_fix_open = $request->rate_fix_open;

                $orderDetails->save();

                // Recalculate total order amount
                $totalAmount = CustOrderDetail::where('order_id', $order->order_id)->sum('amount');

                $order->amount = $totalAmount;
                $order->net_total = $totalAmount;
                $order->due_amount = $totalAmount - $order->paid_amount;
                $order->save();
            }

             return response()->json([
            'success' => true,
            'message' => 'Order edited successfully']);

        /*} catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }*/
    }

    public function show($detailId)
    {
        try{
            $detail = CustOrderDetail::with('order')->findOrFail($detailId);
            return view('cust_order_detail.view', compact('detail'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function destroy(Request $request)
    {
        try{
            
            $c = CustOrderDetail::findOrFail($request->detail_order_id);
            $c->delete();
            return redirect()->back()->with('success', 'Order detail deleted successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
