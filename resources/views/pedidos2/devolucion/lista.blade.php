<?php
use App\Libraries\Tools;

?>
@foreach ($lista as $ob)

<aside class="Subproceso">

    <span rel='inv'><strong>Devolución {{ Tools::fechaMedioLargo($ob->created_at)  }}</strong></span>

    <div rel='st'>
    <label><strong>Estatus </strong></label>
    <span>{{ isset($ob->reason) ? $ob->reason->reason : $ob->reason_id }}</span>
    </div>    
    
    <div rel='ed'><a class="btn editapg" href="{{ url('pedidos2/devolucion_edit/'.$ob->id) }}">Editar</a></div>

    <div rel='fi'>
    @if (isset($ob->file))
        {{ view('pedidos2/view_storage_item',['path'=>$ob->file]) }}
    @endif
    <br/>

    <section mode="view" class='attachList' rel='deb_{{ $ob->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=evidence&event=entregar&debolution_id='.$ob->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=deb_'. $ob->id .'&mode=view&catalog=evidence&debolution_id='.$ob->id) }}"></section> 

    </div>    

</aside>

@endforeach 