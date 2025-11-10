<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productphotos;
use Illuminate\Support\Facades\DB;
use Image;
use App\Models\Attributes;
use App\Models\ProductAttributes;


class ProductController1 extends Controller
{
    public function index(Request $request)
    {
        try{
        $Product = Product::select(
            'product.productId',
            'product.categoryId',
            'product.productname',
            'product.rate',
            'product.iStatus',
            DB::raw("(select MIN(product_attribute_price)  from  product_attributes where product_attributes.product_id=product.productId limit 1) as rate"),
            DB::raw("(select categoryname from category as cat where product.subcategoryid=cat.categoryId and product.subcategoryid IS NOT NULL limit 1) as subcategoryname"),
            DB::raw("(select categoryname from category as cat where product.categoryId=cat.categoryId and product.categoryId IS NOT NULL limit 1) as categoryname")
        )
            ->when($request->search, fn ($query, $search) => $query->where('product.productname','like','%'.$search.'%'))

            ->orderBy('productId', 'desc')
            // ->join('category', 'product.categoryId', '=', 'category.categoryId')
            //->toSql();
            ->paginate(12);
        // dd($Product);

        // $Sql = DB::raw("SELECT categoryId,(select categoryname from category as cat where category.subcategoryid=cat.categoryId) as parentname,categoryname as name,photo as categoryphoto FROM `category`");
            $search=$request->search;

        return view('admin.product.index', compact('Product','search'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function createview()
    {
        try{
        $Category = Category::where('subcategoryid', 0)->orderBy('categoryId', 'desc')->get();

        return view('admin.product.add', compact('Category'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function getsubcategory(Request $request)
    {
        try{
        $html = "";
        $SubCategory = Category::where(['iStatus' => 1, 'isDelete' => 0, 'subcategoryid' => $request->Category])->get();
        $html .= '<option value="" selected >Select Sub Category</option>';
        foreach ($SubCategory as $subcategory) {
            $html .= '<option value=' . $subcategory->categoryId . '>' . $subcategory->categoryname . '</option>';
        }

        echo $html;
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function create(Request $request)
    {
        try
        {
            $img = "";
            if ($request->hasFile('document')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('document');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/Document/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
            }


        $Data = array(
            'categoryId' => $request->categoryId,
            'subcategoryid' => $request->subcategoryid ?? 0,
            'productname' => str_replace('/', '-', $request->productname),
            'slugname' => str_replace('/', '-',$request->slugname),
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'created_at' => date('Y-m-d H:i:s'),
            'viewINHomePage' => $request->viewINHomePage ?? 0,
            'isTrandingProduct' => $request->isTrandingProduct ?? 0,
            'document' => $img ?? '',
            'isStock' => $request->isStock,
            'strIP' => $request->ip()
        );
        $InsetedId = DB::table('product')->insertGetId($Data);
        foreach ($request->file('photo') as $file) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('photo');
                $imgName = time() . '_' . mt_rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = $root . '/Product/Thumbnail';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $img = Image::make($file->getRealPath());
                $img->resize(540, 720, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $imgName);

                $destinationpath = $root . '/Product';
                $file->move($destinationpath, $imgName);

                $data = array(
                    'productid' => $InsetedId,
                    'strphoto' => $imgName,
                    'strIP' => $request->ip(),
                    'created_at' => date('Y-m-d H:i:s'),
                );
                DB::table('productphotos')->insert($data);
            }
        return redirect()->route('product.index')->with('success', 'Product Created Successfully.');
            } catch (\Exception $e) {

                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());

            }
    }

    public function GetSelectedSubCategory(Request $request)
    {
       try
       {

        $html = "";
        $SubCategory = Category::where(['iStatus' => 1, 'isDelete' => 0, 'subcategoryid' => $request->Category])->get();
        $html .= '<option value="" selected >Select Sub Category</option>';
        foreach ($SubCategory as $subcategory) {
            if ($request->SubCategory == $subcategory->categoryId) {
                $html .= '<option value=' . $subcategory->categoryId  . ' selected >' . $subcategory->categoryname . '</option>';
            } else {
                $html .= '<option value=' . $subcategory->categoryId . '>' . $subcategory->categoryname . '</option>';
            }
        }
        echo $html;
       } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function editview(Request $request, $id)
    {
        try{
        $Category = Category::where('subcategoryid', 0)->orderBy('categoryId', 'desc')->get();
        $product = Product::where(['productId' => $id])->first();
        return view('admin.product.edit', compact('product', 'Category'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    public function editStatus($id)
    {
        try
        {
            $data=Product::where(['productId'=>$id])->first();
            if($data->iStatus == 1)
            {
             $status=0;   
            }else{
            $status=1;     
            }
           
             $update = DB::table('product')
                ->where(['productId' => $id])
                ->update([
                    'iStatus' => $status
                ]);
    
        return  redirect()->route('product.index')->with('success', 'Status Updated Successfully.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
        }
    }

    public function getEditsubcategory(Request $request)
    {
        try{
        $html = "";
        $SubCategory = Category::where(['iStatus' => 1, 'isDelete' => 0, 'subcategoryid' => $request->Category])->get();
        $html .= '<option value="" selected >Select Sub Category</option>';
        foreach ($SubCategory as $subcategory) {
            $html .= '<option value=' . $subcategory->categoryId . '>' . $subcategory->categoryname . '</option>';
        }

        echo $html;
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function update(Request $request, $id)
    {
        try
        {
         $img = "";
            if ($request->hasFile('document')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('document');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/Document/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
            }

        
        $update = DB::table('product')
            ->where(['productId' => $id])
            ->update([
                'categoryId' => $request->categoryId,
                'subcategoryid' => $request->subcategoryid ?? 0,
            'productname' => str_replace('/', '-', $request->productname),
            'slugname' => str_replace('/', '-',$request->slugname),
                // 'rate' => $request->rate,
                // 'weight' => $request->weight,
                'description' => $request->description,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'viewINHomePage' => $request->viewINHomePage ?? 0,
                'isTrandingProduct' => $request->isTrandingProduct ?? 0,
                'isStock' => $request->isStock,
                'document' => $img,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

      if ($request->hasFile('photo')) 
            {

                foreach ($request->file('photo') as $file) {
                    $root = $_SERVER['DOCUMENT_ROOT'];
                    $image = $request->file('photo');
                    $imgName = time() . '_' . mt_rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = $root . '/Product/Thumbnail';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    $img = Image::make($file->getRealPath());
                    $img->resize(540, 720, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $imgName);

                    $destinationpath = $root . '/Product';
                    $file->move($destinationpath, $imgName);

                    $data = array(
                        'productid' => $id,
                        'strphoto' => $imgName,
                        'strIP' => $request->ip(),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    DB::table('productphotos')->insert($data);
                }
            }
        return redirect()->route('product.index')->with('success', 'Product Updated Successfully.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    //Product Index Page Delete
    public function delete(Request $request)
    {
        try
        {
        $delete = DB::table('productphotos')->where(['productid' => $request->productId])->get();

        if ($_SERVER['SERVER_NAME'] == "127.0.0.1") {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root . '/Product/';
            $destinationpath1 = $root . '/Product/Thumbnail/';

            foreach ($delete as $deletes) {
                if (file_exists($destinationpath1 . $deletes->strphoto)) {
                    unlink($destinationpath1 . $deletes->strphoto);
                }
                if (file_exists($destinationpath . $deletes->strphoto)) {
                    unlink($destinationpath . $deletes->strphoto);
                }
            }
        } else {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root.'/Product/';
            $destinationpath1 = $root.'/Product/Thumbnail/';

            foreach ($delete as $deletes) {
                if (file_exists($destinationpath1 . $deletes->strphoto)) {
                    unlink($destinationpath1 . $deletes->strphoto);
                }
                if (file_exists($destinationpath . $deletes->strphoto)) {
                    unlink($destinationpath . $deletes->strphoto);
                }
            }
        }
        DB::table('productphotos')->where(['productId' => $request->productId])->delete();

        DB::table('product')->where(['productId' => $request->productId])->delete();

        return redirect()->route('product.index')->with('success', 'Product Deleted Successfully!.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    //Product Image Delete In Edit Page
    public function productimage(Request $request, $id)
    {
        try
        {
        $delete = DB::table('productphotos')->where(['productphotosid' => $id])->first();

        if ($_SERVER['SERVER_NAME'] == "127.0.0.1") {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root .'/Product/';
            $destinationpath1 = $root .'/Product/Thumbnail/';
            if (file_exists($destinationpath1 . $delete->strphoto)) {
                unlink($destinationpath1 . $delete->strphoto);
            }
            if (file_exists($destinationpath . $delete->strphoto)) {
                unlink($destinationpath . $delete->strphoto);
            }
        } else {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root .'/Product/';
            $destinationpath1 = $root .'/Product/Thumbnail/';

            if (file_exists($destinationpath1 . $delete->strphoto)) {
                unlink($destinationpath1 . $delete->strphoto);
            }
            if (file_exists($destinationpath . $delete->strphoto)) {
                unlink($destinationpath . $delete->strphoto);
            }
        }
        DB::table('productphotos')->where(['iStatus' => 1, 'isDelete' => 0, 'productphotosid' => $id])->delete();

        echo 1;
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    //Product Photos Listing Page
    public function productphotos(Request $request, $id)
    {
        try{
        $datas = Productphotos::orderby('productphotosid', 'desc')->where(['iStatus' => 1, 'isDelete' => 0, 'productid' => $id])->paginate(5);

        return view('admin.product.photoslist', compact('datas'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    //In Product Photos Listing Page Photo Delete
    public function productphotosdelete(Request $request)
    {
        try{
        $delete = DB::table('productphotos')->where(['iStatus' => 1, 'isDelete' => 0, 'productphotosid' => $request->productphotosid])->first();

        if ($_SERVER['SERVER_NAME'] == "127.0.0.1") {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root . '/Product/';
            $destinationpath1 = $root . '/Product/Thumbnail/';
            if ($delete->strphoto) {
                unlink($destinationpath1  . $delete->strphoto);
            }
            if ($delete->strphoto) {
                unlink($destinationpath  . $delete->strphoto);
            }
        } else {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root . '/Product/';
            $destinationpath1 = $root . '/Product/Thumbnail/';
            if ($delete->strphoto) {
                unlink($destinationpath1  . $delete->strphoto);
            }
            if ($delete->strphoto) {
                unlink($destinationpath  . $delete->strphoto);
            }
        }

        DB::table('productphotos')->where(['iStatus' => 1, 'isDelete' => 0, 'productphotosid' => $request->productphotosid])->delete();
        return back()->with('success', 'Product Photo Deleted Successfully!.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
    
    public function product_attribute(Request $request, $id)
    {
        try{
        $Product = Product::orderBy('productId', 'desc')
            ->where([ 'product.productId' => $id])
            ->first();
            
        $ProductAttributes = ProductAttributes::select(
            'product_attributes.id',
            'product_attributes.product_id',
            'product_attributes.product_attribute_id',
            'product_attributes.product_attribute_weight',
            'product_attributes.product_attribute_price',
            'product_attributes.product_attribute_offer_price',
            'product_attributes.product_attribute_photo',
            'attributes.name'
        )
            ->orderBY('product_attributes.id', 'desc')
            ->where(['product_id' => $id])
            ->join('attributes', 'product_attributes.product_attribute_id', '=', 'attributes.id')
            ->paginate(25);
        // dd($ProductAttributes);
        $Attribute = Attributes::get();

        return view('admin.product.attribute', compact('Product','Attribute', 'ProductAttributes', 'id'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function product_attribute_store(Request $request)
    {
        try{
        // dd($request);
        $img = "";
        if ($request->hasFile('product_attribute_photo')) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $image = $request->file('product_attribute_photo');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $destinationpath = $root . '/ProductAttribute/';
            if (!file_exists($destinationpath)) {
                mkdir($destinationpath, 0755, true);
            }
            $image->move($destinationpath, $img);
        }
        
        $Data = array(
            'product_id' => $request->productid ?? 0,
            'product_attribute_id' => $request->product_attribute_id ?? 0,
            'product_attribute_weight' => $request->product_attribute_weight,
            'product_attribute_price' => $request->product_attribute_price,
            'product_attribute_offer_price' => $request->product_attribute_offer_price,
            'product_attribute_photo' => $img,
            'created_at' => date('Y-m-d H:i:s'),
        );
        $InsetedId = DB::table('product_attributes')->insertGetId($Data);

        return redirect()->route('product.product_attribute', $request->productid)->with('success', 'Product Attribute Created Successfully.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function product_attribute_editview(Request $request, $id)
    {
        try{
        $ProductAttributes = ProductAttributes::where(['id' => $id])->first();

        echo json_encode($ProductAttributes);
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function product_attribute_update(Request $request)
    {
        try{
        // dd($request);
        $img = "";
        if ($request->hasFile('product_attribute_photo')) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $image = $request->file('product_attribute_photo');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $destinationpath = $root . '/ProductAttribute/';
            if (!file_exists($destinationpath)) {
                mkdir($destinationpath, 0755, true);
            }
            $image->move($destinationpath, $img);
            $oldImg = $request->input('hiddenPhoto') ? $request->input('hiddenPhoto') : null;

            if ($oldImg != null || $oldImg != "") {
                if (file_exists($destinationpath . $oldImg)) {
                    unlink($destinationpath . $oldImg);
                }
            }
        } else {
            $oldImg = $request->input('hiddenPhoto');
            $img = $oldImg;
        }


        $update = DB::table('product_attributes')
            ->where(['id' => $request->id])
            ->update([
                'product_attribute_id' => $request->product_attribute_id ?? 0,
                'product_attribute_weight' => $request->product_attribute_weight,
                'product_attribute_price' => $request->product_attribute_price,
                'product_attribute_offer_price' => $request->product_attribute_offer_price,
                'product_attribute_photo' => $img,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return back()->with('success', 'Product Attribute Updated Successfully.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }

    public function product_attribute_delete(Request $request)
    {
        try{
        // dd($request);
        $delete = DB::table('product_attributes')->where(['id' => $request->id])->first();
        
        $root = $_SERVER['DOCUMENT_ROOT'];
        $destinationpath = $root . '/ProductAttribute/';

        if ($delete->product_attribute_photo) {
            unlink($destinationpath  . $delete->product_attribute_photo);
        }
        
        DB::table('product_attributes')->where(['id' => $request->id])->delete();

        return back()->with('success', 'Product Attribute Deleted Successfully!.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
}
