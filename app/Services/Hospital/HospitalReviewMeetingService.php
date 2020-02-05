<?php

namespace App\Services\Hospital;

use App\Model\HospitalReviewMeeting;
use App\Repositories\HospitalReviewMeetingRepository;
use App\Repositories\HospitalRoleRepository;
use App\Repositories\HospitalRolePermissionRepository;
use App\Repositories\HospitalUserRepository;

class HospitalReviewMeetingService
{
    public function __construct(
        HospitalReviewMeeting $hospital_review_meeting,
        HospitalReviewMeetingRepository $hospital_review_meeting_repository,
        HospitalRolePermissionRepository $hospital_role_permission_repository,
        HospitalUserRepository $hospital_user_repository
    ) {
        $this->hospital_review_meeting = $hospital_review_meeting;
        $this->hospital_review_meeting_repository = $hospital_review_meeting_repository;
        $this->hospital_role_permission_repository = $hospital_role_permission_repository;
        $this->hospital_user_repository = $hospital_user_repository;

        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];

        // $this->hospital_id = 1;
        // $this->hospital_user_id = 1;
    }

    public function list($request_data)
    {
        $where = [
            "hospital_id" => $this->hospital_id,
            "hospital_user_id" => $this->hospital_user_id
        ];

        $data_count = $this->hospital_review_meeting_repository->getDataByCustomeWhere($where, true)->count();
        
        if(isset($request_data['meeting_status']) && $request_data['meeting_status'] != "")
        {
            $where['meeting_status'] = $request_data['meeting_status'];
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

        $list = $this->hospital_review_meeting->where($where)
        ->orderBy('created_at','desc')
        ->offset($offset)
        ->limit($limit)
        ->get()
        ->toArray();
        $data = array("list" => $list);

        $data['data_count'] = $data_count;

        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return $return;
    }

}
