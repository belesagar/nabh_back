<?php

namespace App\Services\Hospital;

use App\Repositories\HospitalIndicatorsRepository;
use App\Repositories\NabhIndicatorsRepository;
use App\Repositories\VirtualHospitalAssetDataRepository;

class HospitalReportsService
{
    public function __construct(
        HospitalIndicatorsRepository $hospital_indicators_repository,
        NabhIndicatorsRepository $nabh_indicators_repository,
        VirtualHospitalAssetDataRepository $virtual_hospital_asset_data_repository
    ) {
        $this->hospital_indicators_repository = $hospital_indicators_repository;
        $this->nabh_indicators_repository = $nabh_indicators_repository;
        $this->virtual_hospital_asset_data_repository = $virtual_hospital_asset_data_repository;

    }

    public function createChartDataOfIndicator($param)
    {
        $indicator_details = $this->nabh_indicators_repository->findByField("indicators_id",$param['indicator_id']);

        $where = ["indicators_id" => $param['indicator_id'],"hospital_id" => $param['hospital_id']];
        $indicator_data = $this->hospital_indicators_repository->getDataByCustomeWhere($where,true);
        
        $collection = collect($indicator_data);

        $chart_data = [];
        $chart_data['label'] = $indicator_details->name;
        for($i = 5;$i>=0;$i--)
        {
            $date = date('Y-m-d', strtotime('-'.$i.' months'));
            $month = date("M",strtotime($date));
            $first_day  = date("Y-m-01", strtotime($date))." 00:00:00";
            $last_day  = date("Y-m-t", strtotime($date))." 00:59:59";

            $indicator_data = $collection->whereBetween('created_at', [$first_day,$last_day]);

            $chart_data['data'][] = $indicator_data->count();
            $chart_data['months'][] = $month;
        }

        $return = array("success" => true, "error_code" => 0, "info" => "", "data" => ["chart_data" => $chart_data]);

        return $return;
    }

}
