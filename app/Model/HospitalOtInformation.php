<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalOtInformation extends Model
{
    protected $table = 'hospital_ot_information';
    protected $primaryKey = 'ot_id';
    protected $fillable = ['ot_name', 'status'];
}
