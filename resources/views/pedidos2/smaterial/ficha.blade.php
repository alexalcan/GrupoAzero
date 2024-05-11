<?php
$estatuses = [5=>"En Puerta", 6=>"Entregado", 7=>"Cancelado"];
?>
<aside class="Subproceso">

    <span rel='inv'><strong>Salida de Material # {{ $ob->code }}</strong></span>

    <span rel='st'>{{ isset($estatuses[$ob->status_id]) ? $estatuses[$ob->status_id] : $ob->status_id  }}</span>
    
    <div rel='ed'><a class="editarsubproceso editarsm" href="{{ url('pedidos2/smaterial_edit/'.$ob->id) }}">Editar</a></div>

    <div rel='fi'>
    <section mode="view" class='attachList form-control' rel='sm_{{$ob->id}}' 
        uploadto="{{ url('pedidos2/attachlist?catalog=pictures&smaterial_id='.$ob->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=sm_'.$ob->id.'&catalog=pictures&mode=view&smaterial_id='.$ob->id) }}"></section> 

    </div>
    

</aside>