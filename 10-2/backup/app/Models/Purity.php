<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purity extends Model
{
    use HasFactory;
    public $table = 'purity_master';
        protected $primaryKey = 'purity_id';

    protected $fillable = [
        'purity_id',
        'purity_value',
    ];
}
