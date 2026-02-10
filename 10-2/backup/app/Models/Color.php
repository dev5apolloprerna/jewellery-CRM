<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    public $table = 'color_master';
        protected $primaryKey = 'color_id';

    protected $fillable = [
        'color_id',
        'color_name',
    ];
}
