<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminUser;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        $this->folder_name = "admin.Userlist.";
        $this->admin_user = new AdminUser();
        $this->payload = auth()->user();
        // dd($this->payload);
    }

    public function userList(Request $request)
    {
        $list = $this->admin_user->with('role')->orderBy('created_at', 'desc')->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function getUserInfo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|numeric'
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
            $user_data = $this->admin_user->where('admin_user_id', $request_data['user_id'])
                ->get();
            if (count($user_data) == 1) {
                $data = array("user_data" => $user_data[0]);
                $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
            } else {
                $return = array("success" => false, "error_code" => 1, "info" => "Invalid Record");
            }

        }
        return json_encode($return);
    }

    public function addUserData(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|same:cpassword',
            'cpassword' => 'required',
            'mobile' => 'required|numeric',
            'role' => 'required|numeric',
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
                    "email" => $request_data['email'],
                    "password" => md5($request_data['password']),
                    "mobile" => $request_data['mobile'],
                    "role" => $request_data['role'],
                    "status" => $request_data['status'],
                    "created_by" => $this->payload['admin_user_id'],
                );
                $response = $this->admin_user->create($insert_data);
                if ($response) {
                    $return = array("success" => true, "error_code" => 0, "info" => "Success");
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

    public function updateUserData(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'same:cpassword',
            'mobile' => 'required|numeric',
            'role' => 'required|numeric',
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
                $check_data = $this->admin_user->select('admin_user_id')->where('email', $request_data['email'])->get();

                $success = 1;
                if (count($check_data) > 0) {
                    if ($request_data['user_id'] != $check_data[0]['admin_user_id']) {
                        $success = 0;
                    }
                }

                if ($success) {
                    $update_data = array(
                        "name" => $request_data['name'],
                        "email" => $request_data['email'],
                        "mobile" => $request_data['mobile'],
                        "role" => $request_data['role'],
                        "status" => $request_data['status'],
                        "created_by" => $this->payload['admin_user_id'],
                    );

                    if ($request_data['password'] != "") {
                        $update_data['password'] = $request_data['password'];
                    }

                    $response = $this->admin_user->where('admin_user_id',
                        $request_data['user_id'])->update($update_data);
                    if ($response) {
                        $return = array("success" => true, "error_code" => 0, "info" => "Success");
                    } else {
                        $return = array(
                            "success" => false,
                            "error_code" => 1,
                            "info" => "Something is wrong, please try again."
                        );
                    }
                } else {
                    $return = array("success" => false, "error_code" => 1, "info" => "Email ID is already taken.");
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
