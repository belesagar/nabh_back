<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Hospital\HospitalPermissionService;

class HospitalPermissionController extends Controller
{
    public function __construct(
        HospitalPermissionService $hospital_permission_service
    )
    {
        $this->hospital_permission_service = $hospital_permission_service;
    }

    public function hospitalMenuList(Request $request)
    {
    	// dd($request->route()->getName());
        $response = $this->hospital_permission_service->hospitalMenuList()->toArray();
        $data = ["list" => $response];

        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

   	public function hospitalRoleList(Request $request)
    {
        $response = $this->hospital_permission_service->hospitalRoleList()->toArray();
        $data = ["list" => $response];

        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function hospitalCheckPermission(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'menu_id' => 'required|numeric'
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
            $return = $this->hospital_permission_service->hospitalCheckPermission($request_data);
        }
        return json_encode($return);
    }
    
    public function hospitalUserRoleList(Request $request)
    {
        $response = $this->hospital_permission_service->hospitalRoleList("ACTIVE")->toArray();
        $data = ["list" => $response];

        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

}
