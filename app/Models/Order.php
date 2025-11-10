<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $table = "order";
    protected $fillable = [
        'cutomerName',
        'mobile',
        'email',
        'address',
        'state',
        'city',
        'pincode',
        'shipping_cutomerName',
        'shipping_companyName',
        'shipping_mobile',
        'shipping_email',
        'shiiping_address1',
        'shiiping_address2',
        'shiiping_state',
        'shipping_city',
        'shipping_pincode',
        'amount',
        'discount',
        'shipping_Charges',
        'netAmount',
        'isPayment',
        'orderNote',
        'courier',
        'docketNo',
    ];
}
