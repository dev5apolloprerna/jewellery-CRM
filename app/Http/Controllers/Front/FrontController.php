<?php
namespace App\Http\Controllers\front;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Productphotos;
use App\Models\ProductAttributes;
use App\Models\Wishlist;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\State;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use App\Models\Offer;
use App\Models\User;
use App\Models\MetaData;
use App\Models\Career;
use App\Models\Banner;
use App\Models\Images;
use App\Models\Pages;
use App\Models\Faq;
use App\Models\Certificate;
use App\Models\BOM;
use Illuminate\Support\Facades\Session;
use App\Models\CustomerCouponApplyed;
use Illuminate\Support\Facades\Cache;

class FrontController extends Controller
{
    public function __construct()
    {
        // dd('__construct');
        Cache::flush();
    }
     public function autosuggest(Request $request)
    {
        $query = $request->input('query');
        
        $suggestions = Product::where('productname', 'like', '%' . $query . '%')->get();


        return response()->json($suggestions);
    }
     public function about()
    {
        $data=Pages::where(['id'=>5])->first();

        return view('frontview.aboutus',compact('data'));
    }
    public function certificate()
    {
        $data=Certificate::where(['iStatus'=>1,'isDelete'=>0])->get();

        return view('frontview.certificates',compact('data'));
    } 
    public function bomview()
    {
        return view('frontview.bom');
    }
    public function faq()
    {   $faq=Faq::where(['iStatus'=>1,'isDelete'=>0])->get();
        return view('frontview.faq',compact('faq'));
    }
    public function editprofile()
    {
        $id=session()->get('user.customerid');

        $customer=Customer::where(['customerid'=>$id])->first();

        return view('frontview.edit-profile',compact('customer'));
    }
       public function index(Request $request)
    {
    try 
    {
        $seo=MetaData::where(['id'=>1])->first();


        if (session()->get('user.customerid') != "" && session()->get('user.customerid') != 0) 
        {
            $id = session()->get('user.customerid');
        }else{
            $id=0;
        }
        
         $Product = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.slugname',
            'product.isStock',
            'product.isTrandingProduct',
            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as weight"),
             DB::raw("(select slugname from category where product.categoryId=category.categoryId limit 1) as cslugname"),
             DB::raw("(select categoryname from category where product.categoryId=category.categoryId limit 1) as category"),
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "),
             DB::raw("(select customerid from wishlist as favourite where product.productId=favourite.productid and favourite.customerid=$id limit 1) as customerid"))
            ->when($request->search, fn ($query, $search) => $query->where('product.productname','like','%'.$search.'%'))
            ->orderBy('productId', 'desc')
            ->where(['product.iStatus' => 1, 'product.isDelete' => 0,'category.iStatus'=>1])
            ->join('category', 'product.categoryId', '=', 'category.categoryId')
            
            //->toSql();
            ->skip(0)->take(4)
            ->get();
        

            $hotProduct = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.slugname',
            'product.isStock',
            'product.isTrandingProduct',
            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as weight"),
             DB::raw("(select slugname from category where product.categoryId=category.categoryId limit 1) as cslugname"),
             DB::raw("(select categoryname from category where product.categoryId=category.categoryId limit 1) as category"),
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "),
             DB::raw("(select customerid from wishlist as favourite where product.productId=favourite.productid and favourite.customerid=$id limit 1) as customerid"))
            ->when($request->search, fn ($query, $search) => $query->where('product.productname','like','%'.$search.'%'))
            ->orderBy('productId', 'desc')
            ->where(['product.iStatus' => 1, 'product.isDelete' => 0,'product.isTrandingProduct'=>1,'category.iStatus'=>1])
            ->join('category', 'product.categoryId', '=', 'category.categoryId')
            //->toSql();
            ->skip(0)->take(12)
            ->get();

        $category = Category::select('category.*','categoryname as name','photo as categoryphoto',DB::raw("(select categoryname from category as cat where category.subcategoryid=cat.categoryId and category.subcategoryid IS NOT NULL limit 1) as parentname"))->where(['iStatus' => 1, 'isDelete' => 0,'subcategoryid'=>0])->orderBy('categoryname', 'asc')->get();

