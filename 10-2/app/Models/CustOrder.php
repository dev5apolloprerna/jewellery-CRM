<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustOrder extends Model
{
    protected $table = 'cust_order';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'cust_id', 'branch_id', 'visit_id', 'amount', 'net_total', 'paid_amount', 'remark', 'rate_type', 'delivery_type'
    ];

    public function orderDetails()
    {
        return $this->hasMany(CustOrderDetail::class, 'order_id', 'order_id');
    }
    public function branch()
    {
        return $this->belongsTo(BranchMaster::class, 'branch_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id','cust_id');
    }
    public function customerVisit()
    {
        return $this->belongsTo(CustomerVisit::class, 'visit_id','visit_id');
    }
    public function payment_detail()
    {
        return $this->belongsTo(PaymentDetail::class, 'order_id','order_id');
    }

}

?>