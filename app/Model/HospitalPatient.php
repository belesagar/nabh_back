<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalPatient extends Model
{
    protected $table = 'hospital_patient_table';
    protected $primaryKey = 'patient_id';
    protected $guarded = [];
}
