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
use App\Smaterial;
use App\Stockreq;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Libraries\Paginacion;
use App\Libraries\Tools;
use Illuminate\Support\Str;
//use App\Paginacion;

class Pedidos2Controller extends Controller
{

    const QS ="";
    const PAG = "last_pag";

    public function index(Request $request)
    {
        $user = auth()->user();

        //$order = Order::find($id);
        $role = $user->role;
        $department = $user->department;
       // $statuses = Status::all();
        $reasons = Reason::all();

        
        //$fav = Follow::where('user_id', auth()->user()->id)->where('order_id', $order->id)->first();


        $queryString = Session::get(self::QS);
        $pag = Session::get(self::PAG,1);


        return view('pedidos2.index', compact('reasons','department','role','queryString','user','pag'));
    }



    public function lista(Request $request){

        $termino = (string)$request->query("termino","");
        $termino = addslashes($termino);

        $fechas = (string)$request->query("fechas","");
        $excel = (string)$request->query("excel",0);

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
        

        Session::put(self::QS,$request->query());

        //*****************************************   FILTROS  *******************
        $status = (array)$request->query("st");
        $subprocesos = (array)$request->query("sp");
        $origen = (array)$request->query("or");
        $sucursal = (array)$request->query("suc");

        $subpstatus = (array)$request->query("spsub");
        $recogido = (array)$request->query("rec");
        $orsub = (array)$request->query("orsub");

        $pag = $request->query("p",0);
        $pag = intval($pag);

        $pag = ($pag > 0) ? $pag : 1 ;        
        
        Session::put(self::PAG, $pag);

        foreach($status as $sk=>$sv){
            if(empty($sv)){unset($status[$sk]);}            
        }



        if($excel == 1){
            Pedidos2::$rpp=2010;
            $lista = Pedidos2::Lista($pag, $termino, $desde, $hasta, $status, $subprocesos, $origen, $sucursal,$subpstatus,$recogido,$orsub);

            // $eParams=compact("termino","desde","hasta","status","subprocesos","origen","sucursal","subpstatus","recogido","orsub");
            $RC = new ReportesController();
            
            $RC->general($lista);
            return;
         }


         $lista = Pedidos2::Lista($pag, $termino, $desde, $hasta, $status, $subprocesos, $origen, $sucursal,$subpstatus,$recogido,$orsub);

         $statuses = Status::all();
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

        
        $user = auth()->user();
        $role = $user->role;

        $pedido = Pedidos2::uno($id);

        $shipments = Shipment::where(["order_id"=>$id])->get();
        $evidences = Evidence::FromOrder($id);
        $debolutions = Debolution::FromOrder($id);
        $quote = Quote::where(["order_id" => $id])->first();
        $imagenesEntrega = Picture::where(["order_id"=>$id,"event"=>"entregar"])->get();
        $parciales = Partial::where(["order_id"=>$id])->get();
        $statuses = Status::get();
        $rebilling = Rebilling::where(["order_id"=>$id])->first();
        $reasons = Reason::get();
        $stockreq = Stockreq::where(["order_id"=>$id])->first();
        $notes = Note::where(["order_id"=>$id])->get();
        $smateriales_num = Smaterial::where(["order_id"=>$id])->count();
        //var_dump($debolutions);

        //$pictures = Picture::FromOrder($id);
        //$morders = ManufacturingOrder::where(["order_id"=>$id])->get();
        //$picturesEntrega = Pictures::EnPuerta($id,"")
        $purchaseOrder = PurchaseOrder::where(["order_id" => $id])->first();
       // var_dump($purchaseOrder);
       

        return view('pedidos2.pedido', compact('id','pedido','shipments',
        'role','user','evidences','debolutions', 'quote', 'purchaseOrder','imagenesEntrega','parciales',
        "statuses","rebilling","reasons","stockreq","notes","smateriales_num"));
    }


    public function nuevo(){
        $user =auth()->user();
   

        return view('pedidos2.nuevo', compact("user"));
    }




    public function parcial_lista($id){
        $id= intval($id);

        $role = auth()->user()->role;
        $user = auth()->user();

        $list =Partial::where(['order_id' => $id])->get();
        $estatuses = Pedidos2::StatusesCat();
    

        foreach($list as $li){
            $pictures = Picture::where(["partial_id"=>$li->id])->get();
            $events = [];

            foreach($pictures as $pic){
                if(!in_array($pic->event, $events)){$events[]=$pic->event;}
            }
            //var_dump($pictures);
            //var_dump($events);
            echo view("pedidos2/parcial/ficha",["parcial"=>$li,"estatuses"=>$estatuses, "pictures"=>$pictures,"events"=>$events,"user"=>$user]);
        }

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
        $nota = !empty($request->nota) ? Tools::_string($request->nota,190) : '';

       // $invoice = Tools::_string($request->invoice,24);

        //Validación requerida
        if($origin!="R" &&  empty($client)){
        Feedback::error("El código de cliente es requerido");
        Feedback::j(0);  
        }
        if(empty($code)){
        Feedback::error("El folio es requerido");
        Feedback::j(0);  
        }


        $now =date("Y-m-d H:i:s");

        //********************* */


        if($origin =="F"){
            $orderData =[
                'office' => $userOffice,
                'invoice_number'=>$code,
                'invoice'=>'',
                'origin'=> $origin,
                'client' => $client,
                'credit' => 0,
                'status_id' => 1,
                'created_at' => $now
            ];

            //Preexistente
            $existe = Order::where(["invoice_number"=>$code])->get()->toArray();
            if(count($existe) > 0 ){
            Feedback::error("Ya existe un pedido con el número de factura '$code'");
            Feedback::j(0);    
            }

            $order = Order::create($orderData);

            
            
            if($request->hasFile('archivo')){
                //ARCHIVO
                $file = $request->file('archivo');
                $name = $order["id"].".".$file->getClientOriginalExtension();
                $sqlPath = 'OrdenesDeCompra/' . $name;
                Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );
    
    /*
                $purchaseOrder=[
                    "required"=> 1,
                    "document"=> $sqlPath,
                    "order_id"=>$order["id"],
                    "is_covered"=> 1,
                    "created_at"=> $now,
                    "updated_at"=> $now,
                    "v2"=>1
                ];      
                PurchaseOrder::create($purchaseOrder);
                */
       
                Order::where("id",$order->id)->update(["invoice_document"=>$sqlPath]);
            }

            
        }
        else if($origin =="C"){

            //Existe folio
            $existe = Order::where(["invoice"=>$code])->get()->toArray();
            if(count($existe) > 0 ){
            Feedback::error("Ya existe un pedido con el folio '$code'");
            Feedback::j(0);    
            }

            $orderData =[
                'office' => $userOffice,
                'invoice'=>$code,
                'origin'=> $origin,
                'client' => $client,
                'credit' => 0,
                'status_id' => 1,
                'created_at' => $now,
            ];

            $order = Order::create($orderData);

                //Preexistente
                $existe = Quote::where(["number"=>$code])->get()->toArray();
                if(count($existe) > 0 ){
                        Feedback::error("Ya existe una cotización con el folio '$code'");
                        Feedback::j(0);    
                }

            //ARCHIVO
            $sqlPath="";
            if($request->hasFile("archivo")){
                $file = $request->file('archivo');
                $name = $order["id"].".".$file->getClientOriginalExtension();
                $sqlPath = 'Cotizaciones/' . $name;
                Storage::putFileAs('/public/Cotizaciones/', $file, $name );
            }


            $quoteData=[
                "order_id"=>$order["id"],
                "number"=>$code,
                "document"=>$sqlPath,
                "created_at"=>$now
            ];
            Quote::create($quoteData);

        }     

