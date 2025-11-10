<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchMaster extends Model
{
    use HasFactory;
    public $table = 'branch_master';
    protected $primaryKey = 'branch_id';

    protected $fillable = [
        'branch_id', 'branch_name', 'branch_ip', 'branch_address', 'branch_emailId', 'branch_phone', 'iStatus', 'isDelete', 'created_at', 'updated_at'
    ];
}
