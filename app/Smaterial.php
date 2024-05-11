<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Smaterial extends Model
{
    protected $fillable = [
        'order_id', 'code'
    ];

    protected $table = "smaterial";

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
