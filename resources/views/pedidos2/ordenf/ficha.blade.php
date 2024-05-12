<?php
$estatuses = [3=>"En Fabricación", 4=>"Fabricado"];
?>
<aside class="Subproceso">

    <span rel='inv'><strong>Orden de Fabricación # {{ $ob->number }}</strong></span>

    <div rel='st'>
    <label><strong>Estatus </strong></label>
    <span>{{ isset($estatuses[$ob->status_id]) ? $estatuses[$ob->status_id] : "" }}</span>
    </div>    
    
    <div rel='ed'><a class="editarsubproceso editof" href="{{ url('pedidos2/ordenf_edit/'.$ob->id) }}">Editar</a></div>

    <div rel='fi'>
    @if (isset($ob->document))
        {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
    @endif
    </div>

    

    

</aside>