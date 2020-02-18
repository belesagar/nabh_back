<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class VirtualHospitalData extends Model
{
    protected $table = 'virtual_hospital_data';
    protected $primaryKey = 'virtual_hospital_data_id';
    protected $guarded = [];


    public function virtual_hospital_asset_data()
    {
        return $this->hasMany('App\Model\VirtualHospitalAssetData', 'virtual_hospital_data_id', 'virtual_hospital_data_id');
    }

}
