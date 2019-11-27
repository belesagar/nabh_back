<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\VirtualHospital;
use App\Model\VirtualHospitalAssetData;
use App\Model\VirtualHospitalData;
use App\Services\Hospital\VirtualHospitalService;
use App\Services\Hospital\HospitalReportsService;
use App\Repositories\VirtualHospitalRepository;
use App\Repositories\VirtualHospitalAssetDataRepository;

class HospitalReportsController extends Controller
{
	public function __construct(
        VirtualHospitalService $virtual_hospital_service,
        HospitalReportsService $hospital_reports_service,
        VirtualHospitalRepository $virtual_hospital_repository,
        VirtualHospitalAssetDataRepository $virtual_hospital_asset_data_repository
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
        $this->virtual_hospital_repository = $virtual_hospital_repository;
        $this->virtual_hospital_asset_data_repository = $virtual_hospital_asset_data_repository;
    }

    public function createChartDataOfIndicator(Request $request)
    {
        $request_data = $request->all();
        $request_data["hospital_id"] = $this->hospital_id;
        $request_data["hospital_user_id"] = $this->hospital_user_id;
        
        $return = $this->hospital_reports_service->createChartDataOfIndicator($request_data);

        return $this->response($return);
    }

}
