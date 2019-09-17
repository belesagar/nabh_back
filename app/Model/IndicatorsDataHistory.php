<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndicatorsDataHistory extends Model
{
    protected $table = 'indicators_data_history';
    protected $primaryKey = 'indicators_history_id';

    protected $guarded = [];

}
