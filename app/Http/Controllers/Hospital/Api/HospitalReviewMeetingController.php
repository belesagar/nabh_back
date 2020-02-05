<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Hospital\HospitalReviewMeetingService;
use App\Repositories\HospitalReviewMeetingRepository;

class HospitalReviewMeetingController extends Controller
{
    public function __construct(
        HospitalReviewMeetingService $hospital_review_meeting_service,
        HospitalReviewMeetingRepository $hospital_review_meeting_repository
    )
    {
        $this->hospital_review_meeting_service = $hospital_review_meeting_service;
        $this->hospital_review_meeting_repository = $hospital_review_meeting_repository;
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];

        // $this->hospital_id = 1;
        // $this->hospital_user_id = 1;
    }

    public function List(Request $request)
    {
        $return = $this->hospital_review_meeting_service->list($request->all());

        return json_encode($return);
    }

    public function getInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'meeting_id' => 'required|numeric'
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

            $where_clouse = [
                'review_meeting_reference_number' => $request_data['meeting_id'],
                'hospital_id' => $this->hospital_id
            ];

            $data_info = $this->hospital_review_meeting_repository->getDataByCustomeWhere($where_clouse);
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
            'review_meeting_type' => 'required',
            'review_meeting_title' => 'required',
            'purpose_review_meeting' => 'required',
            'review_meeting_date' => 'required',
            'location' => 'required',
            'review_meeting_start_date' => 'required',
            'review_meeting_end_date' => 'required',
            'review_meeting_start_time' => 'required',
            'review_meeting_end_time' => 'required'
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
                    "hospital_id" => $this->hospital_id,
                    "hospital_user_id" => $this->hospital_user_id,
                    "review_meeting_reference_number" => $unique_id,
                    "review_meeting_type" => $request_data['review_meeting_type'],
                    "review_meeting_title" => $request_data['review_meeting_title'],
                    "purpose_review_meeting" => $request_data['purpose_review_meeting'],
                    "review_meeting_date" => $request_data['review_meeting_date'],
                    "location" => $request_data['location'],
                    "review_meeting_start_date" => date("Y-m-d",strtotime($request_data['review_meeting_start_date'])),
                    "review_meeting_end_date" => date("Y-m-d",strtotime($request_data['review_meeting_end_date'])),
                    "review_meeting_start_time" => date("H:i:s",strtotime($request_data['review_meeting_start_time'])),
                    "review_meeting_end_time" => date("H:i:s",strtotime($request_data['review_meeting_end_time'])),
//                    "status" => $request_data['status']
                );

                $response = $this->hospital_review_meeting_repository->create($insert_data);
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
            'review_meeting_type' => 'required',
            'review_meeting_title' => 'required',
            'purpose_review_meeting' => 'required',
            'review_meeting_date' => 'required',
            'location' => 'required',
            'review_meeting_start_date' => 'required',
            'review_meeting_end_date' => 'required'
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
                "review_meeting_type" => $request_data['review_meeting_type'],
                "review_meeting_title" => $request_data['review_meeting_title'],
                "purpose_review_meeting" => $request_data['purpose_review_meeting'],
                "review_meeting_date" => $request_data['review_meeting_date'],
                "location" => $request_data['location'],
                "review_meeting_start_date" => $request_data['review_meeting_start_date'],
                "review_meeting_end_date" => $request_data['review_meeting_end_date'],
//                "status" => $request_data['status']
            );

            $where_clouse = [
                "hospital_id" => $this->hospital_id,
                "hospital_user_id" => $this->hospital_user_id,
                "review_meeting_reference_number" => $request_data['meeting_id'],
            ];

            $response = $this->hospital_review_meeting_repository->update($update_data, $where_clouse);
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

}
