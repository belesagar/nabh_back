<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalRegistration;

class HospitalRegistrationController extends Controller
{
	public function __construct(Request $request)
    {
        $this->hospital_registration = new HospitalRegistration();
 		// $this->payload = auth('hospital_api')->user();
 		// dd($this->payload);
    }

    public function addHospitalData(Request $request) {

        $validator = \Validator::make($request->all(),[
            'hospital_name' => 'required|unique:hospital_registration,hospital_name',
            'spoc_name' => 'required',
            'spoc_designation' => 'required',
            'email' => 'required|email|unique:hospital_registration,email',
            'mobile' => 'required|numeric|unique:hospital_registration,mobile',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|numeric',
            'number_of_bed' => 'required|numeric',
        ]); 
          
        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value."\n";
            }
            $return = array("success" => false,"error_code"=>1,"info" => $errors_message);
        }else{
	    	$request_data = $request->all();

        	$insert_data = array(
        		"hospital_name" => $request_data['hospital_name'],
        		"hospital_unique_id" => date("his"),
				"email" => $request_data['email'],
				"password" => md5('123456'),
				"mobile" => $request_data['mobile'],
				"spoc_name" => $request_data['spoc_name'],
				// "status" => $request_data['status'],
				// "created_by" => $this->payload['admin_user_id'],
				"spoc_designation" => $request_data['spoc_designation'],
				"city" => $request_data['city'],
				"state" => $request_data['state'],
				"pincode" => $request_data['pincode'],
				"number_of_bed" => $request_data['number_of_bed'],
        	);
        	
        	$response = $this->hospital_registration->create($insert_data);
        	if($response)
        	{
        		$return = array("success" => true,"error_code"=>0,"info" => "Hospital is register successfully. please login.");
        	}else{
        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
        	}

	        
    	}
        return json_encode($return);
    }
}
