<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndicatorsDataHistory extends Model
{
    protected $table = 'indicators_data_history';
    protected $primaryKey = 'indicators_history_id';

    protected $fillable = ["hospital_id", "indicator_id", "indicator_data_id", "updated_by_id", "updated_data"];

}
