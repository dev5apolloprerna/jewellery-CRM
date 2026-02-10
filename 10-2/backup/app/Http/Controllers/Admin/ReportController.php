<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\CustOrder;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\CustomerProduct;
use App\Models\Product;
use App\Models\CloseReason;
use App\Models\CustomerVisit;


class ReportController extends Controller
{
    public function index(Request $request)
    {

        return view('admin.reports.index');
    }
    public function stock_analysis(Request $request)
    {
        $products = Product::withCount(['view_product', 'sold_product'])->when($request->search, fn ($query, $search) => $query->where('product_name', 'like', '%' . $search . '%'))->orderBy('product_name','asc')->get();

        $totalViews = $products->sum('view_product_count');

        foreach ($products as $product) {
            $viewed = $product->view_product_count;
            $sold = $product->sold_product_count;

            // Conversion Ratio: =IF(Viewed = 0, 0, Sold / Viewed)
            $conversionRatio = ($viewed == 0) ? 0 : ($sold / $viewed);

            // Demand: = Viewed / Total Views
            $demand = ($totalViews == 0) ? 0 : ($viewed / $totalViews);

            // Product Score: =IF(Viewed = 0, 0, (Conversion Ratio * 3) + (Demand * 7))
            $productScore = ($viewed == 0) ? 0 : (($conversionRatio * 3) + ($demand * 7));

            // Attach these to the product object
            $product->conversion_ratio = $conversionRatio * 100; // percentage
            $product->demand = $demand * 100;                    // percentage
            $product->product_score = $productScore;
        }
        $search=$request->search;
        return view('admin.reports.stock_analysis', compact('products','search'));
    }

     public function export_stock_analysis($search="")
    {
            $query = Product::withCount(['view_product', 'sold_product']);

            // Filter by product name if search term provided
            if ($search) {
                $query->where('product_name', 'like', '%' . $search . '%');
            }

            $products = $query->orderBy('product_name','asc')->get();

        $totalViews = $products->sum('view_product_count');

        foreach ($products as $product) 
        {
            $viewed = $product->view_product_count;
            $sold = $product->sold_product_count;

            // Conversion Ratio: =IF(Viewed = 0, 0, Sold / Viewed)
            $conversionRatio = ($viewed == 0) ? 0 : ($sold / $viewed);

            // Demand: = Viewed / Total Views
            $demand = ($totalViews == 0) ? 0 : ($viewed / $totalViews);

            // Product Score: =IF(Viewed = 0, 0, (Conversion Ratio * 3) + (Demand * 7))
            $productScore = ($viewed == 0) ? 0 : (($conversionRatio * 3) + ($demand * 7));

            // Attach these to the product object
            $product->conversion_ratio = $conversionRatio * 100; // percentage
            $product->demand = $demand * 100;                    // percentage
            $product->product_score = $productScore;
        }
        return view('admin.reports.export_stock_analysis', compact('products'));
    }
   public function staff_analysis(Request $request)
    {
        $employees = Employee::withCount(['client_attended', 'client_converted'])
            ->when($request->search, fn ($query, $search) =>
                $query->where('emp_name', 'like', '%' . $search . '%')
            )->orderBy('emp_name','asc')->get();

        $maxConversionRatio = 0;
        $maxClientAttended = $employees->max(function ($emp) {
            return $emp->client_attended_count;
        });

        // Precalculate conversion ratios to find max
        foreach ($employees as $employee) {
            $attended = $employee->client_attended_count;
            $converted = $employee->client_converted_count;
            $conversionRatio = ($attended == 0) ? 0 : ($converted / $attended);
            $employee->conversion_ratio = $conversionRatio * 100;
            $maxConversionRatio = max($maxConversionRatio, $conversionRatio);
        }

        // Final loop to calculate performance score
        foreach ($employees as $employee) {
            $attended = $employee->client_attended_count;
            $conversionRatio = $employee->conversion_ratio / 100;

            $normConversion = ($maxConversionRatio > 0) ? ($conversionRatio / $maxConversionRatio) : 0;
            $normAttendance = ($maxClientAttended > 0) ? ($attended / $maxClientAttended) : 0;

            $employee->performance_score = round(($normConversion * 5) + ($normAttendance * 5), 2);
        }

        return view('admin.reports.staff_analysis', compact('employees'));
    }

