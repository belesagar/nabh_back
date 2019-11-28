<?php

namespace App\Services\Hospital;

use App\Repositories\HospitalIndicatorsRepository;
use App\Repositories\NabhIndicatorsRepository;
use App\Repositories\VirtualHospitalAssetDataRepository;
use App\Repositories\HospitalRegistrationRepository;

class HospitalReportsService
{
    public function __construct(
        HospitalIndicatorsRepository $hospital_indicators_repository,
        NabhIndicatorsRepository $nabh_indicators_repository,
        VirtualHospitalAssetDataRepository $virtual_hospital_asset_data_repository,
        HospitalRegistrationRepository $hospital_registration_repository

    ) {
        $this->hospital_indicators_repository = $hospital_indicators_repository;
        $this->nabh_indicators_repository = $nabh_indicators_repository;
        $this->virtual_hospital_asset_data_repository = $virtual_hospital_asset_data_repository;
        $this->hospital_registration_repository = $hospital_registration_repository;

        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];

    }

    public function createChartDataOfIndicator($param)
    {
        $indicator_details = $this->nabh_indicators_repository->findByField("indicators_id", $param['indicator_id']);

        $where = ["indicators_id" => $param['indicator_id'], "hospital_id" => $param['hospital_id']];
        $indicator_data = $this->hospital_indicators_repository->getDataByCustomeWhere($where, true);

        $collection = collect($indicator_data);

        $chart_data = [];
        $chart_data['label'] = $indicator_details->name;
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime('-' . $i . ' months'));
            $month = date("M", strtotime($date));
            $first_day = date("Y-m-01", strtotime($date)) . " 00:00:00";
            $last_day = date("Y-m-t", strtotime($date)) . " 00:59:59";

            $indicator_data = $collection->whereBetween('created_at', [$first_day, $last_day]);

            $chart_data['data'][] = $indicator_data->count();
            $chart_data['months'][] = $month;
        }

        $return = array("success" => true, "error_code" => 0, "info" => "", "data" => ["chart_data" => $chart_data]);

        return $return;
    }

    public function createSsiPercentage($data)
    {
        $pdf_data = [];
        $ssi_data = [];

        $hospital_data = $this->getHospitalData($this->hospital_id);
        $total_patient = 0;
        $total_ssi_patient = 0;

        $collection = collect($data);
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime('-' . $i . ' months'));
            $month = date("M", strtotime($date));
            $first_day = date("Y-m-01", strtotime($date)) . " 00:00:00";
            $last_day = date("Y-m-t", strtotime($date)) . " 00:59:59";

            $indicator_data = $collection->whereBetween('created_at', [$first_day, $last_day]);

            $filter_data = $indicator_data;

            $patient_data_count = $filter_data->where("name_of_patient", "!=", "");
            $ssi_patient_data_count = $filter_data->where("surgical_site_infection", "Yes");

            $ssi_data[] = [
                "MONTHS" => $month,
                "COUNT OF NAME OF PATIENT" => count($patient_data_count),
                "SUM OF NO OF PATIENTS WITH SSI" => count($ssi_patient_data_count),
                "% OF SSI" => 0,

            ];

            $total_patient = $total_patient + count($patient_data_count);
            $total_ssi_patient = $total_ssi_patient + count($ssi_patient_data_count);

            $chart_data['data'][] = $indicator_data->count();
            $chart_data['months'][] = $month;
        }

        $total_array = [
            "MONTHS" => "Grand Total",
            "COUNT OF NAME OF PATIENT" => $total_patient,
            "SUM OF NO OF PATIENTS WITH SSI" => $total_ssi_patient,
            "% OF SSI" => 0,
        ];

        $heading_array = array_keys($ssi_data[0]);
        $pdf_data["data_array"] = $ssi_data;
        $pdf_data["heading_array"] = $heading_array;
        $pdf_data["total_array"] = $total_array;

        $data = [
            'title' => 'OT UTILIZATION FORM : SSI PERCENTAGE',
            'indicator_title' => "OT UTILIZATION FORM : SSI PERCENTAGE",
            'heading' => $hospital_data['hospital_name'],
            'pdf_data' => $pdf_data
        ];


        return $data;
    }

    private function getHospitalData($hospital_id)
    {
        $hospital_data = $this->hospital_registration_repository->findByField("hospital_id", $hospital_id);
        return $hospital_data;
    }

}
