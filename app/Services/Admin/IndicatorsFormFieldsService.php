<?php

namespace App\Services\Admin;

use App\Repositories\IndicatorsFormFieldsRepository;

class IndicatorsFormFieldsService
{
    public function __construct(
        IndicatorsFormFieldsRepository $indicator_form_fields_repository
    ) {
        $this->indicator_form_fields_repository = $indicator_form_fields_repository;
        
    }

    public function getIndicatorColumns($indicator_id = 0)
    {
        if ($indicator_id > 0) {
            $columns = [];

            $where_clouse = [
                ['indicators_ids', 'like', '%"' . $indicator_id . '"%'],
                ['status', 'ACTIVE']
            ];
            
            $indicator_data = $this->indicator_form_fields_repository->getDataByCustomeWhere($where_clouse);
           
            foreach ($indicator_data as $key => $value) {
                $columns[] = $value['input_name'] . " as " . $value['label'];
            }

            $return = array("success" => true, "error_code" => 0, "info" => "", "data" => ["column_data" => $columns]);
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Invalid Indicator");
        }

        return $return;
    }

    
    
}
