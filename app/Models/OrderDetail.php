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
}
