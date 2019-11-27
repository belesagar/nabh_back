<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VirtualHospital extends Model
{
    protected $table = 'virtual_hospital';
    protected $primaryKey = 'virtual_hospital_id';
    protected $guarded = [];

    public function virtual_hospital_data()
    {
        return $this->belongsTo('App\Model\VirtualHospitalData', 'virtual_hospital_data_id', 'virtual_hospital_id');
    }

}
