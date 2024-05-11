<aside class="Parcial">

    <span rel='inv'><strong>Parcial # {{ $parcial->invoice }}</strong></span>

    <span rel='st'>{{ isset($estatuses[$parcial->status_id]) ? $estatuses[$parcial->status_id] : "?"  }}</span>
    
    <div rel='ed'><a class="editarparcial" href="{{ url('pedidos2/parcial_edit/'.$parcial->id) }}">Editar</a></div>

    <div rel='fi'>
    <section mode="view" class='attachList form-control' rel='prt_{{$parcial->id}}' 
        uploadto="{{ url('pedidos2/attachlist?catalog=pictures&partial_id='.$parcial->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=prt_'.$parcial->id.'&catalog=pictures&mode=view&partial_id='.$parcial->id) }}"></section> 

    </div>
    

</aside>