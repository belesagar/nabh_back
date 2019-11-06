<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NabhIndicators;
use App\Model\IndicatorsFormsFields;
use App\Model\IndicatorsFormsFieldsValidations;

class NabhIndicatorsController extends Controller
{
    public function __construct(Request $request)
    {
        $this->nabh_indicators = new NabhIndicators();
        $this->indicators_forms_fields = new IndicatorsFormsFields();
        $this->indicators_forms_fields_validation = new IndicatorsFormsFieldsValidations();
        $this->payload = auth()->user();
        // dd($this->payload);
    }

    public function indicatorsList(Request $request)
    {
        $list = $this->nabh_indicators->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function getIndicatorsInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'indicators_id' => 'required|numeric'
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
            $data_info = [];
            $data_response = $this->nabh_indicators->where('indicators_id', $request_data['indicators_id'])
                ->get();
            if (count($data_response) == 1) {
                $data_info = $data_response[0];
                $data = array("data_info" => $data_info);
                $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
            } else {
                $return = array("success" => false, "error_code" => 1, "info" => "Invalid Record.");
            }
        }
        return json_encode($return);
    }

    public function addIndicators(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|unique:nabh_indicators,name',
            'short_name' => 'required',
            'indicators_price' => 'required|numeric',
            'group_id' => 'required',
            'formula' => 'required',
            'remark' => 'required',
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
                    "name" => $request_data['name'],
                    "short_name" => $request_data['short_name'],
                    "indicators_price" => $request_data['indicators_price'],
                    "group_id" => $request_data['group_id'],
                    "formula" => $request_data['formula'],
                    "status" => $request_data['status'],
                    "remark" => $request_data['remark'],
                );
                $response = $this->nabh_indicators->create($insert_data);
                if ($response) {
                    $return = array("success" => true, "error_code" => 0, "info" => "Data added successfully");
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

    public function updateIndicators(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'short_name' => 'required',
            'indicators_price' => 'required',
            'group_id' => 'required',
            'formula' => 'required',
            'remark' => 'required',
            'status' => 'required',
            'indicators_id' => 'required|numeric',
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
                $check_data = $this->nabh_indicators->select('indicators_id')->where('name',
                    $request_data['name'])->get();

                $success = 1;
                if (count($check_data) > 0) {
                    if ($request_data['indicators_id'] != $check_data[0]['indicators_id']) {
                        $success = 0;
                    }
                }
                if ($success) {
                    $update_data = array(
                        "name" => $request_data['name'],
                        "short_name" => $request_data['short_name'],
                        "indicators_price" => $request_data['indicators_price'],
                        "group_id" => $request_data['group_id'],
                        "formula" => $request_data['formula'],
                        "status" => $request_data['status'],
                        "remark" => $request_data['remark'],
                    );
                    $response = $this->nabh_indicators->where('indicators_id',
                        $request_data['indicators_id'])->update($update_data);
                    if ($response) {
                        $return = array("success" => true, "error_code" => 0, "info" => "Data updated successfully.");
                    } else {
                        $return = array(
                            "success" => false,
                            "error_code" => 1,
                            "info" => "Something is wrong, please try again."
                        );
                    }
                } else {
                    $return = array("success" => false, "error_code" => 1, "info" => "Indicator Name already present.");
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

    public function addFormData(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'form_type' => 'required',
            'form_name' => 'required',
            'label' => 'required',
            'placeholder' => 'required',
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
                    "form_type" => $request_data['form_type'],
                    "form_name" => $request_data['form_name'],
                    "label" => $request_data['label'],
                    "placeholder" => $request_data['placeholder'],
                    "id" => $request_data['id'],
                    "class" => $request_data['class'],
                    "data_show_type" => $request_data['data_show_type'],
                    "handle_type" => $request_data['handle_type'],
                    "priority" => $request_data['priority'],
                    "form_group" => $request_data['form_group'],
                    "status" => $request_data['status'],
                );

                if (isset($request_data['indicators_ids']) && count($request_data['indicators_ids']) > 0) {
                    $insert_data['indicators_ids'] = json_encode($request_data['indicators_ids']);
                }

                $response_id = $this->indicators_forms_fields->insertGetId($insert_data);
                if ($response_id > 0) {
                    if (!empty($request_data['validations'])) {
                        $validations = [];
                        foreach ($request_data['validations'] as $validation_value) {


                            if ($validation_value == "required") {
                                $validations[] = [
                                    "required" => true,
                                    "message" => "Field is required.",
                                    "type" => "required"
                                ];
                            }
                            if ($validation_value == "minlength") {
                                $validations[] = [
                                    "minLength" => 3,
                                    "message" => "Field must be at least 3 characters long.",
                                    "type" => "minLength"
                                ];

                            }
                        }

                        if (!empty($validations)) {
                            $validation_data = array(
                                "form_id" => $response_id,
                                "validations" => json_encode($validations),
                            );

                            $validation_response = $this->indicators_forms_fields_validation->create($validation_data);
                            if (!$validation_response) {
                                $return = array(
                                    "success" => false,
                                    "error_code" => 1,
                                    "info" => "Something is wrong, please try again."
                                );
                            }
                        }

                    }

                    $return = array("success" => true, "error_code" => 0, "info" => "Data added successfully");
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

    public function getformInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'form_id' => 'required|numeric'
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
            $data_info = [];
            $data_response = $this->indicators_forms_fields->where('form_id', $request_data['form_id'])
                ->with([
                    'getValidations' => function ($query) {
                        $query->select('form_id', 'validations');
                    }
                ])->get();
            if (count($data_response) == 1) {
                $data_info = $data_response[0];
                if ($data_info['indicators_ids'] != "") {
                    $data_info['indicators_ids'] = json_decode($data_info['indicators_ids']);
                }

                if (count($data_info['getValidations']) > 0) {

                    foreach ($data_info['getValidations'] as $validations_data) {
                        if ($validations_data['validations'] != "") {
                            $validation = [];
                            $validations = json_decode($validations_data['validations'], true);

                            foreach ($validations as $validations_value) {
                                $validation[] = $validations_value['type'];
                            }
                            $data_info['validations'] = $validation;
                        }
                    }
                }
                unset($data_info['getValidations']);

                $data = array("data_info" => $data_info);
                $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
            } else {
                $return = array("success" => false, "error_code" => 1, "info" => "Invalid Record.");
            }
        }
        return json_encode($return);
    }

    public function formList(Request $request)
    {
        $list = $this->indicators_forms_fields->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function updateFormData(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'form_type' => 'required',
            'form_name' => 'required',
            'label' => 'required',
            'placeholder' => 'required',
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
                    "form_type" => $request_data['form_type'],
                    "form_name" => $request_data['form_name'],
                    "label" => $request_data['label'],
                    "placeholder" => $request_data['placeholder'],
                    "id" => $request_data['id'],
                    "class" => $request_data['class'],
                    "data_show_type" => $request_data['data_show_type'],
                    "handle_type" => $request_data['handle_type'],
                    "priority" => $request_data['priority'],
                    "form_group" => $request_data['form_group'],
                    "status" => $request_data['status'],
                );

                if (isset($request_data['indicators_ids']) && count($request_data['indicators_ids']) > 0) {
                    $update_data['indicators_ids'] = json_encode($request_data['indicators_ids']);
                }

                $response = $this->indicators_forms_fields->where('form_id',
                    $request_data['form_id'])->update($update_data);
                if ($response) {
                    if (!empty($request_data['validations'])) {
                        $validations = [];
                        foreach ($request_data['validations'] as $validation_value) {


                            if ($validation_value == "required") {
                                $validations[] = [
                                    "required" => true,
                                    "message" => "Field is required.",
                                    "type" => "required"
                                ];
                            }
                            if ($validation_value == "minlength") {
                                $validations[] = [
                                    "minLength" => 3,
                                    "message" => "Field must be at least 3 characters long.",
                                    "type" => "minLength"
                                ];

                            }
                        }

                        if (!empty($validations)) {
                            $validation_data = array(
                                "validations" => json_encode($validations),
                            );

                            $validation_response = $this->indicators_forms_fields_validation->where('form_id',
                                $request_data['form_id'])->update($validation_data);
                            if (!$validation_response) {
                                $return = array(
                                    "success" => false,
                                    "error_code" => 1,
                                    "info" => "Something is wrong, please try again."
                                );
                            }
                        }

                    }

                    $return = array("success" => true, "error_code" => 0, "info" => "Data added successfully");
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
