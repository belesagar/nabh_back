<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NabhIndicators;

class NabhIndicatorsController extends Controller
{
    public function __construct(Request $request)
    {
        $this->nabh_indicators = new NabhIndicators();
 		$this->payload = auth()->user();
 		// dd($this->payload);
    }

    public function indicatorsList(Request $request) {
        $list = $this->nabh_indicators->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }

    public function getIndicatorsInfo(Request $request) {
    	$validator = \Validator::make($request->all(),[
            'indicators_id' => 'required|numeric'
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
            $data_info = [];
	        $data_response = $this->nabh_indicators->where('indicators_id', $request_data['indicators_id'])
	                            ->get();
            if(count($data_response) == 1)
            {
                $data_info = $data_response[0];
                $data = array("data_info" => $data_info);
                $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
            }else{
                $return = array("success" => false,"error_code"=>1,"info" => "Invalid Record.");
            }
    	}
        return json_encode($return);
    }

    public function addIndicators(Request $request) {
        $validator = \Validator::make($request->all(),[
            'name' => 'required|unique:nabh_indicators,name',
            'short_name' => 'required',
            'indicators_price' => 'required|numeric',
            'group_id' => 'required',
            'formula' => 'required',
            'remark' => 'required',
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
	        		"name" => $request_data['name'],
					"short_name" => $request_data['short_name'],
					"indicators_price" => $request_data['indicators_price'],
					"group_id" => $request_data['group_id'],
					"formula" => $request_data['formula'],
					"status" => $request_data['status'],
					"remark" => $request_data['remark'],
	        	);
	        	$response = $this->nabh_indicators->create($insert_data);
	        	if($response)
	        	{
	        		$return = array("success" => true,"error_code"=>0,"info" => "Data added successfully");
	        	}else{
	        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        	}

	        }else{
	        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        }
	    
    	}
        return json_encode($return);
    }

    public function updateIndicators(Request $request) {
        $validator = \Validator::make($request->all(),[
            'name' => 'required',
            'short_name' => 'required',
            'indicators_price' => 'required',
            'group_id' => 'required',
            'formula' => 'required',
            'remark' => 'required',
            'status' => 'required',
            'indicators_id' => 'required|numeric',
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
	        	$check_data = $this->nabh_indicators->select('indicators_id')->where('name', $request_data['name'])->get();
                
                $success = 1;
                if(count($check_data) > 0)
                {
                    if($request_data['indicators_id'] != $check_data[0]['indicators_id'])
                    {
                        $success = 0;
                    }
                }
	        	if($success)
	        	{
		        	$update_data = array(
		        		"name" => $request_data['name'],
						"short_name" => $request_data['short_name'],
						"indicators_price" => $request_data['indicators_price'],
						"group_id" => $request_data['group_id'],
						"formula" => $request_data['formula'],
						"status" => $request_data['status'],
						"remark" => $request_data['remark'],
		        	);
		        	$response = $this->nabh_indicators->where('indicators_id', $request_data['indicators_id'])->update($update_data);
		        	if($response)
		        	{
		        		$return = array("success" => true,"error_code"=>0,"info" => "Data updated successfully.");
		        	}else{
		        		$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
		        	}
	        	}else{
	        		$return = array("success" => false,"error_code"=>1,"info" => "Indicator Name already present.");
	        	}
	        }else{
	        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, please try again.");
	        }
	    
    	}
        return json_encode($return);
    }

}