    public function export_staff_analysis($search="")
    {
        $employees = Employee::withCount(['client_attended', 'client_converted'])
            ->when($search, fn ($query, $search1) =>
                $query->where('emp_name', 'like', '%' . $search1 . '%')
            )->orderBy('emp_name','asc')->get();

        $maxConversionRatio = 0;
        $maxClientAttended = $employees->max(function ($emp) {
            return $emp->client_attended_count;
        });

        // Precalculate conversion ratios to find max
        foreach ($employees as $employee) {
            $attended = $employee->client_attended_count;
            $converted = $employee->client_converted_count;
            $conversionRatio = ($attended == 0) ? 0 : ($converted / $attended);
            $employee->conversion_ratio = $conversionRatio * 100;
            $maxConversionRatio = max($maxConversionRatio, $conversionRatio);
        }

        // Final loop to calculate performance score
        foreach ($employees as $employee) {
            $attended = $employee->client_attended_count;
            $conversionRatio = $employee->conversion_ratio / 100;

            $normConversion = ($maxConversionRatio > 0) ? ($conversionRatio / $maxConversionRatio) : 0;
            $normAttendance = ($maxClientAttended > 0) ? ($attended / $maxClientAttended) : 0;

            $employee->performance_score = round(($normConversion * 5) + ($normAttendance * 5), 2);
        }


        return view('admin.reports.export_staff_analysis', compact('employees'));
    }
    public function cancel_reason_report(Request $request)
    {

            $cancelledReasons = CloseReason::select('followup_close_reason.close_reason')
            ->leftJoin('cust_visit', 'followup_close_reason.close_reason_id', '=', 'cust_visit.close_reason_id')
            ->leftJoin('cust_visit_details', 'cust_visit.visit_id', '=', 'cust_visit_details.visit_id')
            ->select('followup_close_reason.close_reason', DB::raw('COUNT(cust_visit.close_reason_id) as cancel_count'))
            ->groupBy('followup_close_reason.close_reason')
            ->orderBy('followup_close_reason.close_reason','asc')
            ->get();


        return view('admin.reports.cancel_reason_report',compact('cancelledReasons'));
    }
    public function export_cancel_reason_report(Request $request)
    {


            $cancelledReasons = CloseReason::select('followup_close_reason.close_reason')
            ->leftJoin('cust_visit', 'followup_close_reason.close_reason_id', '=', 'cust_visit.close_reason_id')
            ->leftJoin('cust_visit_details', 'cust_visit.visit_id', '=', 'cust_visit_details.visit_id')
            ->select('followup_close_reason.close_reason', DB::raw('COUNT(cust_visit.close_reason_id) as cancel_count'))
            ->groupBy('followup_close_reason.close_reason')
            ->orderBy('followup_close_reason.close_reason','asc')
            ->get();

        return view('admin.reports.export_cancel_reason_report',compact('cancelledReasons'));
    }
    public function showMonthlyConversionReport(Request $request)
    {
            $year = $request->input('year');
            $month = $request->input('month');

            $query = DB::table('cust_visit')
                ->leftJoin('cust_product', 'cust_visit.visit_id', '=', 'cust_product.visit_id')
                ->selectRaw("DATE_FORMAT(cust_visit.visit_date, '%Y-%m') as month")
                ->selectRaw("COUNT(DISTINCT cust_visit.visit_id) as total_clients_visited")
                ->selectRaw("SUM(CASE WHEN cust_product.status = 'view' THEN 1 ELSE 0 END) as total_clients_viewed")
                ->selectRaw("SUM(CASE WHEN cust_product.status IN ('ordered', 'delivered') THEN 1 ELSE 0 END) as total_clients_sold")
                ->groupBy(DB::raw("DATE_FORMAT(cust_visit.visit_date, '%Y-%m')"))
                ->orderBy('month');

            if ($year) {
                $query->whereYear('cust_visit.visit_date', $year);
            }

            if ($month) {
                $query->whereMonth('cust_visit.visit_date', $month);
            }

            $data = $query->get()->map(function ($item) {
                $visited = $item->total_clients_visited;
                $sold = $item->total_clients_sold;
                $item->conversion_ratio = $visited > 0 ? round(($sold / $visited) * 100, 2) . '%' : '0.00%';
                return $item;
            });

        return view('admin.reports.monthly_conversion', compact('data', 'year', 'month'));
    }
    public function export_monthly_conversion($month="",$year="")
    {

            $query = DB::table('cust_visit')
                ->leftJoin('cust_product', 'cust_visit.visit_id', '=', 'cust_product.visit_id')
                ->selectRaw("DATE_FORMAT(cust_visit.visit_date, '%Y-%m') as month")
                ->selectRaw("COUNT(DISTINCT cust_visit.visit_id) as total_clients_visited")
                ->selectRaw("SUM(CASE WHEN cust_product.status = 'view' THEN 1 ELSE 0 END) as total_clients_viewed")
                ->selectRaw("SUM(CASE WHEN cust_product.status IN ('ordered', 'delivered') THEN 1 ELSE 0 END) as total_clients_sold")
                ->groupBy(DB::raw("DATE_FORMAT(cust_visit.visit_date, '%Y-%m')"))
                ->orderBy('month');

            if ($year) {
                $query->whereYear('cust_visit.visit_date', $year);
            }

            if ($month) {
                $query->whereMonth('cust_visit.visit_date', $month);
            }

            $data = $query->get()->map(function ($item) {
                $visited = $item->total_clients_visited;
                $sold = $item->total_clients_sold;
                $item->conversion_ratio = $visited > 0 ? round(($sold / $visited) * 100, 2) . '%' : '0.00%';
                return $item;
            });

        return view('admin.reports.export_monthly_conversion', compact('data', 'year', 'month'));
    }

