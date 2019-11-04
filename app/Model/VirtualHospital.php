<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VirtualHospital extends Model
{
    protected $table = 'virtual_hospital';
    protected $primaryKey = 'virtual_hospital_id';
    protected $guarded = [];
}
