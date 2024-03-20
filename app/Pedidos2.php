<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\AbstractArray;
use Illuminate\Support\Facades\DB;

class Pedidos2 extends Model
{
    protected $fillable = [
     //   'file', 'order_id', 'created_at'
    ];


    public static function uno(int $order_id=0) : object {
        $list = DB::table('orders')->where("id",$order_id)->get();
        return !empty($list) ? $list[0] : [];
    }

    public static function Lista(string $termino, string $desde, string $hasta, int $pag=1) : array {
        $rpp=20;
        $ini= ($pag>1) ? ($pag*$rpp)-1 : 0;

        $wheres=[];
        if(!empty($termino)){
            $wheres[]="(o.office LIKE '%$termino%' OR o.invoice LIKE '%$termino%' OR o.invoice_number LIKE '%$termino%' OR o.client LIKE '%$termino%')";
        }
        $wherestring = implode(" AND ",$wheres);

        $list = DB::select(DB::raw("SELECT 
        o.*
FROM orders o 
WHERE 
o.created_at BETWEEN '$desde' AND '$hasta'".
(!empty($wherestring) ? " AND  $wherestring" : "")
." LIMIT $ini, $rpp"));
        
    return $list;
    }



}