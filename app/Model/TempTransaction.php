<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TempTransaction extends Model
{
    protected $table = 'temp_transaction_table';
    protected $primaryKey = 'temp_transaction_id';
    protected $guarded = [];
}
