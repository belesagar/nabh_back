<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalPatient;
use App\Model\VirtualHospital;

class HospitalPatientController extends Controller
{
    public function __construct(Request $request)
    {
        $this->hospital_patient = new HospitalPatient();
        $this->virtual_hospital = new VirtualHospital();
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
    }


    public function List(Request $request)
    {
        $list = $this->hospital_patient->where("hospital_id", $this->hospital_id)->orderBy('created_at',
            'desc')->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function getInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'patient_id' => 'required|numeric'
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
            $data_info = $this->hospital_patient->where('patient_reference_number',
                $request_data['patient_id'])->where("hospital_id", $this->payload['hospital_id'])
                ->first();
            if (!empty($data_info)) {
                $data = array("data_info" => $data_info->toArray());
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
            'patient_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'pid' => 'required',
            'sex' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
//            'status' => 'required',
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
                    "patient_reference_number" => $unique_id,
                    "patient_name" => $request_data['patient_name'],
                    "email" => $request_data['email'],
                    "mobile" => $request_data['mobile'],
                    "pid" => $request_data['pid'],
                    "sex" => $request_data['sex'],
                    "address" => $request_data['address'],
                    "city" => $request_data['city'],
                    "state" => $request_data['state'],
//                    "status" => $request_data['status']
                );

                $response = $this->hospital_patient->create($insert_data);
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
            'patient_id' => 'required',
            'patient_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'pid' => 'required',
            'sex' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
//            'status' => 'required',
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


            $update_data = array(
                "patient_name" => $request_data['patient_name'],
                "email" => $request_data['email'],
                "mobile" => $request_data['mobile'],
                "pid" => $request_data['pid'],
                "sex" => $request_data['sex'],
                "address" => $request_data['address'],
                "city" => $request_data['city'],
                "state" => $request_data['state'],
//                "status" => $request_data['status']
            );

            $response = $this->hospital_patient->where('patient_reference_number',
                $request_data['patient_id'])->where("hospital_id",
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


        }
        return json_encode($return);
    }

    public function addVirtualHospitalData(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'total_number_of_beds' => 'required',
            'no_of_patient_opd_in_per_day' => 'required',
            'no_of_old_follow_patient_per_day' => 'required',
            'no_of_new_follow_patient_per_day' => 'required',
            'no_of_ipd_admission_per_day' => 'required',
            'occupany_rate_in_hospital' => 'required',
            'total_no_staff' => 'required',
            'no_of_ward_in_ipd' => 'required',
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

                $insert_data = $request_data;

                $response = $this->virtual_hospital->create($insert_data);
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
