<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $table = 'offers';
    protected $primaryKey = 'offer_id';
    protected $guarded = [];
}
