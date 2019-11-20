<?php

namespace App\Services\Hospital;

use App\Repositories\VirtualHospitalRepository;
use App\Repositories\VirtualHospitalDataRepository;
use App\Repositories\VirtualHospitalAssetDataRepository;

class VirtualHospitalService
{
    public function __construct(
        VirtualHospitalRepository $virtual_hospital_repository,
        VirtualHospitalDataRepository $virtual_hospital_data_repository,
        VirtualHospitalAssetDataRepository $virtual_hospital_asset_data_repository
    ) {
        $this->virtual_hospital_repository = $virtual_hospital_repository;
        $this->virtual_hospital_data_repository = $virtual_hospital_data_repository;
        $this->virtual_hospital_asset_data_repository = $virtual_hospital_asset_data_repository;

    }

    public function getVirtualHospitalDetails($hospital_id)
    {
        return $this->virtual_hospital_repository->findByField("hospital_id", $hospital_id);
    }

    public function getVirtualHospitalDataDetails($hospital_id)
    {
        return $this->virtual_hospital_data_repository->findByField("hospital_id", $hospital_id,true);
    }

    public function getVirtualHospitalFloorDataDetails($param)
    {
        $floor_data =  $this->virtual_hospital_data_repository->getVitualFloorData($param);
        $return = array("success" => true, "error_code" => 0, "info" => "", "data" => ["data_info" => $floor_data]);

        return $return;
    }

    public function getVirtualHospitalFloorAssetDataDetails($param)
    {
        $data =  $this->virtual_hospital_asset_data_repository->getVitualFloorAssetData($param);
        $return = array("success" => true, "error_code" => 0, "info" => "", "data" => ["data_info" => $data]);

        return $return;
    }

    public function deleteFloorAssetDataDetails($param)
    {
        
        $response =  $this->virtual_hospital_asset_data_repository->delete($param);
       
        if($response)
        {
             $return = array("success" => true, "error_code" => 0, "info" => "");
        }else{
             $return = array("success" => false, "error_code" => 1, "info" => "Data not deleted");
        }
       
        return $return;
    }

    public function getVirtualHospitalDataDetailsByFloor($param)
    {
        $floor_data =  $this->virtual_hospital_data_repository->getVitualFloorDataByFloor($param);
        
        $data = [];
        $data["asset_data"] = [];

        if(!empty($floor_data))
        {
            $data["floor_data"] = $floor_data;
            $param['virtual_hospital_data_id'] = $floor_data['virtual_hospital_data_id'];
            $asset_data =  $this->virtual_hospital_asset_data_repository->getFloorAssetData($param);
            $data["asset_data"] = $asset_data;
        }
        
        $return = array("success" => true, "error_code" => 0, "info" => "", "data" => $data);
        
        return $return;
    }

}
