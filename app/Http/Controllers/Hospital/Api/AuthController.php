<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalRegistration;
use App\Model\HospitalUsers;
use JWTFactory;
use JWTAuth;

class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        $this->hospiatl_registration = new HospitalRegistration();
        $this->hospiatl_users = new HospitalUsers();
    }

    public function get_login_data(Request $request)
    {

        // $data = array("name"=>"sagar","lname"=>"bele");
        // $user = HospitalRegistration::first();
        // $token = auth('hospital_api')->login($user);

        // // $cuser = auth()->user();
        // $token = auth('api')->login($data);
        // // print_r($cuser);
        // dd($this->respondWithToken($token));
        // $credentials = request(['email', 'password']);
        // $credentials["password"] = md5($credentials['password']);
        // dd($token);
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

        // $data = array("name"=>"sagar","lname"=>"bele");

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


        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $return = [];

        if ($validator->fails()) {
            $errors_message = "";
            $errors = $validator->errors()->all();
            foreach ($errors as $key => $value) {
                $errors_message .= $value . "\n";
            }
            $return = array("success" => false, "error_code" => 1, "info" => $errors_message);
        } else {
            $request_data = $request->all();
            $user_data = $this->hospiatl_users->where('status', 'ACTIVE')
                ->where('email', $request_data['email'])
                ->where('password', md5($request_data['password']))
                ->get();

            if (count($user_data) > 0) {
                $hospital_data = $this->hospiatl_registration->select('hospital_unique_id',
                    'hospital_name')->where('status', 'ACTIVE')
                    ->where('hospital_id', $user_data[0]['hospital_id'])
                    ->get();
                if (count($hospital_data) == 1) {
                    $token_data = $user_data[0];

                    $token_data['hospital_unique_id'] = $hospital_data[0]['hospital_unique_id'];
                    $token_data['hospital_name'] = $hospital_data[0]['hospital_name'];

                    $token = auth('hospital_api')->login($token_data);
                    $data = array("token" => $token);
                    $return = array(
                        "success" => true,
                        "error_code" => 0,
                        "info" => "Login Successfull",
                        "data" => $data
                    );
                } else {

                    $return = array("success" => false, "error_code" => 1, "info" => "Invalid Credentials");
                }
            } else {

                $return = array("success" => false, "error_code" => 1, "info" => "Invalid Credentials");
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
        $return = array(
            "success" => true,
            "error_code" => 0,
            "info" => "Success",
            "data" => response()->json(auth('hospital_api')->user())
        );
        return $return;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('hospital_api')->logout();

        return response()->json(["success" => true, "error_code" => 0, 'info' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('hospital_api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('hospital_api')->factory()->getTTL() * 60
        ]);
    }

}
