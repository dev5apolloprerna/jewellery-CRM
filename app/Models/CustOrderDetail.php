<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustOrderDetail extends Model
{
    protected $table = 'cust_order_detail';
    protected $primaryKey = 'detail_order_id';
    protected $fillable = [
        'order_id', 'cust_id', 'branch_id', 'emp_id', 'product_id', 'cust_pro_id', 'karat', 'color_id', 'weight', 'size', 'refer_tag_number', 'refer_image_url', 'refer_photo', 'status', 'amount', 'net_total', 'given_to', 'rate_type', 'rate_fix_open', 'delivery_status', 'delivery_date', 'remark'
    ];

    public function order()
    {
        return $this->belongsTo(CustOrder::class, 'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function branch()
    {
        return $this->belongsTo(BranchMaster::class, 'branch_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'given_to', 'vendor_id');
    }
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
    public function purity()
    {
        return $this->belongsTo(Purity::class, 'purity_id','karat');
    }
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'delivery_status', 'order_status_id');
    }
     public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    } 


}
