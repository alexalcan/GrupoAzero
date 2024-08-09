<?php
use App\Libraries\Tools;

?>
@foreach ($lista as $ob)

<aside class="Subproceso">

    <span rel='inv'><b>Devolución</b>  {{ isset($ob->reason) ? $ob->reason->reason : $ob->reason_id }} </span>

    <div rel='st'>
    
        <div class="">{{ Tools::fechaMedioLargo($ob->created_at)  }}</div>
    </div>    
    
    <div rel='ed'>
    
    @if ($user->role_id == 1 || in_array($user->department_id,[3,4,8]))   
    <a class="btn editapg" href="{{ url('pedidos2/devolucion_edit/'.$ob->id) }}">Editar</a>
    @endif

    </div>

    <div rel='fi'>
    @if (isset($ob->file))
        {{ view('pedidos2/view_storage_item',['path'=>$ob->file]) }}
        <br/>
    @endif


    <section mode="view" class='attachList' rel='deb_{{ $ob->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=evidence&event=entregar&debolution_id='.$ob->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=deb_'. $ob->id .'&mode=view&catalog=evidence&debolution_id='.$ob->id) }}"></section> 

    </div>    

</aside>

@endforeach 