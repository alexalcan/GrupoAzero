<?php

use App\ManufacturingOrder;
use App\Partial;
use App\Smaterial;

$estatusCodes=[1=>"GEN",2=>"EMB",3=>"FAB", 4=>"FAB", 5=>"PUE", 6=>"ENT", 7=>"CNC",8=>"REF",9=>"DEV"];
    if($item->origin == "R"){
        $estatusCodes[6]="SUR";
        $estatuses[6]="Surtido";
    }

$origenes=[""=>"","C"=>"Cotización","F"=>"Factura", "R"=>"Requisición"];

?>
<aside class="Pedido">

    <div class="EsquinaOrigen" title="Origen: {{ $origenes[$item->origin] }}">{{ $item->origin }}</div>

    <div class="estatusFlags">
        @if (!empty($item->ordenf_status_id))
        <div class="estatus E{{ $item->ordenf_status_id }}" title="Orden de compra #{{$item->ordenf_number}} {{ $estatuses[$item->ordenf_status_id] }}">OF:{{ $estatusCodes[$item->ordenf_status_id] }}</div>
        @endif
        
        @if (!empty($item->parcial_status_id))
        <div class="estatus E{{ $item->parcial_status_id }}" title="Parcial #{{$item->parcial_number}} {{ $estatuses[$item->parcial_status_id] }}">PA:{{ $estatusCodes[$item->parcial_status_id] }}</div>
        @endif

        <div class="estatus main E{{ $item->status_id }}" title="{{ $estatuses[$item->status_id] }}">{{ $estatusCodes[$item->status_id] }}</div>
    </div>

    
        <div class="SuperElementos">
           
            <div class="SuperElemento">
            <a class="granliga" href="{{ url('pedidos2/pedido/'.$item->id) }}">    
            <div class="Elementos" href="{{ url('pedidos2/pedido/'.$item->id) }}">
                
                
                @if (!empty($item->invoice_number)) 
                <div class="datito" rel="main"><label>Factura</label>{{$item->invoice_number}}</div>
                @else
                <div class="datito" rel="main"><label>Cotización</label>{{$item->invoice}}</div>
                @endif


                @if (!empty($item->stockreq_id))
                <div class="datito" rel="sec"> 
                    @if (!empty($item->stockreq_document))
                    <label>Requisición Stock</label> <a href="{{ asset('storage/'.$item->stockreq_document) }}" class="pdf" target="_blank">{{$item->stockreq_number}}</a> 
                    @else 
                    <label>Requisición Stock</label> {{$item->stockreq_number}} 
                    @endif
                </div>
                @else
                <div class="datito" rel="sec"><label>Requisición</label> {{$item->requisition_code}}</div>
                @endif
                

                
                <div class="datito" rel="cliente"><label>Cliente</label>{{$item->client}} </div>
                <div class="datito" rel="sucursal"> <label>Sucursal</label> {{$item->office}}  </div>
                
                <div class="datito detalles" rel="fab">
                    <div class="head">Fabricacion</div>
                
                    <?php
                    $ordersf = ManufacturingOrder::where(["order_id"=>$item->id])->orderBy("id","DESC")->get();
                    $n=0;
                    foreach($ordersf as $part){
                        $n++;
                        if($n==3){echo "<div>...</div>"; break;}
                        echo "<p><b>".$part["number"]."</b> <br/>".$estatuses[$part["status_id"]]."</p>";
                    }
                    ?>
                
                </div>
                <div class="datito detalles" rel="par">
                <div class="head"> Parciales</div>
                <?php
                $partials = Partial::where(["order_id"=>$item->id])->orderBy("id","DESC")->get();
                $n=0;
                foreach($partials as $part){
                    $n++;
                    if($n==3){echo "<div>...</div>"; break;}
                    echo "<p><b>".$part["invoice"]."</b> <br/>".$estatuses[$part["status_id"]]."</p>";
                }
                ?>
                </div>
                <div class="datito detalles" rel="sm"><div class="head">Salidas Materiales</div>

                <?php
                $smateriales = Smaterial::where(["order_id"=>$item->id])->orderBy("id","DESC")->get();
                $n=0;
                foreach($smateriales as $part){
                    $n++;
                    if($n==3){echo "<div>...</div>"; break;}
                    echo "<p><b>".$part["code"]."</b> <br/>".$estatuses[$part["status_id"]]."</p>";
                }
                ?>


                </div>

                <div class="datito" rel=""> </div>
                
                <!-- <div class="datito" rel="mas"><a href="{{ url('pedidos2/masinfo/'.$item->id) }}" class="masinfo">Más Información</a></div> -->

                

                <!--
                <div class="datito" rel="edit"><a class="editar" href="{{ url('pedidos2/pedido/'.$item->id) }}">Cambiar</a></div>
-->


            </div>
            </a>

            </div>


            <div class="SuperElemento conIconos">
                
                <div rel="icons" >
                    <div class='iconSet'>
                    <!--
                        <a class="parciales" title="Parciales" href="{{ url('pedidos2/fragmento/'.$item->id.'/parciales') }}"></a>
                        <a class="ordenf" title="Ordenes de Manufactura" href="{{ url('pedidos2/fragmento/'.$item->id.'/ordenf')}}"></a>
                        
                    -->
                        <a class="historial" title="Más Información" href="{{ url('pedidos2/masinfo/'.$item->id) }}"></a>
                        <a class="notas" title="Notas" href="{{ url('pedidos2/fragmento/'.$item->id.'/notas')}}"></a>
                    </div>
                </div>
            </div>

    </div>

</aside>