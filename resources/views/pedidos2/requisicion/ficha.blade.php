<?php
$estatuses = [1=>"En Proceso", 2=>"Surtida"];
//var_dump($ob->status_id);
?>
<aside class="Subproceso">

    <span rel='inv'><strong>  {{ !empty($ob->number) ? "Requisición # ".$ob->number : "Orden de Compra" }}</strong></span>

    <div rel='st'>
    <label><strong>Estatus </strong></label>
    <span>{{ isset($estatuses[$ob->status_id]) ? $estatuses[$ob->status_id] : "" }}</span>
    </div>    
    
    <div rel='ed'><a class="btn editarrequisicion" href="{{ url('pedidos2/requisicion_edit/'.$ob->id) }}">Editar</a></div>

    <div rel='fi'>
        <div class="space-around">
        <span>
        @if (isset($ob->document))
            Factura 
            {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
        @endif
        </span>
        <span>
        @if (isset($ob->requisition))
            Requisición 
            {{ view('pedidos2/view_storage_item',['path'=>$ob->requisition]) }}
        @endif
        </span>
        </div>
    </div>

</aside>