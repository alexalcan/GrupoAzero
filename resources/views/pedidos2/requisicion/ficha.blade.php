<?php
$estatuses = [1=>"En Proceso", 2=>"Surtida"];
//var_dump($ob->status_id);
?>
<aside class="Subproceso">

    <span rel='inv'><strong>  {{ !empty($ob->number) ? "Requisición # ".$ob->number : "Orden de Compra" }}</strong></span>

    <div rel='st'>
    <label><strong> </strong></label>
    <span>
        @if ( isset($estatuses[$ob->status_id]) )
        <div class="MiniEstatus E{{ $ob->status_id }}">{{ $estatuses[$ob->status_id] }}</div>
        @endif
 
    </span>
    </div>    
    
    <div rel='ed'><a class="btn editarrequisicion" href="{{ url('pedidos2/requisicion_edit/'.$ob->id) }}">Editar</a></div>

    <div rel='fi'>
        <div class="space-around">
        <span>
        @if (isset($ob->document))
            
            {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
            <br/><center>Factura</center> 
        @endif
        </span>
        <span>
        @if (isset($ob->requisition))
            
            {{ view('pedidos2/view_storage_item',['path'=>$ob->requisition]) }}
            <br/><center>Requisición</center>
        @endif
        </span>
        </div>
    </div>

</aside>