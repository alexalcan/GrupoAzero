<?php
$estatuses = [3=>"En Fabricación", 4=>"Fabricado"];
?>
@foreach ($list as $ob)
<div class="">

    <span rel='inv'><strong>Orden de Fabricación # {{ $ob->number }}</strong></span>

    <div rel='st'>
    <label><strong>Estatus </strong></label>
    <span>{{ isset($estatuses[$ob->status_id]) ? $estatuses[$ob->status_id] : "" }}</span>
    </div>    
    
    <div rel='fi'>
    @if (isset($ob->document))
        {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
    @endif
    </div>

</div>
@endforeach