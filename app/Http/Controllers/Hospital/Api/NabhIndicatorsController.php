<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\IndicatorsData;
use App\Model\AssignIndicators;
use App\Model\IndicatorsDataHistory;
use App\Model\NabhIndicators;
use App\Model\HospitalRegistration;
use App\Model\HospitalUsersIndicators;
use App\Model\IndicatorsFormsFields;
use App\Model\IndicatorsFormsFieldsValidations;
use App\Model\HospitalDoctors;
use App\Model\HospitalPatient;
use App\Model\HospitalOtInformation;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Http\Controllers\Excel\DataExportController;
use Illuminate\Support\Facades\Storage;

class NabhIndicatorsController extends Controller
{
    public function __construct(Request $request)
    {
        $this->indicators_data = new IndicatorsData();
        $this->indicators_data_history = new IndicatorsDataHistory();
        $this->assign_indicators = new AssignIndicators();
        $this->nabh_indicators = new NabhIndicators();
        $this->hospiatl_registration = new HospitalRegistration();
        $this->hospital_users_indicators = new HospitalUsersIndicators();
        $this->indicators_forms_fields = new IndicatorsFormsFields();
        $this->indicators_forms_fields_validations = new IndicatorsFormsFieldsValidations();
        $this->hospital_doctors = new HospitalDoctors();
        $this->hospital_patient = new HospitalPatient();
        $this->hospital_ot_information = new HospitalOtInformation();

        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
    }

    public function indicatorsList(Request $request)
    {
        // \DB::enableQueryLog();
        $request_data = $request->all();
        $where = ['status' => 'ACTIVE'];
        if(isset($request_data['search_indicators']) && $request_data['search_indicators'] != "")
        {
            // $where = ['indicators_id' => $request_data['search_indicators']];
            $where[] = ['name','like','%'.$request_data['search_indicators'].'%'];
        }

        $list = $this->nabh_indicators->where($where)
        ->get()
        ->toArray();
//         $query = \DB::getQueryLog();

// $query = end($query);

// print_r($query);
// exit;
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function getIndicatorsInput(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'indicator_id' => 'required',
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

            //Indicators Details
            $indicators_details = $this->getIndicatorsDetail($request_data['indicator_id']);
           
            $formdata = $this->getFormField($request_data);

            /* $indicators_input = [];
             $form_name_array = [];

             $indicator_data = $this->indicators_forms_fields->select("form_id", "form_type as type",
                 "form_name as input_name", "label", "placeholder", "id", "class", "data_show_type", "handle_type")->with([
                 'getValidations' => function ($query) {
                     $query->select('form_id', 'validations');
                 }
             ])->where([
                 ['indicators_ids', 'like', '%' . $indicator_id . '%'],
                 ['status', 'ACTIVE']
             ])->get()->toArray();


             foreach ($indicator_data as $key => $value) {
                 if ($value['handle_type'] == "" || $value['handle_type'] == "outside") {
                     $value['validation'] = [];
                     $value['required'] = false;
                     foreach ($value['get_validations'] as $validation_data) {
                         $value['validation'] = json_decode($validation_data['validations'], true);

                         //For required
                         foreach ($value['validation'] as $key => $validation_value) {
                             if ($validation_value['type'] == "required") {
                                 $value['required'] = $validation_value['required'];
                             }
                         }

                     }
                     unset($value['form_id']);
                     unset($value['get_validations']);

                     $value["data"] = "";
                     $value["data_value"] = [];

                     //For data type
                     //doctor, ot, yesno, time_select
                     if ($value['data_show_type'] == "doctor") {
                         $doctor_list = $this->hospital_doctors->select('doctor_id', 'name')->where([
                             ["hospital_id", $this->hospital_id],
                             ["status", "ACTIVE"]
                         ])->get()->toArray();

                         $value["data_value"] = \Helpers::convertKeyValuePair($doctor_list, 'doctor_id', 'name');
                     }
                     if ($value['data_show_type'] == "ot") {
                         $ot_list = $this->hospital_ot_information->select('ot_id', 'ot_name')->where([
                             ["hospital_id", $this->hospital_id],
                             ["status", "ACTIVE"]
                         ])->get()->toArray();

                         $value["data_value"] = \Helpers::convertKeyValuePair($ot_list, 'ot_id', 'ot_name');
                     }
                     if ($value['data_show_type'] == "time_select") {
                         $time_select_array = ["00:30" => "00:30"];

                         $value["data_value"] = $time_select_array;
                     }
                     if ($value['data_show_type'] == "yesno") {
                         $value["data_value"] = array("Yes" => "Yes", "No" => "No");
                     }

                     $indicators_input[] = $value;
                     $form_name_array[] = $value['input_name'];
                 }
             }*/

            $return = array(
                "success" => true,
                "error_code" => 0,
                "info" => "",
                "data" => [
                    "indicators_input" => $formdata['indicators_input'],
                    "form_name_array" => $formdata['form_name_array'],
                    "indicators_details" => !empty($indicators_details)?$indicators_details[0]:[],
                ]
            );
        }
        return response()->json($return);
    }

