<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndicatorsFormsFieldsValidations extends Model
{
    protected $table = 'indicators_forms_fields_validation';
    protected $primaryKey = 'form_validation_id';
    protected $guarded = [];
}
