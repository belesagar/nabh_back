<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalDoctors;
use App\Model\HospitalDoctorsType;
use App\Services\Hospital\HospitalDoctorService;

class DoctorsController extends Controller
{
    public function __construct(
        HospitalDoctorService $hospital_doctor_service
    )
    {
        $this->hospital_doctors = new HospitalDoctors();
        $this->hospital_doctors_type = new HospitalDoctorsType();
        $this->hospital_doctor_service = $hospital_doctor_service;
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];

    }

    public function List(Request $request)
    {   
        $where = [["hospital_id", $this->hospital_id]];

        $data_count = $this->hospital_doctors->where($where)->count();

        $request_data = $request->all();

        $where[] = ['status',$request_data['status']];

        //Filter option
        if(isset($request_data['search_string']) && $request_data['search_string'] != "")
        {
            $search_key = $request_data['search_key'];
            $search_string = $request_data['search_string'];

            if($search_key == "name")
            {
                $where[] = ['name','like','%'.$search_string.'%'];
            }
            if($search_key == "email")
            {
                $where[] = ['email',$search_string];
            }
            if($search_key == "mobile")
            {
                $where[] = ['mobile',$search_string];
            }
        }

        $offset = 0;
        $limit = 10;

        if(isset($request_data['offset']) && !empty($request_data['offset']))
        {
            $offset = $request_data['offset'];
            if($offset > 1)
            {
                $offset = (($offset-1)*10)-1;
            }else{
                $offset = 0;
            }
        }

        $list = $this->hospital_doctors->where($where)
        ->orderBy('created_at','desc')
        ->offset($offset)
        ->limit($limit)
        ->get()
        ->toArray();
        $data = array("list" => $list);

        $data['data_count'] = $data_count;
        
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
            'doctor_type' => 'required',
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
                    "doctor_type" => $request_data['doctor_type'],
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
            'doctor_type' => 'required',
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
                    "doctor_type" => $request_data['doctor_type'],
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

    public function uploadList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file_data' => 'required|mimes:csv,xls',
        ]);

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $return = $this->hospital_doctor_service->uploadList($request->all());
            
        }
        return json_encode($return);
    }

}
