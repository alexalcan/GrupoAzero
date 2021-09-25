<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'file', 'order_id', 'created_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
