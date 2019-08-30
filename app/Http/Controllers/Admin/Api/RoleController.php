<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Role;

class RoleController extends Controller
{
    public function __construct(Request $request)
    {
        $this->role = new Role();
 		$this->payload = auth()->user();
 		// dd($this->payload);
    }

    public function roleList(Request $request) {
        $list = $this->role->all()->toArray();
        $data = array("list" => $list);
        $return = array("success" => true,"error_code"=>0,"info" => "Success","data" => $data);
        return json_encode($return);
    }
}