    public function getFormField($postdata)
    {
        $indicators_input = [];
        $form_name_array = [];
        $all_form_name_array = [];

        $indicator_data = $this->indicators_forms_fields->select("form_id", "form_type as type",
            "form_name as input_name", "label", "placeholder", "id", "class", "data_show_type", "handle_type",
            'priority','form_group','input_hide_id')->with([
            'getValidations' => function ($query) {
                $query->select('form_id', 'validations');
            }
        ])->where([
            ['indicators_ids', 'like', '%"' . $postdata['indicator_id'] . '"%'],
            ['status', 'ACTIVE']
        ])->orderBy('priority', 'asc')->get()->toArray();
       
        foreach ($indicator_data as $key => $value) {
            if ($value['handle_type'] == "" || $value['handle_type'] == "outside") {
                $value['validation'] = [];
                $value['required'] = false;
                foreach ($value['get_validations'] as $validation_data) {
                    $value['validation'] = json_decode($validation_data['validations'], true);

                    //For required
                    foreach ($value['validation'] as $key => $validation_value) {
                        if ($validation_value['type'] == "required") {
                            $value['required'] = $validation_value['required'];
                        }
                    }

                }
                // unset($value['form_id']);
                unset($value['get_validations']);

                $value["data"] = "";
                $value["data_value"] = [];

                //For data type
                //doctor, ot, yesno, time_select
                if ($value['data_show_type'] == "doctor") {
                    $doctor_list = $this->hospital_doctors->select('doctor_id', 'name')->where([
                        ["hospital_id", $this->hospital_id],
                        ["status", "ACTIVE"]
                    ])->get()->toArray();

                    $value["data_value"] = \Helpers::convertKeyValuePair($doctor_list, 'doctor_id', 'name');
                }
                if ($value['data_show_type'] == "ot") {
                    $ot_list = $this->hospital_ot_information->select('ot_id', 'ot_name')->where([
                        ["hospital_id", $this->hospital_id],
                        ["status", "ACTIVE"]
                    ])->get()->toArray();

                    $value["data_value"] = \Helpers::convertKeyValuePair($ot_list, 'ot_id', 'ot_name');
                }

                if ($value['data_show_type'] == "patient") {
                    $patient_list = $this->hospital_patient->select('patient_reference_number', 'patient_name')->where([
                        ["hospital_id", $this->hospital_id],
                        ["status", "ACTIVE"]
                    ])->get()->toArray();

                    $value["data_value"] = \Helpers::convertKeyValuePair($patient_list, 'patient_reference_number',
                        'patient_name');
                }

                if ($value['data_show_type'] == "time_select") {
                    $time_select_array = ["00:30" => "00:30"];

                    $value["data_value"] = \Helpers::convertKeyIDTextPair($time_select_array);
                }
                if ($value['data_show_type'] == "yesno") {
                    $yesno_array = array("Yes" => "Yes", "No" => "No");
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "on_off_select") {
                    $yesno_array = array("On" => "On", "Off" => "Off");
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "yesnonotknown") {
                    $yesno_array = array("Yes" => "Yes", "No" => "No", "Not Known" => "Not Known");
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "injury_select") {
                    $yesno_array = array("Superficial" => "Superficial", "Deep" => "Deep");
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "usednotused") {
                    $yesno_array = array("Used" => "Used", "Not Used" => "Not Used");
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }
                
                if ($value['data_show_type'] == "product_select") {
                    $yesno_array = array("PCV" => "PCV", "FFP" => "FFP", "SDP" => "SDP", "RDP" => "RDP");
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "root_cause") {
                    $yesno_array = array(
                        "WRONG DRUG WRONG PATIENT" => "WRONG DRUG WRONG PATIENT", 
                        "WRONG DOSE" => "WRONG DOSE", 
                        "WRONG ROUTE" => "WRONG ROUTE", 
                        "WRONG TIME" => "WRONG TIME",
                        "WRONG ROUTE OF ADMINISTRATION" => "WRONG ROUTE OF ADMINISTRATION",
                        "DRUG INTERACTION" => "DRUG INTERACTION",
                        "FOOD DRUG INTERACTION" => "FOOD DRUG INTERACTION",
                        "ALLERGY" => "ALLERGY",
                        "ANY OTHER" => "ANY OTHER",
                    );
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "ward_name") {
                    $yesno_array = array(
                        "SEMIPRIVATE ROOM" => "SEMIPRIVATE ROOM", 
                        "GENERAL MALE WARD" => "GENERAL MALE WARD", 
                        "GENERAL FEMALE WARD" => "GENERAL FEMALE WARD", 
                        "SEMIPRIVATE ROOM" => "SEMIPRIVATE ROOM",
                        "DELUX ROOM" => "DELUX ROOM",
                        "SUPER DELUX ROOM" => "SUPER DELUX ROOM",
                        "ICU" => "ICU",
                        "NICU" => "NICU",
                        "EMERGENCY ROOM" => "EMERGENCY ROOM",
                        "RECOVERY ROOM" => "RECOVERY ROOM",
                    );
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "final_outcome_of_adr") {
                    $yesno_array = array(
                        "PATIENT DIED" => "PATIENT DIED", 
                        "PATIENT NEEDED LIFE SUSTAINING INTERVENTIONS" => "PATIENT NEEDED LIFE SUSTAINING INTERVENTIONS", 
                        "PATIENT SUFFERED PERMENANT HARM" => "PATIENT SUFFERED PERMENANT HARM", 
                        "PATIENT SUFFERED FROM TEMPORARY HARM AND NEEDED INITIAL OR PROLONGED HOSPITALIZATION" => "PATIENT SUFFERED FROM TEMPORARY HARM AND NEEDED INITIAL OR PROLONGED HOSPITALIZATION",
                        "PATIENT SUFFERED FROM TEMPORARY HARM THAT NEEDED INTERVENTION" => "PATIENT SUFFERED FROM TEMPORARY HARM THAT NEEDED INTERVENTION",
                        "PATIENT NEEDED MONITORING OR TEMPORARY  INTERVENTION TO PRECLUDE HARM" => "PATIENT NEEDED MONITORING OR TEMPORARY  INTERVENTION TO PRECLUDE HARM",
                        "PATIENT SUFFERED NO HARM" => "PATIENT SUFFERED NO HARM",
                    );
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "blood_group") {
                    $yesno_array = array("A POSITIVE" => "A POSITIVE", "B POSITIVE" => "B POSITIVE", "AB POSITIVE" => "AB POSITIVE", "O POSITIVE" => "O POSITIVE", "A NEGATIVE" => "A NEGATIVE", "B NEGATIVE" => "B NEGATIVE", "AB NEGATIVE" => "AB NEGATIVE", "O NEGATIVE" => "O NEGATIVE");
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "no_counter_select") {
                    $yesno_array = array(
                        "1" => "1", 
                        "2" => "2",
                        "3" => "3",
                        "4" => "4",
                        "5" => "5",
                    );
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "work_time_period") {
                    $data_array = array(
                        "LESS THAN ONE MONTH" => "LESS THAN ONE MONTH", 
                        "LESS THAN 6 MONTHS" => "LESS THAN 6 MONTHS",
                        "MORE THAN 6 MONTHS" => "MORE THAN 6 MONTHS",
                    );
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($data_array);
                }
                

                if ($value['data_show_type'] == "test_advice_select") {
                    $yesno_array = array(
                        "HEMOGRAM" => "HEMOGRAM", 
                        "SR BILIRUBIN" => "SR BILIRUBIN",
                        "SR ELECTROLYTES" => "SR ELECTROLYTES",
                        "SR CREATININE" => "SR CREATININE",
                        "CRP" => "CRP",
                        "PS FOR MP" => "PS FOR MP",
                        "Bsl random" => "Bsl random",
                        "Other" => "Other",
                    );
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($yesno_array);
                }

                if ($value['data_show_type'] == "cause_of_injury") {
                    $data_array = array(
                        "SOMEONE PUSH YOU" => "SOMEONE PUSH YOU", 
                        "WHILE OPENING THE SHARP" => "WHILE OPENING THE SHARP", 
                        "WHILE RECAPPING THE SHARP" => "WHILE RECAPPING THE SHARP", 
                        "WHILE GIVING THE SHARP TO ANOTHER PERSON" => "WHILE GIVING THE SHARP TO ANOTHER PERSON", 
                        "WHILE CUTTING THE NEEDLE" => "WHILE CUTTING THE NEEDLE", 
                        "OTHER" => "OTHER"
                    );
                    $value["data_value"] = \Helpers::convertKeyIDTextPair($data_array);
                }
                
                if ($value['data_show_type'] == "rate1to5") {
                    $rate1to5_array = array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5");
                    $value["data_value"] = $rate1to5_array;
                }
                if ($value['data_show_type'] == "source_of_information") {
                    $source_array = array(
                        "OLD PATIENT" => "OLD PATIENT",
                        "PRACTO" => "PRACTO",
                        "REF. DOCTORS" => "REF. DOCTORS",
                        "OTHER" => "OTHER"
                    );
                    $value["data_value"] = $source_array;
                }
                $value['is_form_group'] = 0;
                if($value['form_group'] != "")
                {
                    $form_group = json_decode($value['form_group'],true);
                    $value['form_group'] = $form_group;
                    $value['is_form_group'] = 1;
                    foreach ($form_group as $form_group_value) {
                        $value['form_group_lable'][] = $form_group_value;
                    }
                }

                //This for adding hide for name
                $value['input_hide_id_status'] = 1;
                // if($value['input_hide_id'] != "")
                // {
                //     $value['input_hide_id_status'] = 0;
                // }

                $indicators_input[] = $value;
                $form_name_array[] = $value['input_name'];
            }

            $all_form_name_array[] = $value;
        }
        $data = [
            'all_form_name_array' => $all_form_name_array,
            'form_name_array' => $form_name_array,
            'indicators_input' => $indicators_input
        ];
        return $data;
    }

