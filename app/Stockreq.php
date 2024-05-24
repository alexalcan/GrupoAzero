<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stockreq extends Model
{

    protected $table = "stockreq";

    protected $fillable = [
        'order_id', 'number', 'document'
    ];

}
