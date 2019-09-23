<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalDoctors;
use App\Model\HospitalDoctorsType;

class DoctorsController extends Controller
{
    public function __construct(Request $request)
    {
        $this->hospital_doctors = new HospitalDoctors();
        $this->hospital_doctors_type = new HospitalDoctorsType();
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];

    }

    public function List(Request $request)
    {
        $list = $this->hospital_doctors->where("hospital_id", $this->payload['hospital_id'])->orderBy('created_at',
            'desc')->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function getInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'doctor_id' => 'required|numeric'
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
            $data_info = $this->hospital_doctors->where('doctor_id', $request_data['doctor_id'])->where("hospital_id",
                $this->payload['hospital_id'])
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
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
            'doctor_charges' => 'required',
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
                $unique_id = date("his");

                $insert_data = array(
                    "hospital_id" => $this->payload['hospital_id'],
                    "doctor_unique_id" => $unique_id,
                    "name" => $request_data['name'],
                    "email" => $request_data['email'],
                    "mobile" => $request_data['mobile'],
                    "city" => $request_data['city'],
                    "state" => $request_data['state'],
                    "address" => $request_data['address'],
                    "doctor_charges" => $request_data['doctor_charges'],
                    "status" => $request_data['status']
                );

                $response = $this->hospital_doctors->create($insert_data);
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
            'doctor_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
            'doctor_charges' => 'required',
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
                    "name" => $request_data['name'],
                    "email" => $request_data['email'],
                    "mobile" => $request_data['mobile'],
                    "city" => $request_data['city'],
                    "state" => $request_data['state'],
                    "address" => $request_data['address'],
                    "doctor_charges" => $request_data['doctor_charges'],
                    "status" => $request_data['status']
                );

                $response = $this->hospital_doctors->where('doctor_id',
                    $request_data['doctor_id'])->where("hospital_id",
                    $this->payload['hospital_id'])->update($update_data);
                if ($response) {
                    $return = array("success" => true, "error_code" => 0, "info" => "Data updated Successfully");
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

    public function typeList(Request $request)
    {
        $list = $this->hospital_doctors_type->where("hospital_id", $this->payload['hospital_id'])->orderBy('created_at',
            'desc')->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

}
