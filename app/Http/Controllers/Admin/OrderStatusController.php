<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderStatusController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $orderStatus = OrderStatus::orderBy('order_status_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.order_status.index', compact('orderStatus'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
          $request->validate([
            'status' => 'required|unique:order_status_master,status',
        ], [
            'status.required' => 'OrderStatus value is required.',
            'status.unique' => 'This OrderStatus value already exists.',
        ]);
        
        try{
                $OrderStatus=new OrderStatus();
                $OrderStatus->status=$request->status;
                $OrderStatus->save();

                return redirect()->route('orderStatus.index')->with('success', 'OrderStatus Created Successfully.');
            } catch (\Exception $e) {
            report($e);
            return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = OrderStatus::where(['order_status_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
         $request->validate([
            'status' => [
                'required',
                Rule::unique('order_status_master', 'status')->ignore($request->order_status_id, 'order_status_id')
            ],
        ], [
            'status.required' => 'OrderStatus Name is required.',
            'status.unique' => 'This OrderStatus Name already exists.',
        ]);
        // try{
        
        $update = DB::table('order_status_master')
            ->where(['order_status_id' => $request->order_status_id])
            ->update([
                'status' => $request->status ?? 0,
            ]);


        return  redirect()->route('orderStatus.index')->with('success', 'OrderStatus Updated Successfully.');
            /*} catch (\Exception $e) {

                report($e);
         
                return false;
            }*/
    }


    public function delete(Request $request)
    {
       try{
        $delete = DB::table('order_status_master')->where(['order_status_id' => $request->id])->delete();

        return back()->with('success', 'OrderStatus Deleted Successfully!.');
       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }
    public function validatename(Request $request)
{
    try {
        $exists = OrderStatus::where([
            'iStatus' => 1,
            'isDelete' => 0,
            'status' => $request->status
        ])->exists();

        return response()->json($exists ? 1 : 0);
    } catch (\Exception $e) {
        report($e);
        return response()->json(0); // return 0 on error to avoid breaking frontend
    }
}

    public function validateeditname(Request $request)
    {
        try{
        $data = OrderStatus::where(['status' => $request->editcatname])->whereNotIN('order_status_id',[$request->order_status_id])->count();
        if ($data > 0) {
            echo 1;
        } else {
            echo 0;
        }
        } catch (\Exception $e) {

        report($e);
 
        return false;
        }
    }
    
}
