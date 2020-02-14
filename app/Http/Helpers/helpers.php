<?php

class Helpers
{

    //This code for showing error and success message
    public static function show_message()
    {

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
    public static function genRandomCode($size)
    {
        $length = $size;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //length:36
        $final_rand = '';
        for ($i = 0; $i < $length; $i++) {
            $final_rand .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $final_rand;
    }

    public static function genIndicatorsInput()
    {
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
                    ["required" => true, "message" => "Field is required.", "type" => "required"],
                    ["minLength" => 3, "message" => "Field must be at least 3 characters long.", "type" => "minLength"],
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
                    ["required" => true, "message" => "Field is required.", "type" => "required"],
                    ["minLength" => 3, "message" => "Field must be at least 3 characters long.", "type" => "minLength"],
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
                    ["required" => true, "message" => "Field is required.", "type" => "required"]
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
                    ["required" => true, "message" => "Field is required.", "type" => "required"],
                    ["minLength" => 3, "message" => "Field must be at least 3 characters long.", "type" => "minLength"],
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
                    ["required" => true, "message" => "Field is required.", "type" => "required"],
                    ["minLength" => 0, "message" => "Field must be at least 3 characters long.", "type" => "minLength"],
                ),
                "data" => "",
                "data_value" => array("1" => "VINOD BHARATI", "2" => "KH GIRI")
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
                    ["required" => false, "message" => "Field is required.", "type" => "required"],
                    ["minLength" => 3, "message" => "Field must be at least 3 characters long.", "type" => "minLength"],
                ),
                "data" => "",
                "data_value" => []
            ),
        );

        return $indicators_input;

    }

    public static function convertKeyValuePair(Array $data, $key_name = "", $key_value = "")
    {
        $pair_array = [];
        foreach ($data as $key => $value) {
//            $pair_array[$value[$key_name]] = $value[$key_value];
            $pair_array[] = ["id" => $value[$key_name], "text" => $value[$key_value]];
        }

        return $pair_array;
    }

    public static function convertKeyIDTextPair(Array $data)
    {
        $pair_array[] = ["id" => "", "text" => "Select any one"];
        foreach ($data as $key => $value) {
//            $pair_array[$value[$key_name]] = $value[$key_value];
            $pair_array[] = ["id" => $key, "text" => $value];
        }

        return $pair_array;
    }

    public static function encrypt($plainText, $key)
    {
        $secret = self::hextobin(md5($key));
        $plainPad = self::pkcs5_pad($plainText, 16);
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d,
            0x0e, 0x0f);
        $encryptedText = openssl_encrypt($plainPad, 'AES-128-CBC', $secret, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $initVector);
        return bin2hex($encryptedText);
    }

    public static function decrypt($encryptedText, $key)
    {
        $secret = self::hextobin(md5($key));
        $encryptedText = self::hextobin($encryptedText);
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d,
            0x0e, 0x0f);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $secret,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $initVector);
        $decryptedText = rtrim($decryptedText, "\0");
        return $decryptedText;
    }

    public static function pkcs5_pad($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr(0), $pad); //issue with caratlane - bad decrypt issue
    }

    public static function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }

    public static function filterData($request_data)
    {
        
        return $binString;
    }

    public static function getFirstandlastDate($from_date = "", $to_date = "")
    {
        $from_data_bk = $from_date;
        $date_array = [];

        while (strtotime($from_date) <= strtotime($to_date)) {
                 
            if($from_data_bk == $from_date)
            {
                $lastDateOfMonth = date("Y-m-t", strtotime($from_date));
               
                $date_array[] = ["first_date" => $from_date, "last_date" => $lastDateOfMonth];
            }
            
            $from_date = date ("Y-m-d", strtotime("+1 month", strtotime($from_date)));
            $first_date = date('Y-m-01', strtotime($from_date));
            $lastDateOfMonth = date("Y-m-t", strtotime($from_date));
            $date_array[] = ["first_date" => $first_date, "last_date" => $lastDateOfMonth];
                
        }

        return $date_array;
    }

    public static function jd($data) {
        echo json_encode($data);die;
    }

}
