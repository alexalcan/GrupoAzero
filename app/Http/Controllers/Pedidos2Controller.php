<?php

namespace App\Http\Controllers;

use App\Pedidos2;
use App\Cancelation;
use App\Debolution;
use App\Evidence;
use App\Follow;
use App\Log;
use App\ManufacturingOrder;
use App\Note;
use App\Order;
use App\Partial;
use App\Picture;
use App\PurchaseOrder;
use App\Reason;
use App\Rebilling;
use App\Shipment;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Libraries\Paginacion;
use App\Libraries\Tools;
//use App\Paginacion;

class Pedidos2Controller extends Controller
{



    public function index()
    {
        //$order = Order::find($id);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $statuses = Status::all();
        $reasons = Reason::all();

        //$fav = Follow::where('user_id', auth()->user()->id)->where('order_id', $order->id)->first();

        

        return view('pedidos2.index', compact('statuses','reasons','department','role'));
    }

    public function lista(Request $request){


        $termino = (string)$request->query("termino","");
        $fechas = (string)$request->query("fechas","");

        $fechaspts= explode(" - ",$fechas);
        //var_dump($fechaspts);
        if(count($fechaspts) == 2) {
            $desde = trim($fechaspts[0]);
            $hasta = trim($fechaspts[1]);
        }else{
            $fob = new \DateTime();
            $hasta = $fob->format("Y-m-d 23:59:59");
            $fob->modify("-7 day");
            $desde = $fob->format("Y-m-d 00:00:00");
        }
//var_dump($desde);


        $status = (array)$request->query("st");
        $subprocesos = (array)$request->query("sp");
        $origen = (array)$request->query("or");
        $sucursal = (array)$request->query("suc");
        //var_dump($sucursal);

        $pag = $request->query("p",1);
        $pag = !empty($pag) ? $pag : 1 ;


        $statuses = Status::all();
        $lista=Pedidos2::Lista($pag,$termino,$desde,$hasta, $status,$subprocesos,$origen,$sucursal);

        $estatuses = [];
        foreach($statuses as $st){
            $estatuses[$st->id]=$st->name;
        }

        $total = Pedidos2::$total;
        $rpp = Pedidos2::$rpp;

        echo view("pedidos2.lista",compact("lista","estatuses","total","rpp","pag"));

    }


    public function pedido($id){

        $pedido = Pedidos2::uno($id);

        return view('pedidos2.pedido', compact('id','pedido'));
    }


    public function masinfo($id){

        $pedido = Pedidos2::uno($id);
        $logs = Pedidos2::LogsDe($id);

        return view('pedidos2.masinfo', compact('id','pedido',"logs"));      
    }



}