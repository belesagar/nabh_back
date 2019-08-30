<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    protected $table = 'offers';
    protected $primaryKey = 'offer_id';
    protected $fillable = ["offer_code", "details", "message", "amount_type", "amount", "start_date", "end_date", "status", "created_by"];
}
