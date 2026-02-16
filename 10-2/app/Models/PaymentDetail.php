<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    protected $table = 'order_payment_detail';
    protected $primaryKey = 'payment_detail_id';
    public $timestamps = false;

    protected $fillable = ['payment_detail_id', 'payment_id', 'order_id', 'order_detail_id', 'amount', 'due_amount', 'paid_amount', 'next_followup_date'];


}


