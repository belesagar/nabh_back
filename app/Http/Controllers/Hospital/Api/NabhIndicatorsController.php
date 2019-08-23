<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NabhIndicatorsController extends Controller
{
    public function getIndicatorsInput(Request $request) {

    	$indicators_input = array(
    		array(
    			"type" => "text",
    			"input_name" => "name_of_patient",
    			"label" => "Name of Patient",
    			"placeholder" => "Enter Patient Name",
    			"class" => "",
    			"id" => "",
    			"validation" => array(
    				["required" => true,"message" => "Field is required.","type" => "required"],
    				["minLength" => 3,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
    			),
    			"data_value" => []
    		),
    		array(
    			"type" => "text",
    			"input_name" => "pid",
    			"label" => "PID",
    			"placeholder" => "PID",
    			"class" => "",
    			"id" => "",
    			"validation" => array(
    				["required" => true,"message" => "Field is required.","type" => "required"],
    				["minLength" => 3,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
    			),
    			"data_value" => []
    		),
    	);

    	$return = array("success" => true,"error_code"=>0,"info" => "","data"=>$indicators_input);
    	return json_encode($return);
    }
}
