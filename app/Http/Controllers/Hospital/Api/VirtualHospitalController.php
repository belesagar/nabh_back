<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\VirtualHospital;

class VirtualHospitalController extends Controller
{
	public function __construct(Request $request)
    {
        $this->virtual_hospital = new VirtualHospital();
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
    }

    public function addVirtualHospitalData(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'total_number_of_beds' => 'required',
            'no_of_patient_opd_in_per_day' => 'required',
            'no_of_old_follow_patient_per_day' => 'required',
            'no_of_new_follow_patient_per_day' => 'required',
            'no_of_ipd_admission_per_day' => 'required',
            'occupany_rate_in_hospital' => 'required',
            'total_no_staff' => 'required',
            'no_of_ward_in_ipd' => 'required',
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
	                $response = $this->virtual_hospital->create($insert_data);
            	}else{
            		$response = $this->virtual_hospital->where("hospital_id",
	                $this->payload['hospital_id'])->update($insert_data);
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
        return json_encode($return);
    }

    public function getVirtualHospitalData(Request $request)
    {
    	$return = $this->getVirtualHospitalDetails();
    	return json_encode($return);
    }

    private function getVirtualHospitalDetails()
    {
    	$response = $this->virtual_hospital->where('hospital_id', $this->hospital_id)->first();
    	if(!empty($response))
    	{
    		$return = array("success" => true, "error_code" => 0, "info" => "",'data'=>$response);
    	}else{
    		$return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "No data found."
            );
    	}

    	return $return;
    }

}
