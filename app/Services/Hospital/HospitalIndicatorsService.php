<?php

namespace App\Services\Hospital;

use App\Repositories\HospitalIndicatorsRepository;
use App\Services\CommonService;

class HospitalIndicatorsService
{
    public function __construct(
        HospitalIndicatorsRepository $hospital_indicator_repository,
        CommonService $common_service
    ) {
        $this->hospital_indicator_repository = $hospital_indicator_repository;
        $this->common_service = $common_service;

    }

    public function getIndicatorsDetail($indicator_id = "")
    {
        $where = ["status" => "ACTIVE"];
        if ($indicator_id != "") {
            $where['indicators_id'] = $indicator_id;
        }

        $indicators_details = $this->hospital_indicator_repository->getDataByCustomeWhere($where);

        return $indicators_details;
    }


}
