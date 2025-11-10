<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\BranchMaster;


class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    
    protected $table = 'employee_master';

    protected $primaryKey = 'emp_id';

    public $timestamps = true;

    protected $fillable = [
        'branch_id',
        'emp_name',
        'emp_phone',
        'emp_email',
        'emp_phone2',
        'emp_dob',
        'accesOutside',
        'role_id',
        'password',
        'iStatus',
        'isDelete',
    ];

    public function branch()
    {
        return $this->belongsTo(BranchMaster::class, 'branch_id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getAuthPassword()
    {
        return $this->password;
    }
    public function client_attended()
    {
        return $this->hasMany(CustomerProduct::class, 'emp_id');
    }
    public function client_converted()
    {
        return $this->hasMany(CustomerProduct::class, 'emp_id')->where('status','ordered');
    }
}

?>