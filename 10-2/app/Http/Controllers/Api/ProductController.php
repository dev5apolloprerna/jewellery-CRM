<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Productphotos;
use App\Models\Wishlist;
use App\Models\Customer;


class ProductController extends Controller
{
    // private $web_url;
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    //     $this->web_url =  "http://getdemo.in/";
    // }
    
  public function index(Request $request)
  {
        $Product = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.rate',
            'product.weight',
            'product.iStatus',
            'product.isStock',
           DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as weight"),
            DB::raw("(select categoryname from category as cat where product.subcategoryid=cat.categoryId and product.subcategoryid IS NOT NULL limit 1) as subcategoryname"),
            DB::raw("(select categoryname from category as cat where product.categoryId=cat.categoryId and product.categoryId IS NOT NULL limit 1) as categoryname"),
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "),

        )
            ->when($request->search, fn ($query, $search) => $query->where('product.productname','like','%'.$search.'%'))
    ->where(['categoryId'=>$request->category_id,'subcategoryid'=>$request->subcategory_id,'product.iStatus' => 1, 'product.isDelete' => 0])
    
            ->orderBy('productId', 'desc')
            ->get();   
    
    $target_path = 'Product/';

    if(sizeof($Product) != 0)
    {
         foreach($Product as $val)
        {
            if($request->email && $request->password)
            {
                $userData = User::where(['email'=>$request->email])->first();
                if(!empty($userData))
                {
                    if (Hash::check($request->password, $userData->password))
                    {
                        $customer=Customer::select('customerid')->where(['userId'=>$userData->id])->first();
                        $wishlist = Wishlist::where(['productid'=>$val->productId,'customerid'=>$customer->customerid])->first();
                     if(!empty($wishlist))
                    {
                        $favourite="Yes";
                    }else
                    {
                        $favourite="No";
                    }
                    }else 
                    {
                        return response()->json([
                            'status' => 'error1',
                            'message' => 'Invalid login.',
                        ], 401);
                    }
                } else 
                {
                   return response()->json([
                        'status' => 'error',
                        'message' => 'User Not Found.',
                    ], 401);
                }
            }else
            {
                 $favourite="No";
            }
            if($val->isStock == 1)
            {
                $outofstock='No';
            }else{
                $outofstock="Yes";
            }
            
            $ProductList[] = array(
                "product_id" => $val->productId,
                "category_id" => $val->categoryId,
                "subcategory_id" => $val->subcategoryid,
                "category_name" => $val->categoryname,
                "subcategory_name" => $val->subcategoryname,
                "product_name" => $val->productname,
                "price" => $val->offerPrice ?? 0,
                "original_price" => $val->rate ?? 0,
                "weight" => $val->weight ?? 0,
                "product_photo" =>"https://sukti.in/". $target_path . $val->strphoto,
                "favourite" =>$favourite,
                "outofstock" =>$outofstock,
                "iStatus" =>$val->iStatus
            );
        }

            return response()->json([
                'status' => 'success',
                'message' => 'Product List',
                'Product' => $ProductList
            ]);
    

    } else {
        return response()->json([
                'status' => 'error',
                'message' => 'No Data Found',
                'Product' => []
        ]);
    }
                
  }
  public function related_products(Request $request)
  {
        $product=Product::where(['productId'=>$request->product_id,'iStatus'=>1])->first();
        $RelatedProduct = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.rate',
            'product.weight',
            'product.iStatus',
            'product.isStock',
            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as weight"),
            DB::raw("(select categoryname from category as cat where product.subcategoryid=cat.categoryId and product.subcategoryid IS NOT NULL limit 1) as subcategoryname"),
            DB::raw("(select categoryname from category as cat where product.categoryId=cat.categoryId and product.categoryId IS NOT NULL limit 1) as categoryname"),
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "),

        )
    ->where(['categoryId'=>$product->categoryId,'subcategoryid'=>$product->subcategoryid,'product.iStatus' => 1, 'product.isDelete' => 0])->whereNotIN('productId',[$request->product_id])
    
            ->orderBy('productId', 'desc')
            ->get();   
    
    $target_path = 'Product/';

    if(sizeof($RelatedProduct) != 0)
    {
         foreach($RelatedProduct as $val)
        {
            if($request->email && $request->password)
            {
                $userData = User::where(['email'=>$request->email])->first();
                if(!empty($userData))
                {
                    if (Hash::check($request->password, $userData->password))
                    {
                        $customer=Customer::select('customerid')->where(['userId'=>$userData->id])->first();
                        $wishlist = Wishlist::where(['productid'=>$val->productId,'customerid'=>$customer->customerid])->first();
                     if(!empty($wishlist))
                    {
                        $favourite="Yes";
                    }else
                    {
                        $favourite="No";
                    }
                    }else 
                    {
                        return response()->json([
                            'status' => 'error1',
                            'message' => 'Invalid login.',
                        ], 401);
                    }
                } else 
                {
                   return response()->json([
                        'status' => 'error',
                        'message' => 'User Not Found.',
                    ], 401);
                }
            }else
            {
                 $favourite="No";
            }
             if($val->isStock == 1)
            {
                $outofstock='No';
            }else{
                $outofstock="Yes";
            }

            
            $RelatedProductList[] = array(
                "product_id" => $val->productId,
                "category_id" => $val->categoryId,
                "subcategory_id" => $val->subcategoryid,
                "category_name" => $val->categoryname,
                "subcategory_name" => $val->subcategoryname,
                "product_name" => $val->productname,
                "price" => $val->offerPrice ?? 0,
                "original_price" => $val->rate ?? 0,
                "weight" => $val->weight ?? 0,
                "product_photo" =>"https://sukti.in/". $target_path . $val->strphoto,
                "favourite" =>$favourite,
                "outofstock" =>$outofstock,
                "iStatus" =>$val->iStatus
            );
        }

            return response()->json([
                'status' => 'success',
                'message' => 'Related Product List',
                'Related_Product' => $RelatedProductList
            ]);
    

    } else {
        return response()->json([
                'status' => 'error',
                'message' => 'No Data Found',
                'Related_Product' => []
        ]);
    }
                
  }
   public function product_detail(Request $request)
  {
        $product = Product::select('product.*',DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as weight"),DB::raw("(select categoryname from category where product.categoryId=category.categoryId limit 1) as categoryname"))->where(['iStatus' => 1, 'isDelete' => 0,'productId'=>$request->product_id])->first();   
            $productImages = Productphotos::where(['iStatus' => 1, 'isDelete' => 0,'productid'=>$product->productId])->get();

    $target_path = 'Product/';

    if(!empty($product))
    {
        foreach ($productImages as $key => $value) 
        {
            $productImg[]=array(
            "product_photo" =>"https://sukti.in/". $target_path . $value->strphoto);
        }
         if($product->isStock == 1)
        {
            $outofstock='No';
        }else{
            $outofstock="Yes";
        }
         
            $ProductDetail[] = array(
                "product_id" => $product->productId,
                "product_name" => $product->productname,
                "price" => $product->offerPrice ?? 0,
                "original_price" => $product->rate ?? 0,
                "weight" => $product->weight ?? 0,
                "product_description" => strip_tags($product->description),
                "outofstock"=>$outofstock,
                "productImg"=>$productImg
            );
        

            return response()->json([
                'status' => 'success',
                'message' => 'Product Detail',
                'Product' => $ProductDetail
            ]);
    

    } else {
        return response()->json([
                'status' => 'error',
                'message' => 'No Data Found',
                'Product' => []
        ]);
    }
                
  }
   public function wishlist(Request $request)
  {

        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['mobile_number'=>trim($request->mobile_number)])->orWhere(['email'=>$request->email])->first();
             if(!empty($userData))
            {
                if (Hash::check($request->password, $userData->password))
                {
                     $customer=Customer::select('customerid')->where(['userId'=>$userData->id])->first();

                     $WishlistData  =Wishlist::where(['product.iStatus' => 1, 'product.isDelete' => 0, 'wishlist.customerid' => $customer->customerid])
                     ->join('product', 'product.productId', '=', 'wishlist.productid')
                     ->get();
                     if(sizeof($WishlistData) != 0)
                    {
                       foreach($WishlistData as $val)
                        {
                           
                            $Product = Product::select(
                                'product.productId',
                                'product.productname',
                                'product.rate',
                                'product.weight',
                                'product.iStatus',
                                'product.categoryId',
                                'product.isStock',
                                DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId)and product_attributes.product_id=product.productId limit 1) as rate"),
                                DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as offerPrice"),
                                DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as weight"),
                                DB::raw("(select categoryname from category as cat where product.subcategoryid=cat.categoryId and product.subcategoryid IS NOT NULL limit 1) as subcategoryname"),
                                DB::raw("(select categoryname from category as cat where product.categoryId=cat.categoryId and product.categoryId IS NOT NULL limit 1) as categoryname"),
                                 DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "),

                            )->where(['productId'=>$val->productid,'product.iStatus' => 1, 'product.isDelete' => 0])
                            ->join('category', 'product.categoryId', '=', 'category.categoryId')
                            ->first();

                            if($Product->isStock == 1)
                                {
                                    $outofstock='No';
                                }else{
                                    $outofstock="Yes";
                                }
                            
                            $Wishlistt[] = array(
                                "customer_id" => $customer->customerid,
                                "category_id" => $Product->categoryId,
                                "category_name" => $Product->categoryname,
                                "product_id" => $Product->productId,
                                "product_name" => $Product->productname,
                                "price" => $Product->offerPrice ?? 0,
                                "original_price" => $Product->rate ?? 0,
                                "weight" => $Product->weight ?? 0,
                                "outofstock" => $outofstock,
                                "product_photo" =>"http://sukti.in/Product/". $Product->strphoto,
                            );
                           
                        }

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Wishlist',
                                'Wishlist' => $Wishlistt
                            ]);
                    } else {
                            return response()->json([
                                    'status' => 'error',
                                    'message' => 'No Data Found',
                                    'Wishlist' => []
                            ]);
                        }
                        
                }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }
            } else 
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else
        {
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
  }

 public function addtowishlist(Request $request)
  {

        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['mobile_number'=>trim($request->mobile_number)])->orWhere(['email'=>$request->email])->first();
             if(!empty($userData))
            {
                if (Hash::check($request->password, $userData->password))
                {
                    $customer=Customer::select('customerid')->where(['userId'=>$request->id])->first();
                    $wishlist = Wishlist::where(['wishlist.iStatus' => 1, 'wishlist.isDelete' => 0, 'wishlist.customerid' => $customer->customerid, 'productid' => $request->product_id])
                        ->count();
                    if ($wishlist == 0) 
                    {

                            $data = array(
                                "customerid" => $customer->customerid,
                                "productid" => $request->product_id,
                            );
                            wishlist::create($data);
                              return response()->json([
                                    'status' => 'success',
                                    'message' => 'Product Added To Wishlist!'
                                ]);
                    }else{
                        return response()->json([
                        'status' => 'success',
                        'message' => 'Product Already Added To Wishlist!'
                    ]);
                    }
                }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }
            } else 
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else
        {
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
  }
  public function removeFromwishlist(Request $request)
  {

        if(auth()->guard('api')->user())
        {
            $user_login = auth()->guard('api')->user();

            $userData = User::where(['mobile_number'=>trim($request->mobile_number)])->orWhere(['email'=>$request->email])->first();
             if(!empty($userData))
            {
                if (Hash::check($request->password, $userData->password))
                {
                     $customer=Customer::select('customerid')->where(['userId'=>$request->id])->first();

                      Wishlist::where(['iStatus' => 1, 'isDelete' => 0, 'productId' => $request->product_id, 'wishlist.customerid' => $customer->customerid])->delete();

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Product Removed From Wishlist!'
                        ]);
                }else 
                {
                    return response()->json([
                        'status' => 'error1',
                        'message' => 'Invalid login.',
                    ], 401);
                }
            } else 
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found.',
                ], 401);
            }
        }else
        {
            return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authorised.',
            ], 401);
        }
  }
