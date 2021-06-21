<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cancelation extends Model
{
    protected $fillable = [
        'file', 'order_id', 'reason_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }
}
