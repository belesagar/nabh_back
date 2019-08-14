<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminUser;

class UserListController extends Controller
{
    public function __construct(Request $request)
    {
        $this->folder_name = "admin.Userlist.";
        $this->admin_user = new AdminUser();
 
    }
    
    public function userList(Request $request) {
        $user_list = $this->admin_user->all()->toArray();
        return view($this->folder_name.'userlist',["user_list" => $user_list]);
    }

    public function userOperation(Request $request,$id = "") {
        $user_data = [];
        if($id != "" && $id > 0)
        {
            $user_info = $this->admin_user->where('admin_user_id', $id)
                            ->get();
            $user_data = $user_info[0];
        }
        return view($this->folder_name.'useroperation',["user_data" => $user_data]);
    }

}
