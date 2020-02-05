<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HospitalMenu extends Model
{
    protected $table = 'hospital_menu';
    protected $primaryKey = 'menu_id';
    protected $guarded = [];
}
