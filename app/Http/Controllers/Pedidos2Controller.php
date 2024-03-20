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


        $termino = $request->input("termino");
        $termino = !empty($termino) ? $termino : "";
        $desde = $request->input("desde");
        $hasta = $request->input("hasta");

        $statuses = Status::all();
        $lista=Pedidos2::Lista($termino,$desde,$hasta,1);

        $estatuses = [];
        foreach($statuses as $st){
            $estatuses[$st->id]=$st->name;
        }

        $h="";
        foreach($lista as $item){
        $h.= view("pedidos2.pedido_item",compact("item","estatuses"));
        }
        if(count($lista)==0){
            echo "<p>No se encontraron resultados con esos filtros.</p>";
        }
        return $h;
    }


    public function pedido($id){

        $pedido = Pedidos2::uno($id);

        return view('pedidos2.pedido', compact('id','pedido'));
    }



}