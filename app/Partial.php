<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partial extends Model
{
    protected $fillable = [
        'invoice', 'order_id', 'status_id'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}
