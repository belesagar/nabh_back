<?php

namespace App\Services\Hospital;

use App\Repositories\HospitalMenuRepository;
use App\Repositories\HospitalRoleRepository;
use App\Repositories\HospitalRolePermissionRepository;
use App\Repositories\HospitalUserRepository;

class HospitalPermissionService
{
    public function __construct(
        HospitalMenuRepository $hospital_menu_repository,
        HospitalRoleRepository $hospital_role_repository,
        HospitalRolePermissionRepository $hospital_role_permission_repository,
        HospitalUserRepository $hospital_user_repository
    ) {
        $this->hospital_menu_repository = $hospital_menu_repository;
        $this->hospital_role_repository = $hospital_role_repository;
        $this->hospital_role_permission_repository = $hospital_role_permission_repository;
        $this->hospital_user_repository = $hospital_user_repository;

        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];

        // $this->hospital_id = 1;
        // $this->hospital_user_id = 1;
    }

    public function menuList()
    {
        $where_clouse = ["status" => "ACTIVE"];
        return $this->hospital_menu_repository->getDataByCustomeWhere($where_clouse, true);
    }

    public function hospitalMenuList()
    {
        $where_clouse = [
            "hospital_id" => $this->hospital_id,
            "hospital_user_id" => $this->hospital_user_id
        ];

        $user_data = $this->hospital_user_repository->getDataByCustomeWhere($where_clouse);
        if(!empty($user_data))
        {
            $where_clouse['role_id'] = $user_data['role_id'];
        }else{
            $where_clouse['role_id'] = 0;
        }
        return $this->hospital_role_permission_repository->getDataByCustomeWhereWith($where_clouse, true);
    }

    public function hospitalRoleList($status = "")
    {
        $where_clouse = ["hospital_id" => $this->hospital_id];
        if($status != "")
        {
            $where_clouse['status'] = $status;
        }
        return $this->hospital_role_repository->getDataByCustomeWhere($where_clouse, true);
    }

    public function hospitalCheckPermission($postdata)
    {
        $where_clouse = [
                    "hospital_id" => $this->hospital_id,
                    "hospital_user_id" => $this->hospital_user_id,
                ];
                $user_data = $this->hospital_user_repository->getDataByCustomeWhere($where_clouse);

        if(!empty($user_data))
        {
            $where_clouse = [
                "hospital_id" => $this->hospital_id,
                "hospital_user_id" => $this->hospital_user_id,
                "menu_id" => $postdata['menu_id'],
                "role_id" => $user_data['role_id'],
            ];
            $permission_data = $this->hospital_role_permission_repository->getDataByCustomeWhere($where_clouse)->toArray();
            if(!empty($permission_data))
            {
                if($permission_data['view'])
                {
                    $return = array("success" => true, "error_code" => 0, "info" => "","data" => ['permission_data' => $permission_data]);
                } else {
                    $return = array("success" => false, "error_code" => 403, "info" => "You don't have permission to access this page.");
                }
            } else {
                $return = array("success" => false, "error_code" => 403, "info" => "You don't have permission to access this page.");
            }
        } else {
            $return = array("success" => false, "error_code" => 403, "info" => "You don't have permission to access this page.");
        }
        return $return;
    }
    
    public function hospitalCheckMenuPermission($menu_key_name)
    {
        $return = array("success" => true, "error_code" => 0, "info" => "");

        $menu_type_array = [
            "view_key" => "view",
            "add_key" => "add",
            "edit_key" => "edit",
            "export_key" => "export",
        ];

        $key_array = explode("-", $menu_key_name);

        if(count($key_array) > 0)
        {
            $where_clouse = [$key_array[0] => $key_array[1]]; 
            $menu_data = $this->hospital_menu_repository->getDataByCustomeWhere($where_clouse);
             dd($menu_data);
            if(!empty($menu_data))
            {
                $where_clouse = [
                    "hospital_id" => $this->hospital_id,
                    "hospital_user_id" => $this->hospital_user_id,
                ];
                $user_data = $this->hospital_user_repository->getDataByCustomeWhere($where_clouse);

                if(!empty($user_data))
                {
                    $where_clouse = [
                        "hospital_id" => $this->hospital_id,
                        "hospital_user_id" => $this->hospital_user_id,
                        "menu_id" => $menu_data->menu_id,
                        "role_id" => $user_data['role_id'],
                    ];

                    $permission_data = $this->hospital_role_permission_repository->getDataByCustomeWhere($where_clouse)->toArray();
                    
                    if(!$permission_data[$menu_type_array[$key_array[0]]])
                    {
                        $return = array("success" => false, "error_code" => 403, "info" => "You don't have permission to access this page.");
                    }
                }
            } else {
                $return = array("success" => false, "error_code" => 403, "info" => "You don't have permission to access this page.");
            }
        }
        return $return;
    }

    public function hospitalUserMenuPermissionList($postdata)
    {
        $where_clouse = [
            "hospital_id" => $this->hospital_id,
            "hospital_user_id" => $this->hospital_user_id,
            "role_id" => $postdata['role_id']
        ];
        $permission_data = $this->hospital_role_permission_repository->getDataByCustomeWhereWith($where_clouse,true);
        
        if(!empty($permission_data))
        {   
            $return = array("success" => true, "error_code" => 0, "info" => "", "data" => ["list_data" => $permission_data]);
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Invalid user");
        }
      
        return $return;
    }

    public function hospitalRoleMenuPermissionAdd($postdata)
    {
        $where_clouse = [
            "hospital_id" => $this->hospital_id,
            "hospital_user_id" => $this->hospital_user_id,
            "role_id" => $postdata['role_id'],
            "role_permission_id" => $postdata['permission_id'],
        ];

        $update_data[$postdata['type']] = $postdata['is_add']?'1':'0';
        
        $response = $this->hospital_role_permission_repository->update($update_data,$where_clouse);
        // if($response)
        // {
            $return = array("success" => true, "error_code" => 0, "info" => "Data successfully updated");
        // } else {
        //     $return = array("success" => false, "error_code" => 1, "info" => "Data not updated");
        // }
        return $return;
    }

}