        else if($origin =="R"){

            //Preexistente
            $existe = Stockreq::where(["number"=>$code])->get()->toArray();
                if(count($existe) > 0 ){
                Feedback::error("Ya existe un requerimiento de stock con el folio '$code'");
                Feedback::j(0);    
                }

            $orderData =[
                'office' => $userOffice,
                'invoice'=>"",
                'origin'=> $origin,
                'client' => $client,
                'credit' => 0,
                'status_id' => 1,
                'created_at' => $now
            ];

            $order = Order::create($orderData);

            //ARCHIVO
            $sqlPath="";
            if($request->hasFile("archivo")){
                $file = $request->file('archivo');
                $name = $order->id.".".$file->getClientOriginalExtension();
                $sqlPath = 'Stockreq/' . $name;
                Storage::putFileAs('/public/Stockreq/', $file, $name );
            }

            $srData=[
                "order_id" => $order["id"],
                "number" => $code,
                "document"=>"",
                "created_at" => $now,
                "updated_at" => $now
            ];

            if( !empty($sqlPath) ) {
                $srData["document"] = $sqlPath;
            }            
        
            Stockreq::create($srData);
        }


        $note =Note::create([
            "note"=>$nota,
            "order_id"=>$order->id,
            "user_id" => $user->id,
            "created_at"=>$now,
            "updated_at"=>$now
        ]);


        //******************************************     LOG ****
        $status = Status::find(1);

        $origins=["C"=>"con cotización","F"=>"Con factura","R"=>"como requisición stock"];
        $action = 'Creado '.$origins[$origin].': '.$code;

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


    public function guardar($id, Request $request){
        $user = User::find(auth()->user()->id);
        
        // $user = User::find(auth()->user()->id);
         $userOffice = !empty($user->office) ? $user->office : "San Pablo";  
         
         $invoice = !empty($request->invoice) ? Tools::_string($request->invoice,18) : "" ;//Folio cotizacion
         $invoice_number = !empty($request->invoice_number) ? Tools::_string($request->invoice_number,18) : "" ;//Folio Factura
         
         $client = !empty($request->client) ? Tools::_string($request->client,24) : '';
 
         $obPrevio = Order::where("id",$id)->first();
         $aFactura=false;
            if(empty($obPrevio->invoice_number) && !empty($invoice_number)){
                $aFactura=true;
            }

         $now = date("Y-m-d H:i:s");

        $orderData=["updated_at"=>$now];
            if(!empty($invoice)){$orderData["invoice"]=$invoice;}
            if(!empty($invoice_number)){$orderData["invoice_number"]=$invoice_number;}
            if(!empty($client)){$orderData["client"]=$client;}
        

        Order::where("id",$id)->update($orderData);

        Quote::where(["order_id"=>$id])->update(["number"=>$invoice]);

         //ARCHIVO

         if($request->hasFile("cotizacion")){
            $file = $request->file('cotizacion');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Cotizaciones/' . $name;
            Storage::putFileAs('/public/Cotizaciones/', $file, $name );
            
            $quo = Quote::where(["order_id"=>$id])->first();
            //var_dump($quo);die();
                if(is_null($quo) || $quo->exists()==false){                    
                    $quo = Quote::create([
                        "order_id"=>$id,
                        "number"=>$invoice,
                        "document"=>$sqlPath,
                        "created_at"=>$now
                    ]);
                }else{
                    $quo->document = $sqlPath;
                    $quo->updated_at = date("Y-m-d H:i:s");
                    $quo->save();
                }


        }

         if($request->hasFile("factura")){
            $file = $request->file('factura');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPath = 'OrdenesDeCompra/' . $name;
            Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );
            
            //$po = PurchaseOrder::where(["order_id"=>$id])->first();
            //$po->document = $sqlPath;
            //$po->save();
            Order::where("id",$id)->update(["invoice_document"=>$sqlPath,"updated_at"=>date("Y-m-d H:i:s")]);
         }

    $ob = Order::where("id",$id)->first();   
   
         if($aFactura==true){
            $accionTxt = "La cotización {$obPrevio->invoice} se convirtió en factura {$invoice_number}";
         }else{
            $idLog = Pedidos2::CodigoDe($ob);
            $accionTxt = "El pedido $idLog fue modificado ";
         }

    Pedidos2::Log($id,"Pedido", $accionTxt, $ob->status_id,$user);         

    return redirect("pedidos2/pedido/".$id);     

    }


    public function masinfo($id){

        $pedido = Pedidos2::uno($id);
        $logs = Pedidos2::LogsDe($id);

        return view('pedidos2.masinfo', compact('id','pedido',"logs"));      
    }


    public function accion($id, Request $request){ 

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
        if($accion == "enpuerta"){
            $shipment = Shipment::where(["order_id"=>$id])->first();
            return view("pedidos2/accion/enpuerta",compact("id","order","paso","shipment"));
        }
        if($accion == "entregar"){
           // $this->set_accion_entregar($request, $id);
            return view("pedidos2/accion/entregar",compact("id","order","paso"));
        }

        if($accion == "surters"){
            $stockreq = Stockreq::where(["order_id"=>$id])->first();
            return view("pedidos2/accion/surters",compact("id","order","paso","stockreq"));
        }

        if($accion == "desauditoria"){

            return view("pedidos2/accion/desauditoria",compact("id","order","paso"));  
        }

        /*
        if($accion == "refacturar"){

            return view("pedidos2/accion/refacturar",compact("id","order","paso"));
        }

        if($accion == "devolucion"){

            return view("pedidos2/accion/devolucion",compact("id","order","paso"));
        }
        */

        /*
        if($accion == "parcial"){

            return view("pedidos2/accion/parcial",compact("id","order","paso"));
        }
        */

        var_dump($id);
        var_dump($request->a);
    }



    public function subproceso_nuevo($order_id, Request $request){ 

       // $user = User::find(auth()->user()->id);
        $user = auth()->user();

        $order_id = Tools::_string($order_id,16);
        
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";

        $accion = isset($request->a) ? $request->a : "";
        $paso = isset($request->paso) ?Tools::_int( $request->paso) : 1;

        $order = Order::find($order_id);

        if($accion == "ordenf"){

            return view("pedidos2/ordenf/nuevo",compact("order_id","order","paso","user"));
        }
        if($accion == "smaterial"){

            return view("pedidos2/smaterial/nuevo",compact("order_id","order","paso","user"));
        }
        if($accion == "requisicion"){
            if( ($order->origin == "F" && (empty($order->invoice_number) || empty($order->invoice_document)))
            ||
            ($order->origin == "C" && (empty($order->invoice) || empty($order->quote())) ) ){
                //var_dump($order->quote());
                return view("pedidos2/requisicion/faltafactura",compact("order_id","order","paso","user"));
            }
            else{
         
                return view("pedidos2/requisicion/nuevo",compact("order_id","order","paso","user"));
            }
            
            
        }
        if($accion == "devolucion"){

            return view("pedidos2/devolucion/nuevo",compact("order_id","order","paso"));
        }
        if($accion == "refacturacion"){
            $reasons = Reason::get();
            return view("pedidos2/refacturacion/nuevo",compact("order_id","order","paso","user","reasons"));
        }
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

            Pedidos2::Log($id,"Recibido por embarques", "El pedido fue recibido por embarques",2,$user);
        }

        elseif($accion == "ordenf"){

            $this->set_accion_ordenf($request,$id);

        }

        elseif($accion == "fabricado"){

            $this->set_accion_fabricado($request,$id,$user);
            
        }

        elseif($accion == "enpuerta"){

            $this->set_accion_enpuerta($request,$id);

        }

        elseif($accion == "entregar"){
          
            $this->set_accion_entregar($id, $request);

        }

        elseif($accion == "devolucion"){

            $this->set_accion_devolucion($request,$id);

        }





        Feedback::value($id);
        Feedback::j(1);
    }



    public function parcial_nuevo($id, Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        $order_id = $id;
        //$order = Order::where(["id"=>$id])->first();
        //$partial = Partial::where(["id"=>$id])->first();
        $paso=1;

        return view("pedidos2/parcial/nuevo",compact("order_id","paso","user"));
    }


    public function parcial_crear($order_id,Request $request){
        $order_id = Tools::_int($order_id);       

        $user = auth()->user();

        $invoice = Tools::_string( $request->invoice,90);
        $status_id = Tools::_int($request->status_id);       
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";
        $paso=2;
        $error="";
        
        $previo = Partial::where(["order_id" => $order_id,"invoice"=>$invoice])->get()->toArray();
            if(count($previo)>0){
                $paso = 1;
                $partial=(object)[];
                $error ="Ya existe un parcial con el folio ".$invoice." para el pedido ".$order_id;
                return view("pedidos2/parcial/nuevo",compact("order_id","paso","partial","error","user"));   
            }

        $partial = Partial::create([
            "invoice"=>$invoice,
            "order_id"=>$order_id,
            "status_id"=> $status_id,
            "status_".$status_id => 1,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]);  


        Pedidos2::Log($order_id,"Parcial", "Nueva salida parcial #{$invoice}", $status_id, $user);
        
        $paso = 2;
        return view("pedidos2/parcial/nuevo",compact("order_id","paso","partial","user"));
    }


    public function parcial_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $partial = Partial::where(["id"=>$id])->first();
        //$partial = !empty($partials) ? $partials[0] : [] ; 

        $pictures = Picture::where(["partial_id" => $partial->id])->get();
        $events = [];
        foreach($pictures as $pic){
            if(!in_array($pic->event,$events)){$events[]=$pic->event;} 
        }
