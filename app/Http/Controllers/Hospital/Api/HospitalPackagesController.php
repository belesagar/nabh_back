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

    public function List(Request $request)
    {
        $list = $this->nabh_packages->select("package_reference_number", "package_name", "package_amount",
            "per_month_amount", "indicators_type", "no_of_indicators_allowed", "no_of_user_allowed")->get()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true, "error_code" => 0, "info" => "Success", "data" => $data);
        return json_encode($return);
    }

    public function packageDetails(Request $request)
    {
        $request_data = $request->all();

        $data_info = $this->nabh_packages->select("package_reference_number", "package_name", "package_amount",
            "per_month_amount", "indicators_type", "no_of_indicators_allowed", "no_of_user_allowed")->where([
            ["package_reference_number", $request_data['package_id']],
            ["status", "ACTIVE"]
        ])->first();
        if (!empty($data_info)) {
            $return = array(
                "success" => true,
                "error_code" => 0,
                "info" => "Success",
                "data" => ["data_info" => $data_info]
            );
        } else {
            $return = array("success" => false, "error_code" => 1, "info" => "Invalid Package Data.");
        }
        return json_encode($return);
    }

}
