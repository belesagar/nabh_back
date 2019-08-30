<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndicatorsData extends Model
{
    protected $table = 'indicators_data';
    protected $primaryKey = 'id';

    protected $fillable = ["hospital_id", "indicators_id", "indicators_unique_id", "name_of_patient", "pid", "date", "name_of_surgery", "name_of_surgeon", "charges_of_surgeon", "charges_of_anaesthesiologist", "anaesthesia_id", "modification_of_plan_anaesthesia", "reason_for_modification_of_plan_anaesthesia", "adverse_anesthesia_reaction", "description_of_adverse_anesthesia_reaction", "ot_id", "in_time", "out_time", "original_scheduled_time", "cleaning_time", "rescheduling_of_surgeries", "reason_for_reschedule", "utilization_time", "re-exploration_procedure", "reason_for_re-exploration", "surgical_site_infection", "reason_for_surgical_site_infection", "sample_for_culture_and_sensitivity"];
}
