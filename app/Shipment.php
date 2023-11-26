<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\AbstractArray;
use Illuminate\Support\Facades\DB;

class Shipment extends Model
{
    protected $fillable = [
        'file', 'order_id', 'created_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    
    public static function inOrder(int $order_id=0) : object {
        $list = DB::table('shipments')->where("order_id",$order_id)->get();
        return $list;
    }

}
