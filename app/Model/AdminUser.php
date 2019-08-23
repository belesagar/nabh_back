<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable implements JWTSubject
{
    protected $table = 'admin_users';
    protected $primaryKey = 'admin_user_id';

    protected $fillable = ['name','email','password','mobile','role','status','created_by'];

    public function role()
    {
        return $this->belongsTo('App\Model\Role','role','role_id');
    }

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
