<?php

namespace App\Services\Admin;

use App\Repositories\NabhIndicatorsRepository;

class NabhIndicatorsService
{
    public function __construct(
        NabhIndicatorsRepository $nabh_indicator_repository
    ) {
        $this->nabh_indicator_repository = $nabh_indicator_repository;
        
    }

    public function getIndicatorsDetail($indicator_id = "")
    {
        $where = ["status" => "ACTIVE"];
        if($indicator_id != "")
        {
            $where['indicators_id'] = $indicator_id;
        }
        
        $indicators_details = $this->nabh_indicator_repository->getDataByCustomeWhere($where);
        
        return $indicators_details;
    }

    
    
}
