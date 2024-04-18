<?php

namespace App\Http\Controllers;

use App\Pedidos2;
use App\Cancelation;
use App\Debolution;
use App\Evidence;
use App\Follow;
use App\Libraries\Feedback;
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
use App\Quote;
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

        $id= intval($id);

        $role = auth()->user()->role;

        $pedido = Pedidos2::uno($id);

        $shipments = Shipment::where(["order_id"=>$id])->get();
        $pictures = Picture::where(["order_id" => $id])->get();

        return view('pedidos2.pedido', compact('id','pedido','shipments','pictures','role'));
    }


    public function nuevo(){

       // $pedido = Pedidos2::uno($id);

        return view('pedidos2.nuevo', []);
    }




    public function crear(Request $request){
        if(!isset($request->origin)){
            redirect("pedidos2");
        }

        $user = User::find(auth()->user()->id);
        
       // $user = User::find(auth()->user()->id);
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";

        $origin = $request->origin;

        $code = !empty($request->code) ? Tools::_string($request->code,18) : "" ;
        $client = !empty($request->client) ? Tools::_string($request->client,24) : '';

        $invoice = Tools::_string($request->invoice,24);

        //Validación requerida
        if(empty($client)){
        Feedback::error("El código de cliente es requerido");
        Feedback::j(0);  
        }
        if(empty($invoice)){
            Feedback::error("El folio es requerido");
            Feedback::j(0);  
            }


        $now =date("Y-m-d H:i:s");

        //********************* */

        $orderData =[
            'office' => $userOffice,
            'invoice'=>$invoice,
            'origin'=> $origin,
            'client' => $client,
            'credit' => 0,
            'status_id' => 1,
            'created_at' => $now,
        ];

        //Existe folio
        $existe = Order::where(["invoice"=>$invoice])->get()->toArray();
        if(count($existe) > 0 ){
        Feedback::error("Ya existe un pedido con el folio '$invoice'");
        Feedback::j(0);    
        }



        if($origin =="F"){
            $orderData["invoice_number"]=$code;

            //Preexistente
            $existe = Order::where(["invoice_number"=>$code])->get()->toArray();
            if(count($existe) > 0 ){
            Feedback::error("Ya existe un pedido con el número de factura '$code'");
            Feedback::j(0);    
            }

            $order = Order::create($orderData);

            $purchaseOrder=[
                "required"=> 1,
                "document"=> "",
                "order_id"=>$order["id"],
                "is_covered"=> 1,
                "created_at"=> $now,
                "updated_at"=> $now,
                "v2"=>1
            ];  

            PurchaseOrder::create($purchaseOrder);
        }
        else if($origin =="C"){

            //Preexistente
            $existe = Quote::where(["number"=>$code])->get()->toArray();
            if(count($existe) > 0 ){
            Feedback::error("Ya existe una cotización con el folio '$code'");
            Feedback::j(0);    
            }

            $order = Order::create($orderData);

            $quoteData=[
                "order_id"=>$order["id"],
                "number"=>$code,
                "document"=>"",
                "created_at"=>$now
            ];
            Quote::create($quoteData);
        }
        else if($origin =="R"){

            //Preexistente
            $existe = PurchaseOrder::where(["number"=>$code])->get()->toArray();
            if(count($existe) > 0 ){
            Feedback::error("Ya existe una requisición con el Num Requerimiento '$code'");
            Feedback::j(0);    
            }

            $order = Order::create($orderData);

            $purchaseOrder=[
                "required"=> 1,
                "number"=> $code,
                "requisicion"=>"",
                "order_id"=>$order["id"],
                "is_covered"=> 1,
                "created_at"=> $now,
                "updated_at"=> $now,
                "v2"=>1,
                
            ];  

            PurchaseOrder::create($purchaseOrder);
        }

        //******************************************     LOG ****
        $status = Status::find(1);
        $action = $user->name .' creó una orden : '.$order->id;

        Log::create([
            'status' => $status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => $now
        ]);

    Feedback::message("Pedido creado");
    Feedback::custom("goto",url("pedidos2/pedido/".$order->id));
    Feedback::j(1);
    }


    public function masinfo($id){

        $pedido = Pedidos2::uno($id);
        $logs = Pedidos2::LogsDe($id);

        return view('pedidos2.masinfo', compact('id','pedido',"logs"));      
    }


    public function parcial_accion($id, Request $request){ 

        $user = User::find(auth()->user()->id);

        $id = Tools::_string($id,16);
        
       // $user = User::find(auth()->user()->id);
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";

        $accion = isset($request->a) ? $request->a : "";
        $paso = isset($request->paso) ?Tools::_int( $request->paso) : 1;

        $order = Order::find($id);
        //$order = !empty($order) ? $order[0] : [];

        if($accion == "recibido"){

            return view("pedidos2/accion/recibido",compact("id"));
        }
        if($accion == "fabricado"){

            return view("pedidos2/accion/fabricado",compact("id"));
        }
        if($accion == "ordenf"){

            return view("pedidos2/accion/ordenf",compact("id"));
        }
        if($accion == "enpuerta"){

            return view("pedidos2/accion/enpuerta",compact("id","order","paso"));
        }
        if($accion == "entregar"){

            return view("pedidos2/accion/entregar",compact("id","order","paso"));
        }
        if($accion == "devolucion"){

            return view("pedidos2/accion/devolucion",compact("id","order","paso"));
        }

        var_dump($id);
        var_dump($request->a);
    }


    public function set_accion($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);
        
       // $user = User::find(auth()->user()->id);
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";

        $accion = isset($request->a) ? $request->a : "";

        if($accion == "recibido"){
            $data=[
                "status_id"=>2,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            Order::where(["id"=>$id])->update($data);
        }
        if($accion == "fabricado"){
            $data=[
                "status_id"=>4,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            Order::where(["id"=>$id])->update($data);
        }
        if($accion == "enpuerta"){
        $paso = isset($request->paso) ? intval($request->paso) : 1; 
        
            if($paso == 1){
                $shipment = isset($request->shipment) ? Tools::_int($request->shipment) : 0;
                if($shipment==1){
                    
                    Order::where(["id"=>$id])->update( ["status_id" => 5 ] );

                    Shipment::create([
                          'file' => '',//$folder.'/' . $name,
                          "order_id" => intval($id),
                          "created_at" => date("Y-m-d H:i:s"),
                          "updated_at" => date("Y-m-d H:i:s")
                      ]);
                Feedback::custom("url", url("pedidos2/parcial_accion/$id?a=enpuerta&paso=2"));
                Feedback::j(2);
                return;

                }else{

                }
                
            }
            else if($paso == 2){

            }
        }
        if($accion == "entregar"){
            $data=[
                "status_id"=>6,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            Order::where(["id"=>$id])->update($data);
        }

        if($accion == "devolucion"){
            $data=[
                "status_id"=>9,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            Order::where(["id"=>$id])->update($data);
        }

        Feedback::value($id);
        Feedback::j(1);
    }



    public function attachlist(Request $request){
        
        //$fav = Follow::where('user_id', auth()->user()->id)->where('order_id', $order->id)->first();
        $list=[];
        
        $catalog = $request->catalog;
        $order_id = $request->order_id;
        $partial_id = $request->partial_id;
        $cancelation_id = $request->cancelation_id;
        $rebilling_id = $request->rebilling_id;
        $debolution_id = $request->debolution_id;
        $shipment_id = $request->shipment_id;
        $rel = $request->rel;
        $event = $request->event;


        if(empty($catalog)){return "?";}
        
        if($catalog==="evidence"){
            if(!empty($cancelation_id)){
                $list = Evidence::where("cancelation_id",$cancelation_id)->get();
            }
            elseif(!empty($rebilling_id)){
                $list = Evidence::where("rebilling_id",$rebilling_id)->get();
            }
            elseif(!empty($debolution_id)){
                $list = Evidence::where("debolution_id",$debolution_id)->get();
            }

            else{
                return "?r";
            }
        }
        elseif($catalog==="pictures"){
            if(!empty($order_id)){
                $wheres = ["order_id"=>$order_id];
                    if(!empty($event)){$wheres["event"]=$event;}
                $list = Picture::where($wheres)->get();
            }
            elseif(!empty($partial_id)){
                $list = Picture::where("partial_id",$partial_id)->get();
            }
            elseif(!empty($shipment_id)){
                $list = Picture::where("shipment_id",$shipment_id)->get();
            }
            else{
                return "?r";
            }
        }
        elseif($catalog==="shipments"){
            if(!empty($order_id)){
                $list = Shipment::where("order_id",$order_id)->get();
            }
            else{
                return "?r";
            }
        }
        else{
            return "?c";
        }      
        
        $urlParams = [];
        $urlParams["rel"]=$rel;
        $urlParams["catalog"]=$catalog;
        if(!empty($order_id)){$urlParams["order_id"]=$order_id;}
        if(!empty($partial_id)){$urlParams["partial_id"]=$partial_id;}
        if(!empty($cancelation_id)){$urlParams["cancelation_id"]=$cancelation_id;}
        if(!empty($rebilling_id)){$urlParams["rebilling_id"]=$rebilling_id;}
        if(!empty($debolution_id)){$urlParams["debolution_id"]=$debolution_id;}
        if(!empty($shipment_id)){$urlParams["shipment_id"]=$shipment_id;}
        if(!empty($event)){$urlParams["event"]=$event;}

        $url = url('pedidos2/attachlist?'.http_build_query($urlParams));
     
        return view('pedidos2.attachlist', compact('list','catalog','url','rel','urlParams'));
    }





    public function attachpost(Request $request){
        // $order = Order::find($id);
        // $status = Status::find($request->status_id);
         $user = User::find(auth()->user()->id);
         
         $ahora = date("Y-m-d H:i:s");
         
         $catalog = $request->catalog;
         $order_id = $request->order_id;
         $partial_id = $request->partial_id;
         $cancelation_id = $request->cancelation_id;
         $rebilling_id = $request->rebilling_id;
         $debolution_id = $request->debolution_id;
         $shipment_id = $request->shipment_id;

         $event = isset($request->event) ? Tools::_string($request->event,12) : "";
         
         $identfield = "?";
         $ident=0;
         $identt="x";
         $folder="xx";
         
         if(!empty($order_id)){
             $identfield="order_id";
             $ident = $order_id;
             $identt="o";
             $folder="Images";
         }
         elseif(!empty($shipment_id)){
            $identfield="shipment_id";
            $ident = $shipment_id;
            $identt="s";
            $folder="Images";
        }
         elseif(!empty($partial_id)){
             $identfield="partial_id";
             $ident = $partial_id;
             $identt="p";
             $folder="Images";
         }
         elseif(!empty($cancelation_id)){
             $identfield="cancelation_id";
             $ident = $cancelation_id;
             $identt="c";
             $folder="Cencelaciones";
         }
         elseif(!empty($rebilling_id)){
             $identfield="rebilling_id";
             $ident = $rebilling_id;
             $identt="r";
             $folder="Refacturaciones";
         }
         elseif(!empty($debolution_id)){
             $identfield="debolution_id";
             $ident = $debolution_id;
             $identt="d";
             $folder="Devoluciones";
         }
         
         
         $RE=new \stdClass();
         $RE->value="";
         $RE->status=0;
         $RE->error="";
         
         if(empty($ident)){
             $RE->error="Falta valor de orden o parcial.";
             return json_encode($RE); 
         }
         
         if($catalog === "pictures"){            
           
             $file = $request->file("upload");
             if(empty($file)){
                 $RE->error="No se recibió imagen";
                 $RE->status=0;
                 return json_encode($RE);
             }
 
             $name = $identt . "-" . $ident . '-' . date("dHis") .".". $file->getClientOriginalExtension() ;   
             
             $numExists=0;
             if($identfield=="order_id"){
                 $numExists = Order::where("id", intval($ident))->count();
             }elseif($identfield=="partial_id"){
                 $numExists = Partial::where("id", intval($ident))->count();
             }elseif($identfield=="shipment_id"){
                $numExists = Shipment::where("id", intval($ident))->count();
            }
             
             
             if($numExists == 0){
                 $RE->status = 0 ;
                 $RE->error="El registro $identfield = $ident no existe";
                 return json_encode($RE); 
             }
             
      

             Storage::putFileAs('/public/'.$folder.'/', $file, $name );            
             
             Picture::create([
                 'picture' => $folder.'/' . $name,
                 'user_id' => intval($user->id),
                 'event' => $event,
                 $identfield => intval($ident),
                 "created_at" => $ahora,
                 "updated_at" => $ahora
             ]);  
             
             $RE->status=1;
             $RE->value=$name;
             return json_encode($RE);  
         }
         
         else if($catalog === "evidence"){
             
             $file = $request->file("upload");
             if(empty($file)){$RE->error="No se recibió imagen o documento";}
             //$file->getClientOriginalName()
             $name = $identt . "-" . $ident . '-' .date("dHis") .".". $file->getClientOriginalExtension() ;
             
             $numExists=0;
             switch($identfield){
                 case "cancelation_id": 
                     $numExists = Cancelation::where("id",$ident)->count();
                     break;
                 case "rebilling_id":
                     $numExists = Rebilling::where("id",($ident))->count();
                     break;
                 case "debolution_id":
                     $numExists = Debolution::where("id",($ident))->count();
                     break;
             }
             
             
             if($numExists == 0){
                 $RE->status = 0 ;
                 $RE->error="El registro $identfield = $ident no existe";
                 return json_encode($RE);
             }
             
             Storage::putFileAs('/public/'.$folder.'/', $file, $name );
             
             Evidence::create([
                 'file' => $folder.'/' . $name,
                 'user_id' => intval($user->id),
                 $identfield => intval($ident),
                 "created_at" => $ahora,
                 "updated_at" => $ahora
             ]);
             
             $RE->status=1;
             $RE->value=$name;
             return json_encode($RE);  
             
         }
         
         else if($catalog === "shipments"){
            die("x");
            /*
             $folder="Embarques";
             $file = $request->file("upload");
             if(empty($file)){$RE->error="No se recibió imagen o documento";}
             //$file->getClientOriginalName()
             $name = $identt . "-" . $ident . '-' . date("dHis").".".$file->getClientOriginalExtension();
             
             $numExists = Order::where("id", $ident)->count();     
             
             
             if($numExists == 0){
                 $RE->status = 0 ;
                 $RE->error="El registro $identfield = $ident no existe";
                 return json_encode($RE);
             }
             
             Storage::putFileAs('/public/'.$folder.'/', $file, $name );
             
             //$shipmentExists = Shipment::where($identfield,$ident)->get();
             Shipment::create([
                 'file' => $folder.'/' . $name,
                 $identfield => intval($ident),
                 "created_at" => $ahora,
                 "updated_at" => $ahora
             ]);
                        
             
             $RE->status=1;
             $RE->value=$name;
             return json_encode($RE);
             */
         }
 
       
           
     }


     public function attachdelete(Request $request, $id=0){
        // $order = Order::find($id);
        // $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);
        if(empty($user->id)){return "";}
        
        $catalog = $request->catalog;
        $id = $request->id;
       // $order_id = $request->order_id;
        //$partial_id = $request->partial_id;
       // $ident = !empty($order_id) ? $order_id : $partial_id;
        //$identfield = !empty($order_id) ? "order_id" : "partial_id";
        $id = intval($id);
        
        $RE=new \stdClass();
        $RE->value=$id;
        $RE->status=0;
        $RE->error="";
        
        if($catalog =="pictures"){
        $img = Picture::find($id);
        if(empty($img)){
            $RE->status=0;
            $RE->error="Imagen no encontrada en base de datos";
            return json_encode($RE);
        }
        $img->delete($id);
        $RE->status=1;
        return json_encode($RE);  
        }
        elseif($catalog =="evidence"){
            $img = Evidence::find($id);
            if(empty($img)){
                $RE->status=0;
                $RE->error="Evidencia no encontrada en base de datos";
                return json_encode($RE);
            }
            $img->delete($id);
            $RE->status=1;
            return json_encode($RE);
        }
        elseif($catalog =="shipments"){
            $img = Shipment::find($id);
            if(empty($img)){
                $RE->status=0;
                $RE->error="Evidencia de embarque no encontrada en base de datos";
                return json_encode($RE);
            }
            $img->delete($id);
            $RE->status=1;
            return json_encode($RE);
        }
        
    }


    public function attachdev(Request $request){
        $catalog = $request->catalog;
        $order_id = $request->order_id;
        $partial_id = $request->partial_id;
        $cancelation_id = $request->cancelation_id;
        $rebilling_id = $request->rebilling_id;
        $debolution_id = $request->debolution_id;
        $rel = $request->rel;
        
        $urlParams = [];
        $urlParams["rel"]=$rel;
        $urlParams["catalog"]=$catalog;
        if(!empty($order_id)){$urlParams["order_id"]=$order_id;}
        if(!empty($partial_id)){$urlParams["partial_id"]=$partial_id;}
        if(!empty($cancelation_id)){$urlParams["cancelation_id"]=$cancelation_id;}
        if(!empty($rebilling_id)){$urlParams["rebilling_id"]=$rebilling_id;}
        if(!empty($debolution_id)){$urlParams["debolution_id"]=$debolution_id;}
        
        return view('orders.attachdev', compact('catalog','urlParams','rel') );
    }



}