<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AssignIndicators extends Model
{
    protected $table = 'assign_indicators';
    protected $primaryKey = 'assign_indicator_id';


    public function indicators()
    {
        return $this->belongsTo('App\Model\NabhIndicators','indicators_id','indicators_id');
    }

}
