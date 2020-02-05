<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalRole extends Model
{
    protected $table = 'hospital_role';
    protected $primaryKey = 'role_id';
    protected $guarded = [];
}
