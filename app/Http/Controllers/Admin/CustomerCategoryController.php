<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerCategoryController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $Category = CustomerCategory::orderBy('cust_cat_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.customer_category.index', compact('Category'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
          $request->validate([
            'cust_cat_name' => 'required|unique:customer_category,cust_cat_name',
        ], [
            'cust_cat_name.required' => 'Customer Category name is required.',
            'cust_cat_name.unique' => 'This category name already exists.',
        ]);
        
        try{
                $Category=new CustomerCategory();
                $Category->cust_cat_name=$request->cust_cat_name;
                $Category->save();

                return redirect()->route('customerCategory.index')->with('success', 'Customer Category Created Successfully.');
            } catch (\Exception $e) {
            report($e);
            return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = CustomerCategory::where(['cust_cat_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
         $request->validate([
            'cust_cat_name' => [
                'required',
                Rule::unique('customer_category', 'cust_cat_name')->ignore($request->cust_cat_id, 'cust_cat_id')
            ],
        ], [
            'cust_cat_name.required' => 'Customer Category name is required.',
            'cust_cat_name.unique' => 'This category name already exists.',
        ]);
        try{
        
        $update = DB::table('customer_category')
            ->where(['cust_cat_id' => $request->cust_cat_id])
            ->update([
                'cust_cat_name' => $request->cust_cat_name ?? 0,
            ]);


        return  redirect()->route('customerCategory.index')->with('success', 'Customer Category Updated Successfully.');
        } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }


    public function delete(Request $request)
    {
       try{
        $delete = DB::table('customer_category')->where(['cust_cat_id' => $request->id])->delete();

        return back()->with('success', 'Customer Category Deleted Successfully!.');
       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }
    public function validatename(Request $request)
{
    try {
        $exists = CustomerCategory::where([
            'iStatus' => 1,
            'isDelete' => 0,
            'cust_cat_name' => $request->cust_cat_name
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
        $data = CustomerCategory::where(['cust_cat_name' => $request->editcatname])->whereNotIN('cust_cat_id',[$request->cust_cat_id])->count();
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
