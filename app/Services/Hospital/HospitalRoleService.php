<?php

namespace App\Services\Hospital;

use App\Model\HospitalRole;

use App\Repositories\HospitalMenuRepository;
use App\Repositories\HospitalRoleRepository;
use App\Repositories\HospitalRolePermissionRepository;
use App\Repositories\HospitalUserRepository;
use App\Services\Hospital\HospitalUserService;

class HospitalRoleService
{
    public function __construct(
        HospitalRole $hospital_role,
        HospitalMenuRepository $hospital_menu_repository,
        HospitalRoleRepository $hospital_role_repository,
        HospitalRolePermissionRepository $hospital_role_permission_repository,
        HospitalUserRepository $hospital_user_repository,
        HospitalUserService $hospital_user_service
    ) {
        $this->hospital_role = $hospital_role;
        $this->hospital_menu_repository = $hospital_menu_repository;
        $this->hospital_role_repository = $hospital_role_repository;
        $this->hospital_role_permission_repository = $hospital_role_permission_repository;
        $this->hospital_user_repository = $hospital_user_repository;
        $this->hospital_user_service = $hospital_user_service;

        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
        // $this->hospital_id = 1;
        // $this->hospital_user_id = 1;
    }

    public function addRole($postdata, $user_type = "")
    {
        if(!empty($postdata))
        {
            $response = $this->hospital_role_repository->create($postdata);

            if (!empty($response)) {
                //This code for create menu permission 
                $menu_list = $this->hospital_menu_repository->all();
                $insert_data = [];
                foreach ($menu_list as $menu_value) {
                    $permission_data = [
                        "role_id" => $response->role_id,
                        "menu_id" => $menu_value->menu_id,
                        "hospital_id" => $this->hospital_id,
                        // "hospital_user_id" => $this->hospital_user_id,
                    ];

                    if($user_type == "registration")
                    {
                        $permission_data['view'] = '1';
                        $permission_data['add'] = '1';
                        $permission_data['edit'] = '1';
                        $permission_data['export'] = '1';
                    }

                    $insert_data[] = $permission_data;

                }

                if(!empty($insert_data))
                {
                    $this->hospital_role_permission_repository->insert($insert_data);
                }

                $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully", "data" => ["role_data" => $response]);
            } else {
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Something is wrong, please try again."
                );
            }
        }   else {
            $return = array(
                "success" => false,
                "error_code" => 1,
                "info" => "Postdata is missing."
            );
        }
        return $return;
    }

    public function getUserRoleData()
    {
        $return = $this->hospital_user_service->getUserData();
        if($return['success'])
        {
            $user_data = $return['data']['user_data'];
            $where_clouse = [
                "hospital_id" => $this->hospital_id,
                "role_id" => $user_data->role_id,
            ];
            $response = $this->hospital_role_repository->getDataByCustomeWhere($where_clouse);
            if(!empty($response))
            {
                $return = array("success" => true, "error_code" => 0, "info" => "","data" => ["role_data" => $response]);
            } else {
                $return = array("success" => false, "error_code" => 1, "info" => "No data found");
            }
        }
        return $return;
    }

    public function getRoleData($postdata)
    {
        $where_clouse = [
            "hospital_id" => $this->hospital_id,
            "role_id" => $postdata['role_id'],
        ];
        $response = $this->hospital_role_repository->getDataByCustomeWhere($where_clouse);
        
        if(!empty($response))
        {
            $return = array("success" => true, "error_code" => 0, "info" => "","data" => ["role_data" => $response->toArray()]);
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "No data found");
        }
        return $return;
    }

}
