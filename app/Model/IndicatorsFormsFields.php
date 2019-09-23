<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class IndicatorsFormsFields extends Model
{
    protected $table = 'indicators_forms_fields';
    protected $primaryKey = 'form_id';
    protected $guarded = [];

    public function getValidations()
    {
        return $this->hasMany('App\Model\IndicatorsFormsFieldsValidations', 'form_id',
            'form_id');//->select(array('form_id','validations'))
    }

}
