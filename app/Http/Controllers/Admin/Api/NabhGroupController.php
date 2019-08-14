<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NabhGroup;

class NabhGroupController extends Controller
{
    public function __construct(Request $request)
    {
        $this->nabh_group = new NabhGroup();
 		$this->payload = auth()->user();
 		// dd($this->payload);
    }

    public function nabhGroupList(Request $request) {
        $list = $this->nabh_group->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }

    public function addNabhGroup(Request $request) {
        $validator = \Validator::make($request->all(),[
            'nabh_name' => 'required|unique:nabh_group,nabh_name',
            'price' => 'required',
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
	        	$insert_data = array(
	        		"nabh_name" => $request_data['nabh_name'],
					"price" => $request_data['price'],
					"status" => md5($request_data['status']),
	        	);
	        	$response = $this->nabh_group->create($insert_data);
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

     public function updateNabhGroup(Request $request) {
        $validator = \Validator::make($request->all(),[
            'nabh_name' => 'required',
            'price' => 'required',
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
	        	$check_data = $this->nabh_group->select('nabh_group_id')->where('nabh_name', $request_data['nabh_name'])->get();
	        	
	        	if(count($check_data) == 0 && $request_data['nabh_group_id'] == $check_data[0]['nabh_group_id'])
	        	{
		        	$update_data = array(
		        		"nabh_name" => $request_data['nabh_name'],
						"price" => $request_data['price'],
						"status" => md5($request_data['status']),
		        	);
		        	$response = $this->nabh_group->where('nabh_group_id', $request_data['nabh_group_id'])->update($update_data);
		        	if($response)
		        	{
		        		$return = array("success" => true,"error_code"=>0,"info" => "Success");
		        	}else{
		        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
		        	}
	        	}else{
	        		$return = array("success" => false,"error_code"=>1,"info" => "Email ID is already taken.");
	        	}
	        }else{
	        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        }
	    
    	}
        return json_encode($return);
    }

}
