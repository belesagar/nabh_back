<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalPatient;
use App\Model\VirtualHospital;
use App\Repositories\HospitalRoleRepository;
use App\Repositories\HospitalMenuRepository;
use App\Repositories\HospitalRolePermissionRepository;
use App\Services\Hospital\HospitalPermissionService;
use App\Services\Hospital\HospitalRoleService;

class HospitalRoleController extends Controller
{
    public function __construct(
        HospitalRoleRepository $hospital_role_repository,
        HospitalMenuRepository $hospital_menu_repository,
        HospitalRolePermissionRepository $hospital_role_permission_repository,
        HospitalPermissionService $hospital_permission_service,
        HospitalRoleService $hospital_role_service
    )
    {
        $this->hospital_patient = new HospitalPatient(); 
        $this->virtual_hospital = new VirtualHospital();
        $this->hospital_role_repository = $hospital_role_repository;
        $this->hospital_menu_repository = $hospital_menu_repository;
        $this->hospital_role_permission_repository = $hospital_role_permission_repository;
        $this->hospital_permission_service = $hospital_permission_service;
        $this->hospital_role_service = $hospital_role_service;

        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];

        $this->hospital_id = 1;
        $this->hospital_user_id = 1;
    }


    public function List(Request $request)
    {
        $where = [["hospital_id", $this->hospital_id]];
        
        $data_count = $this->hospital_patient->where($where)->count();

        $request_data = $request->all();

        $where[] = ['status',$request_data['status']];

        //Filter option
        if(isset($request_data['search_string']) && $request_data['search_string'] != "")
        {
            $search_key = $request_data['search_key'];
            $search_string = $request_data['search_string'];

            if($search_key == "patient_name")
            {
                $where[] = ['patient_name','like','%'.$search_string.'%'];
            }
            if($search_key == "email")
            {
                $where[] = ['email',$search_string];
            }
            if($search_key == "mobile")
            {
                $where[] = ['mobile',$search_string];
            }
            if($search_key == "pid")
            {
                $where[] = ['pid',$search_string];
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
        
        $list = $this->hospital_patient->where($where)
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
            'role_id' => 'required|numeric'
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
            $return = $this->hospital_role_service->getRoleData($request_data);

        }
        return json_encode($return);
    }

    public function Add(Request $request)
    {
        $return = [];
        $validator = \Validator::make($request->all(), [
            'role_name' => 'required',
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
            
            $check_role_where_clouse = [
                ["role_name" , $request_data['role_name']],
                ["hospital_id" , $this->hospital_id]
            ];
            $check_role_availability = $this->hospital_role_repository->getDataByCustomeWhere($check_role_where_clouse);
            
            if(empty($check_role_availability))
            {
                $insert_data = array(
                    "hospital_id" => $this->hospital_id,
                    "role_name" => $request_data['role_name'],
                    "status" => $request_data['status']
                );

                $return = $this->hospital_role_service->addRole($insert_data);
            } else { 
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Role name is already taken."
                );
            }

            /*$response = $this->hospital_role_repository->create($insert_data);
           
            if (!empty($response)) {
                //This code for create menu permission 
                $menu_list = $this->hospital_menu_repository->all();
                $insert_data = [];
                foreach ($menu_list as $menu_value) {
                    $insert_data[] = [
                        "role_id" => $response->role_id,
                        "menu_id" => $menu_value->menu_id,
                        "hospital_id" => $this->hospital_id,
                        // "hospital_user_id" => $this->hospital_user_id,
                    ];
                }

                if(!empty($insert_data))
                {
                    $this->hospital_role_permission_repository->insert($insert_data);
                }

                $return = array("success" => true, "error_code" => 0, "info" => "Data added Successfully");
            } else {
                $return = array(
                    "success" => false,
                    "error_code" => 1,
                    "info" => "Something is wrong, please try again."
                );
            }*/

        }
        return json_encode($return);
    }

    public function Edit(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'role_name' => 'required',
            'status' => 'required',
            'role_id' => 'required|numeric',
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
            $check_role_where_clouse = [
                ["role_name" , $request_data['role_name']],
                ["hospital_id" , $this->hospital_id],
                ["role_id" , "!=" ,$request_data['role_id']],
            ];
            $check_role_availability = $this->hospital_role_repository->getDataByCustomeWhere($check_role_where_clouse);

            if(empty($check_role_availability))
            {
                $update_data = array(
                    "role_name" => $request_data['role_name'],
                    "status" => $request_data['status']
                );

                $where_clouse = [
                    "hospital_id" => $this->hospital_id,
                    "role_id" => $request_data['role_id'],
                ];

                $response = $this->hospital_role_repository->update($update_data,$where_clouse);

                if (!$response) {
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
                    "info" => "Role name is already taken."
                );
            }

        }
        return json_encode($return);
    }

    public function roleMenuPermissionList(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'role_id' => 'required|numeric'
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
            $return = $this->hospital_permission_service->hospitalUserMenuPermissionList($request_data);
        }
        return json_encode($return);
    }
    
    public function roleMenuPermissionAdd(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'role_id' => 'required|numeric',
            'permission_id' => 'required|numeric',
            'is_add' => 'required',
            'type' => 'required'
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
            $return = $this->hospital_permission_service->hospitalRoleMenuPermissionAdd($request_data);
        }
        return json_encode($return);
    }

}
