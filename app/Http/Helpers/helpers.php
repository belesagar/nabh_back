<?php

class Helpers {

    //This code for showing error and success message
    public static function show_message() {

        $message = "";
        if (Session::has('success_message')) {
            $message = '
    			<div class="alert alert-success mb-2 class_alert_message" role="alert">
			        ' .
                    Session::get("success_message")
                    . '
			        
				</div>
    	';
        }

        if (Session::has('error_message')) {
            $message = '
    			<div class="alert alert-danger mb-2 class_alert_message" role="alert">
			        ' .
                    Session::get("error_message")
                    . '
			        
				</div>
    	';

        // Session()->forget('error_message');
        
        }

        

        return $message;
    }

    //This function for getting random code 
    public static function genRandomCode($size) {
        $length = $size;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //length:36
        $final_rand = '';
        for ($i = 0; $i < $length; $i++) {
            $final_rand .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $final_rand;
    }

    public static function genIndicatorsInput() {
        $indicators_input = array(
            array(
                "type" => "text",
                "input_name" => "name_of_patient",
                "label" => "Name of Patient",
                "placeholder" => "Enter Patient Name",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 3,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => []
            ),
            array(
                "type" => "text",
                "input_name" => "pid",
                "label" => "PID",
                "placeholder" => "PID",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 3,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => []
            ),
            array(
                "type" => "date",
                "input_name" => "date",
                "label" => "Date",
                "placeholder" => "Date",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"]
                ),
                "data" => "",
                "data_value" => []
            ),
            array(
                "type" => "text",
                "input_name" => "name_of_surgery",
                "label" => "Name of Surgery",
                "placeholder" => "Name of Surgery",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 3,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => []
            ),
            array(
                "type" => "select",
                "input_name" => "name_of_surgeon",
                "label" => "Name of surgeon",
                "placeholder" => "Name of surgeon",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 0,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => array("1"=>"VINOD BHARATI","2"=>"KH GIRI")
            ),
            /*array(
                "type" => "text",
                "input_name" => "charges_of_anaesthesiologist",
                "label" => "Name of Anaesthesiologist",
                "placeholder" => "Name of Anaesthesiologist",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 3,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => []
            ),*/
            /*array(
                "type" => "select",
                "input_name" => "charges_of_anaesthesiologist",
                "label" => "Name of Anaesthesiologist",
                "placeholder" => "Name of Anaesthesiologist",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 0,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => array("1"=>"SA","2"=>"GA")
            ),
            array(
                "type" => "select",
                "input_name" => "modification_of_plan_anaesthesia",
                "label" => "Modification of plan anaesthesia",
                "placeholder" => "Modification of plan anaesthesia",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => true,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 0,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => array("1"=>"SA","2"=>"GA")
            ),*/
            array(
                "type" => "text",
                "input_name" => "reason_for_modification_of_plan_anaesthesia",
                "label" => "Reason for Modification of plan anaesthesia",
                "placeholder" => "Reason for Modification of plan anaesthesia",
                "class" => "",
                "id" => "",
                "other" => "",
                "validation" => array(
                    ["required" => false,"message" => "Field is required.","type" => "required"],
                    ["minLength" => 3,"message" => "Field must be at least 3 characters long.","type" => "minLength"],
                ),
                "data" => "",
                "data_value" => []
            ),
        );

        return $indicators_input;

    }

    public static function convertKeyValuePair(Array $data,$key_name = "",$key_value = "") {
        $pair_array = [];
        foreach ($data as $key => $value) {
            $pair_array[$value[$key_name]] = $value[$key_value];
        }

        return $pair_array;
    }

}