//var_dump($pictures);
       // Pedidos2::Log($id,"Parcial", $user->name." registró un nuevo pedido #{$partial->id}", $status_id, $user);

        return view("pedidos2/parcial/edit",compact("id","partial","events","user"));
    }


    public function parcial_update($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);       

        $user = auth()->user();

       // $invoice = Tools::_string( $request->invoice,90);
        $status_id = Tools::_int($request->status_id);       
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";

        //$partial = Partial::where(["id" => $id])->first(); 

        $partialRes = Partial::where("id", $id)->update([
          //  "invoice"=>$invoice,        
            "status_id"=> $status_id,   
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        $partial = Partial::where(["id" => $id])->first(); 

        Pedidos2::Log($id,"Parcial Update", " Cambio en datos de parcial #{$partial->invoice}", $status_id, $user);

        Feedback::j(1);
    }


    public function smaterial_crear($order_id,Request $request){
        $order_id = Tools::_int($order_id);       

        $user = auth()->user();

        $error="";  

        $code = Tools::_string( $request->code, 24);
        $status_id = Tools::_int($request->status_id);       
       // $userOffice = !empty($user->office) ? $user->office : "San Pablo";        
        
        $previo = Smaterial::where(["order_id" => $order_id,"code"=>$code])->get()->toArray();
            if(count($previo)>0){
                $paso = 1;
                $smaterial=(object)[];
                $error ="Ya existe una salida de material con el folio ".$code." para el pedido ".$order_id;
                return view("pedidos2/smaterial/nuevo",compact("order_id","paso","smaterial","error","user"));   
            }

        $smaterial = Smaterial::create([
            "code"=>$code,
            "order_id"=>$order_id,
            "status_id"=> $status_id,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s"),
            "status_".$status_id=>1
        ]); 

        Pedidos2::Log($order_id,"Salida de Material", "Nueva salida de material #{$code} registrada", $status_id, $user);
        //var_dump($smaterial);
       // die();
        $paso=2;
        return view("pedidos2/smaterial/nuevo",compact("order_id","paso","smaterial","user"));
    }

    public function smaterial_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $ob = Smaterial::where(["id"=>$id])->first();

        return view("pedidos2/smaterial/edit",compact("id","ob","user"));
    }


    public function smaterial_lista($order_id,Request $request){
        $user = auth()->user();
        $order_id= intval($order_id);

        $list = Smaterial::where(['order_id' => $order_id])->orderBy("id","DESC")->get();
        $estatuses = Pedidos2::StatusesCat();    

        foreach($list as $li){
            echo view("pedidos2/smaterial/ficha",["order_id"=>$order_id,"estatuses"=>$estatuses, "ob" => $li, "user"=>$user]);
        }
    }


    public function smaterial_update($id,Request $request){
       // $user = User::find(auth()->user()->id);
        $id = Tools::_int($id);       
        $user = auth()->user();

        $status_id = Tools::_int($request->status_id);       
        $code = Tools::_string($request->code,24);

        $updateData = [
            "status_id"=> $status_id,   
            "status_".$status_id => 1,
            "updated_at"=>date("Y-m-d H:i:s")
        ];
            if(!empty($code)){$updateData["code"]=$code;}

        Smaterial::where("id", $id)->update($updateData); 

        $ob = Smaterial::where(["id" => $id])->first(); 

        //$order = Order::where("id",$ob->order_id)->first();
        $estatuses = [4=>"Elaborada",5=>"En Puerta", 6=>"Entregado", 7=>"Cancelado"];
 
        Pedidos2::Log($ob->order_id,"Salida Material Cambio", " Cambio en la salida de material #{$ob->code} Estatus:".$estatuses[$status_id], $status_id, $user);

        Feedback::j(1);
    }





    public function ordenf_crear($order_id,Request $request){
        $order_id = Tools::_int($order_id);       

        $user = auth()->user();

        $error="";

        $code = Tools::_string( $request->code, 24);  
        $status_id = Tools::_int($request->status_id);    
       
        
        $previo = ManufacturingOrder::where(["order_id" => $order_id,"number"=>$code])->get()->toArray();
            if(count($previo)>0){       
                $error ="Ya existe una orden de fabricacion con el numero ".$code." para el pedido ".$order_id;
                Feedback::error($error);
                Feedback::j(0);
                return;  
            }
        $ordenData = [
            "number"=>$code,
            "required"=>1,
            "document"=>"",
            "status_id" => $status_id, 
            "status_".$status_id => 1,
            "order_id"=>$order_id,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ];
   
        $ordenf = ManufacturingOrder::create($ordenData); 

        //ARCHIVO
        if($request->hasFile("document")){
            $file = $request->file('document');
            $name = $ordenf->id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Fabricaciones/' . $name;
            Storage::putFileAs('/public/Fabricaciones/', $file, $name );
    
            $ordenf->document = $sqlPath;
            $ordenf->save();
        }


        Pedidos2::Log($order_id,"Orden de fabricación", "Nueva orden de fabricacion #{$ordenf->number}", $status_id, $user);

       // $paso=2;
        Feedback::value($ordenf->id);
        Feedback::j(1);
       // return view("pedidos2/ordenf/nuevo",compact("order_id","paso","smaterial"));
    }

    public function ordenf_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $ob = ManufacturingOrder::where(["id"=>$id])->first();

        return view("pedidos2/ordenf/edit",compact("id","ob","user"));
    }


    public function ordenf_lista($order_id,Request $request){
        $order_id= intval($order_id);

        $role = auth()->user()->role;
        $user = auth()->user();

        $list = ManufacturingOrder::where(['order_id' => $order_id])->orderBy("id","DESC")->get();
        $estatuses = Pedidos2::StatusesCat();
    

        foreach($list as $li){
            echo view("pedidos2/ordenf/ficha",["order_id"=>$order_id,"estatuses"=>$estatuses, "ob" => $li, "user"=>$user]);
        }

    }

    public function ordenf_update($id,Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $status_id = Tools::_int($request->status_id);   
        
        $morder = ManufacturingOrder::where("id", $id)->first(); 
    
        $current_status_id = $morder->status_id;

        $sqlPath='';
        //ARCHIVO        
            if($request->hasFile('document')){
            $file = $request->file('document');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Fabricaciones/' . $name;
            Storage::putFileAs('/public/Fabricaciones/', $file, $name );
            }

            if($request->hasFile('documentc')){
            $file = $request->file('documentc');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPathc = 'FabricacionesCanc/' . $name;
            Storage::putFileAs('/public/FabricacionesCanc/', $file, $name );
            }

     

            $data = [
                "status_id" => ($current_status_id< $status_id) ? $status_id : $current_status_id,
                "status_".$status_id => 1,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            if(!empty($sqlPath)){
                $data["document"]=$sqlPath;
            }
            if(!empty($sqlPathc)){
                $data["documentc"]=$sqlPathc;
            }

        ManufacturingOrder::where("id", $id)->update($data); 
        $morder = ManufacturingOrder::where("id", $id)->first(); 
        $order = Order::where("id",$morder->order_id)->first();
        $estatuses = [1=>"Elaborada",3=>"En Fabricación", 4=>"Fabricado",7=>"Cancelado"];
        
        Pedidos2::Log($order->id,"Orden Fabricación Update", "Cambio en la orden de fabricación #{$morder->number} Status: ".$estatuses[$status_id], 0, $user);
        Feedback::j(1);
        return;
    }



    public function requisicion_crear($order_id,Request $request){
        $order_id = Tools::_int($order_id);       

        $user = auth()->user();

        $error="";

        $number = Tools::_string( $request->number, 24);  
        $status_id = Tools::_int($request->status_id);      
        $code_smaterial = Tools::_string($request->code_smaterial,24);       
        
        $previo = PurchaseOrder::where(["order_id" => $order_id,"number"=>$number])->get()->toArray();
            if(count($previo)>0){
                $error ="Ya existe una requisición con el numero ".$number." para el pedido ".$order_id;
                Feedback::error($error);
                Feedback::j(0);
                return;  
            }

        $porder = PurchaseOrder::create([
            "number"=>$number,
            "required"=>1,
            "document"=>"",
            "requisition"=>"",
            "code_smaterial"=>$code_smaterial,
            "status_id" => $status_id, 
            "status_1" => date("Y-m-d H:i:s"),
            "order_id"=>$order_id,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        //ARCHIVO
        /*
        if($request->hasFile("document")){
            $file = $request->file('document');
            $name = $porder->id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Facturas/' . $name;
            Storage::putFileAs('/public/Facturas/', $file, $name );
    
            $porder->document = $sqlPath;
            $porder->save();
        }
        */

        if($request->hasFile("requisition")){
            $file = $request->file('requisition');
            $name = $porder->id.".".$file->getClientOriginalExtension();
            $sqlPath = 'OrdenesDeCompra/' . $name;
            Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );
    
            $porder->requisition = $sqlPath;
            $porder->save();
        }


        Pedidos2::Log($order_id,"Requisición", "Requisición #{$number} creada", 0, $user);

        //$paso=2;
        Feedback::value($porder->id);
        Feedback::j(1);
       // return view("pedidos2/ordenf/nuevo",compact("order_id","paso","smaterial"));
    }

    public function requisicion_edit($id,Request $request){
        //$user = User::find(auth()->user()->id);
        $user = auth()->user();

        $id = Tools::_int($id);   
        
        $ob = PurchaseOrder::where(["id"=>$id])->first();

        return view("pedidos2/requisicion/edit",compact("id","ob","user"));
    }


    public function requisicion_lista($order_id,Request $request){
        $order_id= intval($order_id);

        $role = auth()->user()->role;
        $user = auth()->user();

        $list = PurchaseOrder::where(['order_id' => $order_id])->orderBy("id","DESC")->get();
        $estatuses = Pedidos2::StatusesCat();


        foreach($list as $li){
            echo view("pedidos2/requisicion/ficha",["order_id"=>$order_id,"estatuses"=>$estatuses, "ob" => $li, "user"=>$user]);
        }

    }

    public function requisicion_update($id,Request $request){
        $user = User::find(auth()->user()->id);
        $id = Tools::_int($id);       
        $user = auth()->user();

        $status_id = Tools::_int($request->status_id); 
        $number = Tools::_string( $request->number, 24);  
        $code_smaterial = Tools::_string($request->code_smaterial,24);  

        $dsqlPath="";
        $rsqlPath="";
        $rd5Path="";
        $rd6Path="";
        $rd7Path="";

        //ARCHIVO
        if($request->hasFile("document")){
            $file = $request->file('document');
            $name = $id.".".$file->getClientOriginalExtension();
            $dsqlPath = 'Facturas/' . $name;
            Storage::putFileAs('/public/Facturas/', $file, $name );
        }

        if($request->hasFile("requisition")){
            $file = $request->file('requisition');
            $name = $id.".".$file->getClientOriginalExtension();
            $rsqlPath = 'OrdenesDeCompra/' . $name;
            Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );  
        }


        if($request->hasFile("document_5")){
            $file = $request->file('document_5');
            $name = $id."_d5_". ".".$file->getClientOriginalExtension();
            $rd5Path = 'OrdenesDeCompra/' . $name;
            Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );  
        }

        if($request->hasFile("document_6")){
            $file = $request->file('document_6');
            $name = $id."_d6_".".".$file->getClientOriginalExtension();
            $rd6Path = 'OrdenesDeCompra/' . $name;
            Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );  
        }

        if($request->hasFile("document_7")){
            $file = $request->file('document_7');
            $name = $id."_d7_".".".$file->getClientOriginalExtension();
            $rd7Path = 'OrdenesDeCompra/' . $name;
            Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );  
        }

        $pord = PurchaseOrder::where("id", $id)->first(); 
        $status_date = date("Y-m-d H:i:s");
            if($pord->exists()==true){
                $prev = !empty($pord->{"status_".$status_id}) ? new \DateTime($pord->{"status_".$status_id}) : null;
                if($prev!=null){
                    $hoy = new \DateTime();
                    $diff = $hoy->diff($prev)->days;
                    $status_date = ( $diff > 0 ) ? date("Y-m-d H:i:s") : $pord->{"status_".$status_id} ;
                }                
            }

            $data = [
                "status_id" => $status_id,
                "status_".$status_id => $status_date,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            if(!empty($dsqlPath)){
                $data["document"]=$dsqlPath;
            }
            if(!empty($rsqlPath)){
                $data["requisition"]=$rsqlPath;
            }
            if(!empty($number)){
                $data["number"]=$number;
            }
            if(!empty($code_smaterial)){
                $data["code_smaterial"]=$code_smaterial;
            }
            if(!empty($rd5Path)){
                $data["document_5"]=$rd5Path;
            }
            if(!empty($rd6Path)){
                $data["document_6"]=$rd6Path;
            }
            if(!empty($rd7Path)){
                $data["document_7"]=$rd7Path;
            }
            
            PurchaseOrder::where("id", $id)->update($data); 

            //$order = Order::where("id",$pord->id)->first();
            $estatuses = [1=>"Elaborada",5=>"En Puerta",6=>"Entregada",7=>"Cancelada"];

            Pedidos2::Log($pord->order_id,"Requisición Cambio", "Cambio de información en la requisición #{$number} Estatus: ".$estatuses[$status_id], $status_id, $user);
            Feedback::j(1);
            return;

       Feedback::j(0);
    }







    public function devolucion_lista($order_id,Request $request){
        $order_id= intval($order_id);

        $role = auth()->user()->role;
        $user = auth()->user();

        $lista = Debolution::where(['order_id' => $order_id])->orderBy("id","DESC")->get();
        $reasonsres = Reason::get();
        $reasons = Tools::catalogo($reasonsres,"id","reason");


        echo view("pedidos2/devolucion/lista",["order_id"=>$order_id,"reasons"=>$reasons, "lista" => $lista, "user"=>$user]);
    }




    function set_accion_fabricado(Request $request, int $id, object $user){
        $data=[
            "status_id"=>4,
            "status_4"=>1,
            "updated_at"=>date("Y-m-d H:i:s")
        ];
        Order::where(["id"=>$id])->update($data);
        Pedidos2::Log($id,"Fabricado", "El pedido fue fabricado", 4,$user);
    }



    function set_accion_enpuerta(Request $request, int $id){
        $paso = isset($request->paso) ? intval($request->paso) : 1; 
        $user = auth()->user();
        
        $type= isset($request->type) ? (int)($request->type) : 1;
  
        Order::where(["id"=>$id])->update( ["status_id" => 5,"status_5" => 1, "updated_at"=>date("Y-m-d H:i:s") ] );

        Shipment::create([
              'file' => '',
              "order_id" => intval($id),
              "type" => $type,
              "created_at" => date("Y-m-d H:i:s"),
              "updated_at" => date("Y-m-d H:i:s")
          ]);

        Pedidos2::Log($id,"En Puerta", "El pedido pasó por puerta", 5, $user);  

        Feedback::custom("url", url("pedidos2/accion/$id?a=enpuerta&paso=2"));
        Feedback::j(2);
        return;
    }


    function set_accion_ordenf(Request $request, int $id){
        $user = auth()->user();

        $number = Tools::_string( $request->number,90);
        $archivo = $request->file("archivo");
        //
        $mimeType= $archivo->getClientMimeType();
        //var_dump($archivo);
       // var_dump($mimeType);
       // die();

        $filePath = "";
        $mimeExt = Pedidos2::mimeExtensions();
            if(in_array($mimeType,array_keys($mimeExt))){
                $ext= $mimeExt[$mimeType];
                $fileName = $id . "." . $ext;
                $archivo->storeAs("public/Fabricaciones", $fileName);
                $filePath="Fabricaciones/".$fileName;            
            }
       
        $mfup = ManufacturingOrder::upsert([
                "order_id"=>$id,
                "required"=>1,
                "number"=>$number,
                "document"=>$filePath,
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=>date("Y-m-d H:i:s")
            ],["order_id"],["required","number","document","updated_at"]);
          

        //Cambiar status si es necesario
        $orders = Order::where(["id"=>$id])->get()->toArray();
        $order = !empty($orders) ? $orders[0] : [];
            if(intval($order["status_id"]) < 3){
                $data=[
                    "status_id"=>3,
                    "status_3"=>1,
                    "updated_at"=>date("Y-m-d H:i:s")
                ];
        
                Order::where(["id"=>$id])->update($data);
            }


        Pedidos2::Log($id,"Orden de Fabricación", "Orden de fabricación '$number' registrada", 3, $user);
    }


    
    function set_accion_entregar(int $id,Request $request){
        $user = auth()->user();
        
        Order::where(["id"=>$id])->update( ["status_id" => 6, "status_6"=>1, "updated_at" => date("Y-m-d H:i:s") ] );

        $order = Order::where("id",$id)->first();
        $codigo = Pedidos2::CodigoDe($order);

        Pedidos2::Log($id,"Entregado", "El pedido '$codigo' fue entregado", 6, $user);
        
        Feedback::json_service(1);
    }



    function set_accion_surters(int $id,Request $request){
        $user = auth()->user();
        
        Order::where(["id"=>$id])->update( ["status_id" => 5, "status_5"=>1, "updated_at" => date("Y-m-d H:i:s") ] );

        Pedidos2::Log($id, "En Puerta", "El pedido con Requisición Stock está en puerta", 5, $user);
        
        Feedback::json_service(1);
    }


    function set_accion_devolucion_borrar(Request $request, int $id){
        $user = auth()->user();
        $number = Tools::_string( $request->number,90);  
        $razon = $request->razon;
        
        $debId = Debolution::create([
            "order_id"=>$id,
            "reason_id"=>$razon,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]);
        

        if($request->hasFile("archivo")){
            $archivo = $request->file("archivo");
            $mimeType= $archivo->getClientMimeType();

            $mimeExt = Pedidos2::mimeExtensions();

            if(in_array($mimeType,array_keys($mimeExt))){
                $ext= $mimeExt[$mimeType];
                $fileName = $id . "." . $ext;
                $archivo->storeAs("public/Fabricaciones", $fileName);
                $filePath="Fabricaciones/".$fileName;     
                
                Evidence::create([
                    "file" => $filePath,    
                    "debolution_id" => $debId->id,
                    "required"=>1,
                    "number"=>$number,
                    "file"=>$filePath,
                    "created_at"=>date("Y-m-d H:i:s"),
                    "updated_at"=>date("Y-m-d H:i:s")
                ],["debolution_id"],["required","number","document","updated_at"]);

            }

        }
        
        $data=[
            "status_id"=>9,
            "updated_at"=>date("Y-m-d H:i:s")
        ];
        Order::where(["id"=>$id])->update($data);

        Pedidos2::Log($id,"Devolución", "Devolución '$number' registrada", 9, $user);
    }



    function set_accion_refacturar(Request $request, int $id){
        $user = auth()->user();

        $number = Tools::_string( $request->number,90);
        $archivo = $request->file("archivo");
        //var_dump($archivo);die();
        $mimeType= $archivo->getClientMimeType();

        $razon = $request->razon;
        
        $filePath = "";

        $rebId = Rebilling::create([
            "order_id"=>$id,
            "reason_id"=>$razon,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]);

        $mimeExt = Pedidos2::mimeExtensions();
            if(in_array($mimeType, array_keys($mimeExt))){                
                $ext= $mimeExt[$mimeType];
                $fileName = $id . "." . $ext;
                $archivo->storeAs("public/Fabricaciones", $fileName);
                $filePath="Fabricaciones/".$fileName;     
                
                
                Evidence::create([
                    "file"=>"",
                   // "order_id"=>$id,
                   "rebilling_id"=>$rebId,
                    "required"=>1,
                    "number"=>$number,
                    "file"=>$filePath,
                    "created_at"=>date("Y-m-d H:i:s"),
                    "updated_at"=>date("Y-m-d H:i:s")
                ],["rebilling_id"],["required","number","document","updated_at"]);

            }
        
        $data=[
            "status_id"=>8,
            "updated_at"=>date("Y-m-d H:i:s")
        ];

        Order::where(["id"=>$id])->update($data);

        Pedidos2::Log($id,"Refacturación", "El pedido fue refacturado con el número '$number'", 8, $user);

    }










    public function attachlist(Request $request){

        $user = auth()->user();
        
        //$fav = Follow::where('user_id', auth()->user()->id)->where('order_id', $order->id)->first();
        $list=[];
        
        $catalog = $request->catalog;
        $order_id = $request->order_id;
        $partial_id = $request->partial_id;
        $cancelation_id = $request->cancelation_id;
        $rebilling_id = $request->rebilling_id;
        $debolution_id = $request->debolution_id;
        $shipment_id = $request->shipment_id;
        $smaterial_id = $request->smaterial_id;
        $rel = $request->rel;
        $event = $request->event;
        $mode = $request->mode;
        $mode = !empty($request->mode) ? $request->mode : "edit" ;


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
                return "?er";
            }
        }
        elseif($catalog==="pictures"){
            if(!empty($order_id)){
                $wheres = ["order_id"=>$order_id];
                    if(!empty($event)){$wheres["event"]=$event;}
                $list = Picture::where($wheres)->get();
            }
            elseif(!empty($partial_id)){
                $wheres = ["partial_id"=>$partial_id];
                    if(!empty($event)){$wheres["event"]=$event;}
                $list = Picture::where($wheres)->get();
            }
            elseif(!empty($shipment_id)){
                $wheres = ["shipment_id"=>$shipment_id];
                    if(!empty($event)){$wheres["event"]=$event;}                
                $list = Picture::where($wheres)->get();
            }
            elseif(!empty($smaterial_id)){
                $wheres = ["smaterial_id"=>$smaterial_id];
                    if(!empty($event)){$wheres["event"]=$event;}                
                $list = Picture::where($wheres)->get();
            }
            else{
                return "?pr";
            }
        }
        elseif($catalog==="shipments"){
            if(!empty($shipment_id)){
                $list = Picture::where("shipment_id",$shipment_id)->get();
            }
            else{
                return "?sr";
            }
        }
        else{
            return "?cat";
        }      
        
        $urlParams = [];
        $urlParams["rel"]=$rel;
        $urlParams["catalog"]=$catalog;
        $urlParams["mode"]=$mode;
        if(!empty($order_id)){$urlParams["order_id"]=$order_id;}
        if(!empty($partial_id)){$urlParams["partial_id"]=$partial_id;}
        if(!empty($cancelation_id)){$urlParams["cancelation_id"]=$cancelation_id;}
        if(!empty($rebilling_id)){$urlParams["rebilling_id"]=$rebilling_id;}
        if(!empty($debolution_id)){$urlParams["debolution_id"]=$debolution_id;}
        if(!empty($shipment_id)){$urlParams["shipment_id"]=$shipment_id;}
        if(!empty($smaterial_id)){$urlParams["smaterial_id"]=$smaterial_id;}
        if(!empty($event)){$urlParams["event"]=$event;}

        $url = url('pedidos2/attachlist?'.http_build_query($urlParams));
     
        return view('pedidos2.attachlist', compact('list','catalog','url','rel','urlParams','mode', 'user','event'));
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
         $smaterial_id = $request->smaterial_id;

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
         elseif(!empty($smaterial_id)){
            $identfield="smaterial_id";
            $ident = $smaterial_id;
            $identt="m";
            $folder="Smaterial";
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
            }elseif($identfield=="smaterial_id"){
                $numExists = Smaterial::where("id", intval($ident))->count();
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
        
             $folder="Embarques";

             if($request->hasFile("upload")==false){$RE->error="No se recibió imagen o documento";}            
             
             $file = $request->file("upload");
             $name = $identt . $ident . '-' . date("dis").".".$file->getClientOriginalExtension();
             
             $numExists = Shipment::where("id", $ident)->count();                  
             
             if($numExists == 0){
                 $RE->status = 0 ;
                 $RE->error="El embarque '$ident' no existe";
                 return json_encode($RE);
             }
             
             Storage::putFileAs('/public/'.$folder.'/', $file, $name );
             
             /*
             Shipment::create([
                 'file' => $folder.'/' . $name,
                 $identfield => intval($ident),
                 "created_at" => $ahora,
                 "updated_at" => $ahora
             ]);
             */        
            Picture::create([
                'picture' => $folder.'/' . $name,
                'user_id' => intval($user->id),
                'event' => '',
                $identfield => intval($ident),
                "created_at" => $ahora,
                "updated_at" => $ahora
            ]);                 
             
             $RE->status=1;
             $RE->value=$name;
             return json_encode($RE);             
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
            /*
            $img = Shipment::find($id);
            if(empty($img)){
                $RE->status=0;
                $RE->error="Evidencia de embarque no encontrada en base de datos";
                return json_encode($RE);
            }
            $img->delete($id);
            $RE->status=1;
            return json_encode($RE);
            */
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


    public function cancelar($id, Request $request){
       // $hashGet = isset($request->hash) ? $request->hash : "";
        $orders = Order::where(["id"=>$id])->get()->toArray();
        $order = !empty($orders) ? $orders[0] : [] ;

        if(!empty($order)){
            Order::where(["id"=>$id])->update(["status_id" => 7, "status_7"=>1 ,"updated_at"=>date("Y-m-d H:i:s")]);
        }
        return redirect("pedidos2/pedido/$id");
    }

    public function descancelar($id, Request $request){
        // $hashGet = isset($request->hash) ? $request->hash : "";
         $orders = Order::where(["id"=>$id])->get()->toArray();
         $order = !empty($orders) ? $orders[0] : [] ;

         if(!empty($order)){
            $logs = Log::where(["order_id"=>$id])->orderBy("created_at", "DESC")->limit(1)->get();
            $log = !empty($logs) ? $logs[0] : [] ;
            $sid = (!empty($log) && !empty($log["status_id"])) ? $log["status_id"] : 2;
    
            Order::where(["id"=>$id])->update(["status_id" => $sid, "status_7"=>0, "updated_at"=>date("Y-m-d H:i:s")]);
         }
         return redirect("pedidos2/pedido/$id");
     }



     public function historial($order_id){

        //echo "historial $id";
        $lista = Log::where(["order_id" => $order_id])->orderBy("created_at","DESC")->get();

        $order = Order::where("id",$order_id)->first();

        return view("pedidos2/pedido/historial",compact("order_id","lista","order"));
     }





     public function devolucion_crear($order_id,Request $request){
        $user = auth()->user();

        $number = Tools::_string( $request->number,90);  
        $razon = $request->razon;
        
        $deb = Debolution::create([
            "order_id"=>$order_id,
            "reason_id"=>$razon,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]);
        

        if($request->hasFile("archivo")){
            $archivo = $request->file("archivo");
            $mimeType= $archivo->getClientMimeType();

            $mimeExt = Pedidos2::mimeExtensions();

            if(in_array($mimeType,array_keys($mimeExt))){
                $ext= $mimeExt[$mimeType];
                $fileName = $order_id . "." . $ext;
                $archivo->storeAs("public/Devoluciones", $fileName);
                $filePath="Devoluciones/".$fileName;     
                
                Evidence::create([
                    "file" => $filePath,    
                    "debolution_id" => $deb->id,
                    "required"=>1,
                    "number"=>$number,
                    "file"=>$filePath,
                    "created_at"=>date("Y-m-d H:i:s"),
                    "updated_at"=>date("Y-m-d H:i:s")
                ],["debolution_id"],["required","number","document","updated_at"]);

            }
        }
        
        $data=[
            "status_id"=>9,
            "updated_at"=>date("Y-m-d H:i:s")
        ];
        Order::where(["id"=>$order_id])->update($data);

        Pedidos2::Log($order_id,"Devolución", "Devolucion '$number' fue registrada", 9, $user);

        return view("pedidos2/devolucion/nuevo2",["ob"=>$deb]);
      //  Feedback::custom("url",url());
        //Feedback::j(1);
    }



     public function devolucion_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $ob = Debolution::where(["id"=>$id])->first();
        $reasons = Reason::get();

        return view("pedidos2/devolucion/edit",compact("id","ob","reasons"));
    }

    public function devolucion_update($id,Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $reason_id = Tools::_int($request->reason_id);    

        $debo = Debolution::where("id", $id)->first(); 

            $data = [
                "reason_id" => $reason_id,
                "updated_at"=>date("Y-m-d H:i:s")
            ];

        Debolution::where("id", $id)->update($data); 

        $reason = Reason::where("id",$reason_id)->first();

        Pedidos2::Log($debo->order_id,"Devolucion", "Cambio en devolución '$reason->reason' ", 0, $user);
        Feedback::j(1);
        return;
    }



    public function shipment_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $ob = Shipment::where(["id"=>$id])->first();
        $types = [1=>"Envío",2=>"Entrega Directa"];

        return view("pedidos2/shipment/edit",compact("id","ob","types"));
    }

    public function shipment_update($id,Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $type = Tools::_int($request->type);    

            $data = [
                "type" => $type,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
        Shipment::where("id", $id)->update($data); 
        $shipment = Shipment::where("id",$id)->first();
        $order = Order::where("id",$shipment->order_id)->first();
        $codigo = Pedidos2::CodigoDe($order);

        Pedidos2::Log($id,"Shipment", "Cambio en el embarque de '$codigo'", $order->status_id, $user);
        Feedback::j(1);
        return;
    }




    public function entregar_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        $order_id = $id;
        
        $ob = Shipment::where(["order_id"=>$id])->first();
        $types = [1=>"Envío",2=>"Entrega Directa"];

        $order = Order::where(["id"=>$id])->first();

        return view("pedidos2/pedido/entregar_edit",compact("id","ob","types","order_id","order","user"));
    }

    public function entregar_update($id,Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $type = Tools::_int($request->type);    

            $data = [
                "type" => $type,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
        Shipment::where("id", $id)->update($data); 

        $shipment = Shipment::where("id",$id)->first();
        $order = Order::where("id",$shipment->order_id)->first();
        $codigo = Pedidos2::CodigoDe($order);

        Pedidos2::Log($id,"Shipment", "Cambio en el embarque de '$codigo'", $order->status_id, $user);
        Feedback::j(1);
        return;
    }






    public function refacturacion_crear($order_id, Request $request){
        $order_id = Tools::_int($order_id); 
        $user = auth()->user();

        $error="";

        $reason_id = Tools::_int( $request->reason_id);  
        $number = Tools::_string( $request->number, 24);  
        $url = Tools::_string( $request->url, 90);  
      
        
        $previo = Rebilling::where(["order_id" => $order_id])->first();
            if(isset($previo->id)){
                $error ="Ya existe una refacturación para el pedido ".$order_id;
                Feedback::error($error);
                Feedback::j(0);
                return;  
            }
        
        $urlValido = (!empty($url) && filter_var($url,FILTER_VALIDATE_URL)) ? true : false;
        $urlValido = !empty($url)?$urlValido : true;

            if(!$urlValido){
                Feedback::error("La liga no es válida, por favor verifica que sea corrrecta.");
                Feedback::j(0);
                return;    
            }

        $rebilling = Rebilling::create([
            "reason_id" => $reason_id, 
            "order_id"=>$order_id,
            "number" => $number,
            "url" => $url,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        //ARCHIVO
        if($request->hasFile("file")){
            $file = $request->file('file');
            $name = $rebilling->id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Refacturaciones/' . $name;
            Storage::putFileAs('/public/Refacturaciones/', $file, $name );
    
            Evidence::create([
                "file"=>$sqlPath,
                "rebilling_id"=>$rebilling->id,
                "created_at"=>date("Y-m-d H:i:s"),
                "updated_at"=> date("Y-m-d H:i:s")
            ]);
        }

        $res =[];

        Pedidos2::Log($order_id,"Refacturación", "Refacturación #{$number} creada", 0, $user);

        Feedback::value($rebilling->id);
        Feedback::j(1);
    }


    public function refacturacion_edit($id,Request $request){
        //$user = User::find(auth()->user()->id);
        $user = auth()->user();

        $id = Tools::_int($id);   
        
        $ob = Rebilling::where([ "id" => $id ])->first();

        $evidence = Evidence::where(["rebilling_id" => $id])->first();

        $reasons = Reason::get();

        return view("pedidos2/refacturacion/edit",compact("id","ob","user","evidence","reasons"));
    }

    public function refacturacion_update($id, Request $request){
        $id = Tools::_int($id); 
        $user = auth()->user();

        $reason_id = Tools::_int( $request->reason_id);  
        $number = Tools::_string( $request->number, 24);  
        $url = Tools::_string( $request->url, 90); 


        $urlValido = (!empty($url) && filter_var($url,FILTER_VALIDATE_URL)) ? true : false;
        $urlValido = !empty($url)?$urlValido : true;
        if(!$urlValido){
            Feedback::error("La liga no es válida, por favor verifica que sea corrrecta.");
            Feedback::j(0);
            return;    
        }

      
        Rebilling::where(["id" => $id])->update([
            "reason_id" => $reason_id, 
            "number" => $number,
            "url" => $url,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 
      $rebilling = Rebilling::where(["id"=>$id])->first();

        //ARCHIVO
        if($request->hasFile("file")){
            $file = $request->file('file');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Refacturaciones/' . $name;
            Storage::putFileAs('/public/Refacturaciones/', $file, $name );
    
            Evidence::where(["rebilling_id"=>$id])->updateOrCreate([
                'rebilling_id'=>$id,
                "file"=>$sqlPath,
                "updated_at"=> date("Y-m-d H:i:s")
            ]);
        }

        Pedidos2::Log($rebilling->order_id,"Refacturación", "Refacturación #{$number} modificada", 0, $user);

        Feedback::value($rebilling->id);
        Feedback::j(1);
    }





    public function stockreq_edit($id,Request $request){
        //$user = User::find(auth()->user()->id);
        $user = auth()->user();

        $id = Tools::_int($id);   
        
        $ob = Stockreq::where([ "id" => $id ])->first();

      //  $evidence = Evidence::where(["rebilling_id" => $id])->first();

        //$reasons = Reason::get();

        return view("pedidos2/stockreq/edit",compact("id","ob","user"));
    }


    public function stockreq_update($id, Request $request){
        $id = Tools::_int($id); 
        $user = auth()->user();
  
        $number = Tools::_string( $request->number, 24);  

        //ARCHIVO
        $sqlPath="";
        if($request->hasFile("document")){
            $file = $request->file('document');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Stockreq/' . $name;
            Storage::putFileAs('/public/Stockreq/', $file, $name );
        }

        Stockreq::where(["id" => $id])->update([
            "number" => $number,
            "document" => $sqlPath,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 
      $rebilling = Stockreq::where(["id"=>$id])->first();

        Pedidos2::Log($rebilling->order_id,"Refacturación", "Requisición Stock #{$number} modificada", 0, $user);

        Feedback::value($rebilling->id);
        Feedback::j(1);
    }






    public function fragmento($id,$cual){
        $id = Tools::_int($id);       
        $user = auth()->user();
        $cual = Tools::_string($cual,24);

        if($cual=="parciales"){
            $list = Partial::where("order_id", $id)->get();
            return view("pedidos2/parcial/fragmento",compact("list"));
        }
        if($cual=="ordenf"){
            $list = ManufacturingOrder::where("order_id", $id)->get();
            return view("pedidos2/ordenf/fragmento",compact("list"));
        }
        if($cual=="notas"){
            $list = Note::where("order_id", $id)->get();
            return view("pedidos2/pedido/notas",compact("list"));
        }
    }



    public function set_parcial_status($id, Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $estatuses = ["4"=>"Generado", "5" => "En Puerta", "6"=>"Entregado", "7"=>"Cancelado"];

        $status_id = Tools::_int($request->status_id);    

        $partial = Partial::where(["id"=>$id])->first();

            $data = [
                "status_".$status_id => 1,
                "updated_at"=>date("Y-m-d H:i:s")
            ];

        $data["status_id"] = ($partial->status_id > $status_id) ? $partial->status_id : $status_id ;

        Partial::where("id", $id)->update($data);         

        Pedidos2::Log($partial["order_id"], "Parcial", "Cambio de status ".$estatuses[$status_id]." en el parcial #{$partial->invoice}", $status_id, $user);
        Feedback::j(1);
        return;       
    }

    
    public function set_smaterial_status($id, Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $estatuses = ["4"=>"Elaborado", "5" => "En Puerta", "6"=>"Entregado", "7"=>"Cancelado"];

        $status_id = Tools::_int($request->status_id);    

        $smaterial = Smaterial::where(["id"=>$id])->first();

            $data = [
                "status_".$status_id => 1,
                "updated_at"=>date("Y-m-d H:i:s")
            ];

        $data["status_id"] = ($smaterial->status_id > $status_id) ? $smaterial->status_id : $status_id ;

        Smaterial::where("id", $id)->update($data);         

        Pedidos2::Log($smaterial->order_id, "Salida de Material", "Cambio de status ".$estatuses[$status_id]." en la sallida de material #{$smaterial->code}", $status_id, $user);
        Feedback::j(1);
        return;       
    }



    function unset_entregado(int $id, Request $request){
        $user = auth()->user();

        $order = Order::where(["id"=>$id])->first();
            if(empty($order)){
                Feedback::error("No order");
                Feedback::j(0);
            } 

        $estatusesq = Status::get();    
        $estatuses=[];
        foreach($estatusesq as $es){$estatuses[$es["id"]]=$es["name"];}

        $revertTo = 1;
            if($order->status_5 == 1){$revertTo = 5 ; }
            elseif($order->status_4 == 1){$revertTo = 4 ; }
            elseif($order->status_3 == 1){$revertTo = 3 ; }
            elseif($order->status_2 == 1){$revertTo = 2 ; }

        Order::where(["id"=>$id])->update( ["status_id" => $revertTo, "status_6"=>0,"updated_at" => date("Y-m-d H:i:s") ] );

        $codigo = Pedidos2::CodigoDe($order);

        Pedidos2::Log($id,"DesEntregado", "Revertido el estatus de entregado del pedido '$codigo' a ".$estatuses[$revertTo], $revertTo, $user);
        
        Feedback::json_service(1);
    }





    public function multie(Request $request){
        
        $user = auth()->user();
        $role = $user->role;

        $estatus = $request->get("estatus");

        $estatus = intval($estatus);
        $validos =[2,3,4,10];
        $estatus = in_array($estatus,$validos) ? $estatus : 0;       

        return view('pedidos2.multie.multie', compact('user','role','estatus'));
    }


    public function multie_lista(Request $request){
        $user = auth()->user();
        $role = $user->role;

        $term = $request->get("term");
        $term = Tools::_string($term,16);

        $estatus= $request->get("estatus");
        $estatus = Tools::_int($estatus);

        $wseg = (strlen($term) > 1 ) ? "AND (invoice_number LIKE '%{$term}%' OR invoice LIKE '%{$term}%')" : "" ;
        $wsegmo = (strlen($term) > 1 ) ? "AND mo.`number` LIKE '%{$term}%' " : "" ;

        $q = "SELECT * FROM orders 
        WHERE status_id < $estatus $wseg LIMIT 10";

        $qo = "SELECT mo.*, o.invoice_number, o.invoice FROM manufacturing_orders mo JOIN orders o ON o.id = mo.order_id 
        WHERE mo.status_id < $estatus $wsegmo LIMIT 10";
        //echo $q;

        if($estatus==2 || $estatus == 10 ){
            $shipments = DB::select(DB::raw($q));
            $statusesq = Status::all();
            $statuses = [];
                foreach($statusesq as $st){
                    $statuses[$st->id] = $st->name;
                }
    
            return view("pedidos2.multie.lista",compact('user','shipments',"statuses"));
        }else{
           // echo DB::raw($qo);
            $lista = DB::select(DB::raw($qo));
            $statusesq = Status::all();
            $statuses = [];
                foreach($statusesq as $st){
                    $statuses[$st->id] = $st->name;
                }
    
            return view("pedidos2.multie.listamo",compact('user','lista',"statuses"));
        }

    }


    public function set_status($id, Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $estatuses = [2=>"Entregado", 3 => "En Fabricaicón", 4=>"Fabricado"];

        $status_id = Tools::_int($request->ids);    
            if(!isset($estatuses[$status_id])){
                Feedback::error("Status off range");
                Feedback::j(0);
            }

        $data = [
                "status_id"=>$status_id,
                "updated_at"=>date("Y-m-d H:i:s")
            ];

            if($status_id > 2){
                $data["status_".$status_id] = 1;
            }

        Order::where("id", $id)->update($data);      
        $order = Order::where("id", $id)->first();     

        $idLog = Pedidos2::CodigoDe($order);

        Pedidos2::Log($id, "Order", "Cambio de status ".$estatuses[$status_id]." en el pedido #{$idLog}", $status_id, $user);
        Feedback::j(1);
        return;       
    }



    public function set_multistatus(Request $request){
             
        $user = auth()->user();

        $estatuses = [2=>"Entregado", 3 => "En Fabricaicón", 4=>"Fabricado",10=>"Recibido por Auditoria"];

        $catalogo = isset($request->catalogo) ? (string)$request->catalogo : "";
        $listaor = $request->lista;
        $status_id = Tools::_int($request->status_id);  

        $lista=[];

        if(!in_array($status_id,array_keys($estatuses))){
            Feedback::j(0);
        }
        foreach($listaor as $li){
            $lista[]=(int)$li;
        }

        $n=0;

        $d = date("Y-m-d H:i:s");
        foreach($lista as $li){
            $data = [
                "status_id"=> $status_id,
                "updated_at"=> $d
            ];
            if($catalogo==="order"){
                if($status_id > 2){$data["status_".$status_id] = 1; }

                Order::where("id", $li)->update($data);    
                $order = Order::where("id", $li)->first();       

                $idLog = Pedidos2::CodigoDe($order);
                Pedidos2::Log($li, $estatuses[$status_id] , "Cambio de status ".$estatuses[$status_id]." (masivo) en el pedido #{$idLog}", $status_id, $user);
                $n++;
            }
            else if($catalogo=="morder"){
                if($status_id > 2){$data["status_".$status_id] = 1; }

                ManufacturingOrder::where("id", $li)->update($data);
                $morder = ManufacturingOrder::where("id", $li)->first();          
        
                Pedidos2::Log($morder->order_id, $estatuses[$status_id], "Cambio de status ".$estatuses[$status_id]." (masivo) en Orden Fabricación #{$morder->number}", $status_id, $user);                
                $n++;
            }

        }

        Feedback::value($n);
        Feedback::j(1);
        return;       
    }


    public function set_accion_desauditoria($id, Request $request){
    $id = Tools::_int($id);       
    $user = auth()->user();

    $order = Order::where("id",$id)->first();

    $comentario = !empty($request->comentario) ? Tools::_string($request->comentario,162) : "";

        $maxStatus=3;
        for($i=$maxStatus; $i < 9 ; $i++){
            if(isset($order->{"status_".$i}) && $order->{"status_".$i}==1){
                $maxStatus=$i;
            }
        }

    $estatusesor = Status::all();
    $estatuses = Tools::catalogo($estatusesor,"id","name");
    
    Order::where("id",$id)->update(["status_id"=>$maxStatus,"status_10"=>0]);

    Pedidos2::Log($id,"Deshacer Auditoria", "Se revirtió '".Pedidos2::CodigoDe($order). "' desde auditoria. Nuevo estatus: ".( isset($estatuses[$maxStatus])?$estatuses[$maxStatus]:"?" ), $maxStatus,$user);
    Pedidos2::Log($id,"Deshacer Auditoria","Comentario: ".$comentario,$maxStatus,$user);
    
    return redirect("pedidos2/pedido/".$order->id);
    }



    public function add_nota($id, Request $request){
        $id = Tools::_int($id);       
        $user = auth()->user();

        $texto = Tools::_string($request->texto,180);
    
       $order = Order::where("id",$id)->first();   

        $data=[
            "note"=>$texto,
            "order_id" => $id,
            "user_id" =>$user->id,
            "created_at"=>date("Y-m-d H:i:s")      
        ];
        Note::create($data);     

        $textoLog = Str::limit($texto,24,"...");

        Pedidos2::Log($id,"Nota","Nota agregada: ".$textoLog, $order->status_id, $user);

        return redirect("pedidos2/pedido/".$id);
    }




}