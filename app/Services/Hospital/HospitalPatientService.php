<?php

namespace App\Services\Hospital;

use App\Repositories\HospitalPatientRepository;
use App\Services\CommonService;

class HospitalPatientService
{
    public function __construct(
        HospitalPatientRepository $hospital_patient_repository,
        CommonService $common_service
    ) {
        $this->hospital_patient_repository = $hospital_patient_repository;
        $this->common_service = $common_service;
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
    }

    public function validatePatient($request_data = [])
    {
        $validator = \Validator::make($request_data, [
            'patient_name' => 'required',
            'email' => 'required|email|unique:hospital_patient_table,email',
            'mobile' => 'required|numeric|unique:hospital_patient_table,mobile',
            'pid' => 'required',
            'sex' => 'required',
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

    public function addPatient($request_data = [])
    {
        $unique_id = date("his");

        $insert_data = array(
            "hospital_id" => $this->hospital_id,
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

        $response = $this->hospital_patient_repository->create($insert_data);
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

    public function uploadPatientList($request_data = [])
    {
        $required_headings = ["patient_name","pid","sex","email","mobile","dob","state","city","address"];
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
                    $return = $this->validatePatient($insert_data);
                    if(!$return['success'])
                    {
                        $value[] = $return['info'];
                        $error_data[] = $value;
                        $success = 0;
                    }else{
                        $insert_data['hospital_id'] = $this->hospital_id;
                        $insert_data['patient_reference_number'] = date("dmyHis").$key;
                        $insert_data['dob'] = date("Y-m-d",strtotime($insert_data['dob']));
                        $insert_bulk_data[] = $insert_data;
                    }
                    
                }
               
                if($success)
                {
                    $response = $this->hospital_patient_repository->insert($insert_bulk_data);
                    if (!empty($response)) {
                        $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
                    } else {
                        $return = array(
                            "success" => false,
                            "error_code" => 1,
                            "info" => "Something is wrong, please try again.",
                            "data" => $insert_bulk_data
                        );
                    }
                } else {
                    $return = array("success" => false, "error_code" => 2, "info" => "Error found in data.", "data" => ["error_data" => $error_data]);
                }
            }else{
                $return = array("success" => false, "error_code" => 1, "info" => "Invalid data present in excel.");
            }
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "No data present in excel.");
        }
        return $return;
    }
    
}
