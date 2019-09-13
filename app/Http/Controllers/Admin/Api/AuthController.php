<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Model\AdminUser; 
use JWTFactory;
use JWTAuth;

class AuthController extends Controller
{
	public function __construct(Request $request)
    {
        $this->folder_name = "admin.Authentication.";
        $this->admin_user = new AdminUser();
        // $this->middleware('auth:api', ['except' => ['login']]);
 
    }
    public function getLoginData(Request $request) {
        $return = array("name" => "Sagar");
        return json_encode($request->all());
    }

    public function get_admin_login_data(Request $request) {

    	// $data = array("name"=>"sagar","lname"=>"bele");
		// $user = AdminUser::first();
		// $token = auth('api')->login($user);

		// // $cuser = auth()->user();
		// $token = auth('api')->login($data);
		// // print_r($cuser);
		// dd($this->respondWithToken($token));
    	// $credentials = request(['email', 'password']);
    	// $credentials["password"] = md5($credentials['password']);
    	// // dd();
    	// dd($token = JWTAuth::attempt($credentials));
     //    if (! $token = auth()->attempt($credentials)) {
     //        return response()->json(['error' => 'Unauthorized'], 401);
     //    }

     //    return $this->respondWithToken($token);

    	// $credentials = request(['email', 'password']);

     //    if (! $token = auth()->attempt($credentials)) {
     //        return response()->json(['error' => 'Unauthorized'], 401);
     //    }

     //    return $this->respondWithToken($token);

    	// $data = array("name"=>"sagar","lname"=>"bele"); sds

    	// $payload = JWTFactory::make($data);
     //        $token = JWTAuth::encode($payload); 
    	
    	// $customClaims = JWTFactory::customClaims($user);    
    	// $payload = JWTFactory::make($customClaims);    
    	// $token = JWTAuth::encode($payload);   
// dd($token);
    	// $token = auth()->claims(['foo' => 'bar'])->attempt($data);

  //   	$customClaims = JWTFactory::customClaims(['foo' => 'bar', 'baz' => 'bob']);
		// $payload = JWTFactory::make(['foo' => 'bar', 'baz' => 'bob']);
		// $token = JWTAuth::encode($payload);

    	// dd($token);
    	//return $this->respondWithToken($token->get());


        $validator = \Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]); 
        
        $return = [];

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value."\n";
            }
        $return = array("success" => false,"error_code"=>1,"info" => $errors_message);
        }else{
            $request_data = $request->all();
            $user_data = $this->admin_user->where('status', 'ACTIVE')
                            ->where('email', $request_data['email'])
                            ->where('password', md5($request_data['password']))
                            ->get();

            if(count($user_data) > 0)
            {
                $token = auth('api')->login($user_data[0]);
				$data = array("token" => $token);
                $return = array("success" => true,"error_code"=>0,"info" => "Login Successfull","data" => $data);
            }else{
                
                $return = array("success" => false,"error_code"=>1,"info" => "Invalid Credentials");
            }
        }

        return json_encode($return);
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
            $return = array("success" => false,"error_code"=>1,"info" => $errors_message);
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

                $return = array("success" => true,"error_code"=>0,"info" => "Password reset link is sent on your email.");
            }else{
                $return = array("success" => false,"error_code"=>1,"info" => "Your email ID is not register with us.");
            }
        }
        return json_encode($return);
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
           	$return = array("success" => false,"error_code"=>1,"info" => $errors_message);
        }else{
            if ($request_data['admin_reset_id'] != "") {
                $admin_reset_id = $request_data['admin_reset_id'];
                
                $request_data = $request->all();
                $update_array = array("password"=>md5($request_data['password']),'reset_id'=>"");
                $check_data = $this->admin_user->where('admin_user_id', $admin_reset_id)
                            ->update($update_array);
                
                $return = array("success" => true,"error_code"=>0,"info" => "Password set successfully.");
            }else{
                $return = array("success" => false,"error_code"=>1,"info" => "Please set password again.");
            }
            
        }

        return json_encode($return);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
    	$return = array("success" => true,"error_code"=>0,"info" => "Success","data"=>response()->json(auth()->user()));
        return $return;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(["success" => true,"error_code"=>0,'info' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

}
