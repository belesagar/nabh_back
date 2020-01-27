<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\VirtualHospital;
use App\Model\VirtualHospitalAssetData;
use App\Model\VirtualHospitalData;
use App\Services\Hospital\VirtualHospitalService;
use App\Services\Hospital\HospitalReportsService;
use App\Services\Hospital\IndicatorExcelFormatService;
use App\Repositories\VirtualHospitalRepository;
use App\Repositories\VirtualHospitalAssetDataRepository;
use App\Repositories\IndicatorExcelFormatRepository;

class HospitalReportsController extends Controller
{
	public function __construct(
        VirtualHospitalService $virtual_hospital_service,
        HospitalReportsService $hospital_reports_service,
        IndicatorExcelFormatService $indicator_excel_format_service,
        VirtualHospitalRepository $virtual_hospital_repository,
        VirtualHospitalAssetDataRepository $virtual_hospital_asset_data_repository,
        IndicatorExcelFormatRepository $indicator_excel_format_repository
    )
    {
        $this->virtual_hospital = new VirtualHospital();
        $this->virtual_hospital_asset_data = new VirtualHospitalAssetData();
        $this->virtual_hospital_data = new VirtualHospitalData();
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
        $this->virtual_hospital_service = $virtual_hospital_service;
        $this->hospital_reports_service = $hospital_reports_service;
        $this->indicator_excel_format_service = $indicator_excel_format_service;
        $this->virtual_hospital_repository = $virtual_hospital_repository;
        $this->virtual_hospital_asset_data_repository = $virtual_hospital_asset_data_repository;
        $this->indicator_excel_format_repository = $indicator_excel_format_repository;
    }

    public function createChartDataOfIndicator(Request $request)
    {
        $request_data = $request->all();
        $request_data["hospital_id"] = $this->hospital_id;
        $request_data["hospital_user_id"] = $this->hospital_user_id;
        
        $return = $this->hospital_reports_service->createChartDataOfIndicator($request_data);
        
        return $this->response($return);
    }
    
    public function getIndicatorExcelList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'indicator_id' => 'required|numeric'
        ]);

        $return = [];

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $request_data = $request->all();
            $request_data["hospital_id"] = $this->hospital_id;
            $request_data["hospital_user_id"] = $this->hospital_user_id;
            
            $return = $this->indicator_excel_format_service->getIndicatorExcelFormatList($request_data);
        }
        return $this->response($return);
    }

    public function getIndicatorExcelReportData(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'indicator_id' => 'required|numeric',
            'excel_format_id' => 'required|numeric',
        ]);

        $return = [];

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $request_data = $request->all();
            $request_data["hospital_id"] = $this->hospital_id;
            $request_data["hospital_user_id"] = $this->hospital_user_id;
            
            $return = $this->indicator_excel_format_service->getIndicatorExcelFormatData($request_data);
        }
        return $this->response($return);
    }
    
}
