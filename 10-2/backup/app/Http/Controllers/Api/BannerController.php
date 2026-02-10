<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\Category;



class BannerController extends Controller
{
    
 public function index(Request $request)
  {
        $Category=Category::where(['category.iStatus'=>1,'category.isDelete'=>0,'subcategoryid'=>0])->get();
        $target_path = 'Category/';

        if(sizeof($Category) != 0)
        {
            foreach($Category as $val)
            {
                $Images[] = array(
                    "category_name" => $val->categoryname,
                    "category_id" => $val->categoryId,
                    "image" => "https://sukti.in/". $target_path . $val->photo
                );
            }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Images',
                    'Images' => $Images
                ]);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No Data Found!',
                'Images' => []
            ]);
        }
                
  }
  
}
