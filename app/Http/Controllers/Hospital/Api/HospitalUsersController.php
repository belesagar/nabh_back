<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalUsers;

class HospitalUsersController extends Controller
{
    public function __construct(Request $request)
    {
        $this->hospital_users = new HospitalUsers();
 		$this->payload = auth('hospital_api')->user();

    }
    public function List(Request $request) {
    	$list = $this->hospital_users->where("hospital_id",$this->payload['hospital_id'])->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }

    public function getInfo(Request $request) {
        $validator = \Validator::make($request->all(),[
            'user_id' => 'required|numeric'
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
            $user_data = $this->hospital_users->where('hospital_user_id', $request_data['user_id'])->where("hospital_id",$this->payload['hospital_id'])
                                ->get();
            if(count($user_data) == 1)
            {
                $data = array("user_data" => $user_data[0]);
                $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
            }else{
                $return = array("success" => false,"error_code"=>1,"info" => "Invalid Record");
            }
            
        }
        return json_encode($return);
    }

    public function Add(Request $request) {
        $validator = \Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:hospital_users,email',
            'mobile' => 'required|unique:hospital_users,mobile',
            'password' => 'required|same:cpassword',
            'cpassword' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
            'status' => 'required',
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
	        	$user_unique_id = date("his");

	        	$insert_data = array(
	        		"hospital_id" => $this->payload['hospital_id'],
					"user_unique_id" => $user_unique_id,
					"name" => $request_data['name'],
                    "email" => $request_data['email'],
					"password" => md5($request_data['password']),
					"mobile" => $request_data['mobile'],
					"city" => $request_data['city'],
					"state" => $request_data['state'],
                    "address" => $request_data['address'],
					"status" => $request_data['status']
	        	);
                
	        	$response = $this->hospital_users->create($insert_data);
	        	if($response)
	        	{
	        		$return = array("success" => true,"error_code"=>0,"info" => "Data added Successfully");
	        	}else{
	        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        	}

	        }else{
	        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        }
	    
    	}
        return json_encode($return);
    }

    public function Edit(Request $request) {
        $validator = \Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'city' => 'required',
            'password' => 'same:cpassword',
            'state' => 'required',
            'address' => 'required',
            'status' => 'required',
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
	        	$success = 1;

	        	$check_data = $this->hospital_users->select('hospital_user_id')->where('email', $request_data['email'])->get();
	        	
                if(count($check_data) > 0)
                {
                    if($request_data['user_id'] != $check_data[0]['hospital_user_id'])
                    {
                        $return = array("success" => false,"error_code"=>1,"info" => "Email ID is already taken.");
                        return json_encode($return);
                    }
                }

                $check_mobile_data = $this->hospital_users->select('hospital_user_id')->where('mobile', $request_data['mobile'])->get();
	        	
                if(count($check_mobile_data) > 0)
                {
                    if($request_data['user_id'] != $check_mobile_data[0]['hospital_user_id'])
                    {
                        $return = array("success" => false,"error_code"=>1,"info" => "Mobile number is already taken.");
                        return json_encode($return);
                    }
                }

                
	        	$update_data = array(
	        		"hospital_id" => $this->payload['hospital_id'],
					"name" => $request_data['name'],
					"email" => $request_data['email'],
					"mobile" => $request_data['mobile'],
					"city" => $request_data['city'],
                    "state" => $request_data['state'],
					"address" => $request_data['address'],
					"status" => $request_data['status']
	        	);    
                
                if(isset($request_data['password']) && $request_data['password'] != "") 
                {
                    $update_data['password'] = $request_data['password'];
                }

	        	$response = $this->hospital_users->where('hospital_user_id', $request_data['user_id'])->where("hospital_id",$this->payload['hospital_id'])->update($update_data);
	        	if($response)
	        	{
	        		$return = array("success" => true,"error_code"=>0,"info" => "Data updated Successfully");
	        	}else{
	        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        	}

	        }else{
	        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        }
	    
    	}
        return json_encode($return);
    }

    public function AssignIndicators(Request $request) {
    	
    }

}
