<?php

namespace App\Http\Controllers;

#use App\Log;
use App\Reportes;
use App\Stockreq;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use Ellumilel\ExcelWriter;



class ReportesController extends Controller
{
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $user = User::find(auth()->user()->id);

        $hoy = new \DateTime();
        $hastaDef = $hoy->format("Y-m-d");
        $hoy->modify("-7 day");
        $desdeDef = $hoy->format("Y-m-d");

        $action = 'Reportes de Azero';
        
        return view('reportes.index', compact('user', 'action', 'desdeDef','hastaDef'));
    }
    
    
    
    
    public function reporte(Request $request)
    {        
        $tipo = $request->post("tipo");
        $desde = $request->post("desde");
        $hasta = $request->post("hasta");
        
        $user = User::find(auth()->user()->id);


        
        if($tipo == "Tiempos") {

            $lista =Reportes::Tiempos($desde,$hasta);
 //Folio Factura	Folio Cotizacion	Folio ReqStock	Creadoen	Estatus Actual	Fecha Este Estatus	Dias Duracion
            $columnas = [
                'ID'=>'string',
                'Folio Factura' => 'string',
                'Folio Cotización'=>'string',
                'Folio Requisición Stock' => 'string',
                'Sucursal' => 'string',
                'Creado en' => 'DD/MM/YYYY',
                'Estatus Actual'=>'string',
                'Ultimo Cambio' => 'DD/MM/YYYY',
                'Dias' => 'integer'];
            $wExcel = new ExcelWriter();
            $wExcel->writeSheetHeader('Sheet1', $columnas);
            $wExcel->setAuthor('Sistema Evidenciasmars');      

            foreach($lista as $li){
                $row=[
                    $li->id,
                    $li->invoice_number,
                    $li->invoice,
                    $li->rsnumber,
                    $li->office,
                    $li->created_at,
                    $li->status_name,
                    $li->status_at,
                    $li->daynum
                ];
            $wExcel->writeSheetRow('Sheet1', $row );
            }
            
            //$tempPath= public_path("temp")."/".$csvFileName;
            $xlsFileName = "Tiempos_".date("d-m-Y H\hi\m").".xlsx";
            $this->MandaReporte($wExcel, $xlsFileName);
            return;    
        }

    return;
        //$headers=["Content-type"=>"text/csv"];
        //return response()->stream($callback, 200, $headers);
    }
    


    public function general( array $lista ){
        //ID	Sucursal	Origen	Folio cotización	Folio Factura	Cliente	Estatus	Fecha Creación	Fecha Cambio	
        //Parcial Numero	Parcial Estatus	Parcial Creado	Numero_ReqStock	Orden Manufactura Numero	
        //Estatus	Requisicion Numero	Req Status	Req Salida Material	Salida Material ID	Salida Mat Estatus	
        //Salida Material Creado	Devolución Razon	Devolución Creada	
        //Refacturación numero	Refacturación razón	Refacturación Url
        

        $header = [
            'ID'=>'string',
            'Sucursal' => 'string',
            'Origen'=>'string',
            'Folio cotización' => 'string',
            'Folio factura' => 'string',
            'Req Stock #'=>'string',
            'Cliente' => 'string',
            'Estatus' => 'string',
            'Fecha de Creación' => 'DD/MM/YYYY',
            'Último cambio' => 'DD/MM/YYYY',

            'Parcial #' => 'string',
            'SP Estatus' => 'string',
            'SP Creado'=>'DD/MM/YYYY',

            'Orden Manufactura'=>'string',
            'OM Status'=>'string',

            'Requisición #'=>'string',
            'Req Status'=>'string',
            
            'Salida Material'=>'string',
            'SM Estatus'=>'string',
            'SM Creado'=>'DD/MM/YYYY',

            'Devolución Razón'=>'string',
            'Devolución Creada'=>'DD/MM/YYYY',   

            'Refacturación #' => 'string',
            'Ref Razón' => 'string',
            'Ref URL' => 'string'            
        ];

        //string,money, YYYY-MM-DD HH:MM:SS

        //********************************   CATALOGOS    ******************** */
        $statuses = \App\Status::all();
         $estatuses = [];
         foreach($statuses as $st){
             $estatuses[$st->id]=$st->name;
         }

        $origenes=["C"=>"Cotización", "F"=>"Factura", "R"=>"Requisición Stock"];

        $reasonsList = \App\Reason::all();
        $reasons=[];
        foreach($reasonsList as $rea){
            $reasons[$rea->id] = $rea->reason;
        }


        //*******************************    EXCEL    ************************* */ 
        $maxOrders=2000;
        $o=0;
        
        $wExcel = new ExcelWriter();
        $wExcel->writeSheetHeader('Sheet1', $header);
        $wExcel->setAuthor('Sistema Evidenciasmars');      
        
        
        

        foreach($lista as $li){
        $o++;
            if($o>$maxOrders){break;}

            $stockReq = Stockreq::where("order_id",$li->id)->first();


            

            $row = [
                'ID'=>$li->id,
                'Sucursal' => $li->office,
                'Origen'=> !empty($origenes[$li->origin]) ? $origenes[$li->origin] : "",
                'Folio cotizacion' => $li->invoice,
                'Folio factura' => $li->invoice_number,

                
                'Req Stock #'=> !empty($stockReq) ? $stockReq->number : '',
                
                'Cliente' => $li->client,
                'Estatus' => $estatuses[$li->status_id],
                'Fecha de Creacion' => $li->created_at,
                'Ultimo cambio' => $li->updated_at  

            ];  

            $wExcel->writeSheetRow('Sheet1', $row );


            $partials = \App\Partial::where("order_id",$li->id)->get();
            foreach($partials as $partial){
                $row["Parcial #"] = $partial->invoice;
                $row["SP Estatus"] = $estatuses[$partial->status_id];
                $row["SP Creado"] = strval($partial->created_at);
             
                $wExcel->writeSheetRow('Sheet1', $row );
            }

            $row["Parcial #"] = '';
            $row["SP Estatus"] = '';
            $row["SP Creado"] =null;


            $ordenesf =\App\ManufacturingOrder::where("order_id",$li->id)->get();
            foreach($ordenesf as $ord){
                $row['Orden Manufactura']=$ord->number;
                $row['OM Status']= isset($estatuses[$ord->status_id]) ? $estatuses[$ord->status_id] : "";

                $wExcel->writeSheetRow('Sheet1', $row );
            }

            $row['Orden Manufactura']= '';
            $row['OM Status']= '';


            $requisiciones = \App\PurchaseOrder::where("order_id",$li->id)->get();
            foreach($requisiciones as $req){
                $row['Requisición #']= $req->number;
                $row['Req Status']=isset($estatuses[$req->status_id]) ? $estatuses[$req->status_id] : '';

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Requisición #']= '';
            $row['Req Status']='';


            $salidasm = \App\Smaterial::where("order_id", $li->id)->get();
            foreach($salidasm as $salm){
                $row['Salida Material']= $salm->code;
                $row['SM Estatus'] = isset($estatuses[$salm->status_id]) ? $estatuses[$salm->status_id] : '';
                $row['SM Creado'] = strval($salm->created_at);

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Salida Material']= '';
            $row['SM Estatus'] = '';
            $row['SM Creado'] = '';
            

            $devoluciones = \App\Debolution::where("order_id",$li->id)->get();
            foreach($devoluciones as $devol){
                $row['Devolución Razón'] = isset($reasons[$devol->reason_id]) ? $reasons[$devol->reason_id] : '' ;
                $row['Devolución Creada'] = strval($devol->created_at);

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Devolución Razón'] = '' ;
            $row['Devolución Creada'] = '';
            
            $refacturaciones = \App\Rebilling::where("order_id", $li->id)->get();
            foreach($refacturaciones as $ref){
                $row['Refacturación #']= $ref->number;
                $row['Ref Razón'] = isset($reasons[$ref->reason_id]) ? $reasons[$ref->reason_id] : '';
                $row['Ref URL'] = $ref->url;

                $wExcel->writeSheetRow('Sheet1', $row );
            }
            $row['Refacturación #']= '';
            $row['Ref Razón'] = '';
            $row['Ref URL'] = '';

        }
        if(count($lista) > $maxOrders){
            $row=[];
            $row['ID']="Lista truncada. Máximo $maxOrders pedidos se pueden mostrar en este reporte. Haga la consulta con un rango de fechas o filtros más restrictivos.";
            $wExcel->writeSheetRow('Sheet1', $row );
        }


        $xlsFileName = "Busqueda_".date("d-m-Y H\hi\m").".xlsx";
        $this->MandaReporte($wExcel,$xlsFileName);
        return;
    }
    

    function MandaReporte(ExcelWriter $wExcel, string $xlsFileName){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($xlsFileName).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        

        
        $str = $wExcel->writeToString();
        header('Content-Length: '.strlen($str));
        echo $str;
        return;
    }
    
    
}