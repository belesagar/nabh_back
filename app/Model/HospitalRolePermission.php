<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalRolePermission extends Model
{
    protected $table = 'hospital_role_permission';
    protected $primaryKey = 'role_permission_id';
    protected $guarded = [];

    public function menu_data()
    {
        return $this->belongsTo('App\Model\HospitalMenu', 'menu_id', 'menu_id');
    }

}
