<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalRegistration extends Model
{
    protected $table = 'hospital_registration';
    protected $primaryKey = 'hospital_id';
    protected $fillable = ['hospital_name','hospital_unique_id','email','spoc_name','mobile','spoc_designation','city','state','pincode','number_of_bed','password'];

    
}
