<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NabhPackages;

class PackageController extends Controller
{
    public function __construct(Request $request)
    {
        $this->nabh_packages = new NabhPackages();
        $this->payload = auth()->user();
        // dd($this->payload);
    }

    public function List(Request $request)
    {
        $list = $this->nabh_packages->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function getinfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nabh_packages_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $request_data = $request->all();
            $data_info = $this->nabh_packages->where('nabh_packages_id', $request_data['nabh_packages_id'])
                ->get();

            if (count($data_info) == 1) {
                $data = array("data_info" => $data_info[0]);
                $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
            } else {
                $return = array("success" => false, "error_code" => 1, "info" => "Invalid Record");
            }
        }
        return json_encode($return);
    }

    public function Add(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'package_name' => 'required',
            'package_amount' => 'required',
            'per_month_amount' => 'required',
            'indicators_type' => 'required',
            'no_of_indicators_allowed' => 'required',
            'no_of_user_allowed' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $request_data = $request->all();
            if (!empty($this->payload)) {
                $insert_data = array(
                    "package_reference_number" => date("his"),
                    "package_name" => $request_data['package_name'],
                    "package_amount" => $request_data['package_amount'],
                    "per_month_amount" => $request_data['per_month_amount'],
                    "indicators_type" => $request_data['indicators_type'],
                    "no_of_indicators_allowed" => $request_data['no_of_indicators_allowed'],
                    "status" => $request_data['status'],
                    "no_of_user_allowed" => $request_data['no_of_user_allowed'],
                );

                $response = $this->nabh_packages->create($insert_data);
                if ($response) {
                    $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
                } else {
                    $return = array(
                        "success" => false,
                        "error_code" => 1,
                        "info" => "Something is wrong, please try again."
                    );
                }

            } else {
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Something is wrong, please try again."
                );
            }

        }
        return json_encode($return);
    }

    public function Edit(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'package_name' => 'required',
            'package_amount' => 'required',
            'per_month_amount' => 'required',
            'indicators_type' => 'required',
            'no_of_indicators_allowed' => 'required',
            'no_of_user_allowed' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $request_data = $request->all();
            if (!empty($this->payload)) {
                $update_data = array(
                    "package_name" => $request_data['package_name'],
                    "package_amount" => $request_data['package_amount'],
                    "per_month_amount" => $request_data['per_month_amount'],
                    "indicators_type" => $request_data['indicators_type'],
                    "no_of_indicators_allowed" => $request_data['no_of_indicators_allowed'],
                    "status" => $request_data['status'],
                    "no_of_user_allowed" => $request_data['no_of_user_allowed'],
                );

                $response = $this->nabh_packages->where('nabh_packages_id',
                    $request_data['nabh_packages_id'])->update($update_data);
                if ($response) {
                    $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
                } else {
                    $return = array(
                        "success" => false,
                        "error_code" => 1,
                        "info" => "Something is wrong, please try again."
                    );
                }

            } else {
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Something is wrong, please try again."
                );
            }

        }
        return json_encode($return);
    }

}
