<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\ProductCategory;
use App\Models\CustomerVisit;

class CustomerProduct extends Model
{
    protected $table = 'cust_product';
    protected $primaryKey = 'cust_pro_id';
    public $timestamps = false;

    protected $fillable = ['cust_pro_id','cust_id', 'category_id', 'product_id','visit_id', 'quantity', 'visit_date', 'emp_id','branch_id', 'status'];

    
    public function Category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
     public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
     public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'customer_id');
    }
     public function branch()
    {
        return $this->belongsTo(BranchMaster::class, 'branch_id');
    }
     public function customervisit()
    {
        return $this->belongsTo(CustomerVisit::class, 'visit_id', 'visit_id');
    }
    public function orderDetails()
    {
        return $this->hasOne(CustOrderDetail::class, 'cust_pro_id', 'cust_pro_id');
    }
    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class, 'order_status_id', 'status');
    }
}