    public function savendicatorsData(Request $request)
    {

        $request_data = $request->all();
        $insert_data = $request_data;
        unset($insert_data['indicator_id']);

        $updated_data = json_encode($insert_data);

        $postdata = ['indicator_id' => $request_data['indicator_id']];
        $formdata = $this->getFormField($postdata);

        foreach ($formdata['all_form_name_array'] as $form_data) {
            if ($form_data['handle_type'] == "inside") {
                $insert_data[$form_data['input_name']] = $this->hospital_user_id;
            }

            if($form_data['form_group'] != "")
            {
                $insert_data[$form_data['input_name']] = json_encode($insert_data[$form_data['input_name']]);
            }
        }

        $insert_data['hospital_id'] = $this->payload['hospital_id'];
        $indicators_unique_id = date("His");
        $insert_data['indicators_unique_id'] = $indicators_unique_id;
        $indicator_id = $request_data['indicator_id'];
        $insert_data['indicators_id'] = $indicator_id;
        if (!empty($request_data['date'])) {
            $insert_data['date'] = date("Y-m-d", strtotime($request_data['date']));
        }

        $response_id = $this->indicators_data->insertGetId($insert_data);
        if ($response_id) {
            $indicators_history_data = array(
                "hospital_id" => $this->payload['hospital_id'],
                "indicator_id" => $indicator_id,
                "indicator_data_id" => $indicators_unique_id,
                "updated_by_id" => $this->hospital_user_id,
                "updated_data" => $updated_data,
            );

            $indicators_history_response = $this->indicators_data_history->create($indicators_history_data);

            $return = array("success" => true, "error_code" => 0, "info" => "Data Successfully Added.");
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Something is wrong, Please try again.");
        }

        return response()->json($return);
    }