    public function customer_visit_report(Request $request)
    {
        // Assuming each visit has a detail and products
        $query = CustomerVisit::with([
            'customer',
            'employee',
            'branch',
            'visitDetails', // cust_visit_details
            'products',
            'customer.custCat'// cust_product
        ]);

        if ($request->filled('from_date')) {
            $query->whereDate('visit_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('visit_date', '<=', $request->to_date);
        }

        if ($request->filled('emp_id')) {
            $query->where('emp_id', $request->emp_id);
        }
        $visits = $query->latest()->paginate(env('PER_PAGE_COUNT'));
        $employees=Employee::where(['role_id'=>2])->get();

        return view('admin.reports.visit_report', compact('visits','employees'));
    }
     public function export_customer_visit_report($fromDate="",$toDate="",$empId="")
    {

        // Assuming each visit has a detail and products
        $query = CustomerVisit::with([
            'customer',
            'employee',
            'branch',
            'visitDetails', // cust_visit_details
            'products.product',
            'customer.custCat'// cust_product
        ]);

        if ($fromDate) {
            $query->whereDate('visit_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('visit_date', '<=', $toDate);
        }

        if ($empId) {
            $query->where('emp_id', $empId);
        }
        $visits = $query->get();
        $employees=Employee::where(['role_id'=>2])->get();

        return view('admin.reports.export_visit_report', compact('visits','employees'));
    }


    public function order_report(Request $request)
    {

       $query = CustOrder::with([
                'orderDetails' => function ($q) use ($request) {
                    if ($request->filled('emp_id')) {
                        $q->where('emp_id', $request->emp_id);
                    }
                    $q->with(['product', 'color', 'vendor', 'employee']);
                },
            'customer',
            'customer.custCat'
        ]);
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        // Only fetch orders that actually have matching order details
        if ($request->filled('emp_id')) {
            $query->whereHas('orderDetails', function ($q) use ($request) {
                $q->where('emp_id', $request->emp_id);
            });
        }
        
        $orders = $query->paginate(env('PER_PAGE_COUNT'));

        $employees=Employee::where(['role_id'=>2])->get();

        return view('admin.reports.order_report',compact('orders','employees'));
    }

    public function export_order_report($fromDate="",$toDate="",$empId="")
    {

        $query = CustOrder::with([
                'orderDetails' => function ($q) use ($empId) {
                    if ($empId) {
                        $q->where('emp_id', $empId);
                    }
                    $q->with(['product', 'color', 'vendor', 'employee']);
                },
            'customer',
            'customer.custCat'
        ]);
        
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }
        
        // Only fetch orders that actually have matching order details
        if ($empId) {
            $query->whereHas('orderDetails', function ($q) use ($empId) {
                $q->where('emp_id', $empId);
            });
        }

        // Get paginated orders
        $orders = $query->get();


        return view('admin.reports.export_order_report',compact('orders'));
    }

    public function salesstaff_collection_report(Request $request)
    {
        $query = CustOrder::with([
            'orderDetails' => function ($q) use ($request) {
                // Apply filter within the relation if needed
                if ($request->filled('emp_id')) {
                    $q->where('emp_id', $request->emp_id);
                }
        
                // Eager load related models
                $q->with(['vendor', 'employee']);
            }
        ]);
        
        // Apply global filter on related orderDetails if emp_id is provided
        if ($request->filled('emp_id')) {
            $query->whereHas('orderDetails', function ($q) use ($request) {
                $q->where('emp_id', $request->emp_id);
            });
        }

        // Get paginated orders
        $orders = $query->paginate(env('PER_PAGE_COUNT'));

        $employees=Employee::where(['role_id'=>2])->get();

        return view('admin.reports.collection_report', compact('orders','employees'));
    
    }
    public function export_salesstaff_collection_report($empId="")
    {

       $query = CustOrder::with([
            'orderDetails' => function ($q) use ($empId) {
                // Apply filter within the relation if needed
                if ($empId) {
                    $q->where('emp_id', $empId);
                }
        
                // Eager load related models
                $q->with(['vendor', 'employee']);
            }
        ]);
        
        // Apply global filter on related orderDetails if emp_id is provided
        if ($empId) {
            $query->whereHas('orderDetails', function ($q) use ($empId) {
                $q->where('emp_id', $empId);
            });
        }

        // Get paginated orders
        $orders = $query->get();
        $emp=Employee::select('emp_name')->where(['emp_id'=>$empId])->first();

        return view('admin.reports.export_collection_report', compact('orders','emp'));
    
    }

}