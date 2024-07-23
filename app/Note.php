<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'note', 'order_id', 'user_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function getUserOf(int $node_id) : User {
        $note = self::where("id",$node_id)->first();
        if(empty($note)){return null;}
        
        return User::where("id",$note->user_id)->first();
    }
}
