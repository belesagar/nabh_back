<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HospitalRegistration extends Authenticatable implements JWTSubject
{
    protected $table = 'hospital_registration';
    protected $primaryKey = 'hospital_id';
    protected $fillable = ['hospital_name','hospital_unique_id','email','spoc_name','mobile','spoc_designation','city','state','pincode','number_of_bed','password'];

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
