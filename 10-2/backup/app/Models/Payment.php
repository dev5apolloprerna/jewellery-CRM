<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'order_payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = ['payment_id', 'order_id', 'total_amount', 'due_amount', 'paid_amount'];


     public function paymentDetail()
    {
        return $this->belongsTo(PaymentDetail::class, 'payment_id', 'payment_id');
    }
}


