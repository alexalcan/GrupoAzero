<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturingOrder extends Model
{
    protected $fillable = [
        'required',
        'number',
        'document',
        'documentc',
        'iscovered',
        'order_id',
        'status_id',
        'status_1',
        'status_3',
        'status_4',
        'status_7'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
