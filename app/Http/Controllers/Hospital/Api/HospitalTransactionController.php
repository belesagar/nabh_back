<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NabhPackages;
use App\Model\TempTransaction;
use Razorpay\Api\Api;

class HospitalTransactionController extends Controller
{
    public function __construct(Request $request)
    {
    	$this->nabh_packages = new NabhPackages();
    	$this->temp_transaction = new TempTransaction();
 		$this->payload = auth('hospital_api')->user();
 		$this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];

        $this->api = new Api("rzp_test_a8i85PIwYFA2rr", "5lxZSpm4wxmKs5xTr6scBVe0");

 		// dd($this->payload);
    }

    public function initiatePayment(Request $request) {
    	$request_data = $request->all();
    	$package_id = $request_data['package_id'];

    	$data_info = $this->nabh_packages->where([["package_reference_number",$package_id],["status","ACTIVE"]])->first();

    	if(!empty($data_info))
    	{
    		$amount_of_order = $data_info->package_amount;
    		$total_amount = $data_info->package_amount;

    		$temp_transaction_array = array(
    			"temp_transaction_unique_id" => \Helpers::genRandomCode(15),
    			"hospital_id" => $this->hospital_id,
    			"user_id" => $this->hospital_user_id,
    			"package_id" => $package_id,
    			"package_details" => json_encode($data_info),
    			"amount_of_order" => $amount_of_order,
    			"total_amount" => $total_amount,
    		);

    		$transaction_response = $this->temp_transaction->insert($temp_transaction_array);
    		if($transaction_response)
    		{
    			$transaction_payload = array(
    				"temp_transaction_unique_id" => $temp_transaction_array['temp_transaction_unique_id'],
    				"hospital_id" => $this->hospital_id,
    				"user_id" => $this->hospital_user_id,
    				"package_id" => $package_id,
    				"total_amount" => $total_amount
    			);
    			$key = 123456;
    			$encrypted_key = \Helpers::encrypt(json_encode($transaction_payload),$key);
    			$data_info = ["encrypted_key" => $encrypted_key];
    			$return = array("success" => true,"error_code"=>0,"info" => "Success","data" => ["data_info" => $data_info]);
    		}else{
    			$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, Please try again.");
    		}
    	}else{
    		$return = array("success" => false,"error_code"=>1,"info" => "Invalid Package Data.");
    	}
        return json_encode($return);

    }

    public function checkPayment(Request $request) {
    	$request_data = $request->all();
    	$order = $this->api->order->fetch($request_data['response_id']);
    	$return = array("success" => true,"error_code"=>0,"info" => "Success","data" => ["data_info" => $data_info]);
    	return json_encode($return);
    }

}