    public function getIndicatorsList(Request $request)
    {

        $hospital_data = $this->hospiatl_registration->select('hospital_unique_id', 'hospital_name',
            'email')->where('status', 'ACTIVE')
            ->where('hospital_id', $this->hospital_id)
            ->get();

        if ($hospital_data[0]['email'] == $this->payload['email']) {
            $indicators_list = $this->assign_indicators->with(['indicators' => function($query){
                $query->where('status', "ACTIVE");
            }])->where("hospital_id",$this->hospital_id)->get();
            
        } else {
            $indicators_list = $this->hospital_users_indicators->with(['indicators' => function($query){
                $query->where('status', "ACTIVE");
            }])->where("hospital_id",
                $this->hospital_id)->where("hospital_user_id", $this->hospital_user_id)->get();
        }
        $return = array("success" => true, "error_code" => 0, "info" => "", "data" => $indicators_list);
        return response()->json($return);
    }

    public function getIndicatorsDetail($indicator_id = "")
    {
        $where = ["status" => "ACTIVE"];
        if($indicator_id != "")
        {
            $where['indicators_id'] = $indicator_id;
        }
        $indicators_details = $this->nabh_indicators->where($where)->get();
        
        return $indicators_details;
    }

    public function getIndicatorFormDataList(Request $request)
    {
        $request_data = $request->all();
        $hospital_id = $this->payload['hospital_id'];
        $indicator_id = $request_data['indicator_id'];
        $data = [];

        $indicators_details = $this->getIndicatorsDetail($indicator_id);
        $data['indicators_details'] = !empty($indicators_details)?$indicators_details[0]:[];
       

        $return = $this->getIndicatorColumns($indicator_id);
       if ($return['success']) {
        if (!empty($return['data']['column_data'])) {
        $indicators_columns = [];
        if (isset($request_data['type']) && $request_data['type'] == "excel") {
            $indicators_columns[] = "indicators_unique_id";
            $indicators_columns[] = "indicators_id";
            $indicators_columns = array_merge($indicators_columns, $return['data']['column_data']);
        } else {
            $indicators_columns = ['*'];
        }

        $indicator_data = $this->indicators_data->select($indicators_columns)->where('hospital_id',
            $hospital_id)->where('indicators_id', $indicator_id)->orderBy('created_at', 'desc')->get();
        
        if (count($indicator_data) > 0) {
            //This for download excel
            if (isset($request_data['type']) && $request_data['type'] == "excel") {
                $heading_array = array_keys($indicator_data[0]->toArray());
                $excel_data = ["excel_data" => $indicator_data, "heading_array" => $heading_array];

                $file_name = $hospital_id . $request_data['indicator_id'] . $indicator_data[0]->indicators_unique_id . ".xlsx";

                //Excel::store(new DataExportController($excel_data), "public/hospital/excel/" . $file_name);
                $file_url = Storage::url('hospital/excel/' . $file_name);

                $data['file_url'] = $file_url;

            } else {

                $data['list_data'] = $indicator_data;
            }
            $return = array("success" => true, "error_code" => 0, "info" => "", "data" => $data);
        } else {
            $return = array("success" => true, "error_code" => 0, "info" => "", "data" => $data);
        }
        } else {
           $return = array("success" => false, "error_code" => 1, "info" => "Indicators data not present.");
       }
       } else {
           $return = array("success" => false, "error_code" => 1, "info" => "Indicators data not present.");
       }

        return response()->json($return);
    }

