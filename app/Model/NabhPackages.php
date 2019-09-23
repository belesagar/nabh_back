<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NabhPackages extends Model
{
    protected $table = 'nabh_packages';
    protected $primaryKey = 'nabh_packages_id';
    protected $guarded = [];
}
