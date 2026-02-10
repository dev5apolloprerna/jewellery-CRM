<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\ProductCategory;

class CloseReason extends Model
{
    protected $table = 'followup_close_reason';
    protected $primaryKey = 'close_reason_id';
    public $timestamps = false;

    protected $fillable = [ 'close_reason_id', 'close_reason'];

}