    public function ListofAcceptIndicators(Request $request)
    {
        $list = $this->assign_indicators->select("assign_indicators.indicators_id")
        ->where([
            ['assign_indicators.status', 'ACTIVE'],
            ['ni.status', 'ACTIVE'],
            ['assign_indicators.hospital_id', $this->hospital_id]
        ])
        ->join('nabh_indicators as ni', 'ni.indicators_id', '=', 'assign_indicators.indicators_id')
        ->get()
        ->toArray();
        $data = array("list" => $list);


        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function AcceptIndicators(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'indicator_id' => 'required|numeric',
            'is_add' => 'required'
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
            extract($request_data);

            $check_indicator_availability = $this->assign_indicators->where([
                    ['hospital_id', $this->hospital_id],
                    ["indicators_id", $indicator_id]
            ])->first();
            
            if(empty($check_indicator_availability))
            {
                if($is_add)
                {
                    $insert_data_array = array(
                        "hospital_id" => $this->hospital_id,
                        "indicators_id" => $indicator_id,
                    );
                    $response_id = $this->assign_indicators->insert($insert_data_array);  
                }
            }else{
                if($is_add)
                {
                    $updated_data = ['status' => 'ACTIVE'];
                }else{
                    $updated_data = ['status' => 'INACTIVE'];
                }
                $response = $this->assign_indicators->where([
                    ['hospital_id', $this->hospital_id],
                    ["indicators_id", $indicator_id]
                ])->update($updated_data);
            }
            $return = array("success" => true, "error_code" => 0, "info" => "Operation Successfully Done");
        }
        
        // $selected_indicators = [];
        // $check_indicator_selection = true;
        // foreach ($request_data as $key => $value) {
        //     if ($value != "") {
        //         $check_indicator_selection = false;
        //         $selected_indicators[] = $key;
        //     }
        // }

        // if ($check_indicator_selection) {
        //     $return = array("success" => false, "error_code" => 1, "info" => "Please Select the indicators.");
        // } else {
        //     $insert_data_array = [];
        //     foreach ($selected_indicators as $indicators_value) {
        //         $check_indicators_availability = $this->assign_indicators->where([
        //             ['hospital_id', $this->hospital_id],
        //             ["indicators_id", $indicators_value]
        //         ])->get();
        //         if (count($check_indicators_availability) == 0) {
        //             $insert_data_array[] = array(
        //                 "hospital_id" => $this->hospital_id,
        //                 "indicators_id" => $indicators_value,
        //             );
        //         }
        //     }

        //     if (count($insert_data_array) > 0) {
        //         $response_id = $this->assign_indicators->insert($insert_data_array);
        //         if ($response_id > 0) {
        //             $return = array("success" => true, "error_code" => 0, "info" => "Indicators Added Successfully.");
        //         } else {
        //             $return = array(
        //                 "success" => false,
        //                 "error_code" => 1,
        //                 "info" => "Something is wrong, Please try again."
        //             );
        //         }
        //     } else {
        //         $return = array("success" => true, "error_code" => 0, "info" => "Indicators Added Successfully.");
        //     }

        // }

        return json_encode($return);
    }

