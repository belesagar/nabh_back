<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalDoctorsType extends Model
{
    protected $table = 'hospital_doctors_type';
    protected $primaryKey = 'doctors_type_id';
    protected $fillable = ['type_name', 'status'];
}
