<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = [
        'picture', 'user_id', 'deliveries_id', 'order_id', 'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
