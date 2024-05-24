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
//use App\Paginacion;

class Pedidos2Controller extends Controller
{

    const QS ="";

    public function index(Request $request)
    {
        $user = auth()->user();

        //$order = Order::find($id);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $statuses = Status::all();
        $reasons = Reason::all();

        
        //$fav = Follow::where('user_id', auth()->user()->id)->where('order_id', $order->id)->first();


        $queryString = Session::get(self::QS);

        return view('pedidos2.index', compact('statuses','reasons','department','role','queryString','user'));
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

        Session::put(self::QS,$request->query());

        $status = (array)$request->query("st");
        $subprocesos = (array)$request->query("sp");
        $origen = (array)$request->query("or");
        $sucursal = (array)$request->query("suc");
        //var_dump($sucursal);
        //var_dump($subprocesos);

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
        $user = auth()->user();

        $pedido = Pedidos2::uno($id);

        $shipments = Shipment::where(["order_id"=>$id])->get();
        $evidences = Evidence::FromOrder($id);
        $debolutions = Debolution::FromOrder($id);
        $quote = Quote::where(["order_id" => $id])->first();
        $imagenesEntrega = Picture::where(["order_id"=>$id,"event"=>"entregar"])->get();
//var_dump($debolutions);

        //$pictures = Picture::FromOrder($id);
        //$morders = ManufacturingOrder::where(["order_id"=>$id])->get();
        //$picturesEntrega = Pictures::EnPuerta($id,"")
        $purchaseOrder = PurchaseOrder::where(["order_id" => $id])->first();
       // var_dump($purchaseOrder);
       



        return view('pedidos2.pedido', compact('id','pedido','shipments',
        'role','user','evidences','debolutions', 'quote', 'purchaseOrder','imagenesEntrega'));
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
            echo view("pedidos2/parcial/ficha",["parcial"=>$li,"estatuses"=>$estatuses]);
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

       // $invoice = Tools::_string($request->invoice,24);

        //Validación requerida
        if(empty($client)){
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

            $orderData =[
                'office' => $userOffice,
                'invoice'=>"",
                'origin'=> $origin,
                'client' => $client,
                'credit' => 0,
                'status_id' => 1,
                'created_at' => $now,
            ];

            $order = Order::create($orderData);

                //Preexistente
                $existe = Stockreq::where(["number"=>$code])->get()->toArray();
                if(count($existe) > 0 ){
                        Feedback::error("Ya existe un requerimiento de stock con el folio '$code'");
                        Feedback::j(0);    
                }

            //ARCHIVO
            $sqlPath="";
            if($request->hasFile("archivo")){
                $file = $request->file('archivo');
                $name = $order["id"].".".$file->getClientOriginalExtension();
                $sqlPath = 'Reqstock/' . $name;
                Storage::putFileAs('/public/Reqstock/', $file, $name );
            }


            $quoteData=[
                "order_id"=>$order["id"],
                "number"=>$code,
                "document"=>$sqlPath,
                "created_at"=>$now
            ];
            Stockreq::create($quoteData);

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


    public function guardar($id, Request $request){
        $user = User::find(auth()->user()->id);
        
        // $user = User::find(auth()->user()->id);
         $userOffice = !empty($user->office) ? $user->office : "San Pablo";  
         
         $invoice = !empty($request->invoice) ? Tools::_string($request->invoice,18) : "" ;
         $invoice_number = !empty($request->invoice_number) ? Tools::_string($request->invoice_number,18) : "" ;

         
         $client = !empty($request->client) ? Tools::_string($request->client,24) : '';
 

         $now = date("Y-m-d H:i:s");

         $orderData =[
            'invoice_number'=>$invoice_number,
            'invoice'=>$invoice, 
            'client' => $client,            
            'updated_at' => $now
        ];        
        Order::where("id",$id)->update($orderData);
        if(!empty($invoice_number)){
            $hay = PurchaseOrder::where("order_id",$id)->first();
            if($hay==null){
                PurchaseOrder::create([
                    "required"=> 1,
                    "document"=> "",
                    "order_id"=>$id,
                    "number"=>$invoice_number,
                    "is_covered"=> 1,
                    "created_at"=> date("Y-m-d H:i:s"),
                    "updated_at"=> date("Y-m-d H:i:s"),
                    "v2"=>1
                ]);
            }
        }else{
            PurchaseOrder::where(["order_id"=>$id])->update(["number"=>$invoice_number]);
        }
        
        Quote::where(["order_id"=>$id])->update(["number"=>$invoice]);

         //ARCHIVO

         if($request->hasFile("cotizacion")){
            $file = $request->file('cotizacion');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Cotizaciones/' . $name;
            Storage::putFileAs('/public/Cotizaciones/', $file, $name );
            
            $quo = Quote::where(["order_id"=>$id])->first();
                if(is_null($quo) || $quo->isEmpty()){                    
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
            
            $po = PurchaseOrder::where(["order_id"=>$id])->first();
            $po->document = $sqlPath;
            $po->save();
         }

    $ob = Order::where("id",$id)->first();
    Pedidos2::Log($id,"Pedido","El pedido $id fue modificado por ".$user->name,$ob->status_id,$user);         

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
            $this->set_accion_entregar($request, $id);
            return view("pedidos2/accion/entregar",compact("id","order","paso"));
        }
        if($accion == "devolucion"){

            return view("pedidos2/accion/devolucion",compact("id","order","paso"));
        }
        if($accion == "refacturar"){

            return view("pedidos2/accion/refacturar",compact("id","order","paso"));
        }
        if($accion == "parcial"){

            return view("pedidos2/accion/parcial",compact("id","order","paso"));
        }

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

            return view("pedidos2/ordenf/nuevo",compact("order_id","order","paso"));
        }
        if($accion == "smaterial"){

            return view("pedidos2/smaterial/nuevo",compact("order_id","order","paso","user"));
        }
        if($accion == "requisicion"){
            $ob = PurchaseOrder::where(["order_id"=>$order_id])->first();
            if($ob == null){
                return view("pedidos2/requisicion/nuevo",compact("order_id","order","paso","user"));
            }else{
                $id=$order_id;
                return view("pedidos2/requisicion/edit",compact("order_id","order","paso","ob","id","user"));
            }
            
        }
        if($accion == "devolucion"){

            return view("pedidos2/accion/devolucion",compact("order_id","order","paso"));
        }
        if($accion == "refacturacion"){

            return view("pedidos2/accion/refacturar",compact("order_id","order","paso"));
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
            Pedidos2::Log($id,"Recibido por embarques",$user->name." ha registrado que el pedido fue recibido por embarques",2,$user);
        }

        elseif($accion == "ordenf"){

            $this->set_accion_ordenf($request,$id);

        }

        elseif($accion == "fabricado"){
            $data=[
                "status_id"=>4,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            Order::where(["id"=>$id])->update($data);
            Pedidos2::Log($id,"Fabricado",$user->name." ha registrado que el pedido fue fabricado", 4,$user);
        }

        elseif($accion == "enpuerta"){

            $this->set_accion_enpuerta($request,$id);

        }

        elseif($accion == "entregar"){
          
            $this->set_accion_entregar($request,$id);

        }

        elseif($accion == "devolucion"){

            $this->set_accion_devolucion($request,$id);

        }





        Feedback::value($id);
        Feedback::j(1);
    }


    public function parcial_crear($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);       

        $user = auth()->user();

        $error="";

        $invoice = Tools::_string( $request->invoice,90);
        $status_id = Tools::_int($request->status_id);       
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";
        $paso=2;
        
        $previo = Partial::where(["order_id" => $id,"invoice"=>$invoice])->get()->toArray();
            if(count($previo)>0){
                $paso = 1;
                $partial=(object)[];
                $error ="Ya existe un parcial con el folio ".$invoice." para el pedido ".$id;
                return view("pedidos2/accion/parcial",compact("id","paso","partial","error"));   
            }

        $partial = Partial::create([
            "invoice"=>$invoice,
            "order_id"=>$id,
            "status_id"=> $status_id,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        Pedidos2::Log($id,"Parcial", $user->name." registró un nuevo pedido #{$partial->id}", $status_id, $user);

        return view("pedidos2/accion/parcial",compact("id","paso","partial"));
    }

    public function parcial_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $partial = Partial::where(["id"=>$id])->first();
        //$partial = !empty($partials) ? $partials[0] : [] ; 



       // Pedidos2::Log($id,"Parcial", $user->name." registró un nuevo pedido #{$partial->id}", $status_id, $user);

        return view("pedidos2/parcial/edit",compact("id","partial"));
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

        Pedidos2::Log($id,"Parcial Update", $user->name." cambió el pedido #{$partial->id}", $status_id, $user);

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
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        Pedidos2::Log($order_id,"Salida de Material", $user->name." registró una nueva salida de material #{$smaterial->id}", $status_id, $user);

        $paso=2;
        return view("pedidos2/smaterial/nuevo",compact("order_id","paso","smaterial","user"));
    }

    public function smaterial_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $ob = Smaterial::where(["id"=>$id])->first();

        return view("pedidos2/smaterial/edit",compact("id","ob"));
    }


    public function smaterial_lista($order_id,Request $request){
        $order_id= intval($order_id);

        $list = Smaterial::where(['order_id' => $order_id])->orderBy("id","DESC")->get();
        $estatuses = Pedidos2::StatusesCat();    

        foreach($list as $li){
            echo view("pedidos2/smaterial/ficha",["order_id"=>$order_id,"estatuses"=>$estatuses, "ob" => $li]);
        }
    }


    public function smaterial_update($id,Request $request){
        $user = User::find(auth()->user()->id);
        $id = Tools::_int($id);       
        $user = auth()->user();

        $status_id = Tools::_int($request->status_id);       

        Smaterial::where("id", $id)->update([
            "status_id"=> $status_id,   
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        $ob = Smaterial::where(["id" => $id])->first(); 

        Pedidos2::Log($id,"Salida Material Update", $user->name." cambió la salida de material #{$ob->id}", $status_id, $user);

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
                $paso = 1;
                $smaterial=(object)[];
                $error ="Ya existe una orden de fabricacion con el numero ".$code." para el pedido ".$order_id;
                Feedback::error($error);
                Feedback::j(0);
                return;  
            }

        $ordenf = ManufacturingOrder::create([
            "number"=>$code,
            "required"=>1,
            "document"=>"",
            "status_id" => $status_id, 
            "order_id"=>$order_id,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        //ARCHIVO
        if($request->hasFile("document")){
            $file = $request->file('document');
            $name = $ordenf->id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Fabricaciones/' . $name;
            Storage::putFileAs('/public/Fabricaciones/', $file, $name );
    
            $ordenf->document = $sqlPath;
            $ordenf->save();
        }


        Pedidos2::Log($order_id,"Orden de fabricación", $user->name." registró una nueva orden de fabricacion #{$ordenf->id}", 0, $user);

        $paso=2;
        Feedback::value($ordenf->id);
        Feedback::j(1);
       // return view("pedidos2/ordenf/nuevo",compact("order_id","paso","smaterial"));
    }

    public function ordenf_edit($id,Request $request){
        $user = User::find(auth()->user()->id);

        $id = Tools::_int($id);   
        
        $ob = ManufacturingOrder::where(["id"=>$id])->first();

        return view("pedidos2/ordenf/edit",compact("id","ob"));
    }


    public function ordenf_lista($order_id,Request $request){
        $order_id= intval($order_id);

        $role = auth()->user()->role;
        $user = auth()->user();

        $list = ManufacturingOrder::where(['order_id' => $order_id])->orderBy("id","DESC")->get();
        $estatuses = Pedidos2::StatusesCat();
    

        foreach($list as $li){
            echo view("pedidos2/ordenf/ficha",["order_id"=>$order_id,"estatuses"=>$estatuses, "ob" => $li]);
        }

    }

    public function ordenf_update($id,Request $request){
        $user = User::find(auth()->user()->id);
        $id = Tools::_int($id);       
        $user = auth()->user();

        $status_id = Tools::_int($request->status_id);       
        $sqlPath='';
        //ARCHIVO        
            if($request->hasFile('document')){
            $file = $request->file('document');
            $name = $id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Fabricaciones/' . $name;
            Storage::putFileAs('/public/Fabricaciones/', $file, $name );
            }

            $data = [
                "status_id" => $status_id,
                "updated_at"=>date("Y-m-d H:i:s")
            ];
            if(!empty($sqlPath)){
                $data["document"]=$sqlPath;
            }

            ManufacturingOrder::where("id", $id)->update($data); 

            Pedidos2::Log($id,"Salida Material Update", $user->name." cambió la salida de material #{$id}", 0, $user);
            Feedback::j(1);
            return;

       Feedback::j(0);
    }



    public function requisicion_crear($order_id,Request $request){
        $order_id = Tools::_int($order_id);       

        $user = auth()->user();

        $error="";

        $number = Tools::_string( $request->number, 24);  
        $status_id = Tools::_int($request->status_id);       
        
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
            "status_id" => $status_id, 
            "order_id"=>$order_id,
            "created_at"=>date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        ]); 

        //ARCHIVO
        if($request->hasFile("document")){
            $file = $request->file('document');
            $name = $porder->id.".".$file->getClientOriginalExtension();
            $sqlPath = 'Facturas/' . $name;
            Storage::putFileAs('/public/Facturas/', $file, $name );
    
            $porder->document = $sqlPath;
            $porder->save();
        }

        if($request->hasFile("requisition")){
            $file = $request->file('requisition');
            $name = $porder->id.".".$file->getClientOriginalExtension();
            $sqlPath = 'OrdenesDeCompra/' . $name;
            Storage::putFileAs('/public/OrdenesDeCompra/', $file, $name );
    
            $porder->requisition = $sqlPath;
            $porder->save();
        }


        Pedidos2::Log($order_id,"Requisición", $user->name." registró una nueva requisición #{$porder->id}", 0, $user);

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
            echo view("pedidos2/requisicion/ficha",["order_id"=>$order_id,"estatuses"=>$estatuses, "ob" => $li]);
        }

    }

    public function requisicion_update($id,Request $request){
        $user = User::find(auth()->user()->id);
        $id = Tools::_int($id);       
        $user = auth()->user();

        $status_id = Tools::_int($request->status_id); 
        $number = Tools::_string( $request->number, 24);  

        $dsqlPath="";
        $rsqlPath="";


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

            $data = [
                "status_id" => $status_id,
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
            
            PurchaseOrder::where("id", $id)->update($data); 

            Pedidos2::Log($id,"Salida Material Update", $user->name." cambió la salida de material #{$id}", 0, $user);
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


        echo view("pedidos2/devolucion/lista",["order_id"=>$order_id,"reasons"=>$reasons, "lista" => $lista]);
    }







    function set_accion_enpuerta(Request $request, int $id){
        $paso = isset($request->paso) ? intval($request->paso) : 1; 
        $user = auth()->user();
        
        $type= isset($request->type) ? (int)($request->type) : 1;
  
        Order::where(["id"=>$id])->update( ["status_id" => 5, "updated_at"=>date("Y-m-d H:i:s") ] );

        Shipment::create([
              'file' => '',
              "order_id" => intval($id),
              "type" => $type,
              "created_at" => date("Y-m-d H:i:s"),
              "updated_at" => date("Y-m-d H:i:s")
          ]);

        Pedidos2::Log($id,"En Puerta",$user->name." ha registrado que el pedido pasó por puerta", 5, $user);  

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
                    "updated_at"=>date("Y-m-d H:i:s")
                ];
        
                Order::where(["id"=>$id])->update($data);
            }


        Pedidos2::Log($id,"Orden de Fabricación",$user->name." ha registrado una orden de fabricación", 3, $user);
    }


