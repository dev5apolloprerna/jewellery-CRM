<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $Category = ProductCategory::orderBy('category_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.product_category.index', compact('Category'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:product_category,category_name',
        ], [
            'category_name.required' => 'Category name is required.',
            'category_name.unique' => 'This category name already exists.',
        ]);

        try{
                $Category=new ProductCategory();
                $Category->category_name=$request->category_name;
                $Category->save();

                return redirect()->route('productCategory.index')->with('success', 'Product Category Created Successfully.');
            } catch (\Exception $e) {
                report($e);
                return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = ProductCategory::where(['category_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
       $request->validate([
            'category_name' => [
                'required',
                Rule::unique('product_category', 'category_name')->ignore($request->category_id, 'category_id')
            ],
        ], [
            'category_name.required' => 'Category name is required.',
            'category_name.unique' => 'This category name already exists.',
        ]);

        try{
        
        $update = DB::table('product_category')
            ->where(['category_id' => $request->category_id])
            ->update([
                'category_name' => $request->category_name ?? 0,
            ]);


        return  redirect()->route('productCategory.index')->with('success', 'Product Category Updated Successfully.');
        } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }


    public function delete(Request $request)
    {
        // try{
        $delete = DB::table('product_category')->where(['category_id' => $request->id])->delete();

        return back()->with('success', 'Product Category Deleted Successfully!.');
        /*} catch (\Exception $e) {

            report($e);
     
            return false;
        }*/
    }
    public function validatename(Request $request)
    {
        try {
            $exists = ProductCategory::where([
                'iStatus' => 1,
                'isDelete' => 0,
                'category_name' => $request->category_name
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
        $data = ProductCategory::where(['iStatus' => 1, 'isDelete' => 0, 'category_name' => $request->editcatname])->whereNotIN('category_id',[$request->category_id])->count();
        if ($data > 0) 
        {
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
