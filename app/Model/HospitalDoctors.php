<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalDoctors extends Model
{
    protected $table = 'hospital_doctors';
    protected $primaryKey = 'doctor_id';
    protected $guarded = [];
}
