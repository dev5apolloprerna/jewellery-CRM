<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor_master';
    protected $primaryKey = 'vendor_id';
    public $timestamps = false;

    protected $fillable = ['contact_person', 'company_name', 'email', 'phone', 'phone2'];

}


