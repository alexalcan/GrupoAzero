<?php
//$estatuses = [3=>"En Fabricación", 4=>"Fabricado", 7 => "Cancelado"];
$reasonsCat = [];
    foreach($reasons as $rea){$reasonsCat[$rea->id]=$rea->reason;}
?>
<aside class="Subproceso">

    <span rel='inv'><strong>Refacturación</strong></span>

    <div rel='st'>
    <span>
    @if(isset($reasonsCat[$ob->reason_id]))
    <div class="MiniEstatus TE{{$ob->reason_id}}">{{$reasonsCat[$ob->reason_id]}}</div>
    @endif
    </span>
    </div>    
    
    <div rel='ed'>
         <a class="btn editref" href="{{ url('pedidos2/refacturacion_edit/'.$ob->id) }}">Editar</a> 

    </div>



    <div rel='fi'>
        @foreach ($evidences as $ev)
            @if ($ob->id == $ev->rebilling_id) 
                {{ view('pedidos2/view_storage_item',["path"=>$ev->file]) }}
            @endif        
        @endforeach
    </div>

    

    

</aside>