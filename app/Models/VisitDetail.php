<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\BranchMaster;

class VisitDetail extends Model
{
    protected $table = 'cust_visit_details';
    protected $primaryKey = 'visit_detail_id';
    public $timestamps = false;

    protected $fillable = [
        'visit_id', 'cust_id', 'emp_id','visit_date','next_followup_date', 'remark','branch_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
    public function visit()
    {
        return $this->belongsTo(CustomerVisit::class, 'visit_id', 'visit_id');
    }
     public function custVisit()
    {
        return $this->hasOne(CustomerVisit::class, 'visit_id', 'visit_id');
    }
    
}
