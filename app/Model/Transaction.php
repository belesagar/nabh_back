<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction_table';
    protected $primaryKey = 'transaction_id';
    protected $guarded = [];
}
