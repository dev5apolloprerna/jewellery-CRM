<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCategory extends Model
{
    use HasFactory;
    public $table = 'customer_category';
    protected $primaryKey = 'cust_cat_id';
    protected $fillable = [
        'cust_cat_id',
        'cust_cat_name',
    ];
}
