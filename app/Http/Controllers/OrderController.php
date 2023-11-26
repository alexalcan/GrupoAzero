<?php

namespace App\Http\Controllers;

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

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $textos = [];
        $busquedaOrden = trim($request->busquedaOrden);
        $busquedaFactura = trim($request->busquedaFactura);
        $busquedaCliente = trim($request->busquedaCliente);
        $busquedaSucursal = trim($request->busquedaSucursal);

        $fecha = $request->fecha;
        $fechaDos = $request->fechaDos;
        $mensaje = NULL;
        //$orders = Order::where('delete', NULL)->paginate(15);
        //$orders=[];
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        
        $pag = $request->get("page");
        $pag = !empty($pag) ? intval($pag) : 1 ;
  
        
        $ordersbuilder = Order::where('delete', NULL);
        
        if(!empty($busquedaOrden)){
            $textos []=$busquedaOrden;
            $ordersbuilder->where('invoice', 'LIKE', '%'.$busquedaOrden.'%');
        }
        if(!empty($busquedaFactura)){
            $textos []=$busquedaFactura;
            $ordersbuilder->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%');
        }
        if(!empty($busquedaCliente)){
            $textos []=$busquedaCliente;
            $ordersbuilder->where('client', 'LIKE', '%'.$busquedaCliente.'%');
        }
        if(!empty($busquedaSucursal)){
            $textos []=$busquedaSucursal;
            $ordersbuilder->where('office', 'LIKE', '%'.$busquedaSucursal.'%');
        }
        
        if(!empty($fecha) && empty($fechaDos)){
            $ordersbuilder->whereDate('created_at', Carbon::parse($request->fecha)->toDateString());
        }
        else if(!empty($fecha) && !empty($fechaDos)){
            $ordersbuilder->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()]);
        }
        
        $texto = implode(", ",$textos);
        
        $ini = ($pag < 2 ) ? 0 : $pag - 1;
        $rpp = 15;
        
        $ordersbuildertot = $ordersbuilder;
        $total = $ordersbuildertot->count();
        //echo $ordersbuildertot->toSql();
        
        $ordersbuilder->orderBy('created_at', 'desc')
        ->skip($ini)->take($rpp);
        
        $orders = $ordersbuilder->get();
        
        //$total = 1500;
       //var_dump($orders);
        $busquedas = ["order"=>$busquedaOrden, "factura"=>$busquedaFactura, "cliente"=>$busquedaCliente,
            "sucursal"=>$busquedaSucursal, "fecha"=>$fecha, "fechaDos"=>$fechaDos
        ];
        
        return view('orders.index', compact('orders', 'role', 'department', 'texto', 'fecha', 'fechaDos', 'mensaje','pag','rpp','total',"busquedas"));
        
        
        /*
        if ($busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal == NULL && $fecha == NULL && $fechaDos == NULL){
            // $orders = Order::paginate(15);
            return view('orders.index', compact('orders', 'role', 'department', 'texto', 'fecha', 'mensaje'));
        }else{
            // Busqueda por fecha
            if ($fecha && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal == NULL){
                $orders = Order::where('delete', NULL)
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
                            echo "UNO";
                            die();
            }
            if ($fecha && $fechaDos && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal == NULL){
                $orders = Order::where('delete', NULL)
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);   
            }
            // Busqueda combinada
            // una fecha y folio
            if ($fecha && $busquedaOrden && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal == NULL && $fechaDos == NULL){
                $texto = $texto.' '.$busquedaOrden;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // fecha, folio y factura
            if ($fecha && $busquedaOrden && $busquedaFactura && $busquedaCliente == NULL && $busquedaSucursal == NULL && $fechaDos == NULL){
                $texto = $texto.' '.$busquedaOrden.', '.$busquedaFactura;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // fecha, folio, factura y cliente
            if ($fecha && $busquedaOrden && $busquedaFactura && $busquedaCliente && $busquedaSucursal == NULL && $fechaDos == NULL){
                $texto = $texto.' '.$busquedaOrden.', '.$busquedaFactura.', '.$busquedaCliente;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // fecha, folio, factura, cliente, sucursal
            if ($fecha && $busquedaOrden && $busquedaFactura && $busquedaCliente && $busquedaSucursal && $fechaDos == NULL){
                $texto = $texto.' '.$busquedaOrden.', '.$busquedaFactura.', '.$busquedaCliente.', '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Fecha y factura
            if ($fecha && $busquedaOrden == NULL && $busquedaFactura && $busquedaCliente == NULL && $busquedaSucursal == NULL && $fechaDos == NULL){
                $texto = $texto.' '.$busquedaFactura.', '.$busquedaCliente.', '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Fecha y cliente
            if ($fecha && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente && $busquedaSucursal == NULL && $fechaDos == NULL){
                $texto = $texto.' '.$busquedaCliente;
                $orders = Order::where('delete', NULL)
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Fecha y sucursal
            if ($fecha && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal && $fechaDos == NULL){
                $texto = $texto.' '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // rango de fechas
            if ($fecha && $fechaDos && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal == NULL){
                $orders = Order::where('delete', NULL)
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);
            }
            // fechas y folio
            if ($fecha && $fechaDos && $busquedaOrden && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal == NULL){
                $texto = $texto.' '.$busquedaOrden;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);
            }
            // Fechas y factura
            if ($fecha && $fechaDos && $busquedaOrden == NULL && $busquedaFactura && $busquedaCliente == NULL && $busquedaSucursal == NULL){
                $texto = $texto.' '.$busquedaFactura;
                $orders = Order::where('delete', NULL)
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);
            }
            // Fechas y cliente
            if ($fecha && $fechaDos && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente && $busquedaSucursal == NULL){
                $texto = $texto.' '.$busquedaCliente;
                $orders = Order::where('delete', NULL)
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);
            }
            // Fechas y sucursal
            if ($fecha && $fechaDos && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal){
                $texto = $texto.' '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);
            }

            // Busqueda de ordenes
            // Individuales
            // Solo folio
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal == NULL) {
                $texto = $texto.' '.$busquedaOrden;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Folio y factura
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden && $busquedaFactura && $busquedaCliente == NULL && $busquedaSucursal == NULL) {
                $texto = $texto.' '.$busquedaOrden;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Folio y cliente
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden && $busquedaFactura == NULL && $busquedaCliente && $busquedaSucursal == NULL) {
                $texto = $texto.' '.$busquedaOrden.', '.$busquedaCliente;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Folio y sucursal
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal) {
                $texto = $texto.' '.$busquedaOrden.', '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Folio, factura y cliente
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden && $busquedaFactura && $busquedaCliente && $busquedaSucursal == NULL) {
                $texto = $texto.' '.$busquedaOrden.' '.$busquedaFactura.' '.$busquedaCliente;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Factura
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura && $busquedaCliente == NULL && $busquedaSucursal == NULL) {
                $texto = $texto.' '.$busquedaFactura;
                $orders = Order::where('delete', NULL)
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Factura y cliente
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura && $busquedaCliente && $busquedaSucursal == NULL) {
                $texto = $texto.' '.$busquedaFactura.' '.$busquedaCliente;
                $orders = Order::where('delete', NULL)
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Factura cliente y sucursal
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura && $busquedaCliente && $busquedaSucursal) {
                $texto = $texto.' '.$busquedaFactura.' '.$busquedaCliente.' '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Factura y sucursal
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura && $busquedaCliente == NULL && $busquedaSucursal) {
                $texto = $texto.' '.$busquedaFactura.' '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Cliente
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente && $busquedaSucursal == NULL) {
                $texto = $texto.' '.$busquedaCliente;
                $orders = Order::where('delete', NULL)
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Cliente y sucursal
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente && $busquedaSucursal) {
                $texto = $texto.' '.$busquedaCliente.' '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            // Sucursal
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden == NULL && $busquedaFactura == NULL && $busquedaCliente == NULL && $busquedaSucursal) {
                $texto = $texto.' '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }


            // Todas
            if ($fecha == NULL && $fechaDos == NULL && $busquedaOrden && $busquedaFactura && $busquedaCliente && $busquedaSucursal) {
                $texto = $texto.' '.$busquedaOrden.', '.$busquedaFactura.', '.$busquedaCliente.', '.$busquedaSucursal;
                $orders = Order::where('delete', NULL)
                            ->where('invoice', 'LIKE', '%'.$busquedaOrden.'%')
                            ->where('invoice_number', 'LIKE', '%'.$busquedaFactura.'%')
                            ->where('client', 'LIKE', '%'.$busquedaCliente.'%')
                            ->where('office', 'LIKE', '%'.$busquedaSucursal.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            

            return view('orders.index', compact('orders', 'role', 'department', 'texto', 'fecha', 'fechaDos', 'mensaje'));
        }

        */

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$orders = Order::all();
        $orders=[];
        $statuses = Status::all();
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $mensaje = NULL;

        return view('orders.create', compact('orders', 'statuses', 'role', 'department', 'mensaje'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $registro = Order::where('invoice', 'like', $request->invoice)->count();
        // dd($registro);
        if($registro == 0 ){
            $mensaje = 'Órden guardada con éxito!';
        }else{
            $mensaje = 'La orden que almacenaste ya existe';
        }

        $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);
        $action = $user->name .' creó una orden';

        if($request->credit){
            $credit = 1;
            $action = $action . ' a credito';
        }else{
            $credit = 0;
        }
        
        $userOffice = !empty($user->office) ? $user->office : "San Pablo";

        $order = Order::create([
            'office' => $userOffice,
            'invoice' => $request->invoice,
            'invoice_number' => $request->invoice_number,
            'client' => $request->client,
            'credit' => $credit,
            'status_id' => $request->status_id,
            'created_at' => now(),
        ]);
        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'created_at' => now()
            ]);
            $action = $action . ',  se añadió una nota';
        }
        // Ver si va con Orden de Requisición
        if($request->ocCheck == 1){
            if ( $request->documentReq || $request->requisition){
                $hoy = date("Y-m-d H:i:s");
                $path = NULL;
                $pathReq = NULL;
                $action = $action . ', se cubrió la Orden de Requisición';

                $po = PurchaseOrder::create([
                    'required' => $request->ocCheck,
                    'number' => $request->purchase_order,
                    'document' => NULL,
                    'requisition' => NULL,
                    'iscovered' => 1,
                    'order_id' => $order->id
                ]);
                $action = $action . ', se añadió factura y requisición';

                if ($request->documentReq){
                    $file = $request->file('documentReq');
                    $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                    $path = 'OrdenesDeCompra/' . $name;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $file, $name );
                    $po->update([
                        'number' => $request->purchase_order,
                        'document' => $path,
                        'iscovered' => 1,
                    ]);
                }
                if ($request->requisition){
                    $fileReq = $request->file('requisition');
                    $nameReq = str_replace(' ','-', $request->invoice .'-'.$fileReq->getClientOriginalName());
                    $pathReq = 'OrdenesDeCompra/' . $nameReq;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $fileReq, $nameReq );
                    $po->update([
                        'number' => $request->purchase_order,
                        'requisition' => $pathReq,
                        'iscovered' => 1,
                    ]);
                }

            }
        }

        $folios = 0;
        if($request->folio1){
            Partial::create([
                'invoice' => $request->folio1,
                'order_id' => $order->id,
                'status_id' => $request->fol_status1
            ]);
            $folios++;
        }
        if($request->folio2){
            Partial::create([
                'invoice' => $request->folio2,
                'order_id' => $order->id,
                'status_id' => $request->fol_status2
            ]);
            $folios++;
        }
        if($request->folio3){
            Partial::create([
                'invoice' => $request->folio3,
                'order_id' => $order->id,
                'status_id' => $request->fol_status3
            ]);
            $folios++;
        }
        if($request->folio4){
            Partial::create([
                'invoice' => $request->folio4,
                'order_id' => $order->id,
                'status_id' => $request->fol_status4
            ]);
            $folios++;
        }
        if($request->folio5){
            Partial::create([
                'invoice' => $request->folio5,
                'order_id' => $order->id,
                'status_id' => $request->fol_status5
            ]);
            $folios++;
        }
        if($request->folio1){
            $action = $action . ', se añadió ' . $folios . ' entregas parciales';
        }

        Log::create([
            'status' => $status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        // dd($registro, $request->invoice);
        $fecha = '';
        $texto = '';
        $orders = Order::where('delete', NULL)->paginate(15);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        return view('orders.index', compact('orders', 'role', 'department', 'fecha', 'texto', 'mensaje'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        $logs = Log::where('order_id', $id)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        // dd($logs);

        return view('orders.show', compact('order', 'role', 'department', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $statuses = Status::all();
        $reasons = Reason::all();

        $fav = Follow::where('user_id', auth()->user()->id)
        ->where('order_id', $order->id)
        ->first();

        return view('orders.edit', compact('order', 'statuses', 'role', 'department', 'fav', 'reasons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $order = Order::find($id);
        $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);

        if($request->status_id == 5 && $request->credit == true && !isset($request->invoice_number) ){
            return redirect()->back();
        }

        $action = $user->name . ' actualizó la orden';

        // Verificar crédito
        if($request->credit){
            $credit = 1;
        }else{
            $credit = 0;
        }
        // Verificar si hay cambio en estatus de crédito
        if( $order->credit != $credit){
            if($request->credit == true){
                $credit = 1;
                $action = $action . ', orden a credito';
            }else{
                $credit = 0;
                $action = $action . ', orden sin credito';
            }
        }
        // Actualización de orden
        $order->update([
            'office' => $request->office,
            'invoice' => $request->invoice,
            'invoice_number' => $request->invoice_number,
            'client' => $request->client,
            'credit' => $credit,
            'status_id' => $request->status_id,
            'updated_at' => now()
        ]);

        // Ver si va con Orden de Requisición
        if($request->ocCheck == 1){
            PurchaseOrder::create([
                'required' => $request->ocCheck,
                'number' => $request->purchase_order,
                'document' => NULL,
                'requisition' => NULL,
                'order_id' => $order->id
            ]);
            $action = $action . ', el pedido requiere una Orden de Requisición ';
        }

        // Orden de fabricación
        if( $request->status_id == 3 && !isset( $order->manufacturingorder ) ){
            $file = $request->file('manufacturingFile');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Fabricaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                Storage::putFileAs('/public/' . 'Fabricaciones/', $file, $name );
                ManufacturingOrder::create([
                    'required' => 1,
                    'number' => $request->manufacturingOrder,
                    'document' => $path,
                    'iscovered' => NULL,
                    'order_id'=> $order->id
                ]);
                $action = $action . ', se añadió orden de fabricación ' . $request->manufacturingOrder . ' y se añadió un archivo';
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
                ManufacturingOrder::create([
                    'required' => 1,
                    'number' => $request->manufacturingOrder,
                    'document' => $path,
                    'iscovered' => NULL,
                    'order_id'=> $order->id
                ]);
                $action = $action . ', se añadió orden de fabricación ' . $request->manufacturingOrder . ', no se añadió un archivo';
            }
        }elseif( $request->status_id == 3 && !isset( $order->manufacturingorder->document ) ){
            $file = $request->file('manufacturingFile');
            $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
            $path = 'Fabricaciones/' . $name;
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            Storage::putFileAs('/public/' . 'Fabricaciones/', $file, $name );
            $order->manufacturingorder->update([
                'required' => 1,
                'number' => $request->manufacturingOrder,
                'document' => $path,
                'iscovered' => NULL,
                'order_id'=> $order->id
            ]);
            $action = $action . ', se actualizó orden de fabricación ' . $request->manufacturingOrder . ', se añadió un archivo';
        }

        // Evidencia para salida a ruta
        if($request->status_id == 5){
            $file = $request->file('routeEvidence');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Embarques/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }
            $action = $action . ', pedido sale a ruta';
            if($file){
                Storage::putFileAs('/public/' . 'Embarques/', $file, $name );
                $shipment = Shipment::create([
                    'file' => $path,
                    'order_id' => $order->id,
                    'created_at' => now(),
                ]);
                $action = $action . ', se subió evidencia con extensión '. $extension;
            }else{
                $action = $action . ', no se subió evidencia';
            }
        }

        // Cancelación de orden
        if( $request->status_id == 7 ){
            $file = $request->file('cancelation');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Cancelaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }

            $razon = Reason::find($request->cancel_reason_id);
            $razon = $razon->reason;
            $cancelation = Cancelation::create([
                'file' => NULL,
                'order_id' => $order->id,
                'reason_id' => $request->cancel_reason_id,
                'created_at' => now(),
            ]);
            $action = $action . ', se canceló debido a un '. $razon;
            if($file){
                Storage::putFileAs('/public/' . 'Cancelaciones/', $file, $name );
                Evidence::create([
                    'file' => $path,
                    'cancelation_id' => $cancelation->id
                ]);
                $action = $action . ', se subió un archivo con extensión '. $extension;
            }else{
                $action = $action . ', no se subió archivo';
            }

        }
        // Refacturación de orden
        if( $request->status_id == 8){
            $file = $request->file('rebilling');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Refacturaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }

            $razon = Reason::find($request->refact_reason_id);
            $razon = $razon->reason;
            $rebilling = Rebilling::create([
                'file' => NULL,
                'order_id' => $order->id,
                'reason_id' => $request->refact_reason_id,
                'created_at' => now(),
            ]);
            $action = $action . ', se canceló debido a un '. $razon;
            if($file){
                Storage::putFileAs('/public/' . 'Refacturaciones/', $file, $name );
                Evidence::create([
                    'file' => $path,
                    'rebilling_id' => $rebilling->id
                ]);
                $action = $action . ', se subió un archivo con extensión '. $extension;
            }else{
                $action = $action . ', no se subió archivo';
            }
        }
        // Devolución de orden
        if( $request->status_id == 9){
            $file = $request->file('debolution');
            if($file){
                $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                $path = 'Devoluciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
            }else{
                $name = NULL;
                $path = NULL;
                $extension = NULL;
            }

            $razon = Reason::find($request->debolution_reason_id);
            $razon = $razon->reason;
            $debolution = Debolution::create([
                'file' => NULL,
                'order_id' => $order->id,
                'reason_id' => $request->debolution_reason_id,
                'created_at' => now(),
            ]);
            $action = $action . ', se canceló debido a un '. $razon;
            if($file){
                Storage::putFileAs('/public/' . 'Devoluciones/', $file, $name );
                Evidence::create([
                    'file' => $path,
                    'debolution_id' => $debolution->id
                ]);
                $action = $action . ', se subió un archivo con extensión '. $extension;
            }else{
                $action = $action . ', no se subió archivo';
            }
        }

        // Orden de Requisición
        if( $request->iscovered == true || $request->purchaseorder || $request->document ){
            $purchaseorder = $order->purchaseorder;

            if ( $request->document || $request->requisition){
                $hoy = date("Y-m-d H:i:s");
                $path = NULL;
                $pathReq = NULL;

                if ($request->document){
                    $file = $request->file('document');
                    $name = str_replace(' ','-', $request->invoice .'-'.$file->getClientOriginalName());
                    $path = 'OrdenesDeCompra/' . $name;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $file, $name );
                    $purchaseorder->update([
                        'number' => $request->purchaseorder,
                        'document' => $path,
                        'iscovered' => 1,
                    ]);
                }
                if ($request->requisition){
                    $fileReq = $request->file('requisition');
                    $nameReq = str_replace(' ','-', $request->invoice .'-'.$fileReq->getClientOriginalName());
                    $pathReq = 'OrdenesDeCompra/' . $nameReq;
                    Storage::putFileAs('/public/' . 'OrdenesDeCompra/', $fileReq, $nameReq );
                    $purchaseorder->update([
                        'number' => $request->purchaseorder,
                        'requisition' => $pathReq,
                        'iscovered' => 1,
                    ]);
                }

                $action = $action . ', se cubrió la Orden de Requisición';

            }
            else{
                $purchaseorder->update([
                    'number' => $request->purchaseorder,
                    'iscovered' => 1,
                ]);
                $action = $action . ', se cubrió la Orden de Requisición';
            }
        }

        // Hacer favorito
        if($request->favorite){
            Follow::create([
                'user_id' => auth()->user()->id,
                'order_id' => $id
            ]);
        }
        // Añadir nota
        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $order->id,
                'user_id' => auth()->user()->id
            ]);
            $action = $action . ', '.auth()->user()->name.' añadió una nota';
        }
        // Verificar cambio de estatus
        if($request->status_id != $request->statAnt){
            $status = Status::find($request->status_id);
            $user = User::find(auth()->user()->id);
            $action = $action . ', se actualizó estatus a "'. $status->name . '"';
            Note::create([
                'note' => $user->name . ' cambió el estatus a '. $status->name,
                'order_id' => $order->id,
                'user_id' => auth()->user()->id
            ]);
        }
        // Nuevos Parciales
        $folios = 0;
        if($request->folio1){
            Partial::create([
                'invoice' => $request->folio1,
                'order_id' => $order->id,
                'status_id' => $request->fol_status1
            ]);
            $folios++;
        }
        if($request->folio2){
            Partial::create([
                'invoice' => $request->folio2,
                'order_id' => $order->id,
                'status_id' => $request->fol_status2
            ]);
            $folios++;
        }
        if($request->folio3){
            Partial::create([
                'invoice' => $request->folio3,
                'order_id' => $order->id,
                'status_id' => $request->fol_status3
            ]);
            $folios++;
        }
        if($request->folio4){
            Partial::create([
                'invoice' => $request->folio4,
                'order_id' => $order->id,
                'status_id' => $request->fol_status4
            ]);
            $folios++;
        }
        if($request->folio5){
            Partial::create([
                'invoice' => $request->folio5,
                'order_id' => $order->id,
                'status_id' => $request->fol_status5
            ]);
            $folios++;
        }
        if($request->folio1){
            $action = $action . $user->name . ', añadió ' . $folios . ' parciales';
        }
        // Actualización de parciales
        if ( $order->partials->count() > 0 ){
            // dd('el pedido tiene '. $order->partials->count() .' parciales');
            if( $request->stPartID_1 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_1);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_1
                ]);
                if( $request->partImg_1 ){
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_1');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                }
            }
            if( $request->stPartID_2 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_2);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_2
                ]);
                if( $request->partImg_2 ){
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_2');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                }
            }
            if( $request->stPartID_3 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_3);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_3
                ]);
                if( $request->partImg_3 ){
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_3');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                }
            }
            if( $request->stPartID_4 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_4);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_4
                ]);
                if( $request->partImg_4 ){
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_4');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                }
            }
            if( $request->stPartID_5 ){
                // Buscar ID del parcial
                $partialID = Partial::find($request->stPartID_5);
                // Actualizar Status
                $partialID->update([
                    'status_id' => $request->stPartValue_5
                ]);
                if( $request->partImg_5 ){
                    $hoy = date("Y-m-d H:i:s");
                    $file = $request->file('partImg_5');
                    $name = $hoy . '-' . $file->getClientOriginalName();
                    $path = 'Images/' . $name;
                    Storage::putFileAs('/public/' . 'Images/', $file, $name );
                    Picture::create([
                        'picture' => $path,
                        'user_id' => auth()->user()->id,
                        'partial_id' => $partialID->id
                    ]);
                    $partialID->update([
                        'status_id' => 6
                    ]);
                }
            }
            $action = $action . '. Actualización de parciales';
        }
        // Creación de un log
        Log::create([
            'status' => $status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        if( $request->status_id == 7 || $request->status_id == 8 ){
            // dd('cancelar para refacturación.');
        }

        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        // dd($id, $order->status->name);
        $user = User::find(auth()->user()->id);

        $action = $user->name . ' archivó la orden';

        $order->update([
            'delete' => 1,
            'updated_at' => now()
        ]);

        // Creación de un log
        Log::create([
            'status' => $order->status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        return redirect()->route('orders.index');
    }
}
