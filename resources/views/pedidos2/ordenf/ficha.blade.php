<?php
$estatuses = [3=>"En Fabricación", 4=>"Fabricado"];
?>
<aside class="Subproceso">

    <span rel='inv'><strong>Orden de Fabricación # {{ $ob->number }}</strong></span>

    <div rel='st'>
    <label><strong>Estatus </strong></label>
    <span>{{ isset($estatuses[$ob->status_id]) ? $estatuses[$ob->status_id] : "" }}</span>
    </div>    
    
    <div rel='ed'>
         <a class="btn editof" href="{{ url('pedidos2/ordenf_edit/'.$ob->id) }}">Editar</a> 

    </div>



    <div rel='fi'>
    @if (isset($ob->document))
    <!--
        <a class='atticon pdf' href='{{ asset("storage/".$ob->document) }}' target='_blank'>
            @if (!empty($ob->document))
            <embed src='{{ asset("storage/".$ob->document) }}' alt='' style='width: 100%; height: auto;' onclick='this.parentNode.click()'></embed>
            @endif
        </a>
-->
        {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
    @endif
    </div>

    

    

</aside>