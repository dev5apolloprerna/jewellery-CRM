<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $table = 'product_master';
        protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_id',
        'product_name',
        'product_photo',
        'product_tag',
    ];
    public function view_product()
    {
            return $this->hasMany(CustomerProduct::class, 'product_id');
    }
    public function sold_product()
    {
         return $this->hasMany(CustOrderDetail::class, 'product_id')->whereIn('delivery_status', ['1','2']);
    }
}
