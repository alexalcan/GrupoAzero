<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice', 'client', 'credit', 'status_id'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function follow()
    {
        return $this->hasOne(Follow::class);
    }

    public function cancelation()
    {
        return $this->hasOne(Cancelation::class);
    }
}
