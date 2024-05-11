<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Picture extends Model
{
    protected $fillable = [
        'picture', 'user_id', 'order_id', 'partial_id', 'created_at','shipment_id','event','smaterial_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function partial()
    {
        return $this->belongsTo(Partial::class);
    }


    public static function FromOrder(int $order_id) : array{
        $q="SELECT p.*, 
        e.name AS `event_name`,
        pa.invoice AS `partial`, 
        o.invoice AS invoice 
        FROM pictures p 
        LEFT JOIN partials pa ON pa.id = p.partial_id
        LEFT JOIN events e ON e.id = p.`event`
        LEFT JOIN shipments s ON s.id = p.shipment_id 
        JOIN orders o ON o.id IN( p.order_id, pa.order_id, s.order_id)  
        WHERE o.id = '$order_id'";

        return DB::select(DB::raw($q));
    }

}
