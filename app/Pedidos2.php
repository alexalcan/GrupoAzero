<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\AbstractArray;
use Illuminate\Support\Facades\DB;
use App\Log;

class Pedidos2 extends Model
{
    public static $total = 0;
    public static $rpp=10;

    protected $fillable = [
     //   'file', 'order_id', 'created_at'
    ];


    public static function uno(int $order_id=0) : object {
        $q="SELECT o.*,
        s.name AS status_name   
        FROM orders o
        JOIN statuses s ON s.id = o.status_id 
        WHERE o.id = '$order_id'";
        $list = DB::select(DB::raw($q));
       // $list = DB::table('orders')->where("id",$order_id)->get();
        return !empty($list) ? $list[0] : [];
    }

    public static function Lista(int $pag, string $termino, string $desde, string $hasta, 
    array $status=[], array $subprocesos=[], array $origen=[], array $sucursal=[], array $subpstatus=[], array $recogidos=[]) : array {

        $ini= ($pag>1) ? ($pag -1) * self::$rpp : 0;

        $wheres=["o.created_at BETWEEN '$desde 00:00:00' AND '$hasta 23:59:59'"];

        if(!empty($termino)){
            $wheres[]="(o.office LIKE '%$termino%' OR o.invoice LIKE '%$termino%' 
            OR o.invoice_number LIKE '%$termino%' OR o.client LIKE '%$termino%' 
            OR p.number LIKE '%termino%' OR q.number LIKE '%$termino%')";
        }
        if(!empty($status)){
            $wheres[]="o.status_id  IN (".implode(",",$status).")";
        }
        if(!empty($subprocesos)){
            if(in_array("devolucion",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM debolutions WHERE debolutions.order_id = o.id) > 0";
            }
            if(in_array("ordenc",$subprocesos)){
                $wheres[]="IF(LENGTH(o.invoice) > 0,1,0)  > 0";
                foreach($subpstatus as $sps){
                $arr=explode("_",$sps);
                    if($arr[0]=="ordenc"){$wheres[]="p.status_id = '".$arr[1]."'";}
                }
                
            }
            if(in_array("ordenf",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM manufacturing_orders WHERE manufacturing_orders.order_id = o.id) > 0";
                    foreach($subpstatus as $sps){
                    $arr=explode("_",$sps);
                        if($arr[0]=="ordenf"){$wheres[]="p.status_id = '".$arr[1]."'";}
                    }
            }
            
            if(in_array("parcial",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM partials WHERE partials.order_id = o.id) > 0";
            }
            if(in_array("refacturar",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM rebillings WHERE rebillings.order_id = o.id) > 0";
            }
            if(in_array("sm",$subprocesos)){
                $wheres[]="(SELECT COUNT(*) FROM smaterial WHERE smaterial.order_id = o.id) > 0";
            }

        }

        if(!empty($origen)){
            $orarr=[];
            foreach($origen as $ori){$orarr[]="'$ori'";}
            $wheres[]="o.origin IN (".implode(",",$orarr).")";
        }
        if(!empty($sucursal)){
            $suarr=[];
            foreach($sucursal as $su){$suarr[]="'$su'";}
            $wheres[]="o.office IN (".implode(",",$suarr).")";
        }

        $wherestring = implode(" AND ",$wheres);

        //QUERY TOTAL
        $listt = DB::select(DB::raw("SELECT 
        COUNT(*) AS tot 
        FROM orders o 
        LEFT JOIN purchase_orders p ON p.order_id = o.id 
        LEFT JOIN quotes q ON q.order_id = o.id 
        WHERE ". $wherestring));

        self::$total = !empty($listt) ? $listt[0]->tot : 0 ;

        //QUERY MAIN***********
        $q = "SELECT 
        o.*,
        p.number AS requisition_code, 
        p.document AS document,
        p.requisition AS requisition_document,
        q.number AS quote, 
        q.document quote_document, 
        r.id AS stockreq_id,
        r.number AS stockreq_number,
        r.document AS stockreq_document, 
        (SELECT m.number FROM manufacturing_orders m WHERE m.order_id = o.id ORDER BY id DESC LIMIT 1) AS ordenf_number,
        (SELECT m.status_id FROM manufacturing_orders m WHERE m.order_id = o.id ORDER BY id DESC LIMIT 1) AS ordenf_status_id,
        (SELECT pa.invoice FROM partials pa WHERE pa.order_id = o.id ORDER BY id DESC LIMIT 1) AS parcial_number,
        (SELECT pa.status_id FROM partials pa WHERE pa.order_id = o.id ORDER BY id DESC LIMIT 1) AS parcial_status_id
        FROM orders o 
        LEFT JOIN purchase_orders p ON p.order_id = o.id 
        LEFT JOIN quotes q ON q.order_id = o.id 
        LEFT JOIN stockreq r ON r.order_id = o.id 
        WHERE 
        ". $wherestring  ." ORDER BY updated_at DESC LIMIT ".$ini.", ". self::$rpp;
    //echo $q;
        $list = DB::select(DB::raw($q));
        
    return $list;
    }


    public static function Statuses() : array {
         return DB::table('statuses')->get()->toArray();
    }
    public static function StatusesCat(string $default="") : array{
        $arr=[];
        if(!empty($default)){$arr[""]=$default;}
        $list = self::Statuses();
        foreach($list as $li){
            $arr[$li->id]=$li->name;
        }
    return $arr;
    }

    public static function Events() : array {
        return DB::table('events')->get()->toArray();
   }
   public static function EventsCat(string $default="") : array{
       $arr=[];
       if(!empty($default)){$arr[""]=$default;}
       $list = self::Events();
       foreach($list as $li){
           $arr[$li->id]=$li->name;
       }
   return $arr;
   }

   public static function OrderHasDirect(int $order_id) : array{
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



   public static function LogsDe(int $id) : object{
    return DB::table("logs")
    ->select(["logs.*","users.name AS user"])
    ->join("users","users.id","=","logs.user_id")->where("logs.order_id",$id)
    ->orderBy("logs.created_at","DESC")->get();
   }



   public static function mimeExtensions() : array{
    return [
        "image/jpg" =>"jpg",
        "image/jpeg" =>"jpg",
        "image/gif"=>"gif",
        "image/png"=>"png",
        "application/pdf"=>"pdf",
        "application/x-pdf"=>"pdf",
        "x-pdf"=>"pdf"
       // "application/msword"=>"docx",
       // "application/ms-word"=>"docx",
       // "text/rtf"=>"rtf",
       // "application/rtf"=>"rtf",
       // "application/ms-excel"=>"xlsx",
       // "application/msexcel"=>"xlsx",
       // "application/vnd.ms-excel"=>"xlsx",
       // "application/vnd.ms-word"=>"docx"
        ];
   }



   public static function Log(int $order_id, string $statusStr, string $action, int $statusId, object $user) : int {
    
    $nid = Log::create([
        "status"=>$statusStr,
        "action"=> $action,
        "order_id"=>$order_id,
        "user_id" => $user->id,
        "department_id"=>$user->department->id,
        "status_id"=>$statusId,
        "created_at" => date("Y-m-d H:i:s"),
        "updated_at" => date("Y-m-d H:i:s")
    ])->id;
        return $nid;
   }

}