public function tranding_new_products(Request $request)
  {
        $newProducts = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.rate',
            'product.weight',
            'product.iStatus',
            'product.isStock',
            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as weight"),
            DB::raw("(select categoryname from category as cat where product.subcategoryid = cat.categoryId and product.subcategoryid IS NOT NULL limit 1) as subcategoryname"),
            DB::raw("(select categoryname from category as cat where product.categoryId = cat.categoryId and product.categoryId IS NOT NULL limit 1) as categoryname"),
            DB::raw("(select strphoto from productphotos as pimages where product.productId = pimages.productid limit 1) as strphoto ")
        )->where(['iStatus' => 1])
            ->orderBy('productId', 'desc')->skip(0)->take(10)->get();   

        $targetPath = 'Product/';
        $newProductList = [];

        if (sizeof($newProducts) > 0) {
            foreach ($newProducts as $product) {
                $favourite = "No";
                if ($request->email && $request->password) {
                    $userData = User::where(['email' => $request->email])->first();
                    if (!empty($userData)) {
                        if (Hash::check($request->password, $userData->password)) {
                            $customer = Customer::select('customerid')->where(['userId' => $userData->id])->first();
                            $wishlist = Wishlist::where(['productid' => $product->productId, 'customerid' => $customer->customerid])->first();
                            if (!empty($wishlist)) {
                                $favourite = "Yes";
                            }
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Invalid login.',
                            ], 401);
                        }
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'User Not Found.',
                        ], 401);
                    }
                }
                if($product->isStock == 1)
                {
                    $outofstock='No';
                }else{
                    $outofstock="Yes";
                }

                $newProductList[] = [
                    "product_id" => $product->productId,
                    "category_id" => $product->categoryId,
                    "subcategory_id" => $product->subcategoryid,
                    "category_name" => $product->categoryname,
                    "subcategory_name" => $product->subcategoryname,
                    "product_name" => $product->productname,
                    "price" => $product->offerPrice ?? 0,
                    "original_price" => $product->rate ?? 0,
                    "weight" => $product->weight ?? 0,
                    "product_photo" => "https://sukti.in/" . $targetPath . $product->strphoto,
                    "favourite" => $favourite,
                    "outofstock" => $outofstock,
                    "iStatus" => $product->iStatus
                ];
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No New Data Found',
                'new_product' => []
            ]);
        }

        // Trending product list
        $trendingProducts = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.rate',
            'product.weight',
            'product.iStatus',
            'product.isTrandingProduct',
            'product.isStock',

            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId limit 1) as weight"),
            DB::raw("(select categoryname from category as cat where product.subcategoryid = cat.categoryId and product.subcategoryid IS NOT NULL limit 1) as subcategoryname"),
            DB::raw("(select categoryname from category as cat where product.categoryId = cat.categoryId and product.categoryId IS NOT NULL limit 1) as categoryname"),
            DB::raw("(select strphoto from productphotos as pimages where product.productId = pimages.productid limit 1) as strphoto ")
        )->where(['iStatus' => 1, 'isTrandingProduct' => 1])->orderBy('productId', 'desc')->get();   

        $trendingProductList = [];

        if (sizeof($trendingProducts) > 0) {
            foreach ($trendingProducts as $product) {
                $favourite = "No";
                if ($request->email && $request->password) {
                    $userData = User::where(['email' => $request->email])->first();
                    if (!empty($userData)) {
                        if (Hash::check($request->password, $userData->password)) {
                            $customer = Customer::select('customerid')->where(['userId' => $userData->id])->first();
                            $wishlist = Wishlist::where(['productid' => $product->productId, 'customerid' => $customer->customerid])->first();
                            if (!empty($wishlist)) {
                                $favourite = "Yes";
                            }
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Invalid login.',
                            ], 401);
                        }
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'User Not Found.',
                        ], 401);
                    }
                }
                if($product->isStock == 1)
                {
                    $outofstock='No';
                }else{
                    $outofstock="Yes";
                }

                $trendingProductList[] = [
                    "product_id" => $product->productId,
                    "category_id" => $product->categoryId,
                    "subcategory_id" => $product->subcategoryid,
                    "category_name" => $product->categoryname,
                    "subcategory_name" => $product->subcategoryname,
                    "product_name" => $product->productname,
                    "price" => $product->offerPrice ?? 0,
                    "original_price" => $product->rate ?? 0,
                    "weight" => $product->weight ?? 0,
                    "product_photo" => "https://sukti.in/" . $targetPath . $product->strphoto,
                    "favourite" => $favourite,
                    "outofstock" => $outofstock,
                    "iStatus" => $product->iStatus
                ];
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No Trending Products Found',
                'tranding_product' => []
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product Lists',
            'new_product' => $newProductList,
            'tranding_product' => $trendingProductList
        ]);
                
  }
   
}
