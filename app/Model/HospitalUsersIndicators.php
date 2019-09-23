<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalUsersIndicators extends Model
{
    protected $table = 'hospital_users_indicators';
    protected $primaryKey = 'hospital_users_indicators_id';
    protected $guarded = [];

    public function indicators()
    {
        return $this->belongsTo('App\Model\NabhIndicators', 'indicator_id', 'indicators_id');
    }

}
