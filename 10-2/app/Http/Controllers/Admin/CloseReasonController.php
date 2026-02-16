<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CloseReason;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CloseReasonController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $Category = CloseReason::orderBy('close_reason_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.close_reason.index', compact('Category'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
          $request->validate([
            'close_reason' => 'required|unique:followup_close_reason,close_reason',
        ], [
            'close_reason.required' => 'Close Reason is required.',
            'close_reason.unique' => 'This Close Reason already exists.',
        ]);
        
        try{
                $cl=new CloseReason();
                $cl->close_reason=$request->close_reason;
                $cl->save();

                return redirect()->route('closeReason.index')->with('success', 'Close Reason Created Successfully.');
            } catch (\Exception $e) {
            report($e);
            return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = CloseReason::where(['close_reason_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
         $request->validate([
            'close_reason' => [
                'required',
                Rule::unique('followup_close_reason', 'close_reason')->ignore($request->close_reason_id, 'close_reason_id')
            ],
        ], [
            'close_reason.required' => 'Close Reason is required.',
            'close_reason.unique' => 'This Close Reason already exists.',
        ]);
        // try{
        
        $update = DB::table('followup_close_reason')
            ->where(['close_reason_id' => $request->close_reason_id])
            ->update([
                'close_reason' => $request->close_reason ?? 0,
            ]);


        return  redirect()->route('closeReason.index')->with('success', 'Close Reason Updated Successfully.');
            /*} catch (\Exception $e) {

                report($e);
         
                return false;
            }*/
    }


    public function delete(Request $request)
    {
       try{
        $delete = DB::table('followup_close_reason')->where(['close_reason_id' => $request->id])->delete();

        return back()->with('success', 'Close Reason Deleted Successfully!.');
       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }
    public function validatename(Request $request)
{
    try {
        $exists = CloseReason::where([
            'iStatus' => 1,
            'isDelete' => 0,
            'close_reason' => $request->close_reason
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
        $data = CloseReason::where(['close_reason' => $request->editcatname])->whereNotIN('close_reason_id',[$request->close_reason_id])->count();
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
