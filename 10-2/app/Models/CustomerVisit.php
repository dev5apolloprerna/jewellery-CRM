<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVisit extends Model
{
    use HasFactory;

    protected $table = 'cust_visit';
    protected $primaryKey = 'visit_id';
    public $timestamps = false;

    protected $fillable = [
        'visit_id', 'cust_id', 'remark', 'branch_id', 'emp_id', 'visit_date', 'next_followup_date', 'followup_status', 'close_reason_id'
    ];

    public function branch()
    {
        return $this->belongsTo(BranchMaster::class, 'branch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id');
    }
    public function visitDetails()
    {
        return $this->hasOne(VisitDetail::class, 'visit_id', 'visit_id');
    }

    public function closereason()
    {
        return $this->belongsTo(CloseReason::class, 'close_reason_id');
    } 
    public function products() 
    {
        return $this->hasMany(CustomerProduct::class, 'visit_id');
    }

}
