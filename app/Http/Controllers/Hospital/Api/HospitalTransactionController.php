<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NabhPackages;
use App\Model\TempTransaction;
use App\Model\Transaction;
use Razorpay\Api\Api;

class HospitalTransactionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->nabh_packages = new NabhPackages();
        $this->temp_transaction = new TempTransaction();
        $this->transaction = new Transaction();
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];

        $this->api = new Api("rzp_test_QEecyXWy9NgWDK", "7QjTWPBmu5VFy1XJCt3WWPm1");

        // print_r(new Api("rzp_test_QEecyXWy9NgWDK", "7QjTWPBmu5VFy1XJCt3WWPm1"));
        // exit;
    }

    public function initiatePayment(Request $request)
    {
        $request_data = $request->all();
        $package_id = $request_data['package_id'];

        $data_info = $this->nabh_packages->where([
            ["package_reference_number", $package_id],
            ["status", "ACTIVE"]
        ])->first();

        if (!empty($data_info)) {
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
            if ($transaction_response) {
                $transaction_payload = array(
                    "temp_transaction_unique_id" => $temp_transaction_array['temp_transaction_unique_id'],
                    "hospital_id" => $this->hospital_id,
                    "user_id" => $this->hospital_user_id,
                    "package_id" => $package_id,
                    "total_amount" => $total_amount
                );
//                $key = 123456;
//                $encrypted_key = \Helpers::encrypt(json_encode($transaction_payload), $key);
                $data_info = ["encrypted_key" => $temp_transaction_array['temp_transaction_unique_id']];
                $return = array(
                    "success" => true,
                    "error_code" => 0,
                    "info" => "Success",
                    "data" => ["data_info" => $data_info]
                );
            } else {
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Something is wrong, Please try again."
                );
            }
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Invalid Package Data.");
        }
        return json_encode($return);

    }

    public function checkPayment(Request $request)
    {
        $request_data = $request->all();

        $payment_response = $this->api->payment->fetch($request_data['response_id']);
        // $order = $this->api->order->all();

        if ($payment_response['id'] != "") {

            $encrypted_key = $payment_response['notes']->encrypted_key;
            $temp_data = $this->temp_transaction->where("temp_transaction_unique_id",$encrypted_key)->first();

            $transaction_array = array(
                "temp_transaction_unique_id" => $temp_data->temp_transaction_unique_id,
                "temp_transaction_id" => $temp_data->temp_transaction_id,
                "hospital_id" => $temp_data->hospital_id,
                "user_id" => $temp_data->user_id,
                "package_id" => $temp_data->package_id,
                "package_details" => $temp_data->package_details,
                "amount_of_order" => $temp_data->amount_of_order,
                "offer_id" => $temp_data->offer_id,
                "offer_amount" => $temp_data->offer_amount,
                "total_amount" => $temp_data->total_amount,
                "raw_data" => json_encode((array) $payment_response),
                "status" => "SUCCESS"

            );

            $transaction_response = $this->transaction->insert($transaction_array);
            if($transaction_response)
            {
                $return = array(
                    "success" => true,
                    "error_code" => 0,
                    "info" => "Success",
                    "data" => ["data_info" => ["response_id" => $temp_data->temp_transaction_unique_id]]
                );
            }else{
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Something is wrong with transaction."
                );
            }

//            print_r($payment_response['notes']->encrypted_key);
//            $encrypted_key = $payment_response['notes']->encrypted_key;
//            $key = 123456;
//            $decrypted_data = \Helpers::decrypt(json_encode($encrypted_key), $key);

//            print_r($decrypted_data);

        } else {
            $return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "Something is wrong with transaction."
            );
        }

        return json_encode($return);
    }

}
