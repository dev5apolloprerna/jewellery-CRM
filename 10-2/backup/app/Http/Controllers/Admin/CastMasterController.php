<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CastMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CastMasterController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $cast = CastMaster::orderBy('cast_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.customer_cast.index', compact('cast'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
          $request->validate([
            'cast' => 'required|unique:customer_cast,cast',
        ], [
            'cast.required' => 'Customer cast is required.',
            'cast.unique' => 'This category name already exists.',
        ]);
        
        try{
                $cast=new CastMaster();
                $cast->cast=$request->cast;
                $cast->save();

                return redirect()->route('castMaster.index')->with('success', 'Customer Cast Created Successfully.');
            } catch (\Exception $e) {
            report($e);
            return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = CastMaster::where(['cast_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
         $request->validate([
            'cast' => [
                'required',
                Rule::unique('customer_cast', 'cast')->ignore($request->cast_id, 'cast_id')
            ],
        ], [
            'cast.required' => 'Customer Cast Name is required.',
            'cast.unique' => 'This Cast Name already exists.',
        ]);
        try{
        
        $update = DB::table('customer_cast')
            ->where(['cast_id' => $request->cast_id])
            ->update([
                'cast' => $request->cast ?? 0,
            ]);


        return  redirect()->route('castMaster.index')->with('success', 'Customer Category Updated Successfully.');
        } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }


    public function delete(Request $request)
    {
       try{
        $delete = DB::table('customer_cast')->where(['cast_id' => $request->id])->delete();

        return back()->with('success', 'Customer Cast Deleted Successfully!.');
       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }
    public function validatename(Request $request)
{
    try {
        $exists = CastMaster::where([
            'iStatus' => 1,
            'isDelete' => 0,
            'cast' => $request->cast
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
        $data = CastMaster::where(['cast' => $request->editcatname])->whereNotIN('cast_id',[$request->cast_id])->count();
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
