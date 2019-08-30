<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalUsers extends Model
{
    protected $table = 'hospital_users';
    protected $primaryKey = 'hospital_user_id';
    protected $fillable = ["hospital_id", "user_unique_id", "name", "email","password", "mobile", "city", "state", "role_id", "status"];
}