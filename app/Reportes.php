<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\AbstractArray;
use Illuminate\Support\Facades\DB;

class Reportes extends Model
{
    protected $fillable = [
     //   'file', 'order_id', 'created_at'
    ];


    
    public static function Ordenes(string $desde, string $hasta) : array {

        $q="SELECT
o.office AS Sucursal,
o.invoice AS Factura,
o.invoice_number AS `Cotizacion`,
s.name AS `Estatus`,
po.number AS `Requisicion`,
(SELECT count(*) FROM shipments sh WHERE sh.order_id = o.id) AS Salidas,
mo.number AS Fabricacion,
DATE_FORMAT(o.created_at,'%d/%m/%Y') AS FechaFactura,
DATE_FORMAT(po.created_at,'%d/%m/%Y') AS FechaCotizacion,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 0,1) AS `Parcial1`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 1,1) AS `Parcial2`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 2,1) AS `Parcial3`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 3,1) AS `Parcial4`,
(SELECT invoice FROM partials WHERE partials.order_id = o.id ORDER BY partials.created_at ASC LIMIT 4,1) AS `Parcial5`
 FROM orders o
LEFT JOIN statuses s ON s.id = o.status_id
LEFT JOIN purchase_orders po ON po.order_id = o.id
LEFT JOIN manufacturing_orders mo ON mo.order_id = o.id
            
WHERE o.created_at BETWEEN '".$desde."' AND '".$hasta."'"; 
        $list = DB::select(DB::raw($q));
        return $list;
    }
    
    public static function Requisiciones(string $desde, string $hasta) : array {
        //$list = DB::table('purchase_orders')->where("created_at",$desde,">=")->where("created_at",$hasta,"<="->get();
        $list = DB::select(DB::raw("SELECT p.*, 
DATE_FORMAT(p.created_at,'%d/%m/%Y') AS creado,
  o.office, o.invoice 
FROM purchase_orders p 
JOIN orders o ON o.id = p.order_id 
WHERE p.created_at BETWEEN '$desde' AND '$hasta' "));

        return $list;
    }
    
    
    public static function Retrasos(string $desde, string $hasta) : array {
        //$list = DB::table('purchase_orders')->where("created_at",$desde,">=")->where("created_at",$hasta,"<="->get();
        $list = DB::select(DB::raw("SELECT p.*,
DATE_FORMAT(p.created_at,'%d/%m/%Y') AS creado,
  o.office, o.invoice
FROM purchase_orders p
JOIN orders o ON o.id = p.order_id
WHERE p.created_at BETWEEN '$desde' AND '$hasta' "));
        
        return $list;
    }
    
    
    

}
