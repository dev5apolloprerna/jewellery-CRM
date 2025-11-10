<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PurityController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $Category = Purity::orderBy('purity_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.purity.index', compact('Category'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
          $request->validate([
            'purity_value' => 'required|unique:purity_master,purity_value',
        ], [
            'purity_value.required' => 'Purity value is required.',
            'purity_value.unique' => 'This Purity value already exists.',
        ]);
        
        try{
                $purity=new Purity();
                $purity->purity_value=$request->purity_value;
                $purity->save();

                return redirect()->route('purity.index')->with('success', 'Purity Created Successfully.');
            } catch (\Exception $e) {
            report($e);
            return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = Purity::where(['purity_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
         $request->validate([
            'purity_value' => [
                'required',
                Rule::unique('purity_master', 'purity_value')->ignore($request->purity_id, 'purity_id')
            ],
        ], [
            'purity_value.required' => 'Purity Name is required.',
            'purity_value.unique' => 'This Purity Name already exists.',
        ]);
        // try{
        
        $update = DB::table('purity_master')
            ->where(['purity_id' => $request->purity_id])
            ->update([
                'purity_value' => $request->purity_value ?? 0,
            ]);


        return  redirect()->route('purity.index')->with('success', 'Purity Updated Successfully.');
            /*} catch (\Exception $e) {

                report($e);
         
                return false;
            }*/
    }


    public function delete(Request $request)
    {
       try{
        $delete = DB::table('purity_master')->where(['purity_id' => $request->id])->delete();

        return back()->with('success', 'Purity Deleted Successfully!.');
       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }
    public function validatename(Request $request)
{
    try {
        $exists = Purity::where([
            'iStatus' => 1,
            'isDelete' => 0,
            'purity_value' => $request->purity_value
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
        $data = Purity::where(['purity_value' => $request->editcatname])->whereNotIN('purity_id',[$request->purity_id])->count();
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
