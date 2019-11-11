<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalUsers;
use App\Model\HospitalUsersIndicators;

class HospitalUsersController extends Controller
{
    public function __construct(Request $request)
    {
        $this->hospital_users = new HospitalUsers();
        $this->hospital_users_indicators = new HospitalUsersIndicators();
        $this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];
        $this->hospital_user_id = $this->payload['hospital_user_id'];
    }

    public function List(Request $request)
    {
        $where = [["hospital_id", $this->hospital_id]];

        $data_count = $this->hospital_users->where($where)->count();

        $request_data = $request->all();

        $where[] = ['status',$request_data['status']];

        //Filter option
        if(isset($request_data['search_string']) && $request_data['search_string'] != "")
        {
            $search_key = $request_data['search_key'];
            $search_string = $request_data['search_string'];

            if($search_key == "name")
            {
                $where[] = ['name','like','%'.$search_string.'%'];
            }
            if($search_key == "email")
            {
                $where[] = ['email',$search_string];
            }
            if($search_key == "mobile")
            {
                $where[] = ['mobile',$search_string];
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

        $list = $this->hospital_users->where($where)
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
        $request_data = $request->all();
        $user_data = $this->hospital_users->select("user_unique_id", "name", "email", "mobile", "city", "state", "role_id", "status", "address")->where('hospital_user_id',
            $this->hospital_user_id)->where("hospital_id", $this->hospital_id)
            ->get();
        if (count($user_data) == 1) {
            $data = array("user_data" => $user_data[0]);
            $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Invalid Record");
        }


        return json_encode($return);
    }

    public function Add(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:hospital_users,email',
            'mobile' => 'required|unique:hospital_users,mobile',
            'password' => 'required|same:cpassword',
            'cpassword' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
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
                $user_unique_id = date("his");

                $insert_data = array(
                    "hospital_id" => $this->payload['hospital_id'],
                    "user_unique_id" => $user_unique_id,
                    "name" => $request_data['name'],
                    "email" => $request_data['email'],
                    "password" => md5($request_data['password']),
                    "mobile" => $request_data['mobile'],
                    "city" => $request_data['city'],
                    "state" => $request_data['state'],
                    "address" => $request_data['address'],
                    "status" => $request_data['status']
                );

                $response = $this->hospital_users->create($insert_data);
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
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'city' => 'required',
            'password' => 'same:cpassword',
            'state' => 'required',
            'address' => 'required',
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
                $success = 1;

                $check_data = $this->hospital_users->select('hospital_user_id')->where('email',
                    $request_data['email'])->get();

                if (count($check_data) > 0) {
                    if ($request_data['user_id'] != $check_data[0]['hospital_user_id']) {
                        $return = array("success" => false, "error_code" => 1, "info" => "Email ID is already taken.");
                        return json_encode($return);
                    }
                }

                $check_mobile_data = $this->hospital_users->select('hospital_user_id')->where('mobile',
                    $request_data['mobile'])->get();

                if (count($check_mobile_data) > 0) {
                    if ($request_data['user_id'] != $check_mobile_data[0]['hospital_user_id']) {
                        $return = array(
                            "success" => false,
                            "error_code" => 1,
                            "info" => "Mobile number is already taken."
                        );
                        return json_encode($return);
                    }
                }


                $update_data = array(
                    "hospital_id" => $this->payload['hospital_id'],
                    "name" => $request_data['name'],
                    "email" => $request_data['email'],
                    "mobile" => $request_data['mobile'],
                    "city" => $request_data['city'],
                    "state" => $request_data['state'],
                    "address" => $request_data['address'],
                    "status" => $request_data['status']
                );

                if (isset($request_data['password']) && $request_data['password'] != "") {
                    $update_data['password'] = $request_data['password'];
                }

                $response = $this->hospital_users->where('hospital_user_id',
                    $request_data['user_id'])->where("hospital_id",
                    $this->payload['hospital_id'])->update($update_data);
                if ($response) {
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
                    "info" => "Something is wrong, please try again."
                );
            }

        }
        return json_encode($return);
    }

    public function GetUserAssignIndicators(Request $request, $id)
    {
        $list = $this->hospital_users_indicators->select("indicator_id")->where([
            ['status', 'ACTIVE'],
            ['hospital_id', $this->hospital_id],
            ['hospital_user_id', $id]
        ])->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function UserAssignIndicators(Request $request, $id)
    {
        $request_data = $request->all();
        $selected_indicators = [];
        $check_indicator_selection = true;
        foreach ($request_data as $key => $value) {
            if ($value != "") {
                $check_indicator_selection = false;
                $selected_indicators[] = $key;
            }
        }

        if ($check_indicator_selection) {
            $return = array("success" => false, "error_code" => 1, "info" => "Please Select the indicators.");
        } else {
            $insert_data_array = [];
            foreach ($selected_indicators as $indicators_value) {
                $check_indicators_availability = $this->hospital_users_indicators->where([
                    [
                        'hospital_id',
                        $this->hospital_id
                    ],
                    ["indicator_id", $indicators_value],
                    ['hospital_user_id', $id]
                ])->get();
                if (count($check_indicators_availability) == 0) {
                    $insert_data_array[] = array(
                        "hospital_id" => $this->hospital_id,
                        "hospital_user_id" => $id,
                        "indicator_id" => $indicators_value,
                    );
                }
            }

            if (count($insert_data_array) > 0) {
                $response_id = $this->hospital_users_indicators->insert($insert_data_array);
                if ($response_id > 0) {
                    $return = array("success" => true, "error_code" => 0, "info" => "Indicators Applied Successfully.");
                } else {
                    $return = array(
                        "success" => false,
                        "error_code" => 1,
                        "info" => "Something is wrong, Please try again."
                    );
                }
            } else {
                $return = array("success" => true, "error_code" => 0, "info" => "Indicators Applied Successfully.");
            }

        }
        return json_encode($return);
    }

    public function saveProfileData(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'address' => 'required',
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
                    "name" => $request_data['name'],
                    "city" => $request_data['city'],
                    "state" => $request_data['state'],
                    "address" => $request_data['address'],
                );


                $response = $this->hospital_users->where('hospital_user_id',
                    $this->hospital_user_id)->where("hospital_id",
                    $this->hospital_id)->update($update_data);
                if ($response) {
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
                    "info" => "Something is wrong, please try again."
                );
            }

        }
        return json_encode($return);
    }

    public function changePassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'old_password' => 'required|min:3',
            'new_password' => 'required|min:3|same:confirm_password',
            'confirm_password' => 'required|min:3',

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

                $check_data = $this->hospital_users->select('hospital_user_id')->where([
                    ['hospital_user_id', $this->hospital_user_id],
                    ['hospital_id', $this->hospital_id],
                    ['password', md5($request_data['old_password'])]
                ])->first();

                if (empty($check_data)) {
                        $return = array("success" => false, "error_code" => 1, "info" => "Old password is wrong, Please try again.");
                        return json_encode($return);
                }

                $update_data = array(
                    "password" => md5($request_data['new_password'])
                );


                $response = $this->hospital_users->where('hospital_user_id',
                    $this->hospital_user_id)->where("hospital_id",
                    $this->hospital_id)->update($update_data);
                if ($response) {
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
                    "info" => "Something is wrong, please try again."
                );
            }

        }
        return json_encode($return);
    }

}
