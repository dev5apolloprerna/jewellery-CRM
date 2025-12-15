<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\BranchMaster;

class Customer extends Model
{
    protected $table = 'customer_master';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_name', 'customer_phone', 'customer_type', 'refer_by', 'status', 'branch_id', 'customer_email', 'address', 'city', 'state_id','cast_id','birthdate', 'anniversary_date', 'beverage', 'suger'
    ];

    public function branch()
    {
        return $this->belongsTo(BranchMaster::class, 'branch_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id','stateId');
    }
    public function custCat()
    {
        return $this->belongsTo(CustomerCategory::class, 'customer_type','cust_cat_id');
    }
    public function latestVisit()
    {
        return $this->hasOne(CustomerVisit::class, 'cust_id', 'customer_id')
                    ->latest('visit_id'); 
    }
    public function cast()
    {
        return $this->belongsTo(CastMaster::class, 'cast_id');
    }


}
