<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\IndicatorsData;
use App\Model\AssignIndicators;
use App\Model\IndicatorsDataHistory;
use App\Model\NabhIndicators;

class NabhIndicatorsController extends Controller
{
	public function __construct(Request $request)
    {
        $this->indicators_data = new IndicatorsData();
        $this->indicators_data_history = new IndicatorsDataHistory();
        $this->assign_indicators = new AssignIndicators();
        $this->nabh_indicators = new NabhIndicators();
 		$this->payload = auth('hospital_api')->user();

    }

    public function indicatorsList(Request $request) {
        $list = $this->nabh_indicators->where('status','ACTIVE')->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }

    public function getIndicatorsInput(Request $request) {

    	$indicators_input = \Helpers::genIndicatorsInput();

    	$return = array("success" => true,"error_code"=>0,"info" => "","data"=>$indicators_input);
    	return response()->json($return);
    }

    public function savendicatorsData(Request $request) {


    	$request_data = $request->all();
    	$user_id = 1;
    	$insert_data = $request_data;

    	$updated_data = json_encode($insert_data);

    	$insert_data['hospital_id'] = $this->payload['hospital_id'];
    	$insert_data['indicators_unique_id'] = date("His");
    	$insert_data['indicators_id'] = 1;
    	$insert_data['date'] = date("Y-m-d",strtotime($request_data['date']));

        $response_id = $this->indicators_data->insertGetId($insert_data);
        if($response_id)
        {
        	$indicators_history_data = array(
        		"hospital_id" => $this->payload['hospital_id'],
        		"indicator_id" => 1,
        		"indicator_data_id" => $response_id,
        		"updated_by_id" => $user_id,
        		"updated_data" => $updated_data,
        	);

        	$indicators_history_response = $this->indicators_data_history->create($indicators_history_data);

        	$return = array("success" => true,"error_code"=>0,"info" => "Data Successfully Added.");
        }else{
        	$return = array("success" => false,"error_code"=>1,"info" => "Something is wrong, Please try again.");
        }
    	
    	return response()->json($return);
    }
    
    public function getIndicatorsList(Request $request) {

    	$hospital_id = $this->payload['hospital_id'];
    	
    	$indicators_list = $this->assign_indicators->with('indicators')->where("hospital_id",$hospital_id)->get();
    	
    	$return = array("success" => true,"error_code"=>0,"info" => "","data" => $indicators_list);
    	return response()->json($return);
    }
    
    public function getIndicatorData(Request $request) {
    	$request_data = $request->all();
    	$hospital_id = $this->payload['hospital_id'];

    	$indicator_data = $this->indicators_data->where('hospital_id', $hospital_id)->where('indicators_id', $request_data['indicator_id'])->get();

    	$return = array("success" => true,"error_code"=>0,"info" => "","data" => $indicator_data);
    	return response()->json($return);
    }

}
