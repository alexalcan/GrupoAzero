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
    array $status=[], array $subprocesos=[], array $origen=[], array $sucursal=[]) : array {

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
            $wheres[]="(SELECT COUNT(*) FROM order_events oe WHERE oe.id_order = o.id AND oe.id_event IN(".implode(",",$subprocesos)."))";
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
        q.document quote_document 
        FROM orders o 
        LEFT JOIN purchase_orders p ON p.order_id = o.id 
        LEFT JOIN quotes q ON q.order_id = o.id 
        WHERE 
        ". $wherestring  ." ORDER BY updated_at DESC LIMIT ".$ini.", ". self::$rpp;
    //echo $q;
        $list = DB::select(DB::raw($q));
        
    return $list;
    }


    public static function Statuses() : array {
         return DB::table('statuses')->where("v2",1)->get()->toArray();
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



   public static function LogsDe(int $id) : array{
    return DB::table("logs")
    ->select(["logs.*","users.name AS user"])
    ->join("users","users.id","=","logs.user_id")->where("logs.order_id",$id)
    ->orderBy("logs.created_at","DESC")->get()->toArray();
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