    public function getIndicatorFormData(Request $request)
    {
        $request_data = $request->all();
        $hospital_id = $this->payload['hospital_id'];

        $indicator_form_data = $this->indicators_data->where([
            ['hospital_id', $hospital_id],
            ['indicators_id', $request_data['indicator_id']],
            ["indicators_unique_id", $request_data['dataid']]
        ])->first();

        if (!empty($indicator_form_data)) {
            $return = array("success" => true, "error_code" => 0, "info" => "", "data" => $indicator_form_data);
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Invalid Data");
        }
        return response()->json($return);
    }

    public function updateIndicatorFormData(Request $request)
    {

        $request_data = $request->all();
        $insert_data = $request_data;

        $hospital_unique_id = $insert_data['dataid'];
        $indicator_id = $insert_data['indicator_id'];

        unset($insert_data['dataid']);
        unset($insert_data['indicator_id']);

        $postdata = ['indicator_id' => $indicator_id];
        $formdata = $this->getFormField($postdata);

        foreach ($formdata['all_form_name_array'] as $form_data) {
            if ($form_data['handle_type'] == "inside") {
                $insert_data[$form_data['input_name']] = $this->hospital_user_id;
            }
        }

        $updated_data = json_encode($insert_data);

        $response_id = $this->indicators_data->where([
            ['hospital_id', $this->payload['hospital_id']],
            ["indicators_id", $indicator_id],
            ['indicators_unique_id', $hospital_unique_id]
        ])->update($insert_data);
        if ($response_id) {
            $indicators_history_data = array(
                "hospital_id" => $this->payload['hospital_id'],
                "indicator_id" => $indicator_id,
                "indicator_data_id" => $hospital_unique_id,
                "updated_by_id" => $this->hospital_user_id,
                "updated_data" => $updated_data,
            );

            $indicators_history_response = $this->indicators_data_history->create($indicators_history_data);

            $return = array("success" => true, "error_code" => 0, "info" => "Data Successfully Added.");
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Something is wrong, Please try again.");
        }

        return response()->json($return);
    }

