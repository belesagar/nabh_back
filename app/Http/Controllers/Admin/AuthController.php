<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminUser;

class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        $this->folder_name = "admin.Authentication.";
        $this->admin_user = new AdminUser();
 
    }

    public function login(Request $request) {
        
        $title = "Login";
        return view($this->folder_name.'login',["title"=>$title]);
    }

    public function signup(Request $request) {
        $title = "Sign Up";
        return view($this->folder_name.'signup',["title"=>$title]);
    }

    public function forgot_password(Request $request) {
        $title = "Forgot Password";
        return view($this->folder_name.'forgot_password',["title"=>$title]);
    }
    
    public function get_admin_login_data(Request $request) {
        $validator = \Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]); 
          
        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value."\n";
            }
            \Session::flash('error_message', $errors_message);
            return redirect()->back();
        }else{
            $request_data = $request->all();
            $user_data = $this->admin_user->where('status', 'ACTIVE')
                            ->where('email', $request_data['email'])
                            ->where('password', md5($request_data['password']))
                            ->get();
            if(count($user_data) > 0)
            {
                $request->session()->put('admin_user_login', $user_data[0]);
                return redirect()->route('admin.dashboard');
            }else{
                \Session::flash('error_message', "Invalid Credentials");
                return redirect()->back();
            }
        }
    }

    public function get_admin_signup_data(Request $request) {
        $validator = \Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]); 
          
        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value."\n";
            }
            \Session::flash('error_message', $errors_message);
            return redirect()->back();
        }else{
            $request_data = $request->all();
            dd($request_data);
            // $user_data = $this->admin_user->where('status', 'ACTIVE')
            //                 ->where('email', $request_data['email'])
            //                 ->where('password', md5($request_data['password']))
            //                 ->get();
            // if(count($user_data) > 0)
            // {
            //     $request->session()->put('franchise_users', $user_data[0]);
            //     return redirect()->route('admin.dashboard');
            // }else{
            //     \Session::flash('error_message', "Invalid Credentials");
            //     return redirect()->back();
            // }
        }
    }
    
    public function get_forgot_password_data(Request $request) {
        $validator = \Validator::make($request->all(),[
            'email' => 'required|email'
        ]); 
          
        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value."\n";
            }
            \Session::flash('error_message', $errors_message);
            return redirect()->back();
        }else{
            $request_data = $request->all();
            $user_data = $this->admin_user->select('admin_user_id','email')->where('status', 'ACTIVE')
                            ->where('email', $request_data['email'])
                            ->get();
            if(count($user_data) > 0)
            {
                do{
                    $success = true;
                    $reset_id = \Helpers::genRandomCode(40);
                    
                    $check_data = $this->admin_user->select('admin_user_id')->where('reset_id', $reset_id)
                            ->get();
                            
                    if(count($check_data) == 0)
                    {
                        $success = false;
                    }
                    
                }while($success);
                //This code for update reset_id
                $update_array = array("reset_id"=>$reset_id);
                $check_data = $this->admin_user->where('admin_user_id', $user_data[0]['admin_user_id'])
                            ->update($update_array);

                \Session::flash('success_message', "Password reset link is sent on your email.");
                return redirect()->route('admin.login');
            }else{
                \Session::flash('error_message', "Your email ID is not register with us.");
                return redirect()->back();
            }
        }
    }
    
    public function get_reset_password_data(Request $request,$reset_id) {
        if($reset_id != "" && strlen($reset_id) == 40)
        {
            $user_data = $this->admin_user->where('reset_id', $reset_id)
                            ->get();
            
            if(count($user_data) > 0)
            {
                $request->session()->put('admin_reset_id', $user_data[0]['admin_user_id']);
                $title = "Reset Password";
                \Session::flash('success_message', "Please set your password");
                return view($this->folder_name.'reset_password',["title"=>$title]);
            }else{
                \Session::flash('error_message', "Invalid Link");
            }
        }else{
            \Session::flash('error_message', "Invalid Link");
        }
        return redirect()->route('admin.login');
    }
    
    public function set_reset_password(Request $request) {
        $validator = \Validator::make($request->all(),[
            'password' => 'required',
            'cpassword' => 'required|same:password',
        ]); 
          
        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value."\n";
            }
            \Session::flash('error_message', $errors_message);
            return redirect()->back();
        }else{
            if ($request->session()->has('admin_reset_id')) {
                $admin_reset_id = $request->session()->get('admin_reset_id');
                
                $request_data = $request->all();
                $update_array = array("password"=>md5($request_data['password']),'reset_id'=>"");
                $check_data = $this->admin_user->where('admin_user_id', $admin_reset_id)
                            ->update($update_array);
                $request->session()->forget('admin_reset_id');
                \Session::flash('success_message', "Password set successfully.");
                return redirect()->route('admin.login');
                
            }else{
                \Session::flash('error_message', "Please set password again.");
                return redirect()->back();
            }
            
        }
    }

    public function logout(Request $request) {
        $request->session()->forget('admin_user_login');
        return redirect()->route('admin.login');
    }

}