        $ToDate = date('d-m-Y');
        $Offers = Offer::where(['iStatus' => 1, 'isDelete' => 0])
            ->where('enddate','>=', date('Y-m-d 23:59:59', strtotime($ToDate)))->get();   
        $banner=Banner::where(['iStatus' => 1, 'isDelete' => 0])->get();
        $Images=Images::where(['iStatus' => 1, 'isDelete' => 0])->get();
    return view('frontview.index',compact('category','Product','id','seo','hotProduct','banner','Images'));

        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
        
        
    }
    public function newProduct(Request $request)
    {
        try{
            
         if (session()->get('user.customerid') != "" && session()->get('user.customerid') != 0) 
        {
            $id = session()->get('user.customerid');
        }else{
            $id=0;
        }

        $subid=$request->id;
        $cname="";
        $catname="";
        $category=Category::where(['iStatus'=>1,'isDelete'=>0,'subcategoryid'=>0])->get();

        // \DB::enableQueryLog(); // Enable query log

         $Products = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.slugname',
            'product.isStock',
            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId)and product_attributes.product_id=product.productId  limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId)and product_attributes.product_id=product.productId  limit 1) as weight"),
             
             DB::raw("(select MIN(product_attribute_price) as min_price from product_attributes where product_attributes.product_id=product.productId limit 1) as price"),
             DB::raw("(select slugname from category where product.categoryId=category.categoryId limit 1) as cslugname"),
             DB::raw("(select categoryname from category where product.categoryId=category.categoryId limit 1) as category"),
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "),
             DB::raw("(select customerid from wishlist as favourite where product.productId=favourite.productid and favourite.customerid=$id limit 1) as customerid")
        )
        // ->orderBy('productId', 'desc')
        ->where(['product.iStatus' => 1, 'product.isDelete' => 0,'category.iStatus'=>1])
        ->join('category', 'product.categoryId', '=', 'category.categoryId');
        // ->orWhere(['subcategoryid'=>$categories->categoryId])
        if($subid != 0)
        {
            $categories=Category::where(['iStatus'=>1,'isDelete'=>0,'slugname'=>$subid])->first();
            $cat=Category::where(['iStatus'=>1,'isDelete'=>0,'categoryId'=>$categories->subcategoryid])->first();
            $cname=$categories->categoryname;
             if($categories->subcategoryid == 0 && $categories->categoryId != 0)
            {
                $Products->where(['product.categoryId'=>$categories->categoryId]);
            }else
            {
                  if($categories->subcategoryid != 0)
                {
                    $Products->where(['product.categoryId'=>$categories->subcategoryid]);
                } if($categories->categoryId != 0){
                    $Products->where(['product.subcategoryid'=>$categories->categoryId]);
                }
            }
        }

        $newProduct= $Products->when($request->searchProduct, fn ($query, $HeaderSearch) => $query->where('product.productname', 'LIKE', '%' . $HeaderSearch . '%'))
        ->when($request->category, fn ($query, $categorySearch) => $query->whereIN('product.categoryId',[$categorySearch]))->orderBy('productId', 'desc')

        ->paginate(16);
            // dd($Product);

            // dd(\DB::getQueryLog()); // Show results of log

        $search=$request->searchProduct;
        $categorysearch=$request->category;

        $request->session()->put('subcategory', $subid);

        return view('frontview.newProduct',compact('newProduct','category','search','categorysearch','cname','catname','id'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
 
    }
    public function category_details(Request $request)
    {
        try{
           $subid=$request->id;

           $categories=Category::where(['iStatus'=>1,'isDelete'=>0,'slugname'=>$request->id])->first();
           $cname=$categories->categoryname;
           $seo=Category::where(['iStatus'=>1,'isDelete'=>0,'slugname'=>$request->id])->first();

           $Sql = DB::raw("SELECT category.*,(select categoryname from category as cat where category.subcategoryid=cat.categoryId) as parentname,categoryname as name,photo as categoryphoto FROM `category` where iStatus=1 and isDelete=0 and subcategoryid=".$categories->categoryId);

                  //Category::where(['subcategoryid'=>$subid,'iStatus'=>1,'isDelete'=>0,'categoryId'=>0])->get();
            $subcategory= DB::select($Sql);
            $category=Category::where(['iStatus'=>1,'isDelete'=>0,'subcategoryid'=>0])->get();

            return view('frontview.subCategory',compact('subcategory','category','subid','cname','seo'));
            
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }

     }
      public function product(Request $request)
    {
        try{
        // dd($request);
         if (session()->get('user.customerid') != "" && session()->get('user.customerid') != 0) 
        {
            $id = session()->get('user.customerid');
        }else{
            $id=0;
        }

        $subid=$request->id;
        $cname="";
        $catname="";
        $category=Category::where(['iStatus'=>1,'isDelete'=>0,'subcategoryid'=>0])->get();

        // \DB::enableQueryLog(); // Enable query log

         $Products = Product::select(
            'product.productId',
            'product.categoryId',
            'product.subcategoryid',
            'product.productname',
            'product.slugname',
            'product.isStock',
            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId)and product_attributes.product_id=product.productId  limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as weight"),
             
             DB::raw("(select MIN(product_attribute_price) as min_price from product_attributes where product_attributes.product_id=product.productId limit 1) as price"),
             DB::raw("(select slugname from category where product.categoryId=category.categoryId limit 1) as cslugname"),
             DB::raw("(select categoryname from category where product.categoryId=category.categoryId limit 1) as category"),
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "),
             DB::raw("(select customerid from wishlist as favourite where product.productId=favourite.productid and favourite.customerid=$id limit 1) as customerid")
        )
        ->where(['product.iStatus' => 1, 'product.isDelete' => 0,'category.iStatus'=>1])
        ->join('category', 'product.categoryId', '=', 'category.categoryId');
        // ->orWhere(['subcategoryid'=>$categories->categoryId])
        if($subid != 0)
        {
            $categories=Category::where(['iStatus'=>1,'isDelete'=>0,'slugname'=>$subid])->first();
            $cat=Category::where(['iStatus'=>1,'isDelete'=>0,'categoryId'=>$categories->subcategoryid])->first();
            $cname=$categories->categoryname;
             if($categories->subcategoryid == 0 && $categories->categoryId != 0)
            {
                $Products->where(['product.categoryId'=>$categories->categoryId]);
            }else
            {
                  if($categories->subcategoryid != 0)
                {
                    $Products->where(['product.categoryId'=>$categories->subcategoryid]);
                } if($categories->categoryId != 0){
                    $Products->where(['product.subcategoryid'=>$categories->categoryId]);
                }
            }
        }

        $Product= $Products->when($request->searchProduct, fn ($query, $HeaderSearch) => $query->where('product.productname', 'LIKE', '%' . $HeaderSearch . '%'))
        ->when($request->category, fn ($query, $categorySearch) => $query->whereIN('product.categoryId',[$categorySearch]))->orderBy('productId', 'desc')


        ->paginate(16);
            // dd($Product);

            // dd(\DB::getQueryLog()); // Show results of log

        $search=$request->searchProduct;
        $categorysearch=$request->category;

        $request->session()->put('subcategory', $subid);

        return view('frontview.product',compact('Product','category','search','categorysearch','cname','catname','id'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function product_detail($id)
    {
        try
        {
             if (session()->get('user.customerid') != "" && session()->get('user.customerid') != 0) 
        {
            $customerid = session()->get('user.customerid');
        }else{
            $customerid=0;
        }
            
        $p=Product::where(['iStatus' => 1, 'isDelete' => 0,'slugname'=>$id])->first();
        $product = Product::select('product.*'
        ,DB::raw("(select id  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) AND product_id ='".$p->productId."' limit 1) as attributeId")
        ,DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) AND product_id ='".$p->productId."' limit 1) as rate")
        ,DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) AND product_id ='".$p->productId."' limit 1) as offerPrice")
        ,DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId )AND product_id ='".$p->productId."' limit 1) as weight")
        ,DB::raw("(select categoryname from category where product.categoryId=category.categoryId limit 1) as categoryname"),DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto"))->where(['iStatus' => 1, 'isDelete' => 0,'slugname'=>$id])->first();
        $pname=$product->productname;
        
        $productImages = Productphotos::where(['iStatus' => 1, 'isDelete' => 0,'productid'=>$product->productId])->get();
        
        $ProductAttributes = ProductAttributes::select('product_attributes.id as pid','product_attribute_weight','product_attribute_price')->where(['product_id'=>$product->productId])->join('attributes','attributes.id','=','product_attributes.product_attribute_id')->get();

        $relatedProduct = Product::select(
            'product.productId',
            'product.categoryId',
            'product.productname',
            'product.slugname',
            'product.isStock',
            DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) limit 1) as rate"),
             DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) limit 1) as offerPrice"),
             DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) limit 1) as weight"),
             DB::raw("(select AVG(iRate)  from  productreview where productreview.iProjectId=product.productId limit 1) as avgrate"),
             DB::raw("(select count(id)  from  productreview where productreview.iProjectId=product.productId limit 1) as totalRate"),
             DB::raw("(select categoryname from category where product.categoryId=category.categoryId limit 1) as category"),
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto "), 
             DB::raw("(select customerid from wishlist as favourite where product.productId=favourite.productid and favourite.customerid=$customerid limit 1) as customerid")
        )
            ->orderBy('productId', 'desc')
            ->where(['product.iStatus' => 1, 'product.isDelete' => 0,'category.categoryId'=>$product->categoryId,'category.iStatus'=>1])
            ->join('category', 'product.categoryId', '=', 'category.categoryId')
            
            ->whereNotIN('product.slugname',[$id])->skip(0)->take(4)->get();

        $productrate = DB::table('productreview')->where('iProjectId',$product->productId)->avg('iRate');
        $productreview = DB::table('productreview')->where('iProjectId',$product->productId)->skip(0)->take(4)->orderBy('id','desc')->get();
        $productcount = DB::table('productreview')->where('iProjectId',$product->productId)->count();


        return view('frontview.product-detail',compact('product','productImages','relatedProduct','ProductAttributes','productrate','productcount','productreview','pname'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    public function login()
    {
        return view('frontview.login');
    }
    public function login_store(Request $request)
    {
        
            $validatedData = $request->validate([
                'customeremail' => 'required',
                'password' => 'required'
            ], [
                'customeremail.required' => 'The Customer Email is required',
                'password.required' => 'The Password is required'
            ]);
try{
        $login    = $request->customeremail;

        $data     = Customer::where(['customeremail'=>$login])->first();
        if ($data && Hash::check($request->password, $data->password)) 
        {
            if($data->iStatus == '1')
            {   

                $request->session()->put('user.customerid', $data->customerid);
                $request->session()->put('user.name', $data->customername);
                $request->session()->put('user.email', $data->customeremail);
                $request->session()->put('user.mobile', $data->customermobile);
                $request->session()->put('user.userImage', $data->user_image);


                //return redirect()->route('FrontUserprofile')->with('success',"Login successfully");
                return redirect()->route('FrontUserprofile')->with('success',"Login successfully");
             }
            else
            {
               return redirect()->route('FrontLogin')->with('error'," Your Account Is Inactive");
            }
        }
        else
        {
            return redirect()->route('FrontLogin')->with('error',"Please enter correct login email and password");
        } 
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }


    public function register(){
        return view('frontview.signup');
    }
    public function registerstore(Request $request)
    {
            $validatedData = $request->validate([
                'customername' => 'required',
                'customermobile' => 'required|unique:customer,customermobile',
                'customeremail' => 'required|unique:customer,customeremail',
                'password' => 'required|confirmed|min:6',
                'password_confirmation' => 'required|min:6'

            ], [
                'customername.required' => 'The Name field is required.',
                'customermobile.required' => 'The Mobile No field is required.',
                'customeremail.required' => 'The Email id is required',
                'password_confirmation.required' => 'The Confirm Password is required',
                'password.required' => 'The Password is required'

            ]);
               try{
     
            $User=new User();
            $User->first_name=$request->customername ?? "";
            $User->email=$request->customeremail ?? "";
            $User->mobile_number=$request->customermobile ?? "";
            $User->role_id= 2;
            $User->password=Hash::make($request->password);
            $User->save();

            $Customer=new Customer();
            $Customer->userId=$User->id;
            $Customer->customername=$request->customername;
            $Customer->customeremail=$request->customeremail;
            $Customer->customermobile=$request->customermobile;
            $Customer->password=Hash::make($request->password);
            $Customer->strIp=$_SERVER['REMOTE_ADDR'];
            $Customer->save();

            return redirect()->route('FrontLogin')->with('success',"You are successfully register");
            
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    
    public function forgotpassword(Request $request)
    {
        return view('frontview.forgotpassword');
    }


    //send mail for new pass
    public function forgotpasswordsubmit(Request $request)
    {
        try{
        $Customer = DB::table('customer')->where(['customeremail' => $request->customeremail, 'iStatus' => 1, 'isDelete' => 0])->first();

        if (!empty($Customer)) {
            $token = Str::random(64);
            $data = array(
                'customeremail' => $request->customeremail,
                'fetch' => $Customer,
                'token' => $token,
            );

            $update = DB::table('customer')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'customerid' => $Customer->customerid])
                ->update([
                    'token' => $token,
                ]);

            $SendEmailDetails = DB::table('sendemaildetails')
                ->where(['id' => 8])
                ->first();
                $sendmail =$request->customeremail;
            $msg = array(
                'FromMail' => $SendEmailDetails->strFromMail,
                'Title' => $SendEmailDetails->strTitle,
                'ToEmail' => $request->customeremail,
                'Subject' => $SendEmailDetails->strSubject
            );
           
            $root = $_SERVER['DOCUMENT_ROOT'];
            $file = file_get_contents($root . '/mailers/forgetpassword.html', 'r');
            $file = str_replace('#name', $data['fetch']->customername, $file);
            $file = str_replace('#email', 'https://sukti.in/New-Password/' . $token, $file);
            // dd($file);
            $setting = DB::table("setting")->select('email')->first();
            $toMail = $sendmail ; //$setting->email;// "shahkrunal83@gmail.com";//
            // dd($toMail);
            $to = $toMail;
            $subject = $SendEmailDetails->strSubject;
            $message = $file;
            $header = "From:".$SendEmailDetails->strFromMail."\r\n";
            //$header .= "Cc:afgh@somedomain.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            
            $retval = mail($to,$subject,$message,$header);

            return back()->with('success', 'We have emailed your password reset link!');
        } else {
            return back()->with('error', 'Email Is Not Registered');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function newpassword(Request $request, $token)
    {
        return view('frontview.newpassword', ['token' => $token]);
    }

    public function newpasswordsubmit(Request $request)
    {
        
          $validatedData = $request->validate([
                'newpassword' => 'required|min:6',
                'confirmpassword' => 'required|min:6'
            ], [
                'newpassword.required' => 'The New Password is required',
                'confirmpassword.required' => 'The Confirm Password is required'
            ]);
            try{
        $newpassword = $request->newpassword;
        $confirmpassword = $request->confirmpassword;

        $Customer = DB::table('customer')->where(['token' => $request->token, 'iStatus' => 1, 'isDelete' => 0])->first();
        // dd($Customer);

        if ($Customer->token == $request->token) {
            if ($newpassword == $confirmpassword) 
            {
                $User = DB::table('users')
                        ->where(['status' => 1, 'id' => $Customer->userId])
                        ->update([
                            'password' => Hash::make($request->confirmpassword),
                        ]);
                        
                $customer = DB::table('customer')
                    ->where(['iStatus' => 1, 'isDelete' => 0, 'customerid' => $Customer->customerid])
                    ->update([
                        'password' => Hash::make($request->confirmpassword),
                        'token' => null,
                    ]);
                    
                return redirect()->route('FrontLogin')->with('success', 'Your password has been successfully changed!');
            } else {
                return back()->with('error', 'Password And Confirm Password Does Not Match.');
            }
        } else {
            return back()->with('error', 'Token Not Match.');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function cart()
    {
        try{
        $id=session()->get('user.customerid');
        if (isset($id) && (!empty($id))) 
        {
         $cart = OrderDetail::select(
            'orderdetail.*',
            'product.productId as pid',
            'product.slugname',
            'product.productname',
             DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto ")
            )->join('product','product.productid','=','orderdetail.productid')
            ->orderBy('productId', 'desc')
            ->where(['product.iStatus' => 1, 'product.isDelete' => 0,'isFeatures'=>1])
            // ->join('category', 'product.categoryId', '=', 'category.categoryId')
            //->toSql();
            ->get();
            return view('frontview.cart',compact('cart'));
        }
        return redirect()->route('FrontLogin');
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function contact()
    {
        try{
        $seo=MetaData::where(['id'=>3])->first();

        return view('frontview.contact',compact('seo'));
        
        
        } catch (\Exception $e) {
    
            report($e);
     
            return false;
        }
    }
    public function career()
    {
        try{
        $career=Career::where(['iStatus'=>1,'isDelete'=>0])->get();

        return view('frontview.career',compact('career'));
        
            
        } catch (\Exception $e) {

            report($e);
     
            return false;
        }
    }
    public function checkout(Request $request)
    {
        try{
        $Coupon = $request->session()->get('data');
     
        $customerid=session()->get('user.customerid');
        // if (isset($customerid) && (!empty($customerid))) 
        // {
        $cartItems = \Cart::getContent(); 
        $Shipping = Shipping::orderBy('id', 'asc')->first();
        $customer = Customer::where(['customerid'=>$customerid])->first();
        $state = State::orderBy('name','ASC')->get();
        return view('frontview.checkout',compact('cartItems','customer','Shipping','state','Coupon'));
        // } else {
        //     return redirect()->route('FrontLogin');
        // }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    public function couponcodeapply(Request $request)
    {
        try{
        // dd($request);
        $session = Session::get('user.customerid');
        // dd($session);
        $Offer = Offer::where(['iStatus' => 1, 'isDelete' => 0, 'offercode' => $request->coupon])->first();
       if($Offer != null)
       {
               $CouponApply = CustomerCouponApplyed::where(['customerId' => $session, 'offerId' => $Offer->id])->count();
        }
        // dd($CouponApply);
        $Today = date('Y-m-d');
        // dd($Today);
        $Coupon = $request->coupon ?? "";
        $Total = $request->totalAmount ?? 0;
        $Percentage = $Offer->type ?? null;
        $OfferCode = $Offer->offercode ?? null;

        // ]if ($CouponApply <= 0) {
        if ($Coupon == $OfferCode) 
        {
            if ($Total >= $Offer->minvalue) {
                // dd('mainif');
                // 2023-10-05 >= 2023-10-02 && 2023-10-05  <= 2023-10-07
                if (($Today >= $Offer->startdate) && ($Today <= $Offer->enddate)) {

                    $result = (($Total * 1)) * (($Percentage * 1) / (100 * 1));
                    $resultround = round($result);
                    $data = array(
                        'offerId' => $Offer->id,
                        'customerId' => $session ?? 0,
                        'result' => $resultround,
                        'created_at' => date('Y-m-d H:i:s'),
                        "strIP" => $request->ip()
                    );
                    $Coupon = CustomerCouponApplyed::create($data);
                    return redirect()->route('FrontCheckout')->with([
                        'success' => 'Coupon Code Apply Successfully!',
                        'data' => $Coupon
                    ]);
                } else {
                    return redirect()->back()->with('success', 'Coupon is expired!');
                }
            } else {
                return redirect()->back()->with('error', 'Please Enter Min Value!'.$Offer->minvalue);
            }
        } else {
            return redirect()->back()->with('error', 'Coupon Code Not Match!');
        }
        // } else {
        //     return redirect()->back()->with('couponused', 'Coupon Code Already Used!');
        // }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    
    public function checkoutstore(Request $request)
    {
        try{
            
        $customerid=session()->get('user.customerid');
        $random = Str::random(8);
        $password = Hash::make($random);;

        $cartItems = \Cart::getContent();
        $Shipping = Shipping::orderBy('id', 'desc')->first();
        $ShippingCharges = $Shipping->rate;
        $amount = \Cart::getTotal();
        $netamount = $amount + $ShippingCharges;
        
        $customerData = Customer::where(['isDelete' => 0, 'iStatus' => 1, 'customeremail' => $request->billEmail])->orWhere(['customermobile'=>$request->billPhone])->first();
        $customerid=0;

        if (isset($customerData->customeremail) || isset($customerData->customermobile)) 
        {
            $customerid=$customerData->customerid;
            $customername=$customerData->customername;
            $customermobile=$customerData->customermobile;
            $customeremail=$customerData->customeremail;
           
        }
         else 
        {
             $User=new User();
            $User->first_name=$request->billFirstName . ' ' . $request->billLastName;
            $User->email=$request->billEmail ?? "";
            $User->mobile_number=$request->billPhone ?? "";
            $User->role_id= 2;
            $User->save();
            
            $Customer=new Customer();
            $Customer->userId=$User->id;
            $Customer->customername=$request->billFirstName . ' ' . $request->billLastName;
            $Customer->customeremail=$request->billEmail;
            $Customer->customermobile=$request->billPhone;
            $Customer->strIp=$_SERVER['REMOTE_ADDR'];
            $Customer->save();

            $customerid = $Customer->id;
            $customername = $request->billFirstName . ' ' . $request->billLastName;
            $customermobile = $request->billPhone;
            $customeremail = $request->billEmail;
        }
        
     if($request->diffrentAdd == 'yes')
     {
          $Order = array(
            'customerid' => $customerid,
            'cutomerName'=> $request->billFirstName . ' ' . $request->billLastName,
            'mobile'=> $request->billPhone ?? 0,
            'email'=> $request->billEmail ?? '',
            'address'=>$request->billAddress. ' ' .$request->billAddress2,
            'state'=>$request->billState,
            'city'=>$request->billCity,
            'pincode'=>$request->billPinCode,
            'shipping_cutomerName' => $request->shippingFirstName . ' ' . $request->shippingLastName,
            'shipping_companyName' => $request->billCompanyName,
            'shipping_GSTNumber' => $request->billGSTNumber,
            'shipping_mobile' => $request->shippingPhone,
            'shipping_email' => $request->shippingEmail,
            'shiiping_address1' => $request->shippingAddress,
            'shiiping_address2' => $request->shippingAddress2,
            'shipping_city' => $request->shippingCity,
            'shiiping_state' => $request->shippingState,
            'shipping_pincode' => $request->shippingPinCode,
            'orderNote' => $request->billNotes,
            'deliveryType' => $request->billDeliveryType,
            'amount' => $amount,
            'discount' => $request->discount,
            'shipping_Charges' => $request->shippingcharges ?? 0,
            'netAmount' => $request->netamount,
            'created_at' => date('Y-m-d H:i:s'),
            'strIP' => $request->ip()
        );
        $OrderId = DB::table('order')->insertGetId($Order);

     }else{
         $Order = array(
            'customerid' => $customerid,
            'cutomerName'=> $customername,
            'mobile'=> $customermobile ?? 0,
            'email'=> $customeremail ?? '',
            'address'=>$request->billAddress. ' ' .$request->billAddress2,
            'state'=>$request->billState,
            'city'=>$request->billCity,
            'pincode'=>$request->billPinCode,
            'shipping_cutomerName' => $request->billFirstName . ' ' . $request->billLastName,
            'shipping_companyName' => $request->billCompanyName,
            'shipping_GSTNumber' => $request->billGSTNumber,
            'shipping_mobile' => $request->billPhone,
            'shipping_email' => $request->billEmail,
            'shiiping_address1' => $request->billAddress,
            'shiiping_address2' => $request->billAddress2,
            'shipping_city' => $request->billCity,
            'shiiping_state' => $request->billState,
            'shipping_pincode' => $request->billPinCode,
            'orderNote' => $request->billNotes,
            'deliveryType' => $request->billDeliveryType,
            'amount' => $amount,
            'discount' => $request->discount,
            'shipping_Charges' => $request->shippingcharges ?? 0,
            'netAmount' => $request->netamount,
            'created_at' => date('Y-m-d H:i:s'),
            'strIP' => $request->ip()
        );
        $OrderId = DB::table('order')->insertGetId($Order);

     }
      
        foreach ($cartItems as $cartItem) {
            $OrderDetail = array(
                'orderID' => $OrderId,
                'customerid' => $customerid,
                'productId' => $cartItem->productId,
                'quantity' => $cartItem->quantity,
                'weight' => $cartItem->weight,
                'rate' => $cartItem->price,
                'amount' => $cartItem->price * $cartItem->quantity,
                'isPayment' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                "strIP" => $request->ip()
            );
            DB::table('orderdetail')->insert($OrderDetail);
        }

        // return back();
        return redirect()->route('razorpay.index',$OrderId);
        // return view('frontview.razorpayView', compact('Order', 'OrderId'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
 
    public function myorders(Request $request)
    {
        try{
        if ($request->session()->get('user.customerid') != "") 
        {
            $session = session()->get('user.customerid');

            $Order = Order::select('order.*',DB::raw('(SELECT order_id FROM card_payment WHERE order.order_id=card_payment.oid LIMIT 1) as orderId'))->where(['order.iStatus' => 1, 'order.isDelete' => 0, 'order.customerid' => $session])->orderBy('created_at', 'desc')
                // ->join('product', 'orderdetail.productId', '=', 'product.productId')
                ->paginate(10);

            return view('frontview.my-order', compact('Order'));
        } else {
            return redirect()->route('FrontLogin')->with('error', 'Invalid Email or Password');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
   
    public function myordersdetails(Request $request, $id)
    {
        try{
        // dd($request->session());
        if ($request->session()->get('user.customerid') != "") 
        {
            $session = session()->get('user.customerid');
            $Orders = OrderDetail::select(
                'orderdetail.productId',
                'orderdetail.orderDetailId',
                'orderdetail.orderID',
                'orderdetail.created_at',
                'orderdetail.quantity',
                'orderdetail.weight',
                'orderdetail.rate',
                'orderdetail.amount',
                'product.productname',
                DB::raw('(SELECT strphoto FROM productphotos WHERE  productphotos.productid=product.productId  LIMIT 1) as photo'),
                DB::raw('(SELECT iCustomerId FROM productreview WHERE productreview.iProjectId=product.productId  LIMIT 1) as rcustomerId'),
                DB::raw('(SELECT slugname FROM product WHERE product.productId=orderdetail.productId  LIMIT 1) as slugname')

            )
                ->where(['orderdetail.iStatus' => 1, 'orderdetail.isDelete' => 0, 'orderdetail.customerid' => $session, 'orderdetail.orderID' => $id])
                ->join('product', 'orderdetail.productId', '=', 'product.productId')->orderBy('created_at', 'desc')
                ->get();
            // dd($Order);
            $Order = Order::select('order.*',DB::raw('(select states.name from states where order.shiiping_state=states.id limit 1) as state'),DB::raw('(SELECT order_id FROM card_payment WHERE order.order_id=card_payment.oid LIMIT 1) as orderId'))->where(['iStatus' => 1, 'isDelete' => 0, 'customerid' => $session,'order_id'=>$id])->first();
            
            return view('frontview.order-detail', compact('Orders','Order'));
        } else {
            return redirect()->route('FrontLogin')->with('error', 'Invalid Email or Password');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
   
    public function privacypolicy()
    {
        try{
        $data=Pages::where(['id'=>1])->first();
        $seo=MetaData::where(['id'=>4])->first();

        return view('frontview.privacy_policy',compact('data','seo'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    } 
    
     public function termscondition()
    {
        try{
        $data=Pages::where(['id'=>2])->first();
        $seo=MetaData::where(['id'=>5])->first();

        return view('frontview.term_condition',compact('data','seo'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    } 
    
     public function cancellationandrefund()
    {
        try{
        $data=Pages::where(['id'=>3])->first();
        return view('frontview.cancellation_and_refund',compact('data'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    } 
    
     public function shippinganddelhivery()
    {
        try{
        $data=Pages::where(['id'=>4])->first();
        return view('frontview.shipping_and_Delivery',compact('data'));
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    } 
    public function userprofile()
    {
        try{
        $id=session()->get('user.customerid');
        if ($id != "") 
        {
            $customer=Customer::where(['customerid'=>$id])->first();
            return view('frontview.user-profile',compact('customer'));
        } else {
            return redirect()->route('FrontLogin');
        }
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    
    public function changepassword(Request $request)
    {
        try{
        if ($request->session()->get('user.customerid') != "") {
            return view('frontview.changepassword');
        } else {
            return redirect()->route('FrontLogin')->with('error', 'Invalid Email or Password');
        }
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function changepasswordsubmit(Request $request)
    {

        
        $validatedData = $request->validate([
                'newpassword' => 'required|min:6',
                'confirmpassword' => 'required|min:6'
            ], [
                'newpassword.required' => 'The New Password is required',
                'confirmpassword.required' => 'The Confirm Password is required'
            ]);

        try{
         $session = $request->session()->get('user.customerid');
        $current_password=$request->current_password;
        $newpassword = $request->newpassword;
        $confirmpassword = $request->confirmpassword;

        $customer=Customer::where(['customerid'=>$session])->first();

        // The passwords matches
        if (!Hash::check($current_password, $customer->password))
        {
            return back()->with('error', "Current Password is Invalid");
        }
        if ($newpassword == $confirmpassword) 
        {
            $User = DB::table('users')
                ->where(['status' => 1, 'id' => $customer->userId])
                ->update([
                    'password' => Hash::make($confirmpassword),
                ]);
                
            $customer = DB::table('customer')
                ->where(['iStatus' => 1, 'isDelete' => 0, 'customerid' => $session])
                ->update([
                    'password' => Hash::make($request->confirmpassword),
                ]);
                
            
                    $request->session()->forget('cartTotal');
        $request->session()->forget('user.customerid');
        $request->session()->forget('user.email');
        $request->session()->forget('user.name');
        $request->session()->forget('user.mobile');

          return redirect()->route('FrontLogin')->with('success', 'Change Password Successfully!');
            // return back()->with('success', 'Change Password Successfully!');
        } else {
            return back()->with('error', 'Password And Confirm Password Not Match!');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    
    public function userupdate(Request $request)
    {
        
        $id=$request->customerid;

         $validatedData = $request->validate([
                'customeremail' => 'required|unique:customer,customeremail,' . $request->customerid . ',customerid',
                'customername' => 'required',
                'address' => 'required',
                'customermobile' => 'required|unique:customer,customermobile,' . $request->customerid . ',customerid'
            ]);
        //  if($request->hiddenuserPhoto == ""){
        //     $validatedData = $request->validate([
        //         'user_image' => 'required'
        //     ]);
        //  }
        try
        {
        $root = $_SERVER['DOCUMENT_ROOT'];
        if($request->hasFile('user_image'))
        {
            $fimage = $request->file('user_image');
            $userImg = time().'.'.$fimage->getClientOriginalExtension();
            $destinationpath = $root.'/userProfilePic/';
            if(!file_exists($destinationpath)) {

                mkdir($destinationpath, 0755, true);
            }
            $fimage->move($destinationpath,$userImg);
        }
        else
        {
            $oldFrontImage = $request->input('hiddenuserPhoto');
            $userImg = $oldFrontImage;
        }
        
        
            $User = User::where(["id"=>$request->user_id])->update([
                 "first_name"=>$request->customername ?? "",
                "email"=>$request->customeremail ?? "",
                "mobile_number"=>$request->customermobile ?? "",
                ]);
                
            $Customer = Customer::where(["customerid"=>$id])->update([
                            'customeremail'=>$request->customeremail,
                            'customername'=>$request->customername,
                            'customermobile'=>$request->customermobile,
                            'address'=>$request->address,
                            'user_image'=>$userImg
                        ]);
                        
            $request->session()->forget('user.name');
            $request->session()->put('user.name', $request->customername);

            
         return redirect()->route('FrontUserprofile')->with('success',"user profile updated successfully");
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
        
    }

    public function wishlist()
    {
        try
        {
            $id=session()->get('user.customerid');

        if (isset($id) && (!empty($id))) 
        {
            $id=session()->get('user.customerid');
            $wishlist=wishlist::select('product.*','wishlist.customerid as wcid','wishlist.productid as pid'
            ,DB::raw("(select strphoto from productphotos as pimages where product.productId=pimages.productid limit 1) as strphoto")
            ,DB::raw("(select product_attribute_price  from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as rate")
            ,DB::raw("(select product_attribute_offer_price  from  product_attributes where  product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId) and product_attributes.product_id=product.productId  limit 1) as offerPrice")
            ,DB::raw("(select product_attribute_weight from  product_attributes where product_attribute_offer_price=(select min(product_attribute_offer_price) from product_attributes where product_attributes.product_id=product.productId)and product_attributes.product_id=product.productId  limit 1)  as weight"),
             DB::raw("(select customerid from wishlist as favourite where product.productId=favourite.productid and favourite.customerid=$id limit 1) as customerid")

            )
            ->where(['wishlist.customerid'=>$id])->join('customer','customer.customerid','=','wishlist.customerid')->join('product','product.productid','=','wishlist.productid')->get();
           
            return view('frontview.wishlist',compact('wishlist'));
        }  else {
            return redirect()->route('FrontLogin');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    
    public function FrontLogout(Request $request)
    {
        try{
                \Cart::clear();

        $request->session()->forget('cartTotal');
        $request->session()->forget('user.customerid');
        $request->session()->forget('user.email');
        $request->session()->forget('user.name');
        $request->session()->forget('user.mobile');
        // return view('student.logout');
        return redirect()->route('FrontIndex');
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    
        public function productreview(Request $request)
    {
        try{
        $customerid=session()->get('user.customerid');;
        $strName=session()->get('user.name');
        $strEmail=session()->get('user.email');
        $image=session()->get('user.userImage');

        $data = array(
            "iProjectId" => $request->productId,
            "iCustomerId" => $customerid,
            "iRate" => $request->iRate,
            "strMessage" => $request->strMessage,
            "strName" => $strName,
            "strEmail" => $strEmail,
            "userImage" => $image,
            "strIP" => $request->ip()
        );
        if(DB::table('productreview')->insert($data)){
            return redirect()->back()->with('success', 'Review Added Successfully.');
        } else {
            return redirect()->back()->with('error', 'Invalid Request.');
        }
        
        
    } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    public function checkmobile(Request $request)
    {
        $Data = Order::orderBy('order_id', 'DESC')
            ->where(['email' => $request->email])
            ->first();
       
        return  json_encode($Data);
    }


}