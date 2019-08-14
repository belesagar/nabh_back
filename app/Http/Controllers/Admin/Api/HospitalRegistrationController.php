<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalRegistration;

class HospitalRegistrationController extends Controller
{
    public function __construct(Request $request)
    {
        $this->hospital_registration = new HospitalRegistration();
 		$this->payload = auth()->user();
 		// dd($this->payload);
    }

    public function hospitalList(Request $request) {
        $list = $this->hospital_registration->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }

    public function getHospitalInfo(Request $request) {
    	$validator = \Validator::make($request->all(),[
            'hospital_id' => 'required|numeric'
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
	        $data_info = $this->hospital_registration->where('hospital_id', $request_data['hospital_id'])
	                            ->get();
	        $data = array("data_info" => $data_info);
	        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
    	}
        return json_encode($return);
    }

    public function addHospitalData(Request $request) {
        $validator = \Validator::make($request->all(),[
            'hospital_name' => 'required|unique:hospital_registration,hospital_name',
            'spoc_name' => 'required',
            'spoc_designation' => 'required',
            'email' => 'required|email|unique:hospital_registration,email',
            'mobile' => 'required|numeric|unique:hospital_registration,mobile',
            'password' => 'required',
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
	        if(!empty($this->payload))
	        {
	        	$insert_data = array(
	        		"hospital_name" => $request_data['hospital_name'],
					"email" => $request_data['email'],
					"password" => md5($request_data['password']),
					"mobile" => $request_data['mobile'],
					"spoc_name" => $request_data['spoc_name'],
					"status" => $request_data['status'],
					"created_by" => $this->payload['admin_user_id'],
					"spoc_designation" => $request_data['spoc_designation'],
					"city" => $request_data['city'],
					"state" => $request_data['state'],
					"pincode" => $request_data['pincode'],
					"number_of_bed" => $request_data['number_of_bed'],
	        	);
	        	$response = $this->hospital_registration->create($insert_data);
	        	if($response)
	        	{
	        		$return = array("success" => true,"error_code"=>0,"info" => "Success");
	        	}else{
	        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        	}

	        }else{
	        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        }
	    
    	}
        return json_encode($return);
    }

    public function updateHospitalData(Request $request) {
        $validator = \Validator::make($request->all(),[
            'name' => 'required',
            'hospital_name' => 'required',
            'spoc_name' => 'required',
            'spoc_designation' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|numeric',
            'number_of_bed' => 'required|numeric',
            'hospital_id' => 'required|numeric',
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
	        if(!empty($this->payload))
	        {
	        	$update_data = array(
	        		"hospital_name" => $request_data['hospital_name'],
					"email" => $request_data['email'],
					"password" => md5($request_data['password']),
					"mobile" => $request_data['mobile'],
					"spoc_name" => $request_data['spoc_name'],
					"status" => $request_data['status'],
					"created_by" => $this->payload['admin_user_id'],
					"spoc_designation" => $request_data['spoc_designation'],
					"city" => $request_data['city'],
					"state" => $request_data['state'],
					"pincode" => $request_data['pincode'],
					"number_of_bed" => $request_data['number_of_bed'],
	        	);

	        	if($request_data['password'] != "")
	        	{
	        		$update_data['password'] = $request_data['password'];
	        	}

	        	$response = $this->hospital_registration->where('hospital_id', $request_data['hospital_id'])->update($update_data);
	        	if($response)
	        	{
	        		$return = array("success" => true,"error_code"=>0,"info" => "Success");
	        	}else{
	        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        	}

	        }else{
	        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        }
	    
    	}
        return json_encode($return);
    }

}
