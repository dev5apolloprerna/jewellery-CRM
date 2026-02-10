<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $Category = Color::orderBy('color_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.color.index', compact('Category'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
          $request->validate([
            'color_name' => 'required|unique:color_master,color_name',
        ], [
            'color_name.required' => 'Color name is required.',
            'color_name.unique' => 'This Color Name already exists.',
        ]);
        
        try{
                $Category=new Color();
                $Category->color_name=$request->color_name;
                $Category->save();

                return redirect()->route('color.index')->with('success', 'Color Created Successfully.');
            } catch (\Exception $e) {
            report($e);
            return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = Color::where(['color_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
         $request->validate([
            'color_name' => [
                'required',
                Rule::unique('color_master', 'color_name')->ignore($request->color_id, 'color_id')
            ],
        ], [
            'color_name.required' => 'Color Name is required.',
            'color_name.unique' => 'This Color Name already exists.',
        ]);
        // try{
        
        $update = DB::table('color_master')
            ->where(['color_id' => $request->color_id])
            ->update([
                'color_name' => $request->color_name ?? 0,
            ]);


        return  redirect()->route('color.index')->with('success', 'Color Updated Successfully.');
            /*} catch (\Exception $e) {

                report($e);
         
                return false;
            }*/
    }


    public function delete(Request $request)
    {
       try{
        $delete = DB::table('color_master')->where(['color_id' => $request->id])->delete();

        return back()->with('success', 'Color Deleted Successfully!.');
       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }
    public function validatename(Request $request)
{
    try {
        $exists = Color::where([
            'iStatus' => 1,
            'isDelete' => 0,
            'color_name' => $request->color_name
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
        $data = Color::where(['color_name' => $request->editcatname])->whereNotIN('color_id',[$request->color_id])->count();
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
