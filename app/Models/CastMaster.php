<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CastMaster extends Model
{
    use HasFactory;
    public $table = 'customer_cast';
    protected $primaryKey = 'cast_id';
    protected $fillable = [
        'cast_id',
        'cast',
    ];
}
