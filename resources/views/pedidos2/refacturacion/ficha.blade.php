<?php
//$estatuses = [3=>"En Fabricación", 4=>"Fabricado", 7 => "Cancelado"];

$reasonsCat = [];
    foreach($reasons as $rea){$reasonsCat[$rea->id]=$rea->reason;}
?>
<aside class="Subproceso">

    <span rel='inv'><strong>Refacturación {{ !empty($ob->number) ? "#".$ob->number : "" }}</strong></span>

    <div rel='st'>
    <span>
    @if(isset($reasonsCat[$ob->reason_id]))
    <div class="MiniEstatus TE{{$ob->reason_id}}">{{$reasonsCat[$ob->reason_id]}}</div>
    @endif
    </span>
    </div>    
    
    <div rel='ed'>


        @if ($user->role_id == 1 || in_array($user->department_id,[3,4]))
         <a class="btn editref" href="{{ url('pedidos2/refacturacion_edit/'.$ob->id) }}">Editar</a> 
         @endif

    </div>



    <div rel='fi'>
        <aside class="alGridset">
            <?php
            $protocol = (strpos($ob->url,"http://",0) > -1) ? "" : "http://";
             echo !empty($ob->url) ? "<div><a href='".$protocol.$ob->url."' target='_blank'>Link nueva factura</a></div>" : "";
             ?>
             <div>
             @foreach ($evidences as $ev)
                @if ($ob->id == $ev->rebilling_id) 
                {{ view('pedidos2/view_storage_item',["path"=>$ev->file]) }}
                @endif        
             @endforeach
             </div>
        </aside>


    </div>

    

    

</aside>