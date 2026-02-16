<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    public $table = "orderdetail";
    protected $fillable = [
        'orderID',
        'customerid',
        'productId',
        'quantity',
        'weight',
        'rate',
        'amount',
        'isPayment',
    ];

    public function notPurchasedReason()
    {
        return $this->belongsTo(
            CloseReason::class,
            'not_purchased_reason',   // FK column in orderdetail / cust_order_detail table
            'close_reason_id'         // PK column in close_reason table
        );
    }


}
