<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\HospitalOtInformation;

class OtController extends Controller
{
    public function __construct(Request $request)
    {
        $this->ot_information = new HospitalOtInformation();
 		$this->payload = auth('hospital_api')->user();
        $this->hospital_id = $this->payload['hospital_id'];

    }
    public function List(Request $request) {
    	$list = $this->ot_information->where("hospital_id",$this->payload['hospital_id'])->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }
}
