<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'address', 'contact', 'phone', 'order_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
