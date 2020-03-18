<?php

namespace App\Services\Hospital;

use App\Repositories\HospitalDoctorRepository;
use App\Services\CommonService;

class HospitalDoctorService
{
    public function __construct(
        HospitalDoctorRepository $hospital_doctor_repository,
        CommonService $common_service
    ) {
        $this->hospital_doctor_repository = $hospital_doctor_repository;
        $this->common_service = $common_service;
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
    }

    public function validateData($request_data = [])
    {
        $validator = \Validator::make($request_data, [
            'name' => 'required',
            'email' => 'required|email|unique:hospital_doctors,email',
            'mobile' => 'required|numeric|unique:hospital_doctors,mobile',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
            'dob' => 'date',
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
            // if (!empty($this->payload)) {
                
                $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
            // } else {
            //     $return = array(
            //         "success" => false,
            //         "error_code" => 1,
            //         "info" => "Something is wrong, please try again."
            //     );
            // }

        }

        return $return;
    }

    public function add($request_data = [])
    {
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

        $response = $this->hospital_doctor_repository->create($insert_data);
        if (!empty($response)) {
            $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
        } else {
            $return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "Something is wrong, please try again."
            );
        }

        return $return;
    }

    public function uploadList($request_data = [])
    {
        $required_headings = ["name","email","mobile","city","state","address","dob"];
        $heading_data = $required_headings;
        //This code for getting excel data
        $excel_data = $request_data['excel_data'];

        if(count($excel_data) > 0)
        {
            //This for creating headings
            foreach ($excel_data[0] as &$index_value) {
                $index_value = str_replace(" ","_",strtolower($index_value));
            }

            if(empty(array_diff($required_headings,$excel_data[0])))
            {
                $success = 1;
                $insert_bulk_data = [];
                $heading_data[] = "error";
                $error_data[] = $heading_data;
                unset($excel_data[0]);

                foreach ($excel_data as $key => $value) {
                    $insert_data=array_combine($required_headings,$value);
                    $return = $this->validateData($insert_data);
                    if(!$return['success'])
                    {
                        $value[] = $return['info'];
                        $error_data[] = $value;
                        $success = 0;
                    }else{
                        $insert_data['hospital_id'] = $this->hospital_id;
                        $insert_data['doctor_unique_id'] = date("ymdhis").$key;
                        $insert_data['dob'] = date("Y-m-d",strtotime($insert_data['dob']));
                        $insert_bulk_data[] = $insert_data;
                    }
                    
                }
                
                if($success)
                {
                    $response = $this->hospital_doctor_repository->insert($insert_bulk_data);
                    if (!empty($response)) {
                        $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
                    } else {
                        $return = array(
                            "success" => false,
                            "error_code" => 1,
                            "info" => "Something is wrong, please try again."
                        );
                    }
                } else {
                    $return = array("success" => false, "error_code" => 2, "info" => "Error found in data.", "data" => ["error_data" => $error_data]);
                }
            }else{
                $return = array("success" => false, "error_code" => 1, "info" => "Invalid column is added.");
            }
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "No data Found.");
        }
        return $return;
    }
    
}
