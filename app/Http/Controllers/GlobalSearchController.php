<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalSearchController extends Controller
{
    public function suggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        if ($q === '' || mb_strlen($q) < 2) {
            return response()->json([
                'customers' => [],
                'products'  => [],
            ]);
        }

        // Customers: name / email / phone
        $customers = DB::table('customer_master')
            ->select('customer_id', 'customer_name', 'customer_email', 'customer_phone')
            ->where('isDelete', 0)
            ->where('iStatus', 1)
            ->where(function ($w) use ($q) {
                $w->where('customer_name', 'like', "%{$q}%")
                  ->orWhere('customer_email', 'like', "%{$q}%")
                  ->orWhere('customer_phone', 'like', "%{$q}%");
            })
            ->limit(8)
            ->get();

        // Products: name / tag
        $products = DB::table('product_master')
            ->select('product_id', 'product_name', 'product_tag')
            ->where('isDelete', 0)
            ->where('iStatus', 1)
            ->where(function ($w) use ($q) {
                $w->where('product_name', 'like', "%{$q}%")
                  ->orWhere('product_tag', 'like', "%{$q}%");
            })
            ->limit(8)
            ->get();

        return response()->json([
            'customers' => $customers,
            'products'  => $products,
        ]);
    }
}
