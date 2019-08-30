<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NabhIndicators extends Model
{
    protected $table = 'nabh_indicators';
    protected $primaryKey = 'indicators_id';
    
    protected $fillable = ["name", "short_name", "indicators_price", "group_id", "formula", "remark", "status"];


}
