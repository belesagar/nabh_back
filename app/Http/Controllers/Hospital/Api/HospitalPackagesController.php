<?php

namespace App\Http\Controllers\Hospital\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NabhPackages;

class HospitalPackagesController extends Controller
{
    public function __construct(Request $request)
    {
        $this->nabh_packages = new NabhPackages();
 		$this->payload = auth('hospital_api')->user();
 		// dd($this->payload);
    }

    public function List(Request $request) {
        $list = $this->nabh_packages->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }
}