    function set_accion_entregar(Request $request, int $id){
        $user = auth()->user();
        
        Order::where(["id"=>$id])->update( ["status_id" => 6, "updated_at" => date("Y-m-d H:i:s") ] );

        Pedidos2::Log($id,"Entregado",$user->name." ha registrado que el pedido fue entregado", 6, $user);
    }



    function set_accion_devolucion(Request $request, int $id){
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

        Pedidos2::Log($id,"Devolución",$user->name." ha registrado que el pedido fue devuelto", 9, $user);
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

        Pedidos2::Log($id,"Refacturación", $user->name." ha registrado que el pedido fue refacturado", 8, $user);

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
     
        return view('pedidos2.attachlist', compact('list','catalog','url','rel','urlParams','mode', 'user'));
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
            Order::where(["id"=>$id])->update(["status_id" => 7, "updated_at"=>date("Y-m-d H:i:s")]);
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
    
            Order::where(["id"=>$id])->update(["status_id" => $sid, "updated_at"=>date("Y-m-d H:i:s")]);
         }
         return redirect("pedidos2/pedido/$id");
     }



     public function historial($order_id){

        //echo "historial $id";
        $lista = Log::where(["order_id" => $order_id])->orderBy("created_at","DESC")->get();

        $order = Order::where("id",$order_id)->first();

        return view("pedidos2/pedido/historial",compact("order_id","lista","order"));
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

            $data = [
                "reason_id" => $reason_id,
                "updated_at"=>date("Y-m-d H:i:s")
            ];

        Debolution::where("id", $id)->update($data); 

        Pedidos2::Log($id,"Devolucion", $user->name." hizo un cambio en devolución #{$id}", 0, $user);
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

        Pedidos2::Log($id,"Shipment", $user->name." hizo un cambio en el embarque #{$id}", 0, $user);
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

        return view("pedidos2/pedido/entregar_edit",compact("id","ob","types","order_id","order"));
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

        Pedidos2::Log($id,"Shipment", $user->name." hizo un cambio en el embarque #{$id}", 0, $user);
        Feedback::j(1);
        return;
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


}