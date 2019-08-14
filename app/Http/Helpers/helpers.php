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

}