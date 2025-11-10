<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Wishlist;
use App\Models\Shipping;

class CartController extends Controller
{
   public function cartList(Request $request)
    {
        try{
        $cartItems = \Cart::getContent();
        return view('frontview.cart', compact('cartItems'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }


    public function addToCart(Request $request)
    {
        try{
        // $id=session()->get('user.customerid');

        // if (isset($id) && (!empty($id))) 
        // {
       
        if($request->price == 0){
                    return redirect()->back()->with('error', 'This Product cannot be added to the cart because it has no price');
            }else{
             \Cart::add([
            'id' => $request->attributeId,
            'productId' => $request->productId,
            'name' => $request->name,
            'price' => $request->price,
            'weight' =>$request->weight,
            'quantity' => $request->quantity,
            'attributes' => array(
            'image' => $request->image ?? "",
            )
        ]);
    
        $wishlist=wishlist::where(['customerid'=>$request->customerid,'productid'=>$request->productId])->get();
        if(sizeof($wishlist) != 0)
        {
            $removefromWhislist=wishlist::where(['customerid'=>$request->customerid,'productid'=>$request->productId])->delete();
        }
            return redirect()->route('FrontCart')->with('success', 'Product is Added to Cart Successfully !');

               
            //  return redirect()->back()->with('success', 'Product is Added to Cart Successfully !');
            }
            
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

       public function updateCart(Request $request)
    {
       try{
        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        session()->flash('success', 'Item Cart is Updated Successfully !');
        return back();

        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
        // return redirect()->route('FrontCart');
    }

    public function removeCart(Request $request)
    {
        try{
        \Cart::remove($request->id);
        session()->flash('success', 'Item Cart Remove Successfully !');
        
        return back();

        // return redirect()->route('FrontCart');
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function clearAllCart()
    {
        try{
        \Cart::clear();

        session()->flash('success', 'All Item Cart Clear Successfully !');
        return back();

        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
        // return redirect()->route('FrontCart');
    }
}
