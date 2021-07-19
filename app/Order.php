<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice', 'invoice_number', 'client', 'credit', 'status_id'
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

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function partials()
    {
        return $this->hasMany(Partial::class);
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
