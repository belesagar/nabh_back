<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Offers;

class OfferController extends Controller
{
    public function __construct(Request $request)
    {
        $this->offers = new Offers();
 		$this->payload = auth()->user();
 		// dd($this->payload);
    }

    public function offerList(Request $request) {
        $list = $this->offers->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }	

    public function getOfferInfo(Request $request) {
    	$validator = \Validator::make($request->all(),[
            'offer_id' => 'required|numeric'
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
	        $offer_data = $this->offers->where('offer_id', $request_data['offer_id'])
	                            ->get();
	        $data = array("user_data" => $offer_data);
	        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
    	}
        return json_encode($return);
    }

    public function addOffer(Request $request) {
        $validator = \Validator::make($request->all(),[
            'offer_code' => 'required|unique:offers,offer_code',
            'details' => 'required',
            'message' => 'required',
            'amount_type' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
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
	        		"offer_code" => $request_data['offer_code'],
					"details" => $request_data['details'],
					"message" => md5($request_data['message']),
					"amount_type" => $request_data['amount_type'],
					"amount" => $request_data['amount'],
					"status" => $request_data['status'],
					"start_date" => $request_data['start_date'],
					"end_date" => $request_data['end_date'],
					"created_by" => $this->payload['admin_user_id'],
	        	);
	        	$response = $this->offers->create($insert_data);
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

    public function updateOffer(Request $request) {
        $validator = \Validator::make($request->all(),[
            'offer_id' => 'required|numeric',
            'offer_code' => 'required',
            'details' => 'required',
            'message' => 'required',
            'amount_type' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
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
	        	$check_data = $this->offers->select('offer_id')->where('offer_code', $request_data['offer_code'])->get();
	        	
	        	if(count($check_data) == 0 && $request_data['offer_id'] == $check_data[0]['offer_id'])
	        	{
		        	$update_data = array(
		        		"offer_code" => $request_data['offer_code'],
						"details" => $request_data['details'],
						"message" => md5($request_data['message']),
						"amount_type" => $request_data['amount_type'],
						"amount" => $request_data['amount'],
						"status" => $request_data['status'],
						"start_date" => $request_data['start_date'],
						"end_date" => $request_data['end_date'],
						"created_by" => $this->payload['admin_user_id'],
		        	);
		        	$response = $this->offers->where('offer_id', $request_data['offer_id'])->update($update_data);
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
