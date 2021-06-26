<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'file', 'cancelation_id'
    ];

    public function cancelation()
    {
        return $this->belongsTo(Cancelation::class);
    }
}
