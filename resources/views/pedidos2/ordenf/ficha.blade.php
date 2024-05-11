<?php
$estatuses = [5=>"En Puerta", 6=>"Entregado", 7=>"Cancelado"];
?>
<aside class="Subproceso">

    <span rel='inv'><strong>Orden de Fabricación # {{ $ob->number }}</strong></span>

    <span rel='st'>
        @if (isset($ob->document))
        {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
        @endif
    </span>    
    
    <div rel='ed'><a class="editarsubproceso editof" href="{{ url('pedidos2/ordenf_edit/'.$ob->id) }}">Editar</a></div>

    <div rel='fi'>

    </div>

    

    

</aside>