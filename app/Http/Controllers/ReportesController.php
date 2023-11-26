<?php

namespace App\Http\Controllers;

#use App\Log;
use App\Reportes;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

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
        // dd($request->all());
        
        $tipo = $request->post("tipo");
        $desde = $request->post("desde");
        $hasta = $request->post("hasta");
        
        //$user = User::find(auth()->user()->id);
        
        //$callback= function(){};

        
        if($tipo=="Ordenes"){
            
            $csvFileName = "Ordenes_".date("d-m-Y H_i").".csv";

            $tempPath= public_path("temp")."/".$csvFileName;          

            $lista =Reportes::Ordenes($desde,$hasta);
            //$out = fopen('php://output', 'w');
            $ar = fopen($tempPath, 'w');
            fputcsv($ar, ["Sucursal","Factura","Cotizacion","Estatus","Requisicion","Salidas Material", "Orden de fabricacion",
                "Fecha de Factura", "Fecha de Cotizacion", "Parcial1","Parcial2","Parcial3","Parcial4","Parcial5"]);
            foreach($lista as $li){
                $arr = [
                    $li->Sucursal, $li->Factura, 
                    strval($li->Cotizacion), 
                    strval($li->Estatus), 
                    strval($li->Requisicion), 
                    strval($li->Salidas),
                    strval($li->Fabricacion), 
                    strval($li->FechaFactura), 
                    strval($li->FechaCotizacion),
                    strval($li->Parcial1), strval($li->Parcial2), strval($li->Parcial3), strval($li->Parcial4), strval($li->Parcial5)
                ];
                //var_dump($arr);
                fputcsv($ar, $arr);
            }
            fclose($ar);
                
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
            $ar = fopen($tempPath, 'r');
            echo fread($ar,filesize($tempPath));
            fclose($ar); 
            unlink($tempPath);
            return;
            //return Response::make('',200, $headers);
    
        }
        elseif($tipo == "Requisiciones") {
            
            $csvFileName = "Requisiciones_".date("d-m-Y H_i").".csv";
            
            $tempPath= public_path("temp")."/".$csvFileName;
            
            $lista =Reportes::Requisiciones($desde,$hasta);
            //$out = fopen('php://output', 'w');
            $ar = fopen($tempPath, 'w');
            fputcsv($ar, ['Numero','Orden', 'Sucursal','Fecha']);
            foreach($lista as $li){
                fputcsv($ar, [$li->number,$li->invoice,$li->office,$li->creado ]);
            }
            fclose($ar);
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
            $ar = fopen($tempPath, 'r');
            echo fread($ar,filesize($tempPath));
            fclose($ar);
            unlink($tempPath);
            return;
    
        }
    return;
        //$headers=["Content-type"=>"text/csv"];
        //return response()->stream($callback, 200, $headers);
    }
    
    
    
    
}