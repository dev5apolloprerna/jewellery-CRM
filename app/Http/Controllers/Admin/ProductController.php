<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $Product = Product::orderBy('product_id', 'desc')->paginate(env('PER_PAGE_COUNT'));
            return view('admin.product_master.index', compact('Product'));
        } catch (\Exception $e) 
        {
            report($e);
            return false;
        }
    }

    public function store(Request $request)
    {
         $request->validate([
                    'product_name' => 'required',
                    'product_tag' => 'required'
                ]);
        try{


                 $img = "";
                if ($request->hasFile('product_photo')) {
                    $root = $_SERVER['DOCUMENT_ROOT'];
                    $image = $request->file('product_photo');
                    $img = time() . '.' . $image->getClientOriginalExtension();
                    $destinationpath = $root . '/Product/';
                    if (!file_exists($destinationpath)) {
                        mkdir($destinationpath, 0755, true);
                    }
                    $image->move($destinationpath, $img);
                }

                $Product=new Product();
                $Product->product_name=$request->product_name;
                $Product->product_photo=$img ?? '';
                $Product->product_tag=$request->product_tag ?? '';
                $Product->save();

                return redirect()->route('product.index')->with('success', 'Product Created Successfully.');
            } catch (\Exception $e) {
                report($e);
                return false;
            }
    }

    public function editview(Request $request, $id)
    {
        try{
        $data = Product::where(['product_id' => $id])->first();
        return json_encode($data);

       } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }

    public function update(Request $request)
    {
        if($request->hiddenphoto == "")
            {

                $request->validate([
                    'product_name' => 'required',
                    'product_tag' => 'required'
                ]);
            }
        try{        
        
        $root = $_SERVER['DOCUMENT_ROOT'];
        if($request->hasFile('product_photo'))
        {
            $fimage = $request->file('product_photo');
            $aImg = time().'.'.$fimage->getClientOriginalExtension();
            $destinationpath = $root.'/Product/';
            if(!file_exists($destinationpath)) {

                mkdir($destinationpath, 0755, true);
            }
            $fimage->move($destinationpath,$aImg);
        }
        else
        {
            $oldFrontImage = $request->input('hiddenphoto');
            $aImg = $oldFrontImage;
        }

        $update = DB::table('product_master')
            ->where(['product_id' => $request->product_id])
            ->update([
                'product_name' => $request->product_name ?? 0,
                'product_photo' => $aImg ?? '',
                'product_tag' => $request->product_tag ?? '',
            ]);


        return  redirect()->route('product.index')->with('success', 'Product Updated Successfully.');
        } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }


    public function delete(Request $request)
    {
        // try{
        $delete = DB::table('product_master')->where(['product_id' => $request->id])->delete();

        return back()->with('success', 'Product Deleted Successfully!.');
        /*} catch (\Exception $e) {

            report($e);
     
            return false;
        }*/
    }
    public function validatename(Request $request)
    {
        try{
        $data = Product::where(['iStatus' => 1, 'isDelete' => 0, 'product_name' => $request->product_name])->count();
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

    public function validateeditname(Request $request)
    {
        try{
        $data = Product::where(['iStatus' => 1, 'isDelete' => 0, 'product_name' => $request->product_name])->whereNotIN('product_id',[$request->product_id])->count();
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

    public function validatetag(Request $request)
    {
        try{
        $data = Product::where(['iStatus' => 1, 'isDelete' => 0, 'product_tag' => $request->product_tag])->count();
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

    public function validateedittag(Request $request)
    {
        try{
        $data = Product::where(['iStatus' => 1, 'isDelete' => 0, 'product_tag' => $request->product_tag])->whereNotIN('product_id',[$request->product_id])->count();
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
