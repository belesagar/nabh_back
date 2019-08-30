<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NabhPackages extends Model
{
    protected $table = 'nabh_packages';
    protected $primaryKey = 'nabh_packages_id';
    protected $fillable = ["package_name", "package_amount", "per_month_amount", "indicators_type", "no_of_indicators_allowed", "no_of_user_allowed", "status"];
}