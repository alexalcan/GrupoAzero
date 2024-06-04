<?php
$estatuses = [3=>"En Fabricación", 4=>"Fabricado", 7 => "Cancelado"];
?>
<aside class="Subproceso">

    <span rel='inv'><strong>Orden de Fabricación # {{ $ob->number }}</strong></span>

    <div rel='st'>
    <label><strong> </strong></label>
    <span>
    @if(isset($estatuses[$ob->status_id]))
    <div class="MiniEstatus E{{$ob->status_id}}">{{$estatuses[$ob->status_id]}}</div>
    @endif
    </span>
    </div>    
    
    <div rel='ed'>
         <a class="btn editof" href="{{ url('pedidos2/ordenf_edit/'.$ob->id) }}">Editar</a> 

    </div>



    <div rel='fi'>
        <div class="alGridset">
            
            @if (isset($ob->document))   
            <div class="alGridItem center">
                <div class="MiniEstatus E3">En fabricación</div>
                <div>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
                </div>
            </div>
            @endif
            
            
            @if (isset($ob->documentc))
            <div class="alGridItem center">
                <div class="MiniEstatus E7">Cancelado</div>
                <div>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->documentc]) }}
                </div>
            </div>
            @endif
            
        </div>

    </div>

    

    

</aside>