<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;



class CategoryController extends Controller
{
    
    private $web_url;
   /* public function __construct()
    {
        $this->middleware('auth:api');
        // $this->web_url =  "http://getdemo.in/sukti/api";
        $this->web_url =  "http://127.0.0.1:8000";
    }*/

  public function index(Request $request)
  {
        $Category=Category::where(['category.iStatus'=>1,'category.isDelete'=>0,'subcategoryid'=>0])
                        ->when($request->search_category, fn ($query, $search_category) => $query->where('category.categoryname','like','%'.$search_category.'%'))
                        ->orderBy('categoryname', 'asc')->get();
        $target_path = 'Category/';

        if(sizeof($Category) != 0)
        {
            foreach($Category as $val)
            {
                $CategoryList[] = array(
                    "category_id" => $val->categoryId,
                    "category_name" => $val->categoryname,
                );
            }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Category List',
                    'Category' => $CategoryList
                ]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No Data Found!',

                    'Category' => []
            ]);
        }
                
  }
  public function subcategory_list(Request $request)
  {
        $Category=Category::where(['category.iStatus'=>1,'category.isDelete'=>0,'subcategoryid'=>$request->category_id])->orderBy('categoryname', 'asc')->get();
        $target_path = 'Category/';

        if(sizeof($Category) != 0)
        {
            foreach($Category as $val)
            {
                $CategoryList[] = array(
                    "category_id" => $val->subcategoryid,
                    "subcategory_id" => $val->categoryId,
                    "subcategory_name" => $val->categoryname,
                    "subcategory_image" =>"https://sukti.in/". $target_path . $val->photo
                );
            }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Sub Category List',
                    'Category' => $CategoryList
                ]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No Data Found!',

                    'Category' => []
            ]);
        }
                
  }
        
}
