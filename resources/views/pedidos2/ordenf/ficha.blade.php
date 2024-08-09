<?php
$estatuses = [1=>"Elaborada", 3=>"En Fabricación", 4=>"Fabricado", 7 => "Cancelado"];
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

        @if ($user->role_id == 1 || in_array($user->department_id,[4,5,7]))
         <a class="btn editof" href="{{ url('pedidos2/ordenf_edit/'.$ob->id) }}">Editar</a> 
        @endif 

    </div>



    <div rel='fi'>
        <div class="alGridset">

        @if ($ob->status_1==1)   
            <div class="alGridItem center">
                <div class="MiniEstatus E1">Elaborada</div>
                @if (isset($ob->document))   
                <div>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
                </div>
                @endif
            </div>
            @endif

            @if ($ob->status_3==1)   
            <div class="alGridItem center">
                <div class="MiniEstatus E3">En fabricación</div>
                {{--
                @if (isset($ob->document))   
                <div>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
                </div>
                @endif
                --}}
            </div>
            @endif

            @if($ob->status_4==1)
            <div class="alGridItem center">
                <div class="MiniEstatus E4">Fabricado</div>
            </div>
            @endif
            
            
            @if ($ob->status_7==1)
            <div class="alGridItem center">
                <div class="MiniEstatus E7">Cancelado</div>
                @if (isset($ob->documentc))
                <div>
                {{ view('pedidos2/view_storage_item',['path'=>$ob->documentc]) }}
                </div>
                @endif
            </div>
            @endif
            
        </div>

    </div>

    

    

</aside>