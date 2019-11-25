<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\VirtualHospital;
use App\Model\VirtualHospitalAssetData;
use App\Model\VirtualHospitalData;
use App\Services\Hospital\VirtualHospitalService;
use App\Repositories\VirtualHospitalRepository;
use App\Repositories\VirtualHospitalAssetDataRepository;

class VirtualHospitalController extends Controller
{
	public function __construct(
        VirtualHospitalService $virtual_hospital_service,
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
        $this->virtual_hospital_repository = $virtual_hospital_repository;
        $this->virtual_hospital_asset_data_repository = $virtual_hospital_asset_data_repository;
    }

    public function addVirtualHospitalData(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'floor_count' => 'required|numeric',
            'hospital_area' => 'required',
            'official_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $request_data = $request->all();
            if (!empty($this->payload)) {
                $unique_id = date("his");

                $insert_data = $request_data;
                $virtual_hospital_data = $this->getVirtualHospitalDetails();
                if(!$virtual_hospital_data['success'])
                {
                    $insert_data['hospital_id'] = $this->hospital_id;
                    $insert_data['virtual_hospital_reference_number'] = $unique_id;
                    $insert_data['official_email'] = $request_data['official_email'];
	                $insert_data['floor_count'] = $request_data['floor_count'];
	                $response = $this->virtual_hospital_repository->create($insert_data);
            	}else{
            		$where_clouse = ["hospital_id" => $this->payload['hospital_id']];
                    $response = $this->virtual_hospital_repository->update($insert_data,$where_clouse);
            	}
            	
                if ($response) {
                    $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
                } else {
                    $return = array(
                        "success" => false,
                        "error_code" => 1,
                        "info" => "Something is wrong, please try again."
                    );
                }

            } else {
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Something is wrong, please try again."
                );
            }

        }
        return $this->response($return);
    }

    public function getVirtualHospitalData(Request $request)
    {
    	$response = $this->virtual_hospital_service->getVirtualHospitalDetails($this->hospital_id);
        if(!empty($response))
        {
            $data = ['data_info' => $response];
            $return = array("success" => true, "error_code" => 0, "info" => "",'data'=>$data);
        }else{
            $return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "No data found."
            );
        }
    	return json_encode($return);
    }

    private function getVirtualHospitalDetails()
    {
    	$response = $this->virtual_hospital->where('hospital_id', $this->hospital_id)->first();
    	if(!empty($response))
    	{
            $data = ['data_info' => $response];
    		$return = array("success" => true, "error_code" => 0, "info" => "",'data'=>$data);
    	}else{
    		$return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "No data found."
            );
    	}

    	return $return;
    }

    public function addVirtualfloorData(Request $request)
    {
         // print_r($request->all());
         // exit;   
        $virtual_data = $response = $this->virtual_hospital_service->getVirtualHospitalDetails($this->hospital_id);
        if(!empty($virtual_data))
        {
             $request_data = $request->all();
             
             $current_floor = $request_data['floor_no'];

             $param = [
                "hospital_id" => $this->hospital_id,
                "virtual_hospital_id" => $virtual_data['virtual_hospital_id'],
                "floor_no" => $request_data['floor_no'],
             ];

             $floor_data = $this->virtual_hospital_service->getVirtualHospitalFloorDataDetails($param);
             
             if(empty($floor_data['data']['data_info']))
             {
                $insert_data = [
                    "hospital_id" => $this->hospital_id,
                    "virtual_hospital_id" => $virtual_data['virtual_hospital_id'],
                    "floor_no" => $request_data['floor_no'],
                 ];

                 $last_id = $this->virtual_hospital_data->insertGetId($insert_data);

             }else{
                $last_id = $floor_data['data']['data_info']['virtual_hospital_data_id'];
             }
             

             $asset_data = [
                "hospital_id" => $this->hospital_id,
                "virtual_hospital_data_id" => $last_id,
             ];
                   
             if(count($request_data['ot_data']) > 0)
             {  
                $asset_data['type'] = "OT";

                $asset_data_count = $this->virtual_hospital_asset_data_repository->getVitualFloorAssetDataCount($asset_data);
                if($asset_data_count > 0)
                {
                    $delete_where = [
                        "hospital_id" => $this->hospital_id,
                        "virtual_hospital_data_id" => $last_id,
                        "type" => "OT",
                    ];
                    $delete_response = $this->virtual_hospital_service->deleteFloorAssetDataDetails($delete_where);
                }
                
                foreach ($request_data['ot_data'] as $ot_value) {
                    $asset_data['name'] = $ot_value['name'];
                    $asset_data['number_of_beds'] = $ot_value['type'];
                    
                    $this->virtual_hospital_asset_data->insert($asset_data);
                }
                
             }  

            /* if(count($request_data['opd_data']) > 0)
             {  
                $asset_data['type'] = "OPD";

                $asset_data_count = $this->virtual_hospital_asset_data_repository->getVitualFloorAssetDataCount($asset_data);
                if($asset_data_count > 0)
                {
                    $delete_where = [
                        "hospital_id" => $this->hospital_id,
                        "virtual_hospital_data_id" => $last_id,
                        "type" => "OPD",
                    ];
                    
                    $delete_response = $this->virtual_hospital_service->deleteFloorAssetDataDetails($delete_where);
                }
               
                foreach ($request_data['opd_data'] as $ot_value) {
                    $asset_data['name'] = $ot_value['name'];
                    $asset_data['number_of_beds'] = $ot_value['no_of_bed'];
                   
                    $this->virtual_hospital_asset_data->insert($asset_data);
                }
                
             } */

             if($current_floor == $virtual_data['floor_count'])
             {
                $next_floor = 0;  
             }else{
                $next_floor = $current_floor + 1;
             }

             $data = ["next_floor" => $next_floor];

             $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully", "data" => $data);

        }else{
            $return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "No data present for hispital"
            );
        }

        return $this->response($return);
    }

    public function getVirtualHospitalFloorData(Request $request)
    {
        $response = $this->virtual_hospital_service->getVirtualHospitalDataDetails($this->hospital_id);

        if(!empty($response))
        {
            $data = ['data_info' => $response];
            $return = array("success" => true, "error_code" => 0, "info" => "",'data'=>$data);
        }else{
            $return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "No data found."
            );
        }
        return json_encode($return);
    }

    public function getfloorDataByFloorNumber(Request $request)
    {
        $param = [
            "hospital_id" => $this->hospital_id,
            "floor_no" => $request['floor_no'],
        ];
        $response = $this->virtual_hospital_service->getVirtualHospitalDataDetailsByFloor($param);
        $asset_list = [];
       
        if($response['success'])
        {   
            foreach ($response['data']['asset_data'] as $key => $value) {
                if($value['type'] == "OT")
                {
                    $asset_list["ot_data"][] = $value;
                }
                if($value['type'] == "OPD")
                {
                    $asset_list["opd_data"][] = $value;
                }
            }
            
        }
        $response['data']['asset_list'] = $asset_list;
        return json_encode($response);
    }

}
