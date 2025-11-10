<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productphotos;
use App\Models\ProductAttributes;
use App\Models\Wishlist;

use Illuminate\Support\Facades\DB;

class FrontProductController extends Controller
{

      public function ajax_product_listing()
      {
          try{
         $category=Category::where(['iStatus'=>1,'isDelete'=>0])->get();

      $Product = Product::select(
            'product.productId',
            'product.categoryId',
            'product.productname',
            'product.slugname',
            'product.rate',
            'product.weight',
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto ")
        )
            ->orderBy('productId', 'desc')
            ->where(['product.iStatus' => 1, 'product.isDelete' => 0])->skip(0)->take(8)
            // ->join('category', 'product.categoryId', '=', 'category.categoryId')
            //->toSql();
            ->paginate(6);
            return view('frontview.ajax_product_listing',compact('Product','category'));

        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
      }

    public function getAttribute(Request $request)
    {
        try{
       $data['rate'] = ProductAttributes::where(['id'=>$request->size])->get(["product_attribute_price","product_attribute_offer_price","id"]);

        return response()->json($data);
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }

    }
   public function addtowishlist(Request $request)
    {
        try{
        $id=session()->get('user.customerid');
        
        // $session = Session::get('customerid');
        $wishlist = Wishlist::where(['wishlist.iStatus' => 1, 'wishlist.isDelete' => 0, 'wishlist.customerid' => $id, 'productid' => $request->productid])
            ->count();

        if (isset($id) && (!empty($id))) 
        {
            if ($wishlist == 0) {
                $data = array(
                    "customerid" => $id,
                    "productid" => $request->productid,
                );
                wishlist::create($data);
                echo 1;
               // return back()->with('success', 'Product Added To Wishlist!');
            } else {
                wishlist::where(['iStatus' => 1, 'isDelete' => 0, 'productId' => $request->productid])->delete();
                echo 2;
               // return back()->with('error', 'Product Is Already In Your Wishlist');
            }
        } else {
            echo 0;
           // return redirect()->route('FrontLogin');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
}
