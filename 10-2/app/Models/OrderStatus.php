<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    public $table = 'order_status_master';
        protected $primaryKey = 'order_status_id';

    protected $fillable = [
        'order_status_id',
        'status',
    ];
}