    public function getIndicatorFormDataDetails(Request $request)
    {
        $request_data = $request->all();
        $hospital_id = $this->payload['hospital_id'];
        $indicator_id = $request_data['indicator_id'];

        /*$indicator_data = $this->indicators_forms_fields->select("form_name as input_name","label")->where([
            ['indicators_ids','like', '%'.$indicator_id.'%'],
            ['status','ACTIVE']
        ])->get()->toArray();

        foreach ($indicator_data as $key => $value) {
            $columns[] = $value['input_name']." as ".$value['label'];
        }*/

        $return = $this->getIndicatorColumns($indicator_id);
        if ($return['success']) {
            $columns = $return['data']['column_data'];
            $columns[] = "indicators_unique_id";
            $columns[] = "indicators_id";

            $indicator_form_data = $this->indicators_data->select($columns)->where([
                ['hospital_id', $hospital_id],
                ["indicators_unique_id", $request_data['dataid']]
            ])->first();

            if (!empty($indicator_form_data)) {
                $return = array("success" => true, "error_code" => 0, "info" => "", "data" => $indicator_form_data);
            } else {
                $return = array("success" => false, "error_code" => 1, "info" => "Invalid Data");
            }
        }
        return response()->json($return);
    }

    //This for getiing indicator columns
    private function getIndicatorColumns($indicator_id = 0)
    {
        if ($indicator_id > 0) {
            $columns = [];
            $indicator_data = $this->indicators_forms_fields->select("form_name as input_name", "label")->where([
                ['indicators_ids', 'like', '%"' . $indicator_id . '"%'],
                ['status', 'ACTIVE']
            ])->get()->toArray();

            foreach ($indicator_data as $key => $value) {
                $columns[] = $value['input_name'] . " as " . $value['label'];
            }

            $return = array("success" => true, "error_code" => 0, "info" => "", "data" => ["column_data" => $columns]);
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Invalid Indicator");
        }

        return $return;
    }

}
