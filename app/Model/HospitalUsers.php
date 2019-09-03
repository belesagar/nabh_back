<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HospitalUsers extends Authenticatable implements JWTSubject
{
    protected $table = 'hospital_users';
    protected $primaryKey = 'hospital_user_id';
    protected $fillable = ["hospital_id", "user_unique_id", "name", "email","password", "mobile", "city", "state", "role_id", "status"];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
}